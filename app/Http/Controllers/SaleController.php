<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\ProductPrice;
use App\Models\Sales;
use App\Models\SaleDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);
        $search = $request->input('search', '');
        $startDate = $request->input('start_date', '');
        $endDate = $request->input('end_date', '');

        $salesQuery = Sales::query();

        if ($search) {
            $salesQuery->where(function ($query) use ($search) {
                $query->where('buyer_name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        if ($startDate && $endDate) {
            $salesQuery->whereBetween('sale_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $salesQuery->where('sale_date', '>=', $startDate);
        } elseif ($endDate) {
            $salesQuery->where('sale_date', '<=', $endDate);
        }

        $sales = $salesQuery->with('details.product')
            ->orderBy('sale_date', 'desc')
            ->paginate($entries);

        return view('sale-list', compact('sales', 'search', 'entries', 'startDate', 'endDate'));
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
            'sale_date' => 'required|date',
            'total_amount' => 'required',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required',
        ]);

        try {
            $totalAmount = (float) preg_replace('/[^\d]/', '', $request->total_amount);
            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['product_id']);

                // Validasi harga produk
                if (is_null($product->selling_price)) {
                    $productUrl = route('products.edit', $product->id);

                    return redirect()->back()->withInput()->with([
                        'error' => 'Harga produk ' . $product->name . ' tidak tersedia.',
                        'error_title' => 'Peringatan Harga',
                        'error_type' => 'warning',
                        'show_link_button' => true,
                        'product_url' => $productUrl,
                    ]);
                }

                // Validasi stok
                if ($product->current_stock < $productData['quantity']) {
                    return redirect()->back()->withInput()->with([
                        'error' => 'Stok produk ' . $product->name . ' tidak mencukupi.',
                        'error_title' => 'Peringatan Stok',
                        'error_type' => 'warning',
                    ]);
                }
            }

            $sale = Sales::create([
                'buyer_name' => $request->buyer_name,
                'sale_date' => $request->sale_date,
                'total_amount' => $totalAmount,
            ]);

            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['product_id']);
                // TODO yang edit blom ini buat tanda aja

                $unitPrice = (float) preg_replace('/[^\d]/', '', $productData['unit_price']);

                SaleDetail::create([
                    'sales_id' => $sale->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $unitPrice * $productData['quantity'],
                ]);

                $oldStock = $product->current_stock;

                $product->current_stock -= $productData['quantity'];
                $product->save();

                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $oldStock,
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Sale added',
                ]);
            }

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan penjualan. Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $sale = Sales::with('details')->findOrFail($id);
        $products = Product::all();

        return view('sale-edit', compact('sale', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'buyer_name' => 'required',
            'sale_date' => 'required|date',
            'total_amount' => 'required',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required',
        ]);

        try {
            $sale = Sales::findOrFail($id);
            $totalAmount = (float) preg_replace('/[^\d]/', '', $request->total_amount);

            // Validasi semua produk terlebih dahulu
            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['product_id']);

                // Validasi harga
                if (is_null($product->selling_price)) {
                    $productUrl = route('products.edit', $product->id);

                    return redirect()->back()->withInput()->with([
                        'error' => 'Harga produk ' . $product->name . ' tidak tersedia.',
                        'error_title' => 'Peringatan Harga',
                        'error_type' => 'warning',
                        'show_link_button' => true,
                        'product_url' => $productUrl,
                    ]);
                }

                // Validasi stok (jika diperlukan)
                if ($product->current_stock + $sale->details->where('product_id', $product->id)->sum('quantity') < $productData['quantity']) {
                    return redirect()->back()->withInput()->with([
                        'error' => 'Stok produk ' . $product->name . ' tidak mencukupi.',
                        'error_title' => 'Peringatan Stok',
                        'error_type' => 'warning',
                    ]);
                }
            }

            // Kembalikan stok semua produk ke kondisi awal
            foreach ($sale->details as $detail) {
                $product = Product::findOrFail($detail->product_id);
                $product->current_stock += $detail->quantity;
                $product->save();

                // Catat perubahan stok
                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $product->current_stock - $detail->quantity,
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Sale updated (restocking)',
                ]);

                $detail->delete(); // Hapus detail lama
            }

            // Perbarui data penjualan
            $sale->update([
                'buyer_name' => $request->buyer_name,
                'sale_date' => $request->sale_date,
                'total_amount' => $totalAmount,
            ]);

            // Tambahkan detail baru
            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['product_id']);
                $unitPrice = (float) preg_replace('/[^\d]/', '', $productData['unit_price']);

                SaleDetail::create([
                    'sales_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $unitPrice * $productData['quantity'],
                ]);

                // Kurangi stok produk
                $oldStock = $product->current_stock;
                $product->current_stock -= $productData['quantity'];
                $product->save();

                // Catat perubahan stok
                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $oldStock,
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Sale updated',
                ]);
            }

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui penjualan. Error: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        try {
            $sale = Sales::with(['details.product', 'details.product.productPrices'])
                ->findOrFail($id);

            return view('sale-detail', compact('sale'));
        } catch (\Exception $e) {
            return redirect()->route('sales.index')
                ->with('error', 'Penjualan tidak ditemukan.');
        }
    }

    public function destroy($id)
    {
        try {
            $sale = Sales::findOrFail($id);

            foreach ($sale->details as $detail) {
                $product = Product::findOrFail($detail->product_id);
                $oldStock = $product->current_stock;

                $product->current_stock += $detail->quantity;
                $product->save();

                ProductHistory::create([
                    'product_id' => $product->id,
                    'changed_field' => 'current_stock',
                    'old_value' => $oldStock,
                    'new_value' => $product->current_stock,
                    'reason_changed' => 'Sale deleted',
                ]);

                $detail->delete();
            }

            $sale->delete();

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus penjualan. Error: ' . $e->getMessage());
        }
    }

    public function exportPDF($id)
    {
        $sale = Sales::with('details.product')->findOrFail($id);

        $pdf = Pdf::loadView('sales.pdf', compact('sale'));

        return $pdf->download('detail_penjualan_' . $sale->id . '.pdf');
    }
}