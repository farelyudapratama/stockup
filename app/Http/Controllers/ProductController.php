<?php

namespace App\Http\Controllers;

use App\Models\PriceChange;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Carbon\Carbon;

// use App\Models\Vendor;
// XXX Ganti fungsi price, buat agar ada price penjualan dan price rata rata pembelian
class ProductController extends Controller
{
    public function create()
    {
        return view('create-product');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initial_stock' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',  // Validasi untuk harga jual
            'purchase_price' => 'nullable|numeric|min:0', // Validasi untuk harga pembelian
        ]);

        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,     // Atur jadi optional di form
                'initial_stock' => $request->initial_stock,
                'current_stock' => $request->initial_stock,
                'selling_price' => $request->selling_price,  // Atur jadi optional di form
                'purchase_price' => $request->purchase_price,  // Atur jadi optional di form
            ]);

            if ($request->purchase_price) {
                $product->logPriceChange($request->purchase_price);
            }

            // Riwayat perubahan stok
            ProductHistory::create([
                'product_id' => $product->id,
                'changed_field' => 'initial_stock',
                'old_value' => 0,
                'new_value' => $product->initial_stock,
                'reason_changed' => 'Produk baru ditambahkan',
            ]);

            ProductHistory::create([
                'product_id' => $product->id,
                'changed_field' => 'current_stock',
                'old_value' => 0,
                'new_value' => $product->current_stock,
                'reason_changed' => 'Produk baru ditambahkan',
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
        $month = $request->input('month', 'current');

        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate($entries);

        $dateRange = $this->getDateRange($month);

        foreach ($products as $product) {
            $stockInfo = $this->getStockForMonth($product->id, $dateRange['start'], $dateRange['end']);
            // dd($dateRange, $stockInfo);
            $product->initial_stock = $stockInfo['initial_stock'];
            $product->current_stock = $stockInfo['current_stock'];
            $product->average_purchase_price = $product->calculateAveragePurchasePrice(); // NOTE sementara gini dulu gapake history
        }

        return view('product-list', compact('products', 'entries', 'search', 'month'));
    }

    private function getDateRange($month)
    {
        $now = Carbon::now();

        switch ($month) {
            case 'three_months_ago':
                $start = $now->copy()->subMonths(3)->startOfMonth();
                $end = $now->copy()->subMonths(3)->endOfMonth();
                break;
            case 'two_months_ago':
                $start = $now->copy()->subMonths(2)->startOfMonth();
                $end = $now->copy()->subMonths(2)->endOfMonth();
                break;
            case 'previous':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end = $now->copy()->subMonth()->endOfMonth();

                break;
            case 'current':
            default:
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
        }

        return ['start' => $start, 'end' => $end];
    }

    private function getStockForMonth($productId, $startDate, $endDate)
    {
        $product = Product::find($productId);
        $productCreatedAt = Carbon::parse($product->created_at);

        // kalo produk dibuat setelah periode yang diminta
        if ($productCreatedAt > $endDate) {
            return [
                'initial_stock' => 0,
                'current_stock' => 0
            ];
        }

        // Mengambil riwayat perubahan stok dalam periode yang diminta
        $stockChanges = ProductHistory::where('product_id', $productId)
            ->where('changed_field', 'current_stock')
            ->where('created_at', '<=', $endDate)
            ->orderBy('created_at')
            ->get();

        // Filter perubahan yang terjadi dalam rentang waktu yang diminta
        $changesInPeriod = $stockChanges->filter(function ($change) use ($startDate, $endDate) {
            $changeDate = Carbon::parse($change->created_at);
            return $changeDate >= $startDate && $changeDate <= $endDate;
        });

        // Jika produk dibuat dalam periode yang diminta
        if ($productCreatedAt >= $startDate && $productCreatedAt <= $endDate) {
            // Ambil perubahan pertama dalam periode ini
            $firstChange = $changesInPeriod->first();
            $lastChange = $changesInPeriod->last();

            return [
                'initial_stock' => $firstChange ? $firstChange->new_value : $product->initial_stock,
                'current_stock' => $lastChange ? $lastChange->new_value : $product->initial_stock
            ];
        }

        // Jika produk dibuat sebelum periode yang diminta
        if ($productCreatedAt < $startDate) {
            if ($changesInPeriod->isEmpty()) {
                // Jika tidak ada perubahan dalam periode, cari perubahan terakhir sebelum periode
                $lastChangeBeforePeriod = $stockChanges->filter(function ($change) use ($startDate) {
                    return Carbon::parse($change->created_at) < $startDate;
                })->last();

                $stockValue = $lastChangeBeforePeriod ? $lastChangeBeforePeriod->new_value : $product->initial_stock;
                return [
                    'initial_stock' => $stockValue,
                    'current_stock' => $stockValue
                ];
            } else {
                // Jika ada perubahan dalam periode
                $lastChangeBeforePeriod = $stockChanges->filter(function ($change) use ($startDate) {
                    return Carbon::parse($change->created_at) < $startDate;
                })->last();

                $firstChange = $changesInPeriod->first();
                $lastChange = $changesInPeriod->last();

                return [
                    'initial_stock' => $lastChangeBeforePeriod ? $lastChangeBeforePeriod->new_value : $firstChange->old_value,
                    'current_stock' => $lastChange->new_value
                ];
            }
        }

        return [
            'initial_stock' => $product->initial_stock,
            'current_stock' => $product->initial_stock
        ];
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
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'reason_changed' => 'required|string',
        ]);

        $product = Product::findOrFail($id);

        $isNameChanged = $product->name !== $request->name;
        $isDescriptionChanged = $product->description !== $request->description;
        $isInitialStockChanged = $product->initial_stock != $request->initial_stock;
        $isCurrentStockChanged = $product->current_stock != $request->current_stock;
        $isSellingPriceChanged = $product->selling_price != $request->selling_price;
        $isPurchasePriceChanged = $product->purchase_price != $request->purchase_price;

        if ($isSellingPriceChanged) {
            $product->update([
                'selling_price' => $request->selling_price,
            ]);
        }

        if ($isPurchasePriceChanged && $request->purchase_price) {
            $product->logPriceChange($request->purchase_price);
        }

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

        // Cari relasi terkait (Pembelian dan Pesanan)
        $relatedPurchases = PurchaseDetail::where('product_id', $product->id)->count();
        // $relatedOrders = OrderDetail::where('product_id', $product->id)->count();

        // Menyusun pesan error jika terdapat relasi terkait
        $errorMessage = 'Produk ini tidak dapat dihapus karena memiliki hubungan berikut: ';

        $hasError = false;
        if ($relatedPurchases > 0) {
            $errorMessage .= "$relatedPurchases pembelian, ";
            $hasError = true;
        }

        // if ($relatedOrders > 0) {
        //     $errorMessage .= "$relatedOrders pesanan, ";
        //     $hasError = true;
        // }

        if ($hasError) {
            return redirect()->route('products.index')->with('error', rtrim($errorMessage, ', '));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function relationships($id)
    {
        $product = Product::findOrFail($id);

        $relatedPurchases = PurchaseDetail::where('product_id', $product->id)->count();
        // $relatedOrders = OrderDetail::where('product_id', $product->id)->count();

        $related = [];

        if ($relatedPurchases > 0) {
            $related[] = "$relatedPurchases pembelian";
        }

        // if ($relatedOrders > 0) {
        //     $related[] = "$relatedOrders pesanan";
        // }

        return response()->json([
            'related' => $related,
        ]);
    }
    public function relationshipDetails($id)
    {
        $product = Product::findOrFail($id);

        $relatedPurchases = PurchaseDetail::with('purchase')
            ->where('product_id', $product->id)
            ->get(['id', 'purchase_id', 'quantity', 'created_at'])
            ->map(function ($purchaseDetail) {
                return [
                    'id' => $purchaseDetail->id,
                    'purchase_id' => $purchaseDetail->purchase_id,
                    'quantity' => $purchaseDetail->quantity,
                    'date' => $purchaseDetail->created_at->format('Y-m-d'),
                ];
            });

        // $relatedOrders = OrderDetail::with('order')
        //     ->where('product_id', $product->id)
        //     ->get(['id', 'order_id', 'quantity', 'created_at'])
        //     ->map(function ($orderDetail) {
        //         return [
        //             'id' => $orderDetail->id,
        //             'order_id' => $orderDetail->order_id,
        //             'quantity' => $orderDetail->quantity,
        //             'date' => $orderDetail->created_at->format('Y-m-d'),
        //         ];
        //     });

        return response()->json([
            'purchases' => $relatedPurchases,
            // 'orders' => $relatedOrders,
        ]);
    }
}