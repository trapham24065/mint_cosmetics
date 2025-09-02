<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        RedirectIfAuthenticated::redirectUsing(static function ($request) {
            return route('admin.dashboard');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //hàm để chạy được js , css trên ngrok
        if (app()->environment('production') || str_contains(request()->getHost(), 'ngrok-free.app')) {
            URL::forceScheme('https');
        }
    }

}
