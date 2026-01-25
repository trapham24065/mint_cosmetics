<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{

    /**
     * Display order details to the customer.
     */
    public function show(Order $order): View
    {
        if ($order->customer_id !== Auth::guard('customer')->id()) {
            abort(403, 'You do not have permission to view this order.');
        }

        $order->load(['items.product', 'items.productVariant']);
        return view('storefront.customer.order-detail', compact('order'));
    }
}
