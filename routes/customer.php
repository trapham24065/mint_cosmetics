<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/22/2025
 * @time 3:11 PM
 */
declare(strict_types=1);

use App\Http\Controllers\Storefront\Auth\CustomerAuthController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\CustomerDashboardController;
use App\Http\Controllers\Storefront\PaymentController;
use App\Http\Controllers\Storefront\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
Route::put('profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
Route::put('address', [CustomerDashboardController::class, 'updateAddress'])->name('address.update');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/place-order', [PaymentController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::post('/hooks/sepay-payment', [WebhookController::class, 'handlePaymentWebhook']);
