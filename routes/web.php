<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\Storefront\{
    AboutUsController,
    BlogController,
    CartController,
    ContactController,
    HomeController,
    PaymentController,
    ProductController,
    ReviewController,
    ShopController,
    WishlistController
};
use Illuminate\Support\Facades\Route;

// --- Static Pages ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [AboutUsController::class, 'index'])->name('about-us.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

// --- Shop & Products ---
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/quick-view', [ShopController::class, 'quickView'])->name('products.quickView');
Route::get('api/products/search', [ProductController::class, 'searchApi'])->name('api.products.search');

// --- Cart Management ---
Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/add', 'add')->name('add');
    Route::patch('/update', 'update')->name('update');
    Route::delete('/remove/{variantId}', 'remove')->name('remove');
    Route::get('/contents', 'getContents')->name('contents');
    Route::post('/apply-coupon', 'applyCoupon')->name('applyCoupon');
    Route::post('/remove-coupon', 'removeCoupon')->name('removeCoupon');
});

Route::controller(PaymentController::class)->prefix('order')->group(function () {
    // Payment specific routes (Require signed/auth logic inside controller or middleware)
    Route::middleware('signed')->group(function () {
        Route::get('/{order}/payment', 'showPaymentPage')->name('payment.show');
        Route::get('/{order}/thank-you', 'thankYou')->name('order.thankyou');
    });

    Route::get('/{order}/status', 'checkOrderStatus')->name('payment.status');
});

// --- Reviews ---
Route::controller(ReviewController::class)->prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
});
// --- Wishlist ---
Route::controller(WishlistController::class)->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/toggle', 'toggle')->name('toggle');
    Route::get('/ids', 'getIds')->name('ids');
});

Route::controller(ChatController::class)->prefix('chat')->name('chat.')->group(function () {
    Route::post('/send', 'sendMessage')->name('send');
    Route::get('/fetch', 'fetchMessages')->name('fetch');
    Route::get('/suggestions', 'getSuggestions')->name('suggestions');
});

// --- Admin & Other Auth Routes ---
require __DIR__.'/auth.php';

