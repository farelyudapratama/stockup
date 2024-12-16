<?php

namespace App\Services;

use App\Models\ProductHistory;
use App\Models\Product;

class StockDataService
{
    public function getStockData($userLevel)
    {
        // Ambil data stok masuk, dikelompokkan per produk dan bulan
        $stockIn = ProductHistory::where('changed_field', 'current_stock')
            ->whereRaw('CAST(new_value AS INTEGER) > CAST(old_value AS INTEGER)')
            ->selectRaw('product_id, strftime("%Y-%m", created_at) as month, SUM(CAST(new_value AS INTEGER) - CAST(old_value AS INTEGER)) as total')
            ->groupBy('product_id', 'month')
            ->orderBy('month', 'asc');

        // Ambil data stok keluar, dikelompokkan per produk dan bulan
        $stockOut = ProductHistory::where('changed_field', 'current_stock')
            ->whereRaw('CAST(new_value AS INTEGER) < CAST(old_value AS INTEGER)')
            ->selectRaw('product_id, strftime("%Y-%m", created_at) as month, SUM(CAST(old_value AS INTEGER) - CAST(new_value AS INTEGER)) as total')
            ->groupBy('product_id', 'month')
            ->orderBy('month', 'asc');

        // Filter berdasarkan level user
        switch ($userLevel) {
            case 'admin':
                break;
            case 'stocker':
                break;
            case 'seller':
                $stockOut = $stockIn->where('', '');
                break;
            case 'purchaser':
                $stockOut = $stockIn->where('', '');
                break;
        }

        $stockIn = $stockIn->get();
        $stockOut = $stockOut->get();

        // Ambil produk
        $products = Product::all()->pluck('name', 'id');

        // Menyiapkan data untuk chart
        $stockInData = [];
        $stockOutData = [];
        $dates = [];

        foreach ($stockIn as $entry) {
            $stockInData[$entry->product_id][$entry->month] = $entry->total;
            if (!in_array($entry->month, $dates)) {
                $dates[] = $entry->month;
            }
        }

        foreach ($stockOut as $entry) {
            $stockOutData[$entry->product_id][$entry->month] = $entry->total;
        }

        // Siapkan data untuk chart
        $chartData = [];
        foreach ($products as $productId => $productName) {
            $chartData[] = [
                'product_name' => $productName,
                'stock_in' => array_map(fn($month) => $stockInData[$productId][$month] ?? 0, $dates),
                'stock_out' => array_map(fn($month) => $stockOutData[$productId][$month] ?? 0, $dates),
            ];
        }

        return [
            'chartData' => $chartData,
            'dates' => $dates
        ];
    }
}