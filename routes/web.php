<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SubProsuctController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrackingController;

Route::get('/', function () {
    return view('Pages.home');
});
Route::get('/delivery', [TrackingController::class, 'index'])->name('delivery.track');
Route::post('/delivery/track', [TrackingController::class, 'search'])->name('delivery.search');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('auth');

Route::get('/sub-product/{id}', [SubProsuctController::class, 'show']);
Route::get('/all-sub-products', [SubProsuctController::class, 'showAll']);

Route::post('/cart/add', [CartController::class, 'add']);
Route::get('/cart', [CartController::class, 'cart']);
Route::post('/cart/increase', [CartController::class, 'increaseQty']);
Route::post('/cart/decrease', [CartController::class, 'decreaseQty']);
Route::post('/cart/remove', [CartController::class, 'remove']);
Route::post('/cart/clear', [CartController::class, 'clear']);

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

require __DIR__.'/admin.php';