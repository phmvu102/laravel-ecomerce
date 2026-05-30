<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CLIENT CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController;

/*
|--------------------------------------------------------------------------
| AUTH CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ProfileController;

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
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [ClientProductController::class, 'index'])
    ->name('home');

Route::view('/about', 'client.about')
    ->name('client.about');

/*
|--------------------------------------------------------------------------
| SHOP
|--------------------------------------------------------------------------
*/

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
| AUTH REQUIRED
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | ORDERS & CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::prefix('orders')
        ->name('client.orders.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Checkout
            |--------------------------------------------------------------------------
            */

            Route::get('/checkout', [OrderController::class, 'checkout'])
                ->name('checkout');

            Route::post('/checkout', [OrderController::class, 'placeOrder'])
                ->name('place');

            Route::get('/success/{order}', [OrderController::class, 'success'])
                ->name('success');

            /*
            |--------------------------------------------------------------------------
            | Orders
            |--------------------------------------------------------------------------
            */

            Route::get('/', [OrderController::class, 'index'])
                ->name('index');

            Route::get('/{order}', [OrderController::class, 'show'])
                ->name('show');

            Route::post('/{order}/cancel', [OrderController::class, 'cancel'])
                ->name('cancel');
        });
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

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::resource('categories', CategoryController::class)
            ->except(['show', 'create']);

        /*
        |--------------------------------------------------------------------------
        | Attributes
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        Route::resource('products', AdminProductController::class)
            ->except(['show']);

        /*
        |--------------------------------------------------------------------------
        | Brands
        |--------------------------------------------------------------------------
        */

        Route::resource('brands', BrandController::class)
            ->except(['show', 'create', 'edit']);
    });

/*
|--------------------------------------------------------------------------
| VENDOR ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:vendor,admin'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return 'Trang quản lý gian hàng';
        })->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| DEFAULT DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/auth.php';
