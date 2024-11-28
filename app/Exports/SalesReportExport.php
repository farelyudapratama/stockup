<?php

namespace App\Exports;

use App\Models\SaleDetail;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromQuery, WithMapping, WithHeadings, WithStyles, WithEvents
{
    use Exportable;

    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = SaleDetail::with('product');

        // Apply filters based on the passed parameters
        if (!empty($this->filters['product_id'])) {
            $query->where('product_id', $this->filters['product_id']);
        }

        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $startDate = $this->filters['start_date'] . ' 00:00:00';
            $endDate = $this->filters['end_date'] . ' 23:59:59';

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query;
    }

    public function map($sale): array
    {
        return [
            $sale->product_id,
            $sale->product->name,
            $sale->quantity,
            number_format($sale->unit_price, 0, ',', '.'),
            number_format($sale->subtotal, 0, ',', '.'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID Barang',
            'Nama Barang',
            'Jumlah Barang Terjual',
            'Harga Jual Satuan (IDR)',
            'Total Penjualan (IDR)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow(); // Get the last row with data
        return [
            1 => ['font' => ['bold' => true, 'size' => 18], 'alignment' => ['horizontal' => 'center']],
            2 => ['font' => ['bold' => true, 'size' => 16], 'alignment' => ['horizontal' => 'center']],
            3 => ['font' => ['size' => 14], 'alignment' => ['horizontal' => 'center']],
            'A5:E5' => ['font' => ['bold' => true]],
            "A6:E$highestRow" => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow(); // Get the last row with data
    
                // Ambil filter tanggal
                $startDate = Arr::get($this->filters, 'start_date', 'Semua Waktu');
                $endDate = Arr::get($this->filters, 'end_date', 'Semua Waktu');

                // Set title dan merge cells
                $sheet->setCellValue('A1', 'Laporan Penjualan');
                $websiteName = env('APP_NAME', 'Website Name');
                $sheet->setCellValue('A2', $websiteName);

                // Set rentang tanggal
                if ($startDate !== 'Semua Waktu' && $endDate !== 'Semua Waktu') {
                    $sheet->setCellValue('A3', 'Rentang Tanggal: ' . $startDate . ' - ' . $endDate);
                } else {
                    $sheet->setCellValue('A3', 'Rentang Tanggal: ' . $startDate);
                }

                // Merge cells untuk judul dan nama website
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A3:E3');

                // Set row heights untuk memberi jarak antara judul dan data
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(25);
                $sheet->getRowDimension(3)->setRowHeight(20);

                // Set lebar kolom untuk setiap kolom
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(21);
                $sheet->getColumnDimension('E')->setWidth(20);

                // Apply border ke semua sel dari A1 sampai dengan baris terakhir
                $sheet->getStyle("A5:E$highestRow")
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle("A5:E$highestRow")->getAlignment()->setHorizontal('center');

                // Buat A4 : E5 kosong dan tidak ada style
                $sheet->setCellValue('A4', '');
                $sheet->setCellValue('B4', '');
                $sheet->setCellValue('C4', '');
                $sheet->setCellValue('D4', '');
                $sheet->setCellValue('E4', '');
                $sheet->getStyle('A4:E4')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);

                $sheet->setCellValue('A5', 'ID Barang');
                $sheet->setCellValue('B5', 'Nama Barang');
                $sheet->setCellValue('C5', 'Jumlah Barang Terjual');
                $sheet->setCellValue('D5', 'Harga Jual Satuan (IDR)');
                $sheet->setCellValue('E5', 'Total Penjualan (IDR)');

                // Format angka untuk kolom D dan E (Harga Jual & Total Penjualan)
                $sheet->getStyle("D6:D$highestRow")
                    ->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("E6:E$highestRow")
                    ->getNumberFormat()->setFormatCode('#,##0');
            }
        ];
    }
}
