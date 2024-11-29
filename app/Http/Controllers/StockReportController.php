<?php

namespace App\Http\Controllers;

use App\Exports\StockReportExport;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class StockReportController extends Controller
{
    //
    public function index()
    {
        $products = Product::with([
            'purchaseDetails',
            'productHistories' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->get();

        $chartData = $products->map(function($product) {
            $histories = collect([$product->initial_stock]);
            
            // Tambahkan data histories
            $histories = $histories->merge($product->productHistories->pluck('new_value'));
            
            // Buat labels tanggal
            $dates = collect([Carbon::parse($product->created_at)->format('Y-m-d')]);
            $dates = $dates->merge($product->productHistories->pluck('created_at')
                ->map(fn($date) => Carbon::parse($date)->format('Y-m-d')));

            return [
                'name' => $product->name,
                'data' => $histories->values(),
                'dates' => $dates->values()
            ];
        });

        return view('stock.stock', compact('products', 'chartData'));
    }
    public function export()
    {
        return Excel::download(new StockReportExport, 'laporan-stok-' . now()->format('Y-m-d') . '.xlsx');
    }
}