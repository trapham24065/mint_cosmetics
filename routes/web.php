<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\PaymentController;
use App\Http\Controllers\Storefront\ProductController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{variantId}', [CartController::class, 'remove'])->name('cart.remove');
// Route để hiển thị trang checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
// Route để xử lý việc đặt hàng
Route::post('/checkout/place-order', [PaymentController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::post('/hooks/sepay-payment', [WebhookController::class, 'handlePaymentWebhook']);

// Route để hiển thị trang chứa mã QR
Route::get('/order/{order}/payment', [PaymentController::class, 'showPaymentPage'])->name('payment.show');
// Route để JavaScript kiểm tra trạng thái đơn hàng
Route::get('/order/{order}/status', [PaymentController::class, 'checkOrderStatus'])->name('payment.status');

Route::get('/products/{product}/quick-view', [ShopController::class, 'quickView'])->name('products.quickView');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

