<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/add-to-cart', [\App\Http\Controllers\CartController::class, 'addToCart'])->name('add-to-cart');
Route::post('/cart/update-item', [\App\Http\Controllers\CartController::class, 'updateCartItem'])->name('update-cart-item');
Route::post('/cart/remove-item', [\App\Http\Controllers\CartController::class, 'removeCartItem'])->name('remove-cart-item');


Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'confirmCheckout'])->name('confirm-checkout');
Route::post('/checkout/get-totals', [\App\Http\Controllers\CheckoutController::class, 'getTotals'])->name('get-totals');

Route::middleware('auth')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::localized(function () {
    require __DIR__.'/auth.php';

    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'confirmCheckout'])->name('confirm-checkout');
    Route::get('/category/{slug}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('category');
    Route::get('/product/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('product');
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'show'])->name('cart');

    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout');
    Route::get('/transaction/{transaction}', [\App\Http\Controllers\CheckoutController::class, 'showTransaction'])->name('transaction');
    Route::get('/sync-cities/{region}/{stateId}', [\App\Http\Controllers\CheckoutController::class, 'syncCities'])->name('syncCities');
    Route::get('/search', [App\Http\Controllers\ProductController::class, 'search'])->name('search');
    Route::middleware('auth')->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

        Route::get('/orders', [ProfileController::class, 'orders'])->name('profile.orders');



    });

    Route::get('/orders/{order}', [ProfileController::class, 'order'])->name('profile.order');
    Route::get('/orders/{order}/pay', [ProfileController::class, 'payOrder'])->name('profile.pay-order');

    Route::get('/{slug?}', [\App\Http\Controllers\PageController::class, 'show'])->name('page');
});
