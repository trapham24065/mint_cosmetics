<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/22/2025
 * @time 3:11 PM
 */
declare(strict_types=1);
use Illuminate\Support\Facades\Route;

// The prefix 'admin' and middleware 'auth' are already applied from bootstrap/app.php

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Category Management
Route::resource('categories', CategoryController::class);
