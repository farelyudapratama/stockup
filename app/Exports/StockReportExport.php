<?php
// app/Exports/StockReportExport.php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StockReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return Product::with([
            'productHistories' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Stok Awal',
            'Stok Saat Ini',
            'Perubahan',
            'Riwayat Perubahan'
        ];
    }

    public function map($product): array
    {
        $stockDiff = $product->current_stock - $product->initial_stock;

        $history = $product->productHistories->map(function ($item) {
            return sprintf(
                "%s → %s (%s) pada %s",
                $item->old_value,
                $item->new_value,
                $item->reason_changed,
                \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i')
            );
        })->join("\n");

        return [
            $product->name,
            $product->initial_stock,
            $product->current_stock,
            ($stockDiff >= 0 ? '+' : '') . $stockDiff,
            $history
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],

            'A1:E1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E5E7EB']
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle('D2:D' . $highestRow)->getNumberFormat()
                    ->setFormatCode('0');

                $sheet->getStyle('D2:D' . $highestRow)
                    ->getFont()
                    ->getColor()
                    ->setARGB('FF0000');
                foreach ($sheet->getColumnIterator('D') as $column) {
                    foreach ($column->getCellIterator() as $cell) {
                        if ($cell->getValue() > 0) {
                            $cell->getStyle()->getFont()->getColor()->setARGB('008000');
                        }
                    }
                }

                foreach (range('A', 'E') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $sheet->getStyle('E2:E' . $highestRow)
                    ->getAlignment()
                    ->setWrapText(true);
            }
        ];
    }
}