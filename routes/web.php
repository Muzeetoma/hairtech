<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\ProductController::class, 'products'])->name('products');
Route::get('/product/{id}/{type}', [App\Http\Controllers\ProductController::class, 'product'])->name('product');
Route::get('/admin/hair', [App\Http\Controllers\Admin\AdminProductController::class, 'showHairCreationForm'])->name('admin.show.hair');
Route::get('/admin/hair/item', [App\Http\Controllers\Admin\AdminProductController::class, 'showHairItemCreationForm'])->name('admin.show.hair.item');
Route::post('/admin/hair', [App\Http\Controllers\Admin\AdminProductController::class, 'storeHair'])->name('admin.create.hair');
Route::post('/admin/hair/item', [App\Http\Controllers\Admin\AdminProductController::class, 'storeHairItem'])->name('admin.create.hair.item');
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/delete', [App\Http\Controllers\CartController::class, 'deleteFromCart'])->name('cart.delete');
Route::post('/cart/products/in', [App\Http\Controllers\CartController::class, 'getCartItems'])->name('cart.products.in');

