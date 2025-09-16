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
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $title = "Dashboard";
        // --- Data for Summary Cards ---
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', OrderStatus::Completed)->sum('total_price');
        $totalCustomers = Order::distinct('email')->count('email'); // Assuming all users are customers
        $totalProducts = Product::count();

        // --- Data for Recent Orders Table ---
        $recentOrders = Order::with('items')->latest()->take(5)->get();

        // --- Data for Performance Chart (Revenue per month for the last year) ---
        $revenueData = Order::query()
            ->select(
                DB::raw('SUM(total_price) as revenue'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->where('status', OrderStatus::Completed)
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $chartLabels = $revenueData->pluck('month');
        $chartData = $revenueData->pluck('revenue');

        return view(
            'admin.dashboard',
            compact(
                'title',
                'totalOrders',
                'totalRevenue',
                'totalCustomers',
                'totalProducts',
                'recentOrders',
                'chartLabels',
                'chartData'
            )
        );
    }

}
