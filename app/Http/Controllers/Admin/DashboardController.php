<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Visit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{

    public function index(): View
    {
        $title = "Admin Dashboard";

        // --- 1. THỐNG KÊ TỔNG QUAN ---
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', OrderStatus::Completed)->sum('total_price');
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status', OrderStatus::Pending)->count();

        // --- 2. NGƯỜI DÙNG ONLINE (REAL-TIME) ---
        // Đếm số session hoạt động trong 5 phút gần đây
        $onlineUsers = 0;
        try {
            $onlineUsers = DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(5)->getTimestamp())
                ->count();
        } catch (\Exception $e) {
            // Bỏ qua lỗi nếu bảng sessions chưa tồn tại
        }

        // --- 3. LƯỢT TRUY CẬP HÔM NAY & TĂNG TRƯỞNG ---
        $todayVisits = Visit::whereDate('visited_at', Carbon::today())->count();
        $yesterdayVisits = Visit::whereDate('visited_at', Carbon::yesterday())->count();

        $growth = 0;
        if ($yesterdayVisits > 0) {
            $growth = (($todayVisits - $yesterdayVisits) / $yesterdayVisits) * 100;
        } elseif ($todayVisits > 0) {
            $growth = 100;
        }

        // --- 4. DỮ LIỆU BIỂU ĐỒ DOANH THU (12 THÁNG QUA) ---
        // Sử dụng DATE_FORMAT cho MySQL. Nếu dùng PostgreSQL đổi thành TO_CHAR(created_at, 'YYYY-MM')
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

        $revenueLabels = $revenueData->pluck('month')->toArray();
        $revenueValues = $revenueData->pluck('revenue')->toArray();

        // --- 5. DỮ LIỆU BIỂU ĐỒ LƯỢT TRUY CẬP (7 NGÀY GẦN NHẤT) ---
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6);
        $period = CarbonPeriod::create($startDate, $endDate);

        $visitLabels = [];
        $visitValues = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            // Nhãn hiển thị ngày/tháng (VD: 05/08)
            $visitLabels[] = $date->format('d/m');
            // Đếm số lượt trong ngày đó
            $visitValues[] = Visit::whereDate('visited_at', $formattedDate)->count();
        }

        // --- 6. ĐƠN HÀNG GẦN ĐÂY ---
        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'title',
                'totalOrders',
                'totalRevenue',
                'totalCustomers',
                'totalProducts',
                'pendingOrders',
                'onlineUsers',
                'todayVisits',
                'growth',
                'revenueLabels',
                'revenueValues',
                'visitLabels',
                'visitValues',
                'recentOrders'
            )
        );
    }

}
