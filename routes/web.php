<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;

/*
|--------------------------------------------------------------------------
| CLIENT ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [ClientProductController::class, 'index'])
    ->name('home');

Route::prefix('shop')
    ->name('client.')
    ->group(function () {

        Route::get('/{category_slug?}', [ClientProductController::class, 'shop'])
            ->name('shop');

        Route::get('/product/{slug}', [ClientProductController::class, 'show'])
            ->name('product.show');
    });

/*
|--------------------------------------------------------------------------
| AUTH USER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::prefix('profile')
        ->name('profile.')
        ->group(function () {

            Route::get('/', [ProfileController::class, 'edit'])
                ->name('edit');

            Route::patch('/', [ProfileController::class, 'update'])
                ->name('update');

            Route::delete('/', [ProfileController::class, 'destroy'])
                ->name('destroy');
        });

    Route::prefix('cart')
        ->name('client.cart.')
        ->group(function () {

            Route::get('/', [CartController::class, 'index'])
                ->name('index');

            Route::post('/add', [CartController::class, 'add'])
                ->name('add');

            Route::patch('/update', [CartController::class, 'update'])
                ->name('update');

            Route::delete('/remove/{id}', [CartController::class, 'remove'])
                ->name('remove');

            Route::delete('/clear', [CartController::class, 'clear'])
                ->name('clear');
        });

    Route::get('/checkout', [CartController::class, 'checkout'])
        ->name('client.checkout');

    Route::post('/checkout', [CartController::class, 'placeOrder'])
        ->name('client.checkout.place');

    Route::get('/order-success/{order}', [CartController::class, 'orderSuccess'])
        ->name('client.order.success');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('categories', CategoryController::class)
            ->except(['show', 'create']);

        Route::resource('attributes', AttributeController::class)
            ->except(['show', 'create']);

        Route::post(
            'attributes/{attribute}/values',
            [AttributeController::class, 'storeValue']
        )->name('attributes.storeValue');

        Route::delete(
            'attributes/values/{value}',
            [AttributeController::class, 'destroyValue']
        )->name('attributes.destroyValue');

        Route::resource('products', AdminProductController::class)
            ->except(['show']);

        Route::resource('brands', BrandController::class)
            ->except(['show', 'create', 'edit']);
    });

Route::middleware(['auth', 'role:vendor,admin'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return 'Trang quản lý gian hàng';
        })->name('dashboard');
    });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Client routes
Route::get('/', [ClientProductController::class, 'index'])->name('home');
Route::get('/shop/{category_slug?}', [ClientProductController::class, 'shop'])->name('client.shop');
Route::get('/product/{slug}', [ClientProductController::class, 'show'])->name('client.product.show');

// Auth required
Route::middleware('auth')->group(function () {
    // Breeze profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('client.profile.orders');
    Route::get('/profile/orders/{order}', [ProfileController::class, 'orderDetail'])->name('client.profile.orders.show');

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
