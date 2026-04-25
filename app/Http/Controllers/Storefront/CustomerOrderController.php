<?php

namespace App\Http\Controllers\Storefront;

use App\Enums\OrderStatus;
use App\Enums\ReturnStatus;
use App\Http\Controllers\Controller;
use App\Services\Admin\OrderReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CustomerOrderController extends Controller
{
    protected OrderReturnService $returnService;

    public function __construct(OrderReturnService $returnService)
    {
        $this->returnService = $returnService;
    }

    /**
     * Display order details to the customer.
     */
    public function show(Order $order): View
    {
        if ($order->customer_id !== Auth::guard('customer')->id()) {
            abort(403, 'You do not have permission to view this order.');
        }

        if ($order->status === OrderStatus::Pending && empty($order->payment_token)) {
            $order->forceFill([
                'payment_token' => Str::random(64),
            ])->save();
        }

        $order->load(['items.product', 'items.productVariant', 'items.review', 'returns.items']);

        $returnedQtyByItem = [];
        $lockedQtyByItem = [];

        foreach ($order->returns as $return) {
            $status = $return->status instanceof ReturnStatus
                ? $return->status->value
                : (string) $return->status;

            if (!in_array($status, ['pending', 'approved', 'refunded'], true)) {
                continue;
            }

            foreach ($return->items as $returnItem) {
                $itemId = (int) $returnItem->order_item_id;
                $qty = (int) $returnItem->quantity;

                $lockedQtyByItem[$itemId] = ($lockedQtyByItem[$itemId] ?? 0) + $qty;

                if (in_array($status, ['approved', 'refunded'], true)) {
                    $returnedQtyByItem[$itemId] = ($returnedQtyByItem[$itemId] ?? 0) + $qty;
                }
            }
        }

        $hasReturnableItems = false;
        foreach ($order->items as $item) {
            $itemId = (int) $item->id;
            $lockedQty = $lockedQtyByItem[$itemId] ?? 0;
            if ((int) $item->quantity - $lockedQty > 0) {
                $hasReturnableItems = true;
                break;
            }
        }

        $isCompleted = $order->status === OrderStatus::Completed;
        $returnDays = (int)config('orders.return_days', 7);
        $completedAt = $order->completed_at ?? $order->created_at;
        $returnDeadline = $completedAt?->copy()->addDays($returnDays);
        $isReturnWindowExpired = $isCompleted && (!$returnDeadline || now()->greaterThan($returnDeadline));

        return view('storefront.customer.order-detail', compact(
            'order',
            'returnedQtyByItem',
            'lockedQtyByItem',
            'hasReturnableItems',
            'isCompleted',
            'isReturnWindowExpired',
            'returnDays'
        ));
    }

    /**
     * Store a return request from customer
     */
    public function storeReturnRequest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'reason' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.selected' => ['required'],
            'items.*.order_item_id' => ['required', 'exists:order_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.refund_price' => ['required', 'numeric', 'min:0'],
            'items.*.item_reason' => ['nullable', 'string', 'max:500'],
        ]);

        // Verify order belongs to customer
        $order = Order::findOrFail($validated['order_id']);
        if ($order->customer_id !== Auth::guard('customer')->id()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        // Check if order is completed
        if ($order->status !== \App\Enums\OrderStatus::Completed) {
            return back()->with('error', 'Chỉ có thể yêu cầu trả hàng cho đơn hàng đã hoàn thành.');
        }

        $returnDays = (int)config('orders.return_days', 7);
        $completedAt = $order->completed_at ?? $order->created_at;
        $returnDeadline = $completedAt?->copy()->addDays($returnDays);
        if (!$returnDeadline || now()->greaterThan($returnDeadline)) {
            return back()->with('error', "Đơn hàng đã quá hạn {$returnDays} ngày để yêu cầu trả hàng.");
        }

        // Check if already has return request
        if ($order->returns()->exists()) {
            return back()->with('error', 'Đơn hàng này đã có yêu cầu trả hàng.');
        }

        // Filter only selected items
        $selectedItems = [];
        foreach ($validated['items'] as $itemId => $itemData) {
            if (isset($itemData['selected']) && $itemData['selected'] == '1') {
                $selectedItems[] = $itemData;
            }
        }

        if (empty($selectedItems)) {
            return back()->with('error', 'Vui lòng chọn ít nhất một sản phẩm để trả hàng.');
        }

        // Create return request
        $returnData = [
            'order_id' => $validated['order_id'],
            'customer_id' => Auth::guard('customer')->id(),
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'items' => $selectedItems,
        ];

        try {
            $this->returnService->createReturn($returnData);
            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Yêu cầu trả hàng đã được gửi thành công. Chúng tôi sẽ xem xét và phản hồi trong vòng 24-48 giờ.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại sau.');
        }
    }
}
