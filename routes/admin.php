<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\BoomPromotionController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::resource('users', UserController::class);
    Route::resource('main-categories', MainCategoryController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('collections', CollectionController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('sizes', SizeController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::get('returns', [AdminOrderController::class, 'returnsIndex'])->name('admin.returns.index');
    Route::post('orders/{order}/accept-refund', [AdminOrderController::class, 'acceptRefund'])->name('admin.orders.accept-refund');
    Route::post('orders/{order}/reject-refund', [AdminOrderController::class, 'rejectRefund'])->name('admin.orders.reject-refund');
    Route::resource('banners', BannerController::class);
    Route::resource('coupons', CouponController::class);
    Route::resource('boom-promotions', BoomPromotionController::class);
    
    // Stock Inventory Routes
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/update-stock', [InventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::post('inventory/bulk-update', [InventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');

    // Sales Report Routes
    Route::get('reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('reports/all', [AdminController::class, 'reportAll'])->name('admin.reports.all');
    Route::get('reports/pdf/{date}', [AdminController::class, 'reportPdf'])->name('admin.reports.pdf');
});