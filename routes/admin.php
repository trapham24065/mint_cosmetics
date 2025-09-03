<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/22/2025
 * @time 3:11 PM
 */
declare(strict_types=1);

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

// The prefix 'admin' and middleware 'auth' are already applied from bootstrap/app.php

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Category Management
Route::resource('categories', CategoryController::class);
// Attribute Management
Route::resource('attributes', AttributeController::class);
// Coupon Management
Route::resource('coupons', CouponController::class);
// Product Management
Route::resource('products', ProductController::class);

Route::get('/categories/{category}/attributes', [CategoryController::class, 'getAttributes'])
    ->name('categories.attributes');
// Brand Management
Route::resource('brands', BrandController::class);

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
