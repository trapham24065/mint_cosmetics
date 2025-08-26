<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/23/2025
 * @time 4:56 PM
 */
declare(strict_types=1);
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{

    public function index(): View
    {
        $title = "Dashboard";

        return view('admin.dashboard', compact('title'));
    }

}
