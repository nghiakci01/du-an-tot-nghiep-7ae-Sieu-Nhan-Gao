<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('welcome');
Route::get('/shop', [App\Http\Controllers\Frontend\ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('product.detail');

Route::get('/cart', [App\Http\Controllers\Frontend\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [App\Http\Controllers\Frontend\CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/update', [App\Http\Controllers\Frontend\CartController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/remove', [App\Http\Controllers\Frontend\CartController::class, 'remove'])->name('cart.remove');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{id}', [App\Http\Controllers\Frontend\CheckoutController::class, 'success'])->name('checkout.success');
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/my-account', [App\Http\Controllers\Frontend\AccountController::class, 'index'])->name('account.index');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);

});
