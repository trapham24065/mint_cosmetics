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
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use ValueError;

class OrderController extends Controller
{

    public function index(Request $request): View
    {
        // --- Get data for main table ---
        $query = Order::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->latest()->paginate(10);

        // --- Get data for summary tags ---
        $statusCounts = Order::query()
            ->select('status', DB::raw('count(*) as total'))
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
            Mail::to($order->email)->send(new OrderStatusUpdated($order));
            return redirect()->route('admin.orders.show', $order)
                ->with('success', "Order #$order->id status updated to '$newStatus->value'.");
        } catch (ValueError) {
            return back()->with('error', 'Invalid status selected.');
        }
    }

    /**
     * Provide data for the Grid.js table via AJAX.
     */
    public function getDataForGrid(): JsonResponse
    {
        $query = Order::latest()->get();

        // Format data for Grid.js
        $data = $query->map(function ($order) {
            return [
                'id'           => $order->id,
                'customer'     => $order->first_name.' '.$order->last_name,
                'total'        => $order->total_price,
                'status'       => $order->status->value,
                'status_color' => $order->status->color(),
                'created_at'   => $order->created_at->format('d M, Y'),
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

}
