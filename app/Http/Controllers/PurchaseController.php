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
            'total_amount' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric',
        ]);

        try {
            $purchase = Purchase::create([
                'vendor_id' => $request->vendor_id,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $request->total_amount,
            ]);

            foreach ($request->products as $productData) {
                // Create purchase detail
                $purchaseDetail = PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'subtotal' => $productData['quantity'] * $productData['unit_price'],
                ]);

                // Update product stock
                $product = Product::findOrFail($productData['product_id']);

                // Capture the old value before modifying
                $oldStock = $product->current_stock;

                // Update the current stock
                $product->current_stock += $productData['quantity'];
                $product->save();

                // Log the stock change in product history
                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $oldStock, // Use the captured old stock value
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Purchase added',
                ]);
            }


            return response()->json(['message' => 'Purchase and stock updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to process purchase. Error: ' . $e->getMessage()], 500);
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
        $vendors = Vendor::all(); // Load vendors for the edit view
        $products = Product::all(); // Load products for the edit view

        return view('purchase-edit', compact('purchase', 'vendors', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric',
        ]);

        try {
            $purchase = Purchase::findOrFail($id);

            // Update purchase main information
            $purchase->update([
                'vendor_id' => $request->vendor_id,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $request->total_amount,
            ]);

            // First, remove old purchase details
            foreach ($purchase->details as $detail) {
                $product = Product::findOrFail($detail->product_id);
                $oldStock = $product->current_stock;

                // Decrease the stock back by the old quantity
                $product->current_stock -= $detail->quantity;
                $product->save();

                // Log the stock change in product history
                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $oldStock,
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Purchase updated (detail removed)',
                ]);

                $detail->delete(); // Delete the old detail
            }

            // Add new purchase details
            foreach ($request->products as $productData) {
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'subtotal' => $productData['quantity'] * $productData['unit_price'],
                ]);

                // Update the product stock
                $product = Product::findOrFail($productData['product_id']);
                $oldStock = $product->current_stock;
                $product->current_stock += $productData['quantity'];
                $product->save();

                // Log the stock change in product history
                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $oldStock,
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Purchase updated (detail added)',
                ]);
            }

            return response()->json(['message' => 'Purchase and stock updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update purchase. Error: ' . $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);

            // Update stock before deleting purchase details
            foreach ($purchase->details as $detail) {
                $product = Product::findOrFail($detail->product_id);
                $oldStock = $product->current_stock;
                $product->current_stock -= $detail->quantity;
                $product->save();

                // Optionally log the stock change in product history
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

            return response()->json(['message' => 'Purchase deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete purchase. Error: ' . $e->getMessage()], 500);
        }
    }
}