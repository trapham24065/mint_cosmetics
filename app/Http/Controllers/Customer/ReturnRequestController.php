<?php

namespace App\Http\Controllers\Customer;

use App\Enums\OrderStatus;
use App\Enums\ReturnStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreReturnRequestRequest;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Services\Admin\OrderReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReturnRequestController extends Controller
{

    public function __construct(private readonly OrderReturnService $returnService) {}

    public function create(Order $order): View|RedirectResponse
    {
        $blockReason = $this->getRequestBlockReason($order);
        if ($blockReason !== null) {
            return back()->with('error', $blockReason);
        }

        return view('customer.returns.create', compact('order'));
    }

    public function store(StoreReturnRequestRequest $request, Order $order): RedirectResponse
    {
        $blockReason = $this->getRequestBlockReason($order);
        if ($blockReason !== null) {
            return back()->with('error', $blockReason);
        }

        $customerId = Auth::guard('customer')->id();
        $selectedItems = $this->extractSelectedItems($request);
        $evidenceImages = $this->storeEvidenceImages($request);

        if (empty($selectedItems)) {
            return back()->with('error', 'Vui lòng chọn ít nhất một sản phẩm để trả hàng.');
        }

        $itemBlockReason = $this->validateSelectedItemsCanReturn($order, $selectedItems);
        if ($itemBlockReason !== null) {
            return back()->with('error', $itemBlockReason);
        }

        $returnPayload = [
            'order_id'        => $order->id,
            'customer_id'     => $customerId,
            'reason'          => $request->validated('reason'),
            'description'     => $request->validated('details'),
            'evidence_images' => $evidenceImages,
        ];

        if (!empty($selectedItems)) {
            $returnPayload['items'] = $selectedItems;
        }

        $this->returnService->createReturn($returnPayload);

        return redirect()
            ->route('customer.dashboard')
            ->with('success', 'Đã gửi yêu cầu trả hàng. Hệ thống sẽ xử lý sớm.');
    }

    private function getRequestBlockReason(Order $order): ?string
    {
        $customerId = Auth::guard('customer')->id();
        if ((int)$order->customer_id !== (int)$customerId) {
            return 'Bạn không có quyền gửi yêu cầu trả hàng cho đơn này.';
        }

        $rawStatus = $order->status;
        $orderStatus = $rawStatus instanceof OrderStatus
            ? $rawStatus->value
            : strtolower((string)$rawStatus);

        if ($orderStatus !== OrderStatus::Completed->value) {
            return 'Chi co the yeu cau tra hang voi don da hoan thanh.';
        }

        $returnDays = (int)config('orders.return_days', 7);
        $completedAt = $order->completed_at ?? $order->created_at;
        $returnDeadline = $completedAt?->copy()->addDays($returnDays);

        if (!$returnDeadline || now()->greaterThan($returnDeadline)) {
            return "Đơn hàng đã quá hạn {$returnDays} ngày để yêu cầu trả hàng.";
        }

        $exists = OrderReturn::query()
            ->where('order_id', $order->id)
            ->where('customer_id', $customerId)
            ->where('status', ReturnStatus::Pending->value)
            ->exists();

        if ($exists) {
            return 'Bạn đang có đơn hàng đang chờ xử lý.';
        }

        return null;
    }

    private function extractSelectedItems(Request $request): array
    {
        $items = $request->input('items', []);
        if (!is_array($items)) {
            return [];
        }

        $selectedItems = [];
        foreach ($items as $itemData) {
            if (($itemData['selected'] ?? null) !== '1' && ($itemData['selected'] ?? null) !== 1) {
                continue;
            }

            $selectedItems[] = [
                'order_item_id' => $itemData['order_item_id'] ?? null,
                'quantity'      => (int)($itemData['quantity'] ?? 0),
                'refund_price'  => $itemData['refund_price'] ?? 0,
                'item_reason'   => $itemData['item_reason'] ?? null,
            ];
        }

        return array_values(
            array_filter(
                $selectedItems,
                static fn(array $item): bool => !empty($item['order_item_id']) && $item['quantity'] > 0
            )
        );
    }

    private function validateSelectedItemsCanReturn(Order $order, array $selectedItems): ?string
    {
        if (empty($selectedItems)) {
            return null;
        }

        $orderItems = $order->items()->get()->keyBy('id');
        $lockedQtyByItem = $this->getLockedQtyByItem($order);

        foreach ($selectedItems as $selectedItem) {
            $orderItemId = (int)($selectedItem['order_item_id'] ?? 0);
            $requestQty = (int)($selectedItem['quantity'] ?? 0);

            $orderItem = $orderItems->get($orderItemId);
            if (!$orderItem) {
                return 'Có sản phẩm không thuộc đơn hàng này.';
            }

            $availableQty = (int)$orderItem->quantity - (int)($lockedQtyByItem[$orderItemId] ?? 0);
            if ($availableQty <= 0) {
                return 'Có sản phẩm đã được trả hoặc đang xử lý trả hàng, không thể yêu cầu lại.';
            }

            if ($requestQty > $availableQty) {
                return 'Số lượng trả vượt quá số lượng còn lại có thể trả.';
            }
        }

        return null;
    }

    private function getLockedQtyByItem(Order $order): array
    {
        $customerId = Auth::guard('customer')->id();

        $returns = OrderReturn::query()
            ->with('items')
            ->where('order_id', $order->id)
            ->where('customer_id', $customerId)
            ->whereIn('status', [
                ReturnStatus::Pending->value,
                ReturnStatus::Approved->value,
                ReturnStatus::Refunded->value,
            ])
            ->get();

        $lockedQtyByItem = [];
        foreach ($returns as $return) {
            foreach ($return->items as $item) {
                $itemId = (int)$item->order_item_id;
                $lockedQtyByItem[$itemId] = ($lockedQtyByItem[$itemId] ?? 0) + (int)$item->quantity;
            }
        }

        return $lockedQtyByItem;
    }

    private function storeEvidenceImages(Request $request): array
    {
        if (!$request->hasFile('evidence_images')) {
            return [];
        }

        $paths = [];
        foreach ($request->file('evidence_images', []) as $image) {
            if ($image && $image->isValid()) {
                $paths[] = $image->store('returns/evidence', 'public');
            }
        }

        return $paths;
    }
}
