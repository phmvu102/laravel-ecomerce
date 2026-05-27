<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Client routes
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/shop/{category_slug?}', [ProductController::class, 'shop'])->name('client.shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('client.product.show');

// Auth required
Route::middleware('auth')->group(function () {
    // Breeze profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('client.cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('client.cart.add');
    Route::patch('/cart/update', [CartController::class, 'update'])->name('client.cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('client.cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('client.cart.clear');

    // Checkout
    Route::get('/checkout', [CartController::class, 'checkout'])->name('client.checkout');
    Route::post('/checkout', [CartController::class, 'placeOrder'])->name('client.checkout.place');
    Route::get('/order-success/{order}', [CartController::class, 'orderSuccess'])->name('client.order.success');
});
require __DIR__.'/auth.php';
