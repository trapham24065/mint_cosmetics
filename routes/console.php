<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('orders:cancel-expired')->everyMinute();

// generate sitemap file once per day at midnight (use --force to avoid prompt)
Schedule::command('sitemap:generate --force')->daily();
