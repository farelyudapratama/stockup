<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductHistory;
use App\Services\StockDataService;

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

        $stockData = $this->stockDataService->getStockData($role);
        return view('welcome', [
            'chartData' => $stockData['chartData'],
            'dates' => $stockData['dates']
        ]);
    }
}
