<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/03/2025
 * @time 4:56 PM
 */
declare(strict_types=1);
namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use ValueError;

class OrderController extends Controller
{

    public function index(Request $request): View
    {
        // --- Lấy dữ liệu cho bảng chính ---
        $query = Order::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('search')) {
            $searchTerm = '%'.$request->input('search').'%';
            $query->where(
                fn($q) => $q->where('id', 'like', $searchTerm)->orWhere('first_name', 'like', $searchTerm)->orWhere(
                    'email',
                    'like',
                    $searchTerm
                )
            );
        }

        $orders = $query->latest()->paginate(15);

        // --- Get data for summary tags ---
        $statusCounts = Order::query()
            ->select('status', (array)DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statuses = OrderStatus::cases();

        return view('admin.management.orders.index', compact('orders', 'statuses', 'statusCounts'));
    }

    public function show(Order $order): View
    {
        $order->load('items');
        $statuses = OrderStatus::cases();

        return view('admin.management.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string'],
        ]);

        try {
            $newStatus = OrderStatus::from($request->input('status'));
            $order->status = $newStatus;
            $order->save();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', "Order #$order->id status updated to '$newStatus->value'.");
        } catch (ValueError) {
            return back()->with('error', 'Invalid status selected.');
        }
    }

}
