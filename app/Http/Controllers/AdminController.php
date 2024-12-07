<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductHistory;

class AdminController extends Controller
{
    function index()
    {
        $user = Auth::user();

        // Pastikan pengguna sudah login
        if (!$user) {
            return redirect('/login');
        }

        // Redirect sesuai role
        switch ($user->role) {
            case 'admin':
                return redirect('/admin');
            case 'stocker':
                return redirect('/stocker');
            case 'purchaser':
                return redirect('/purchaser');
            case 'seller':
                return redirect('/seller');
            default:
                return redirect('/'); // Jika role tidak dikenal
        }
    }

    // function admin()
    // {
    //     return $this->showStockInOut();
    //     // return view('welcome');
    // }
    // function stocker()
    // {
    //     return $this->showStockInOut();
    //     // return view('welcome');
    // }
    // function purchaser()
    // {
    //     return $this->showStockInOut();
    //     // return view('welcome');
    // }
    // function seller()
    // {
    //     return $this->showStockInOut();
    //     // return view('welcome');
    // }

    function showWelcome()
    {
        $user = Auth::user();
        $role = $user->role;

        // Kirim data yang dibutuhkan ke view welcome
        return $this->showStockInOut();
    }

    function showStockInOut()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

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
        switch ($user->level) {
            case 'admin':
                // Admin bisa lihat semua
                break;
            case 'stocker':
                // Stocker tidak bisa lihat apa-apa
                $stockIn->whereRaw('1 = 0');
                $stockOut->whereRaw('1 = 0');
                break;
            case 'seller':
                // Seller hanya bisa lihat stock out
                $stockIn->whereRaw('1 = 0');
                break;
            case 'purchaser':
                // Purchaser hanya bisa lihat stock in
                $stockOut->whereRaw('1 = 0');
                break;
        }

        $stockIn = $stockIn->get();
        $stockOut = $stockOut->get();

        // Ambil daftar produk
        $products = Product::all()->pluck('name', 'id');

        // Siapkan data untuk grafik
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

        // Data yang akan dikirim ke view
        $chartData = [];
        foreach ($products as $productId => $productName) {
            $chartData[] = [
                'product_name' => $productName,
                'stock_in' => array_map(fn($month) => $stockInData[$productId][$month] ?? 0, $dates),
                'stock_out' => array_map(fn($month) => $stockOutData[$productId][$month] ?? 0, $dates),
            ];
        }

        // Tambahkan variabel untuk mengontrol tampilan di view
        $userLevel = $user->level;

        return view('welcome', compact('chartData', 'dates', 'userLevel'));
    }
}
