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
        // Allow overriding mail configuration via database settings.
        // Values fall back to whatever the current config (or env) provides.
        config([
            'mail.default' => setting('mail_driver', config('mail.default')),

            'mail.mailers.smtp.host'     => setting('mail_host', config('mail.mailers.smtp.host')),
            'mail.mailers.smtp.port'     => setting('mail_port', config('mail.mailers.smtp.port')),
            'mail.mailers.smtp.username' => setting('mail_username', config('mail.mailers.smtp.username')),
            'mail.mailers.smtp.password' => setting('mail_password', config('mail.mailers.smtp.password')),
            'mail.mailers.smtp.encryption' => setting('mail_encryption', config('mail.mailers.smtp.encryption')),

            'mail.from.address' => setting('mail_from_address', config('mail.from.address')),
            'mail.from.name'    => setting('mail_from_name', config('mail.from.name')),
        ]);
    }
}
