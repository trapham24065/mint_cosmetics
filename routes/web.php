<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\ChatbotController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\PaymentController;
use App\Http\Controllers\Storefront\ProductController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\WebhookController;
use App\Http\Controllers\Storefront\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Storefront\ReviewController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{variantId}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/contents', [CartController::class, 'getContents'])->name('cart.contents');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/place-order', [PaymentController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::post('/hooks/sepay-payment', [WebhookController::class, 'handlePaymentWebhook']);

Route::get('/order/{order}/payment', [PaymentController::class, 'showPaymentPage'])->name('payment.show');
Route::get('/order/{order}/status', [PaymentController::class, 'checkOrderStatus'])->name('payment.status');

Route::get('/products/{product}/quick-view', [ShopController::class, 'quickView'])->name('products.quickView');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/order/{order}/thank-you', [PaymentController::class, 'thankYou'])->name('order.thankyou');

Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
Route::get('/chatbot/suggestions', [ChatbotController::class, 'getSuggestions'])->name('chatbot.suggestions');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist/ids', [WishlistController::class, 'getIds'])->name('wishlist.ids');
require __DIR__.'/auth.php';

