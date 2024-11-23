<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sales;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10); // Default pagination 10
        $search = $request->input('search', '');  // Pencarian berdasarkan nama pembeli atau ID penjualan

        $salesQuery = Sales::query();

        // Pencarian berdasarkan ID penjualan atau nama pembeli
        if ($search) {
            $salesQuery->where(function ($query) use ($search) {
                $query->where('buyer_name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        $sales = $salesQuery->with('details.product')
            ->orderBy('sale_date', 'desc')
            ->paginate($entries);

        return view('sale-list', compact('sales', 'search', 'entries'));
    }

    public function create()
    {
        $products = Product::all();

        return view('sale-add', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buyer_name' => 'required',
            'sale_date' => 'required',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.price' => 'required|numeric|min:0',
        ]);

        $sale = Sales::create([
            'buyer_name' => $request->buyer_name,
            'sale_date' => $request->sale_date,
        ]);

        $total = 0;
        foreach ($request->details as $detail) {
            $product = Product::find($detail['product_id']);
            $total += $detail['quantity'] * $detail['price'];

            $sale->details()->create([
                'product_id' => $detail['product_id'],
                'quantity' => $detail['quantity'],
                'price' => $detail['price'],
            ]);

            $product->stock -= $detail['quantity'];
            $product->save();
        }

        $sale->total = $total;
        $sale->save();

        return redirect()->route('sales.index');
    }
}