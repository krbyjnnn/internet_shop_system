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
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';