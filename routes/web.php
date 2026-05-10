<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::redirect('/', '/products');

Route::middleware('auth')->group(function () {
    // Products
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
    Route::post('products/{product}/createInWoocommerce', [ProductController::class, 'createInWoocommerce'])->name('products.createInWoocommerce');
    Route::post('products/{product}/generateDescription', [ProductController::class, 'generateDescription'])->name('products.generateDescription');
    Route::get('products/{product}/checkWcStatus', [ProductController::class, 'checkWcStatus'])->name('products.checkWcStatus');
    Route::post('products/checkWcStatusBulk', [ProductController::class, 'checkWcStatusBulk'])->name('products.checkWcStatusBulk');
    Route::post('products/{product}/optimizeImages', [ProductController::class, 'optimizeImages'])->name('products.optimizeImages');

    // Users (admin only)
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});
