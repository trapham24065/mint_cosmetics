<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/1/2025
 * @time 3:07 PM
 */

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;

use App\Services\Storefront\CartService;
use App\Services\Storefront\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use cuongnm\viet_qr_pay\QRPay;

class PaymentController extends Controller
{

    /**
     * Handle the submission of the checkout form and create an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\Storefront\OrderService  $orderService
     * @param  \App\Services\Storefront\CartService  $cartService
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeOrder(Request $request, OrderService $orderService, CartService $cartService): RedirectResponse
    {
        // 1. Validate customer information from the checkout form
        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'address'    => ['required', 'string'],
            'phone'      => ['required', 'string'],
            'email'      => ['required', 'email'],
            'notes'      => ['nullable', 'string'],
        ]);

        // 2. Get cart contents from the service
        $cartData = $cartService->getCartContents();
        if (empty($cartData['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // 3. Call the OrderService to create a new order in the database
        $order = $orderService->createOrder($validatedData, $cartData);

        // 4. Clear the cart from the session after the order is created
        session()->forget('cart');

        // 5. Redirect the user to the payment page with the new order ID
        return redirect()->route('payment.show', $order);
    }

    /**
     * Display the QR code payment page for a specific order.
     *
     * @param  \App\Models\Order  $order
     *
     * @return \Illuminate\View\View
     */
    public function showPaymentPage(Order $order): View
    {
        $paymentDescription = 'DH'.$order->id;
        // 1. Initialize the QRPay object using the correct library
        $qrPay = QRPay::initVietQR(
            config('payment.vietqr.bank_id'),
            config('payment.vietqr.account_number'),
            (string)(int)$order->total_price,
            $paymentDescription
        );

        // 2. Generate the QR string
        $qrString = $qrPay->build();

        // 3. Return the view, passing the order and the QR string
        return view('storefront.payment.show', compact('order', 'qrString'));
    }

    /**
     * API endpoint for JavaScript to check the current status of an order.
     *
     * @param  \App\Models\Order  $order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkOrderStatus(Order $order): JsonResponse
    {
        // Simply return the current status of the order as a JSON response
        return response()->json(['status' => $order->status]);
    }

}
