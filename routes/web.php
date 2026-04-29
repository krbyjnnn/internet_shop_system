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
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/select-station', function () {
        $stations = \App\Models\Station::where('is_occupied', false)->get();
        return view('customer.select_station', compact('stations'));
    })->name('customer.select_station');

    Route::post('/select-station', function (\Illuminate\Http\Request $request) {
        $station = \App\Models\Station::findOrFail($request->station_id);
        $station->update(['is_occupied' => true, 'user_id' => auth()->id()]);
        auth()->user()->update(['station_id' => $request->station_id]);
        return redirect()->route('customer.dashboard');
    })->name('customer.select_station.store');

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