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
use App\Services\Storefront\GhnService;
use App\Services\Storefront\CartService;
use App\Services\Storefront\OrderService;
use App\Services\Storefront\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewOrderNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use RuntimeException;

class PaymentController extends Controller
{

    protected CartService $cartService;

    protected OrderService $orderService;

    protected PaymentService $paymentService;

    protected GhnService $ghnService;

    public function __construct(
        CartService $cartService,
        OrderService $orderService,
        PaymentService $paymentService,
        GhnService $ghnService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
        $this->ghnService = $ghnService;
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
            return redirect()->route('customer.login')->with(
                'error',
                'Vui lòng đăng nhập để đặt hàng.'
            );
        }
        // 1. Validate customer information from the checkout form
        $validatedData = $request->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'max:500'],
            'phone'         => ['required', 'string', 'regex:/^0[0-9]{9,10}$/'],
            'email'         => ['required', 'email', 'lowercase', 'max:255'],
            'province_id'   => ['required', 'integer'],
            'district_id'   => ['required', 'integer'],
            'ward_code'     => ['required', 'string', 'max:50'],
            'province_name' => ['nullable', 'string', 'max:255'],
            'district_name' => ['nullable', 'string', 'max:255'],
            'ward_name'     => ['nullable', 'string', 'max:255'],
            'notes'         => ['nullable', 'string', 'max:1000'],
        ], [
            'first_name.required' => 'Vui lòng nhập tên.',
            'last_name.required'  => 'Vui lòng nhập họ.',
            'address.required'    => 'Vui lòng nhập địa chỉ.',
            'address.max'         => 'Địa chỉ không được vượt quá 500 ký tự.',
            'phone.required'      => 'Vui lòng nhập số điện thoại.',
            'phone.regex'         => 'Số điện thoại không hợp lệ (10-11 chữ số, bắt đầu bằng 0).',
            'email.required'      => 'Vui lòng nhập email.',
            'email.email'         => 'Email không đúng định dạng.',
            'email.lowercase'     => 'Email phải viết thường.',
            'province_id.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'district_id.required' => 'Vui lòng chọn quận/huyện.',
            'ward_code.required'   => 'Vui lòng chọn phường/xã.',
            'notes.max'            => 'Ghi chú không được vượt quá 1000 ký tự.',
        ]);

        // 2. Get cart contents from the service
        $cartData = $this->cartService->getCartContents();
        if (empty($cartData['items'])) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn trống.');
        }

        try {
            $weightGram = $this->ghnService->estimateWeight($cartData['items']);
            $feeData = $this->ghnService->calculateFee(
                (int)$validatedData['district_id'],
                (string)$validatedData['ward_code'],
                $weightGram
            );
            $shippingFee = (float)($feeData['total'] ?? 0);
        } catch (\Throwable $e) {
            Log::error('Failed to calculate GHN shipping fee: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Không thể tính phí vận chuyển GHN sandbox. Vui lòng thử lại.');
        }

        $customerId = Auth::guard('customer')->id();
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return back()->withInput()->with('error', 'Không tìm thấy thông tin tài khoản đặt hàng.');
        }

        $validatedData['customer_id'] = $customerId;
        $validatedData['shipping_fee'] = $shippingFee;
        $validatedData['shipping_provider'] = 'ghn';
        $validatedData['shipping_province_id'] = $validatedData['province_id'];
        $validatedData['shipping_district_id'] = $validatedData['district_id'];
        $validatedData['shipping_ward_code'] = $validatedData['ward_code'];

        // 3. Call the OrderService to create a new order in the database
        try {
            $order = $this->orderService->createOrder($validatedData, $cartData);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        try {
            $ghnOrder = $this->ghnService->createOrder($order, $validatedData, $weightGram);
            $order->forceFill([
                'ghn_order_code' => $ghnOrder['order_code'] ?? $ghnOrder['orderCode'] ?? null,
                'ghn_response'   => $ghnOrder,
            ])->save();
        } catch (\Throwable $e) {
            Log::warning('GHN sandbox shipping order creation failed: ' . $e->getMessage());
        }

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

        // 5. Redirect the user to the payment page with a per-order payment token
        return redirect()->route('payment.show', [
            'order' => $order->id,
            'token' => $order->payment_token,
        ]);
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
        $token = (string) request()->query('token', '');
        if ($token === '' || !hash_equals((string)$order->payment_token, $token)) {
            abort(403, 'Liên kết thanh toán không hợp lệ hoặc đã hết hạn.');
        }

        $customerId = Auth::guard('customer')->id();
        if ($customerId !== null && (int)$order->customer_id !== (int)$customerId) {
            abort(403, 'Hành vi trái phép.');
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
        $token = (string) request()->query('token', '');
        if ($token === '' || !hash_equals((string)$order->payment_token, $token)) {
            abort(403, 'Liên kết thanh toán không hợp lệ hoặc đã hết hạn.');
        }

        $customerId = Auth::guard('customer')->id();
        if ($customerId !== null && (int)$order->customer_id !== (int)$customerId) {
            abort(403, 'Hành vi trái phép.');
        }

        $order->load('items');
        return view('storefront.payment.thank-you', compact('order'));
    }
}
