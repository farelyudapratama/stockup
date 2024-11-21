<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index(Request $request)
    {
        $entries = $request->input('entries');
        $search = $request->input('search', '');

        $query = Product::with('productPrices');

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $products = $query->paginate($entries);

        return view('price-list', compact('products', 'search', 'entries'));
    }


    public function create()
    {
        $products = Product::with('productPrices')->get();
        return view('price-add', compact('products'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required',
        ]);

        try {
            $price = (float) preg_replace('/[^\d]/', '', $request->price);
            ProductPrice::create([
                'product_id' => $request->input('product_id'),
                'price' => $price,
            ]);
            return redirect()->route('prices.index')->with('success', 'Data harga berhasil diatur');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengatur harga: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->productPrices->isEmpty()) {
                return redirect()->route('prices.index')->with('error', 'Tidak ada harga yang bisa dihapus untuk produk ini.');
            }
            $product->productPrices()->delete();

            return redirect()->route('prices.index')->with('success', 'Semua harga produk berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('prices.index')->with('error', 'Gagal menghapus harga: ' . $e->getMessage());
        }
    }
}