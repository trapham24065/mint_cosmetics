<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;

// The prefix 'admin' and middleware 'auth' are already applied from bootstrap/app.php

Route::get('/dashboard', static function () {
    return view('admin.dashboard');
})->name('dashboard');

