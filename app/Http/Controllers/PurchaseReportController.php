<?php

namespace App\Http\Controllers;

use App\Exports\PurchasesExport;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Vendor;

class PurchaseReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua vendor dan produk untuk form filter
        $vendors = Vendor::all();
        $products = Product::all();
        $entries = $request->input('entries', 10);

        // Query dasar untuk pembelian
        $purchaseQuery = Purchase::with(['vendor', 'details.product'])
            ->latest('purchase_date');

        // Filter berdasarkan vendor
        if ($request->filled('vendor_id')) {
            $purchaseQuery->where('vendor_id', $request->vendor_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $purchaseQuery->whereDate('purchase_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $purchaseQuery->whereDate('purchase_date', '<=', $request->end_date);
        }

        // Filter berdasarkan produk
        if ($request->filled('product_id')) {
            // Pastikan hanya pembelian yang memiliki produk tertentu
            $purchaseQuery->whereHas('details', function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            });

            // Mengambil hanya produk yang sesuai di dalam setiap pembelian
            $purchaseQuery->with([
                'details' => function ($query) use ($request) {
                    $query->where('product_id', $request->product_id);
                }
            ]);
        }

        $totalPurchases = (clone $purchaseQuery)->count();
        $totalAmount = (clone $purchaseQuery)->sum('total_amount');
        $totalVendors = (clone $purchaseQuery)->distinct('vendor_id')->count('vendor_id');

        // Pagination dengan 10 item per halaman
        $purchases = $purchaseQuery->paginate($entries);

        // Query untuk mendapatkan stok produk
        $productQuery = Product::query();

        // Filter produk berdasarkan vendor jika ada
        if ($request->filled('vendor_id')) {
            $productQuery->whereHas('purchaseDetails.purchase', function ($query) use ($request) {
                $query->where('vendor_id', $request->vendor_id);
            });
        }

        // Filter produk berdasarkan tanggal jika ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $productQuery->whereHas('purchaseDetails.purchase', function ($query) use ($request) {
                $query->whereBetween('purchase_date', [
                    $request->start_date,
                    $request->end_date
                ]);
            });
        }

        // Filter berdasarkan ID produk
        if ($request->filled('product_id')) {
            $productQuery->where('id', $request->product_id);
        }

        // Ambil data produk dengan stok
        $productsWithStock = $productQuery->withSum('purchaseDetails as total_purchased', 'quantity')
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'description' => $product->description,
                    'initial_stock' => $product->initial_stock,
                    'current_stock' => $product->initial_stock + ($product->total_purchased ?? 0)
                ];
            });

        return view('reports.purchase', compact(
            'products',
            'purchases',
            'vendors',
            'productsWithStock',
            'entries',
            'totalPurchases',
            'totalAmount',
            'totalVendors'
        ));
    }

    public function exportToExcel(Request $request)
    {
        return Excel::download(new PurchasesExport($request), 'laporan-pembelian.xlsx');
    }
}