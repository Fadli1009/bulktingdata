<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingpages.index');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/customers/create', [AdminController::class, 'customerCreate'])->name('customers.create');
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers.index');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/packages', [AdminController::class, 'packages'])->name('packages.index');
    Route::get('/faqs', [AdminController::class, 'faqs'])->name('faqs.index');
    Route::get('/uploads', [AdminController::class, 'uploads'])->name('uploads.index');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs.index');
});
