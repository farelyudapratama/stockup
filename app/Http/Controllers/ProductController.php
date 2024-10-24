<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductHistory;
// use App\Models\Vendor;

class ProductController extends Controller
{
    public function create()
    {
        // $vendors = Vendor::all(); // Ambil semua vendor dari database
        // return view('create-product', compact('vendors'));

        return view('create-product');
    }
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initial_stock' => 'required|integer|min:0',
        ]);

        // Ini nyimpan produk baru
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'initial_stock' => $request->initial_stock,
            'current_stock' => $request->initial_stock,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan');
    }


    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);
        $search = $request->input('search', '');

        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate($entries);

        return view('product-list', compact('products', 'entries', 'search'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('product-edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initial_stock' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
            'reason_changed' => 'required|string',
        ]);

        $product = Product::findOrFail($id);

        if ($product->name !== $request->name) {
            ProductHistory::create([
                'product_id' => $product->id,
                'changed_field' => 'name',
                'old_value' => $product->name,
                'new_value' => $request->name,
                'reason_changed' => $request->reason_changed,
            ]);
        }

        if ($product->description !== $request->description) {
            ProductHistory::create([
                'product_id' => $product->id,
                'changed_field' => 'description',
                'old_value' => $product->description,
                'new_value' => $request->description,
                'reason_changed' => $request->reason_changed,
            ]);
        }

        if ($product->initial_stock != $request->initial_stock) {
            ProductHistory::create([
                'product_id' => $product->id,
                'changed_field' => 'initial_stock',
                'old_value' => $product->initial_stock,
                'new_value' => $request->initial_stock,
                'reason_changed' => $request->reason_changed,
            ]);
        }

        if ($product->current_stock != $request->current_stock) {
            ProductHistory::create([
                'product_id' => $product->id,
                'changed_field' => 'current_stock',
                'old_value' => $product->current_stock,
                'new_value' => $request->current_stock,
                'reason_changed' => $request->reason_changed,
            ]);
        }

        // Update produk setelah semua perbandingan selesai
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'initial_stock' => $request->initial_stock,
            'current_stock' => $request->current_stock,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui');
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }
}