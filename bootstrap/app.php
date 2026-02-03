<?php

use App\Http\Middleware\CheckLockScreen;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Define the route group for the admin panel
            Route::middleware(['web', 'auth.admin', CheckLockScreen::class])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
            Route::middleware(['web', 'auth.customer'])
                ->prefix('customer')
                ->name('customer.')
                ->group(base_path('routes/customer.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'hooks/*',
        ]);
        $middleware->alias([
            'guest.customer' => \App\Http\Middleware\RedirectIfCustomerAuthenticated::class,
            'auth.customer'  => \App\Http\Middleware\AuthenticateCustomer::class,
            'auth.admin'     => \App\Http\Middleware\AuthenticateAdmin::class,
            'locked'         => \App\Http\Middleware\CheckLockScreen::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisits::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
