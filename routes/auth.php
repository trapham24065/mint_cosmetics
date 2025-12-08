<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Storefront\Auth\CustomerAuthController;
use App\Http\Controllers\Storefront\Auth\ForgotPasswordController;
use App\Http\Controllers\Storefront\CustomerDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:web')->middleware(['web'])->group(function () {
    Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])
        ->name('admin.login');

    Route::post('admin/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth.admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('verify-email', EmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });
Route::prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::middleware('guest.customer')->group(function () {
            Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
            Route::post('login', [CustomerAuthController::class, 'login'])->name('login.submit');
            Route::post('register', [CustomerAuthController::class, 'register'])->name('register.submit');

            Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
                ->name('password.request');

            Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
                ->name('password.email');

            Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])
                ->name('password.reset');

            Route::post('password/reset', [ForgotPasswordController::class, 'reset'])
                ->name('password.update');
        });
    });
