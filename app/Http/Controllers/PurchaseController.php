<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Vendor;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function create()
    {
        $vendors = Vendor::all();
        $products = Product::all();

        return view('purchase-add', compact('vendors', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'total_amount' => 'required',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required',
        ]);

        try {
            $totalAmount = (float) preg_replace('/[^\d]/', '', $request->total_amount);

            $purchase = Purchase::create([
                'vendor_id' => $request->vendor_id,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $totalAmount,
            ]);

            foreach ($request->products as $productData) {
                $unitPrice = (float) preg_replace('/[^\d]/', '', $productData['unit_price']);

                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $unitPrice * $productData['quantity'],
                ]);

                $product = Product::findOrFail($productData['product_id']);

                $oldStock = $product->current_stock;

                $product->current_stock += $productData['quantity'];
                $product->save();

                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $oldStock,
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Purchase added',
                ]);
            }

            return redirect()->back()->with('success', 'Pembelian berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add purchase. Error: ' . $e->getMessage());
        }
    }


    public function index(Request $request)
    {
        $query = Purchase::query()->with(['vendor', 'details.product']);

        if ($request->filled('vendor')) {
            $query->where('vendor_id', $request->vendor);
        }

        if ($request->filled('purchase_id')) {
            $query->where('id', 'LIKE', '%' . $request->purchase_id . '%');
        }

        if ($request->filled('product')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('product_id', $request->product);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        $entries = $request->get('entries', 10);
        $purchases = $query->orderBy('purchase_date', 'desc')->paginate($entries)->withQueryString();

        $vendors = Vendor::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchase-list', compact('purchases', 'vendors', 'products'));
    }

    public function edit($id)
    {
        $purchase = Purchase::with('details')->findOrFail($id);
        $vendors = Vendor::all();
        $products = Product::all();

        return view('purchase-edit', compact('purchase', 'vendors', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'total_amount' => 'required',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required',
        ]);

        try {
            $purchase = Purchase::findOrFail($id);

            $totalAmount = (float) preg_replace('/[^\d]/', '', $request->total_amount);

            $purchase->update([
                'vendor_id' => $request->vendor_id,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $totalAmount,
            ]);

            foreach ($purchase->details as $detail) {
                $product = Product::findOrFail($detail->product_id);
                $oldStock = $product->current_stock;

                $product->current_stock -= $detail->quantity;
                $product->save();

                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $oldStock,
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Purchase updated (detail removed)',
                ]);

                $detail->delete();
            }

            foreach ($request->products as $productData) {
                $unitPrice = (float) preg_replace('/[^\d]/', '', $productData['unit_price']);
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $productData['quantity'] * $unitPrice,
                ]);

                $product = Product::findOrFail($productData['product_id']);
                $oldStock = $product->current_stock;
                $product->current_stock += $productData['quantity'];
                $product->save();

                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $oldStock,
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Purchase updated (detail added)',
                ]);
            }

            return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update purchase. Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);

            foreach ($purchase->details as $detail) {
                $product = Product::findOrFail($detail->product_id);
                $oldStock = $product->current_stock;
                $product->current_stock -= $detail->quantity;
                $product->save();

                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $oldStock,
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Purchase deleted',
                ]);

                $detail->delete();
            }

            $purchase->delete();

            return redirect()->back()->with('success', 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete purchase. Error: ' . $e->getMessage()], 500);
        }
    }
}