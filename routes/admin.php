<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/22/2025
 * @time 3:11 PM
 */
declare(strict_types=1);

use App\Http\Controllers\Admin\{AttributeController,
    BlogPostController,
    BrandController,
    CategoryController,
    ChatbotController,
    ChatbotReplyController,
    CouponController,
    CustomerController,
    DashboardController,
    OrderController,
    ProductController,
    PurchaseOrderController,
    ReviewController,
    ScraperController,
    SettingsController,
    ChatController as AdminChatController,
    SupplierController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Prefix: 'admin', Middleware: 'auth' (Applied globally)
*/

require __DIR__.'/api.php';

// --- Dashboard ---
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// --- Core Management Resources (CRUD) ---
Route::resources([
    'attributes' => AttributeController::class,
    'brands'     => BrandController::class,
    'coupons'    => CouponController::class,
    'blog-posts' => BlogPostController::class, // Note: name prefix is admin.blog-posts.*
]);

// --- Category Management ---
Route::get('categories/{category}/attributes', [CategoryController::class, 'getAttributes'])->name(
    'categories.attributes'
);
Route::resource('categories', CategoryController::class);

// --- Product Management ---
Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
    Route::post('bulk-update', 'bulkUpdate')->name('bulkUpdate');
    Route::post('upload-tinymce-image', 'uploadTinyMCEImage')->name('upload.tinymce');
});
Route::resource('products', ProductController::class);

// --- Order Management ---
Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{order}', 'show')->name('show');
    Route::put('/{order}/update-status', 'updateStatus')->name('updateStatus');
    Route::get('/{order}/invoice', 'downloadInvoice')->name('invoice.download');
});

// --- Review Management ---
Route::controller(ReviewController::class)->prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::put('/{review}/approve', 'approve')->name('approve');
    Route::put('/{review}/reject', 'reject')->name('reject');
    Route::delete('/{review}', 'destroy')->name('destroy');
});

// --- Settings ---
Route::controller(SettingsController::class)->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'update')->name('update');
});

// --- Chatbot System ---
// Chatbot Rules (Converted manual routes to Resource)
Route::resource('chatbot', ChatbotController::class)->except(['show']);

// Chatbot Replies & Keywords
Route::resource('chatbot-replies', ChatbotReplyController::class);
Route::controller(ChatbotReplyController::class)->group(function () {
    Route::post('chatbot-replies/{reply}/keywords', 'storeKeyword')->name('chatbot-replies.keywords.store');
    Route::delete('chatbot-keywords/{keyword}', 'destroyKeyword')->name('chatbot-keywords.destroy');
});

// --- Scraper ---
Route::controller(ScraperController::class)->prefix('scraper')->name('scraper.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/run', 'run')->name('run');
});

// Customer Management
Route::controller(CustomerController::class)->prefix('customers')->name(
    'customers.'
)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{customer}', 'show')->name('show');
    Route::delete('/{customer}', 'destroy')->name('destroy');
    Route::put('/{customer}/toggle-status', 'toggleStatus')->name('toggle-status');
});

Route::controller(AdminChatController::class)->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/{conversation}/reply', 'reply')->name('reply');
    Route::get('/{conversation}/fetch', 'fetchMessages')->name('fetch');
});

Route::resource('suppliers', SupplierController::class);
Route::post('suppliers/bulk-update', [SupplierController::class, 'bulkUpdate'])->name('suppliers.bulkUpdate');

Route::controller(PurchaseOrderController::class)
    ->prefix('inventory')
    ->name('inventory.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');

        Route::get('/{purchaseOrder}', 'show')->name('show');

        // Actions
        Route::put('/{purchaseOrder}/approve', 'approve')->name('approve');
        Route::put('/{purchaseOrder}/cancel', 'cancel')->name('cancel');
    });
