<?php

namespace App\Http\Controllers;

use App\Models\Product;
// use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductHistory;
use App\Services\StockDataService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected $stockDataService;
    public function __construct(StockDataService $stockDataService)
    {
        $this->stockDataService = $stockDataService;
    }
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
                return redirect('/');
        }
    }
    function showWelcome()
    {
        $user = Auth::user();
        $role = $user->role;
        $products = Product::all();

        // Ambil 5 produk terlaris dalam 30 hari terakhir
        $topProducts = Product::select('products.name', DB::raw('SUM(sale_details.quantity) as total_sold'))
            ->join('sale_details', 'sale_details.product_id', '=', 'products.id')
            ->join('sales', 'sales.id', '=', 'sale_details.sales_id')
            ->where('sales.created_at', '>=', Carbon::now()->subDays(30)) // Filter berdasarkan waktu penjualan
            ->groupBy('products.id', 'products.name') // Grouping berdasarkan produk
            ->orderByDesc('total_sold') // Urutkan berdasarkan total penjualan
            ->limit(5) // Ambil 5 produk terlaris
            ->get();

        // total stock
        $totalStock = Product::sum('current_stock');

        // stock paling sedikit dengan nama barangnya
        $minStock = Product::select('name', 'current_stock')
            ->orderBy('current_stock')
            ->first();

        // Pendapatan kotor bulan ini
        $totalPendapatan = DB::table('sale_details')
            ->join('products', 'products.id', '=', 'sale_details.product_id')
            ->join('sales', 'sales.id', '=', 'sale_details.sales_id')
            ->whereMonth('sales.created_at', '=', Carbon::now()->month) // Filter berdasarkan bulan ini
            ->whereYear('sales.created_at', '=', Carbon::now()->year) // Filter berdasarkan tahun ini
            ->select(DB::raw('SUM(sale_details.quantity * products.selling_price) as total_income')) // Menghitung total pendapatan
            ->first(); // Ambil hasil pertama (karena hanya ada satu nilai total pendapatan)

        $totalPendapatan = $totalPendapatan->total_income ?? 0; // Jika tidak ada data, set menjadi 0

        // Pendapatan kotor bulan lalu
        $totalPendapatanBulanLalu = DB::table('sale_details')
            ->join('products', 'products.id', '=', 'sale_details.product_id')
            ->join('sales', 'sales.id', '=', 'sale_details.sales_id')
            ->whereRaw("strftime('%Y-%m', sales.created_at) = ?", [Carbon::now()->subMonth()->format('Y-m')])  // Filter berdasarkan bulan lalu
            ->select(DB::raw('SUM(sale_details.quantity * products.selling_price) as total_income'))
            ->first();


        $totalPendapatanBulanLalu = $totalPendapatanBulanLalu->total_income ?? 0; // Jika tidak ada data, set menjadi 0
        // dd(Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth());


        $persentasePerubahan = 0;
        if ($totalPendapatanBulanLalu > 0) {
            $persentasePerubahan = (($totalPendapatan - $totalPendapatanBulanLalu) / $totalPendapatanBulanLalu) * 100;
        } elseif ($totalPendapatan > 0) {
            // Jika bulan lalu tidak ada pendapatan, tetapi bulan ini ada, berarti ada kenaikan yang signifikan
            $persentasePerubahan = 'Tidak terdeteksi';
        }

        $stockData = $this->stockDataService->getStockData($role);
        return view('welcome', [
            // 'chartData' => $stockData['chartData'],
            // 'dates' => $stockData['dates']
            'products' => $products,
            'topProducts' => $topProducts,
            'totalStock' => $totalStock,
            'minStock' => $minStock,
            'totalPendapatan' => $totalPendapatan,
            'persentasePerubahan' => $persentasePerubahan
        ]);
    }
}
