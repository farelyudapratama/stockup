<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\PurchaseDetail;
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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initial_stock' => 'required|integer|min:0',
        ]);

        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'initial_stock' => $request->initial_stock,
                'current_stock' => $request->initial_stock,
            ]);

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan produk: ' . $e->getMessage());
        }
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

        $isNameChanged = $product->name !== $request->name;
        $isDescriptionChanged = $product->description !== $request->description;
        $isInitialStockChanged = $product->initial_stock != $request->initial_stock;
        $isCurrentStockChanged = $product->current_stock != $request->current_stock;

        if (!$isNameChanged && !$isDescriptionChanged && !$isInitialStockChanged && !$isCurrentStockChanged) {
            return redirect()->back()->with('error', 'Tidak ada perubahan yang disimpan karena data sama.');
        }

        if ($isNameChanged) {
            ProductHistory::create([
                'product_id' => $product->id,
                'changed_field' => 'name',
                'old_value' => $product->name,
                'new_value' => $request->name,
                'reason_changed' => $request->reason_changed,
            ]);
        }

        if ($isDescriptionChanged) {
            ProductHistory::create([
                'product_id' => $product->id,
                'changed_field' => 'description',
                'old_value' => $product->description,
                'new_value' => $request->description,
                'reason_changed' => $request->reason_changed,
            ]);
        }

        if ($isInitialStockChanged) {
            ProductHistory::create([
                'product_id' => $product->id,
                'changed_field' => 'initial_stock',
                'old_value' => $product->initial_stock,
                'new_value' => $request->initial_stock,
                'reason_changed' => $request->reason_changed,
            ]);
        }

        if ($isCurrentStockChanged) {
            ProductHistory::create([
                'product_id' => $product->id,
                'changed_field' => 'current_stock',
                'old_value' => $product->current_stock,
                'new_value' => $request->current_stock,
                'reason_changed' => $request->reason_changed,
            ]);
        }

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

        PurchaseDetail::where('product_id', $product->id)->delete();
        ProductHistory::where('product_id', $product->id)->delete();

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }
}