<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AddOnController;
use App\Http\Controllers\CheckoutAddressController;
use App\Http\Controllers\CheckoutAuthController;
use App\Http\Controllers\CheckoutOrderController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

Route::get('/users', [UserController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('users.index');

Route::resource('categories', CategoryController::class)
    ->middleware(['auth', 'verified']);

Route::resource('products', ProductController::class)
    ->middleware(['auth', 'verified']);

// Product Add-ons Management Routes
Route::get('/products/{product}/addons', [ProductController::class, 'editAddons'])
    ->middleware(['auth', 'verified'])
    ->name('products.addons');
Route::post('/api/products/{product}/addons', [ProductController::class, 'attachAddon'])
    ->middleware(['auth', 'verified']);
Route::delete('/addons/{addon}/detach', [ProductController::class, 'detachAddon'])
    ->middleware(['auth', 'verified'])
    ->name('addons.detach');

Route::resource('addons', AddOnController::class)
    ->middleware(['auth', 'verified']);

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::post('/otp/send', [CheckoutAuthController::class, 'sendOtp'])
        ->middleware('throttle:otp-send')
        ->name('otp.send');

    Route::post('/otp/verify', [CheckoutAuthController::class, 'verifyOtp'])
        ->middleware('throttle:otp-verify')
        ->name('otp.verify');

    Route::middleware(['auth', 'cart.not_empty'])->group(function () {
        Route::get('/addresses', [CheckoutAddressController::class, 'index'])->name('addresses.index');
        Route::post('/addresses', [CheckoutAddressController::class, 'store'])->name('addresses.store');
        Route::put('/addresses/{address}', [CheckoutAddressController::class, 'update'])->name('addresses.update');
        Route::delete('/addresses/{address}', [CheckoutAddressController::class, 'destroy'])->name('addresses.destroy');
        Route::post('/addresses/{address}/default', [CheckoutAddressController::class, 'setDefault'])->name('addresses.default');

        Route::post('/orders', [CheckoutOrderController::class, 'store'])->name('orders.store');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/orders/{order}/success', [CheckoutOrderController::class, 'success'])->name('orders.success');
    });
});
