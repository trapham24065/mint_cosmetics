<?php

namespace App\Http\Controllers\Storefront;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

    public function handlePaymentWebhook(Request $request): JsonResponse
    {
        // 1.Get data from SePay payload
        $paymentCode = $request->input('code');
        $amount = $request->input('transferAmount');

        // 2. Extract order ID from payment code
        preg_match('/(\d+)$/', $paymentCode, $matches);

        if (isset($matches[1])) {
            $orderId = $matches[1];
            $order = Order::find($orderId);

            // 3.Check order validity
            if ($order && $order->status === OrderStatus::Pending && (int)$order->total_price === (int)$amount) {
                $order->status = OrderStatus::Processing;
                $order->save();

                Log::info("Order #$orderId was updated successfully via webhook from SePay.");
            } else {
                Log::warning("Webhook for Order #$orderId: Mismatch or order not found/pending.", [
                    'expected_amount' => (int)($order->total_price ?? 'N/A'),
                    'received_amount' => (int)$amount,
                    'current_status'  => $order->status ?? 'Not Found',
                ]);
            }
        } else {
            Log::warning('Webhook received but could not extract Order ID from code.', ['code' => $paymentCode]);
        }

        return response()->json(['status' => 'success']);
    }

}
