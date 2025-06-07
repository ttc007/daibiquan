<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\VisitController;

Route::get('/', function () {
    return view('home');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', AdminProductController::class);
        Route::resource('categories', CategoryController::class);
        
        Route::get('products/category/{id}', [AdminProductController::class, 'byCategory'])->name('products.byCategory');

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');
        Route::put('orders/{id}', [AdminOrderController::class, 'update'])->name('orders.update');
    });
});

Route::get('/menu', [ProductController::class, 'index'])->name('menu');
Route::get('/products/category/{id}', [ProductController::class, 'byCategory'])->name('products.byCategory');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
Route::get('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');

Route::get('/checkout/{type}', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout/{type}', [OrderController::class, 'placeOrder'])->name('checkout.placeOrder');

Route::get('/lich-su-don-hang', [OrderController::class, 'history'])->name('orders.history');
Route::post('/reorder/{id}', [OrderController::class, 'reorder'])->name('orders.reorder');


Route::prefix('api')->name('api.')->group(function () {
    Route::get('orders/history', [OrderApiController::class, 'history']);
    Route::get('latest-order/{customerId}', [OrderApiController::class, 'latestOrder']);

    Route::post('/orders/{order}/cancel', [OrderApiController::class, 'cancel']);
    Route::post('/orders/{order}/received', [OrderApiController::class, 'received']);

    Route::post('/track-visit', [VisitController::class, 'track']);
    Route::get('/visits', [VisitController::class, 'getVisitsByDate'])->name('visits');
});

