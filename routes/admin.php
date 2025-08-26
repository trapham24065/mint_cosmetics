<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/22/2025
 * @time 3:11 PM
 */
declare(strict_types=1);
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// The prefix 'admin' and middleware 'auth' are already applied from bootstrap/app.php

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Category Management
Route::resource('categories', CategoryController::class);
// Attribute Management
Route::resource('attributes', AttributeController::class);
