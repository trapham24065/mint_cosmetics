<?php

declare(strict_types=1);

use App\Http\Controllers\Customer\ReturnRequestController;
use App\Http\Controllers\Storefront\Auth\CustomerAuthController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\CustomerDashboardController;
use App\Http\Controllers\Storefront\CustomerOrderController;
use App\Http\Controllers\Storefront\PaymentController;
use App\Http\Controllers\Storefront\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
Route::put('profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
Route::put('address', [CustomerDashboardController::class, 'updateAddress'])->name('address.update');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/checkout/ghn/provinces', [CheckoutController::class, 'provinces'])->name('checkout.ghn.provinces');
Route::get('/checkout/ghn/districts', [CheckoutController::class, 'districts'])->name('checkout.ghn.districts');
Route::get('/checkout/ghn/wards', [CheckoutController::class, 'wards'])->name('checkout.ghn.wards');
Route::get('/checkout/ghn/fee', [CheckoutController::class, 'fee'])->name('checkout.ghn.fee');
Route::post('/checkout/place-order', [PaymentController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::post('/hooks/sepay-payment', [WebhookController::class, 'handlePaymentWebhook']);

Route::get('orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');

// Return request routes (không lồng thêm prefix/name customer ở đây)
Route::get('returns', [ReturnRequestController::class, 'index'])->name('returns.index');
Route::get('orders/{order}/return', [ReturnRequestController::class, 'create'])->name('returns.create');
Route::post('orders/{order}/return', [ReturnRequestController::class, 'store'])->name('returns.store');
