<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\StationController::class, 'index'])
        ->name('admin.dashboard');

    // Customer Management
    Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])
        ->name('admin.customers.index');
    Route::get('/customers/create', [App\Http\Controllers\Admin\CustomerController::class, 'create'])
        ->name('admin.customers.create');
    Route::post('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'store'])
        ->name('admin.customers.store');
    Route::post('/topup', [App\Http\Controllers\Admin\CustomerController::class, 'topup'])
        ->name('admin.customers.topup');
    Route::get('/topup', [App\Http\Controllers\Admin\CustomerController::class, 'topupForm'])
    ->name('admin.topup');

        // Product Management
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])
        ->name('admin.products.index');
    Route::get('/products/create', [App\Http\Controllers\ProductController::class, 'create'])
        ->name('admin.products.create');
    Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])
        ->name('admin.products.store');
    Route::delete('/products/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])
        ->name('admin.products.destroy');

    // Orders
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])
        ->name('admin.orders.index');
    Route::post('/orders/{order}/deliver', [App\Http\Controllers\OrderController::class, 'deliver'])
        ->name('admin.orders.deliver');
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/dashboard', function () {
        $products = \App\Models\Product::where('stock', '>', 0)->get();
        return view('customer.dashboard', compact('products'));
    })->name('customer.dashboard');

    Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])
    ->name('customer.orders.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';