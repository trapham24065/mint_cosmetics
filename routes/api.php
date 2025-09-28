<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/5/2025
 * @time 6:13 PM
 */

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChatbotController;
use App\Http\Controllers\Admin\ChatbotReplyController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;

Route::name('api.')->group(function () {
    Route::get('/brands/api', [BrandController::class, 'getDataForGrid'])->name('brands.data');

    Route::get('/orders/api', [OrderController::class, 'getDataForGrid'])->name('orders.data');

    Route::get('/categories/api', [CategoryController::class, 'getDataForGrid'])->name('categories.data');

    Route::get('/attributes/api', [AttributeController::class, 'getDataForGrid'])->name('attributes.data');

    Route::get('/coupons/api', [CouponController::class, 'getDataForGrid'])->name('coupons.data');

    Route::get('/products/api', [ProductController::class, 'getDataForGrid'])->name('products.data');

    Route::get('/reviews/api', [ReviewController::class, 'getDataForGrid'])->name('reviews.data');

    Route::get('/chatbots/api', [ChatbotController::class, 'getDataForGrid'])->name('chatbot.data');

    Route::get('/chatbot-replies/api', [ChatbotReplyController::class, 'getDataForGrid'])->name('chatbot-replies.data');
});
