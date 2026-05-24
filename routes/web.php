<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::redirect('/', '/products');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    // Products
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
    Route::post('products/{product}/createInWoocommerce', [ProductController::class, 'createInWoocommerce'])->name('products.createInWoocommerce');
    Route::post('products/{product}/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('products/{product}/generateDescription', [ProductController::class, 'generateDescription'])->name('products.generateDescription');
    Route::get('products/{product}/checkWcStatus', [ProductController::class, 'checkWcStatus'])->name('products.checkWcStatus');
    Route::post('products/checkWcStatusBulk', [ProductController::class, 'checkWcStatusBulk'])->name('products.checkWcStatusBulk');
    Route::post('products/{product}/optimizeImages', [ProductController::class, 'optimizeImages'])->name('products.optimizeImages');
    Route::post('wc/sync', [ProductController::class, 'syncWooCommerceData'])->name('wc.sync');

    // Users (admin only)
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('users-trash',                    [UserController::class, 'trash'])->name('users.trash');
        Route::post('users-trash/{id}/restore',      [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users-trash/{id}/force',      [UserController::class, 'forceDelete'])->name('users.forceDelete');
    });
});
