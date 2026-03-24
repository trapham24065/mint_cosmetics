<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use App\Services\Admin\OrderReturnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderReturnController extends Controller
{

    protected OrderReturnService $returnService;

    public function __construct(OrderReturnService $returnService)
    {
        $this->returnService = $returnService;
    }

    /**
     * Hiển thị danh sách yêu cầu trả hàng
     */
    public function index(): View
    {
        return view('admin.management.returns.index');
    }

    /**
     * Hiển thị chi tiết yêu cầu trả hàng
     */
    public function show(OrderReturn $return): View
    {
        $return->load(['order.items', 'customer', 'items.orderItem.product', 'processedBy']);
        return view('admin.management.returns.show', compact('return'));
    }

    /**
     * Duyệt yêu cầu trả hàng
     */
    public function approve(Request $request, OrderReturn $return): RedirectResponse
    {
        if (!$return->isPending()) {
            return back()->with('error', 'Chỉ có thể duyệt yêu cầu đang chờ xử lý.');
        }

        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($this->returnService->approveReturn($return, $validated['admin_note'] ?? null)) {
            return back()->with('success', 'Yêu cầu trả hàng đã được duyệt thành công.');
        }

        return back()->with('error', 'Có lỗi xảy ra khi duyệt yêu cầu.');
    }

    /**
     * Từ chối yêu cầu trả hàng
     */
    public function reject(Request $request, OrderReturn $return): RedirectResponse
    {
        if (!$return->isPending()) {
            return back()->with('error', 'Chỉ có thể từ chối yêu cầu đang chờ xử lý.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        if ($this->returnService->rejectReturn($return, $validated['reason'])) {
            return back()->with('success', 'Yêu cầu trả hàng đã bị từ chối.');
        }

        return back()->with('error', 'Có lỗi xảy ra khi từ chối yêu cầu.');
    }

    /**
     * Xử lý hoàn tiền
     */
    public function refund(Request $request, OrderReturn $return): RedirectResponse
    {
        if (!$return->isApproved()) {
            return back()->with('error', 'Chỉ có thể hoàn tiền cho yêu cầu đã được duyệt.');
        }

        $validated = $request->validate([
            'refund_method'         => ['required', 'string', 'in:bank_transfer,cash,momo,zalopay'],
            'refund_transaction_id' => ['nullable', 'string', 'max:255'],
        ]);

        if ($this->returnService->processRefund($return, $validated)) {
            return back()->with('success', 'Đã hoàn tiền và cộng lại tồn kho thành công.');
        }

        return back()->with('error', 'Có lỗi xảy ra khi xử lý hoàn tiền.');
    }

    /**
     * Hủy yêu cầu trả hàng
     */
    public function cancel(OrderReturn $return): RedirectResponse
    {
        if ($return->isRefunded()) {
            return back()->with('error', 'Không thể hủy yêu cầu đã hoàn tiền.');
        }

        if ($this->returnService->cancelReturn($return)) {
            return back()->with('success', 'Yêu cầu trả hàng đã được hủy.');
        }

        return back()->with('error', 'Có lỗi xảy ra khi hủy yêu cầu.');
    }

    /**
     * API: Lấy dữ liệu cho Grid.js table
     */
    public function getDataForGrid(): JsonResponse
    {
        $returns = OrderReturn::with(['order', 'customer', 'processedBy'])
            ->latest()
            ->get()
            ->map(function ($return) {
                return [
                    'id'            => $return->id,
                    'return_code'   => $return->return_code,
                    'order_id'      => '#'.$return->order_id,
                    'customer'      => $return->customer ? $return->customer->first_name.' '.$return->customer->last_name : 'N/A',
                    'reason'        => Str::limit($return->reason, 50),
                    'refund_amount' => number_format($return->refund_amount, 0, ',', '.').' ₫',
                    'status'        => '<span class="badge bg-'.$return->status_color.'">'.$return->status_label.'</span>',
                    'created_at'    => $return->created_at->format('d/m/Y H:i'),
                ];
            });

        return response()->json(['data' => $returns]);
    }

}
