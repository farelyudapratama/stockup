<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $productId = $request->input('product_id', 'all');
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $products = Product::all();

        $query = ProductHistory::with('product')
            ->where('changed_field', 'current_stock')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        if ($productId !== 'all') {
            $query->where('product_id', $productId);
        }

        $stockMovements = $query->orderBy('created_at', 'desc')->get()->map(function ($movement) {
            $quantity = abs((int) $movement->new_value - (int) $movement->old_value);
            $movement->quantity = $quantity; // Tambahkan jumlah ke setiap entri
            $movement->type = (int) $movement->new_value > (int) $movement->old_value ? 'Masuk' : 'Keluar';
            return $movement;
        });

        $stockIn = $stockMovements->filter(function ($movement) {
            return (int) $movement->new_value > (int) $movement->old_value;
        });

        $stockOut = $stockMovements->filter(function ($movement) {
            return (int) $movement->new_value < (int) $movement->old_value;
        });

        $totalStockIn = $stockIn->sum(function ($movement) {
            return (int) $movement->new_value - (int) $movement->old_value;
        });

        $totalStockOut = $stockOut->sum(function ($movement) {
            return (int) $movement->old_value - (int) $movement->new_value;
        });

        // Versi simpel stok masuk
        $simpleStockIn = $stockIn->groupBy('product_id')->map(function ($group) {
            return [
                'product_name' => $group->first()->product->name,
                'total_in' => $group->sum(function ($movement) {
                    return (int) $movement->new_value - (int) $movement->old_value;
                })
            ];
        });

        // Versi simpel stok keluar berdasarkan penjualan
        $simpleStockOut = SaleDetail::with('product', 'sale')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->when($productId !== 'all', function ($query) use ($productId) {
                return $query->where('product_id', $productId);
            })
            ->groupBy('product_id')
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_out')
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->product->name,
                    'total_out' => $item->total_out
                ];
            });

        return view('stock.stock', [
            'products' => $products,
            'stockMovements' => $stockMovements,
            'stockIn' => $stockIn,
            'stockOut' => $stockOut,
            'totalStockIn' => $totalStockIn,
            'totalStockOut' => $totalStockOut,
            'simpleStockIn' => $simpleStockIn,
            'simpleStockOut' => $simpleStockOut,
            'selectedProductId' => $productId,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}