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
use App\Http\Controllers\Admin\ChatbotController;
use App\Http\Controllers\Admin\ChatbotReplyController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ScraperController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

// The prefix 'admin' and middleware 'auth' are already applied from bootstrap/app.php
require __DIR__.'/api.php';
//Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Category Management
Route::resource('categories', CategoryController::class);
// Attribute Management
Route::resource('attributes', AttributeController::class);
// Coupon Management
Route::resource('coupons', CouponController::class);
// Product Management
Route::resource('products', ProductController::class);
//Category Attribute Management
Route::get('/categories/{category}/attributes', [CategoryController::class, 'getAttributes'])
    ->name('categories.attributes');
// Brand Management
Route::resource('brands', BrandController::class);
// Order Management
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice.download');

Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::put('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
Route::put('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

Route::prefix('chatbot')->name('chatbot.')->group(function () {
    Route::get('/', [ChatbotController::class, 'index'])->name('index');
    Route::get('/create', [ChatbotController::class, 'create'])->name('create');
    Route::post('/', [ChatbotController::class, 'store'])->name('store');
    Route::get('/{rule}/edit', [ChatbotController::class, 'edit'])->name('edit');
    Route::put('/{rule}', [ChatbotController::class, 'update'])->name('update');
    Route::delete('/{rule}', [ChatbotController::class, 'destroy'])->name('destroy');
});
Route::resource('chatbot-replies', ChatbotReplyController::class);
Route::post('chatbot-replies/{reply}/keywords', [ChatbotReplyController::class, 'storeKeyword'])->name(
    'chatbot-replies.keywords.store'
);
Route::delete('chatbot-keywords/{keyword}', [ChatbotReplyController::class, 'destroyKeyword'])->name(
    'chatbot-keywords.destroy'
);

Route::get('/scraper', [ScraperController::class, 'index'])->name('scraper.index');
Route::post('/scraper/run', [ScraperController::class, 'run'])->name('scraper.run');
