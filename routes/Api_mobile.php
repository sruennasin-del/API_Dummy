<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthApiController;
use App\Http\Controllers\API\HomeApiController;
use App\Http\Controllers\API\BannerApiController;
use App\Http\Controllers\API\ProductApiController;
use App\Http\Controllers\API\CategoryApiController;
use App\Http\Controllers\API\CollectionApiController;
use App\Http\Controllers\API\WishlistApiController;
use App\Http\Controllers\API\CartApiController;
use App\Http\Controllers\API\CouponApiController;
use App\Http\Controllers\API\CheckoutApiController;
use App\Http\Controllers\API\OrderApiController;

/*
|--------------------------------------------------------------------------
| Mobile API Routes
|--------------------------------------------------------------------------
| Prefix  : /api/mobile
| Middleware: api (stateless, no sessions)
|
| Authentication: Laravel Sanctum - Bearer Token
| Public routes  : No auth required
| Protected routes: Require 'Authorization: Bearer <token>' header
*/

// ─────────────────────────────────────────────────────────────
// PUBLIC ROUTES — No authentication required
// ─────────────────────────────────────────────────────────────

// Auth
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthApiController::class, 'register']);
    Route::post('login',    [AuthApiController::class, 'login']);
});

// Home (banners + promotions + categories + collections snapshot)
Route::get('home', [HomeApiController::class, 'index']);

// Banners
Route::get('banners',    [BannerApiController::class, 'index']);
Route::get('promotions', [BannerApiController::class, 'promotions']);

// Main Categories & Sub-Categories
Route::prefix('categories')->group(function () {
    Route::get('main',            [CategoryApiController::class, 'mainCategories']);
    Route::get('/',               [CategoryApiController::class, 'categories']);
    Route::get('{id}/products',   [CategoryApiController::class, 'products']);
});

// Collections
Route::prefix('collections')->group(function () {
    Route::get('/',               [CollectionApiController::class, 'index']);
    Route::get('{slug}/products', [CollectionApiController::class, 'products']);
});

// Products
Route::prefix('products')->group(function () {
    Route::get('/',        [ProductApiController::class, 'index']);
    Route::get('search',   [ProductApiController::class, 'search']);
    Route::get('{slug}',   [ProductApiController::class, 'show']);
});

// ─────────────────────────────────────────────────────────────
// PROTECTED ROUTES — Require valid Sanctum Bearer Token
// ─────────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth — Profile & Logout
    Route::prefix('auth')->group(function () {
        Route::post('logout',  [AuthApiController::class, 'logout']);
        Route::get('profile',  [AuthApiController::class, 'profile']);
        Route::put('profile',  [AuthApiController::class, 'updateProfile']);
    });

    // Wishlist
    Route::prefix('wishlist')->group(function () {
        Route::get('/',      [WishlistApiController::class, 'index']);
        Route::post('toggle',[WishlistApiController::class, 'toggle']);
    });

    // Cart
    Route::prefix('cart')->group(function () {
        Route::get('/',       [CartApiController::class, 'index']);
        Route::post('add',    [CartApiController::class, 'add']);
        Route::post('increase',[CartApiController::class, 'increase']);
        Route::post('decrease',[CartApiController::class, 'decrease']);
        Route::delete('remove',[CartApiController::class, 'remove']);
        Route::delete('clear', [CartApiController::class, 'clear']);
    });

    // Coupon
    Route::prefix('coupon')->group(function () {
        Route::post('apply',  [CouponApiController::class, 'apply']);
        Route::post('remove', [CouponApiController::class, 'remove']);
    });

    // Checkout
    Route::post('checkout', [CheckoutApiController::class, 'checkout']);

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/',          [OrderApiController::class, 'index']);
        Route::get('{id}',       [OrderApiController::class, 'show']);
        Route::post('{id}/cancel', [OrderApiController::class, 'cancel']);
        Route::post('{id}/refund', [OrderApiController::class, 'refund']);
    });
});
