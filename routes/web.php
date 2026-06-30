<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SubProsuctController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CouponController;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/shop', [FrontendController::class, 'shop'])->name('frontend.shop');
Route::get('/delivery', [TrackingController::class, 'index'])->name('delivery.track');
Route::post('/delivery/track', [TrackingController::class, 'search'])->name('delivery.search');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('auth');

Route::get('/category/{slug}', [FrontendController::class, 'category'])->name('frontend.category');
Route::get('/category/{slug}/ajax', [FrontendController::class, 'categoryAjax'])->name('frontend.category.ajax');
Route::get('/product/{slug}', [FrontendController::class, 'product'])->name('frontend.product');
Route::get('/collection/{slug}', [FrontendController::class, 'collection'])->name('frontend.collection');

Route::post('/cart/add', [CartController::class, 'add']);
Route::get('/cart', [CartController::class, 'cart']);
Route::post('/cart/increase', [CartController::class, 'increaseQty']);
Route::post('/cart/decrease', [CartController::class, 'decreaseQty']);
Route::post('/cart/remove', [CartController::class, 'remove']);
Route::post('/cart/clear', [CartController::class, 'clear']);
Route::post('/wishlist/toggle', [CartController::class, 'addWishlist']);

// Coupon AJAX Routes
Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

require __DIR__.'/admin.php';