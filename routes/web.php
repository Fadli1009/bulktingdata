<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', fn () => redirect()->route('login'))->name('register');
    Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::post('/auth/otp/send', [AuthController::class, 'sendOtp'])->middleware('throttle:6,1')->name('auth.otp.send');
    Route::post('/auth/otp/verify', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('auth.otp.verify');
});

Route::middleware('auth')->group(function () {
    Route::get('/account/type', [AuthController::class, 'showAccountType'])->name('account.type');
    Route::post('/account/type', [AuthController::class, 'storeAccountType']);
    Route::get('/account/complete', [AuthController::class, 'showCompleteForm'])->name('account.complete');
    Route::post('/account/complete', [AuthController::class, 'storeCompleteForm']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'account.setup'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/checkout/{package:slug}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{package:slug}', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('dashboard.orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/proof', [OrderController::class, 'submitProof'])->name('orders.proof');
    Route::get('/orders/{order}/proof', [OrderController::class, 'downloadProof'])->name('orders.proof.download');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin authentication cuma tamu doang (tidak login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::get('/auth/google/redirect', [AdminAuthController::class, 'redirectToGoogle'])->name('auth.google');
        Route::post('/auth/otp/send', [AdminAuthController::class, 'sendOtp'])->middleware('throttle:6,1')->name('auth.otp.send');
        Route::post('/auth/otp/verify', [AdminAuthController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('auth.otp.verify');
    });

    // Admin area yang udah login
    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/customers/create', [AdminController::class, 'customerCreate'])->name('customers.create');
        Route::get('/customers', [AdminController::class, 'customers'])->name('customers.index');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
        Route::patch('/orders/{order}/status', [AdminController::class, 'orderStatus'])->name('orders.status');
        Route::post('/orders/{order}/confirm', [AdminController::class, 'orderConfirm'])->name('orders.confirm');
        Route::post('/orders/{order}/cancel', [AdminController::class, 'orderCancel'])->name('orders.cancel');
        Route::get('/orders/{order}/proof', [AdminController::class, 'orderProof'])->name('orders.proof');
        Route::get('/packages', [AdminController::class, 'packages'])->name('packages.index');
        Route::post('/packages', [AdminController::class, 'packageStore'])->name('packages.store');
        Route::put('/packages/{package}', [AdminController::class, 'packageUpdate'])->name('packages.update');
        Route::patch('/packages/{package}/toggle', [AdminController::class, 'packageToggle'])->name('packages.toggle');
        Route::delete('/packages/{package}', [AdminController::class, 'packageDestroy'])->name('packages.destroy');
        Route::get('/faqs', [AdminController::class, 'faqs'])->name('faqs.index');
        Route::get('/uploads', [AdminController::class, 'uploads'])->name('uploads.index');
        Route::get('/logs', [AdminController::class, 'logs'])->name('logs.index');
    });
});
