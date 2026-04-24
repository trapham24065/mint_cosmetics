<?php

namespace App\Http\Controllers\Storefront;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmed;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{

    public function handlePaymentWebhook(Request $request): JsonResponse
    {
        try {
            // 1. Get data from SePay payload
            $paymentCode = (string) $request->input('code', '');
            $amount = (int) $request->input('transferAmount', 0);
            $transferType = (string) $request->input('transferType', '');
            $transactionId = (string) ($request->input('id')
                ?? $request->input('transaction_id')
                ?? $request->input('referenceCode')
                ?? $request->input('gatewayTransactionId')
                ?? '');

            if (strtolower($transferType) !== 'in') {
                Log::info('Webhook ignored because transferType is not incoming.', [
                    'transferType' => $transferType,
                    'code' => $paymentCode,
                ]);

                return response()->json(['status' => 'success']);
            }

            // 2. Extract order ID from payment code
            preg_match('/(\d+)$/', $paymentCode, $matches);

            if (!isset($matches[1])) {
                Log::warning('Webhook received but could not extract Order ID from code.', ['code' => $paymentCode]);

                return response()->json(['status' => 'success']);
            }

            $orderId = (int) $matches[1];
            $shouldSendEmail = false;

            DB::transaction(function () use ($orderId, $amount, $transactionId, &$shouldSendEmail): void {
                $order = Order::lockForUpdate()->find($orderId);

                if (!$order) {
                    Log::warning("Webhook for Order #$orderId: order not found.");

                    return;
                }

                $payment = Payment::where('order_id', $order->id)
                    ->latest('id')
                    ->lockForUpdate()
                    ->first();

                if (!$payment) {
                    $payment = Payment::create([
                        'order_id' => $order->id,
                        'amount' => $order->total_price,
                        'payment_method' => $order->payment_method,
                        'status' => 'pending',
                    ]);
                }

                if ((int) $order->total_price !== $amount) {
                    $payment->fill([
                        'status' => 'failed',
                        'transaction_id' => $transactionId !== '' ? $transactionId : $payment->transaction_id,
                    ])->save();

                    Log::warning("Webhook for Order #$orderId: amount mismatch.", [
                        'expected_amount' => (int) $order->total_price,
                        'received_amount' => $amount,
                    ]);

                    return;
                }

                $payment->fill([
                    'status' => 'success',
                    'transaction_id' => $transactionId !== '' ? $transactionId : $payment->transaction_id,
                ])->save();

                if ($order->status === OrderStatus::Pending) {
                    $order->fill([
                        'status' => OrderStatus::Processing,
                        'transaction_id' => $transactionId !== '' ? $transactionId : $order->transaction_id,
                    ])->save();
                    $shouldSendEmail = true;
                }

                Log::info("Order #$orderId payment webhook processed successfully.");
            });

            if ($shouldSendEmail) {
                $order = Order::find($orderId);

                if ($order) {
                    try {
                        Mail::to($order->email)->send(new OrderConfirmed($order));
                    } catch (\Throwable $e) {
                        // Do not fail webhook response if email service is down.
                        Log::error("Webhook for Order #$orderId: failed to send confirmation email.", [
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            // SePay may retry aggressively on non-2xx, so always acknowledge and log.
            Log::error('SePay webhook processing failed.', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return response()->json(['status' => 'success']);
        }
    }
}
