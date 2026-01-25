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
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewOrderNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

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
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')->with('error', 'Please login to place an order.');
        }
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

        $customerId = Auth::guard('customer')->id();
        $validatedData['customer_id'] = $customerId;

        // 3. Call the OrderService to create a new order in the database
        $order = $this->orderService->createOrder($validatedData, $cartData);

        try {
            // Lấy tất cả user trong bảng users (giả định là admin)
            $admins = User::all();

            // Nếu bạn có logic phân quyền, hãy lọc ra admin, ví dụ:
            // $admins = User::where('role', 'admin')->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewOrderNotification($order));
            }
        } catch (\Exception $e) {
            // Ghi log lỗi nếu gửi thông báo thất bại để không làm gián đoạn quy trình mua hàng
            Log::error('Failed to send new order notification: ' . $e->getMessage());
        }

        // 4. Clear the cart after the order is created (both session and database)
        $this->cartService->clear();

        // 5. Redirect the user to the payment page with the new order ID
        $signedUrl = URL::signedRoute('payment.show', ['order' => $order->id]);

        return redirect($signedUrl);
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
        if ($order->customer_id !== Auth::guard('customer')->id()) {
            abort(403, 'Unauthorized action.');
        }

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

    public function thankYou(Order $order): View
    {
        if ($order->customer_id !== Auth::guard('customer')->id()) {
            abort(403);
        }

        $order->load('items');
        return view('storefront.payment.thank-you', compact('order'));
    }
}
