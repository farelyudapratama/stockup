<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PurchasesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $request;
    protected $rowCount = 0;
    protected $mergeRows = [];
    protected $vendorRows = [];
    protected $summaryRows = [];

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $purchaseQuery = Purchase::with(['vendor', 'details.product'])
            ->latest('purchase_date');

        // Filter berdasarkan vendor
        if ($this->request->filled('vendor_id')) {
            $purchaseQuery->where('vendor_id', $this->request->vendor_id);
        }

        // Filter berdasarkan tanggal
        if ($this->request->filled('start_date')) {
            $purchaseQuery->whereDate('purchase_date', '>=', $this->request->start_date);
        }

        if ($this->request->filled('end_date')) {
            $purchaseQuery->whereDate('purchase_date', '<=', $this->request->end_date);
        }

        // Filter berdasarkan produk
        if ($this->request->filled('product_id')) {
            $purchaseQuery->whereHas('details', function ($query) {
                $query->where('product_id', $this->request->product_id);
            });
        }

        $purchases = $purchaseQuery->get()->groupBy('vendor_id');
        $rows = new Collection();

        // Catat posisi awal data transaksi
        $this->rowCount = count($rows);

        foreach ($purchases as $vendorId => $vendorPurchases) {
            // Add vendor header
            $vendorRow = $this->rowCount + 2; // +2 untuk header
            $this->vendorRows[] = $vendorRow;

            $rows->push([
                'type' => 'vendor_header',
                'vendor_name' => $vendorPurchases->first()->vendor->name
            ]);

            $this->rowCount++;

            foreach ($vendorPurchases as $purchase) {
                $purchaseDetails = $this->request->filled('product_id')
                    ? $purchase->details->where('product_id', $this->request->product_id)
                    : $purchase->details;

                $startRow = $this->rowCount + 2;
                $totalRows = count($purchaseDetails);

                if ($totalRows > 0) {
                    $this->mergeRows[] = [
                        'start' => $startRow,
                        'end' => $startRow + $totalRows - 1
                    ];
                }

                foreach ($purchaseDetails as $detail) {
                    $rows->push([
                        'type' => 'detail',
                        'purchase_date' => $purchase->purchase_date,
                        'vendor_name' => $purchase->vendor->name,
                        'product' => $detail->product->name,
                        'quantity' => $detail->quantity,
                        'price' => $detail->unit_price,
                        'subtotal' => $detail->subtotal,
                        'total' => $purchase->total_amount
                    ]);
                    $this->rowCount++;
                }
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal Pembelian',
            'Vendor',
            'Nama Barang',
            'Jumlah',
            'Harga Satuan (IDR)',
            'Total (IDR)',
            'Total Keseluruhan'
        ];
    }

    public function map($row): array
    {
        if ($row['type'] === 'summary_header') {
            return [$row['content'], '', '', '', '', '', ''];
        }

        if ($row['type'] === 'summary') {
            return [$row['label'], $row['value'], '', '', '', '', ''];
        }

        if ($row['type'] === 'empty') {
            return ['', '', '', '', '', '', ''];
        }

        if ($row['type'] === 'vendor_header') {
            return ['Pemasok: ' . $row['vendor_name'], '', '', '', '', '', ''];
        }

        if ($row['type'] === 'detail') {
            return [
                $row['purchase_date']->format('d/m/Y'),
                $row['vendor_name'],
                $row['product'],
                number_format($row['quantity'], 0, ',', '.'),
                number_format($row['price'], 0, ',', '.'),
                number_format($row['subtotal'], 0, ',', '.'),
                number_format($row['total'], 0, ',', '.')
            ];
        }

        return ['', '', '', '', '', '', '']; // fallback
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header style
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Style untuk seluruh cell
                $sheet->getStyle('A1:G' . ($this->rowCount + 1))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Style untuk ringkasan
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1:G4')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F8FAFC');

                // Vendor headers
                foreach ($this->vendorRows as $row) {
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$row}")->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F3F4F6');

                    $sheet->getStyle("A{$row}:G{$row}")->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER_CONTINUOUS);
                }

                // Merge cells for total keseluruhan
                foreach ($this->mergeRows as $merge) {
                    if ($merge['start'] !== $merge['end']) {
                        $sheet->mergeCells("G{$merge['start']}:G{$merge['end']}");
                    }
                }

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(20); // Tanggal
                $sheet->getColumnDimension('B')->setWidth(25); // Vendor
                $sheet->getColumnDimension('C')->setWidth(35); // Nama Barang
                $sheet->getColumnDimension('D')->setWidth(15); // Jumlah
                $sheet->getColumnDimension('E')->setWidth(20); // Harga Satuan
                $sheet->getColumnDimension('F')->setWidth(20); // Total
                $sheet->getColumnDimension('G')->setWidth(20); // Total Keseluruhan

                // Align numbers to right
                $sheet->getStyle('D6:G' . ($this->rowCount + 1))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Center align headers
                $sheet->getStyle('A1:G1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Auto-wrap text
                $sheet->getStyle('A1:G' . ($this->rowCount + 1))
                    ->getAlignment()
                    ->setWrapText(true);
            },
        ];
    }
}
