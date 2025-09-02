<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/1/2025
 * @time 3:07 PM
 */
declare(strict_types=1);
namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Storefront\CartService;
use App\Services\Storefront\OrderService;
use App\Services\Storefront\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{

    protected CartService $cartService;

    protected OrderService $orderService;

    protected PaymentService $paymentService;

    public function __construct(CartService $cartService, OrderService $orderService, PaymentService $paymentService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    /**
     * Handle the submission of the checkout form and create an order.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeOrder(Request $request): RedirectResponse
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
        $cartData = $this->cartService->getCartContents();
        if (empty($cartData['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // 3. Call the OrderService to create a new order in the database
        $order = $this->orderService->createOrder($validatedData, $cartData);

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
        $qrString = $this->paymentService->generateVietQrString($order);

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
