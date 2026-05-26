<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SubProsuctController;
Route::get('/', function () {
    return view('Pages.home');
});
Route::get('/sub-product/{id}', [SubProsuctController::class, 'show']);
Route::get('/all-sub-products', [SubProsuctController::class, 'showAll']);

Route::post('/cart/add', [CartController::class, 'add']);
Route::get('/cart', [CartController::class, 'cart']);
Route::post('/cart/increase', [CartController::class, 'increaseQty']);
Route::post('/cart/decrease', [CartController::class, 'decreaseQty']);
Route::post('/cart/remove', [CartController::class, 'remove']);
Route::post('/cart/clear', [CartController::class, 'clear']);

