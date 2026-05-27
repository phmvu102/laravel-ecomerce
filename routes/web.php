<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\CartController;
use Illuminate\Support\Facades\Route;

//===================== User =======================
use App\Http\Controllers\ProfileController;
//===================== User =======================

//===================== Admin =======================
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BrandController;
//===================== Admin =======================

// Nhóm các Route dành riêng cho ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Cụm quản lý Thương hiệu
    Route::prefix('categories')->name('admin.categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Cụm quản lý Thuộc tính động
    Route::prefix('attributes')->name('admin.attributes.')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('index');
        Route::post('/', [AttributeController::class, 'store'])->name('store');
        Route::delete('/{attribute}', [AttributeController::class, 'destroy'])->name('destroy');

        // THÊM 2 DÒNG NÀY ĐỂ XỬ LÝ SỬA THUỘC TÍNH
        Route::get('/{attribute}/edit', [AttributeController::class, 'edit'])->name('edit');
        Route::put('/{attribute}', [AttributeController::class, 'update'])->name('update');

        // Route dành cho các giá trị con
        Route::post('/{attribute}/values', [AttributeController::class, 'storeValue'])->name('storeValue');
        Route::delete('/values/{value}', [AttributeController::class, 'destroyValue'])->name('destroyValue');
    });

    // QUẢN LÝ SẢN PHẨM CRUD
    Route::prefix('products')->name('admin.products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');

        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // QUẢN LÝ THƯƠNG HIỆU (BRANDS)
    Route::prefix('brands')->name('admin.brands.')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::post('/', [BrandController::class, 'store'])->name('store');
        Route::put('/{brand}', [BrandController::class, 'update'])->name('update');
        Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('destroy');
    });
});

// Nhóm các Route dành riêng cho VENDOR (Nhà bán hàng)
Route::middleware(['auth', 'role:vendor,admin'])->prefix('vendor')->group(function () {
    Route::get('/dashboard', function () {
        return 'Trang quản lý gian hàng của Thương hiệu';
    })->name('vendor.dashboard');
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
