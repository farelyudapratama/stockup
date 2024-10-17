<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome',);
});

Route::get('/products', function () {
    return view('product-list');
});

Route::get('/product/add', function () {
    return view('product-add');
});

Route::get('/vendors', function () {
    return view('vendor-list');
});

Route::get('/prices', function () {
    return view('price-list');
});