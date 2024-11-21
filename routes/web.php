<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReportController;
<<<<<<< HEAD

Route::get('/', function () {
    return view('welcome', );
});

Route::get('/products', [Controllers\ProductController::class, 'index'])->name('products.index');

Route::get('/product/add', function () {
    return view('product-add');
})->name('products.create');

Route::post('/products', [ProductController::class, 'store'])->name('products.store');

Route::get('/products/{id}/edit', [Controllers\ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [Controllers\ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [Controllers\ProductController::class, 'destroy'])->name('products.destroy');
=======
use App\Http\Controllers\SesiController;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [SesiController::class, 'index'])->name('login');
    Route::post('/login', [SesiController::class, 'login']);
});

Route::get('/home', function () {
    return redirect('/admin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [Controllers\AdminController::class, 'index'])->name('welcome');
    Route::get('/admin', [Controllers\AdminController::class, 'admin'])->middleware('userAkses:admin');
    Route::get('/stocker', [Controllers\AdminController::class, 'stocker'])->middleware('userAkses:stocker');
    Route::get('/purchaser', [Controllers\AdminController::class, 'purchaser'])->middleware('userAkses:purchaser');
    Route::get('/sales', [Controllers\AdminController::class, 'sales'])->middleware('userAkses:sales');
    Route::get('/logout', [SesiController::class, 'logout']);

    Route::get('/products', [Controllers\ProductController::class, 'index'])->middleware('userAkses:admin', 'userAkses:stocker')->name('products.index');

    Route::get('/product/add', function () {
        return view('product-add');
    })->middleware('userAkses:admin', 'userAkses:stocker')->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])->middleware('userAkses:admin', 'userAkses:stocker')->name('products.store');

    Route::get('/products/{id}/edit', [Controllers\ProductController::class, 'edit'])->middleware('userAkses:admin', 'userAkses:stocker')->name('products.edit');
    Route::put('/products/{id}', [Controllers\ProductController::class, 'update'])->middleware('userAkses:admin', 'userAkses:stocker')->name('products.update');
    Route::delete('/products/{id}', [Controllers\ProductController::class, 'destroy'])->middleware('userAkses:admin', 'userAkses:stocker')->name('products.destroy');
});
>>>>>>> baru

Route::get('/vendors', [Controllers\VendorController::class, 'index'])->name('vendors.index');
Route::delete('/vendors/{id}', [Controllers\VendorController::class, 'destroy'])->name('vendors.destroy');


Route::get('/vendor/add', function () {
    return view('vendor-add');
})->name('vendors.create');
Route::post('/vendors', [Controllers\VendorController::class, 'store'])->name('vendors.store');
Route::get('/vendors/{id}/edit', [Controllers\VendorController::class, 'edit'])->name('vendors.edit');
Route::put('/vendors/{id}', [Controllers\VendorController::class, 'update'])->name('vendors.update');

<<<<<<< HEAD
Route::get('/prices', function () {
    return view('price-list');
});
=======
Route::get('/prices', [Controllers\PriceController::class, 'index'])->name('prices.index');
Route::get('/price/add', [Controllers\PriceController::class, 'create'])->name('prices.create');
Route::post('/prices', [Controllers\PriceController::class, 'store'])->name('prices.store');
Route::delete('/prices/{id}', [Controllers\PriceController::class, 'destroy'])->name('prices.destroy');
>>>>>>> baru

Route::get('/purchase/add', [PurchaseController::class, 'create'])->name('purchases.create');
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
Route::get('/purchases/{id}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
Route::put('/purchases/{id}', [PurchaseController::class, 'update'])->name('purchases.update');
Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

Route::get('/reports/purchase', [PurchaseReportController::class, 'index'])->name('reports.purchase');
<<<<<<< HEAD
Route::get('/reports/purchase/export', [PurchaseReportController::class, 'exportToExcel'])->name('reports.export');
=======
Route::get('/reports/purchase/export', [PurchaseReportController::class, 'exportToExcel'])->name('reports.purchaseexport');

Route::get('/reports/stock', [Controllers\StockReportController::class, 'index'])->name('reports.stock');
Route::get('/reports/stock/export', [Controllers\StockReportController::class, 'export'])->name('reports.export');
>>>>>>> baru
