<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateReturnRequestStatusRequest;
use App\Models\ReturnRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = ReturnRequest::query()->with(['user', 'order'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $returns = $query->paginate(15)->withQueryString();

        return view('admin.returns.index', compact('returns'));
    }

    public function show(ReturnRequest $returnRequest): View
    {
        $returnRequest->load(['user', 'order']);
        return view('admin.returns.show', compact('returnRequest'));
    }

    public function updateStatus(
        UpdateReturnRequestStatusRequest $request,
        ReturnRequest $returnRequest
    ): RedirectResponse {
        $newStatus = $request->validated('status');
        $currentStatus = $returnRequest->status;

        if (!$this->canTransition($currentStatus, $newStatus)) {
            return back()->with('error', 'Chuyển trạng thái không hợp lệ.');
        }

        $returnRequest->update([
            'status' => $newStatus,
            'admin_note' => $request->validated('admin_note'),
            'resolved_at' => in_array($newStatus, ['rejected', 'refunded'], true) ? now() : $returnRequest->resolved_at,
        ]);

        return back()->with('success', 'Cập nhật trạng thái trả hàng thành công.');
    }

    private function canTransition(string $from, string $to): bool
    {
        $allowed = [
            'pending' => ['approved', 'rejected'],
            'approved' => ['received'],
            'received' => ['refunded'],
            'rejected' => [],
            'refunded' => [],
        ];

        return in_array($to, $allowed[$from] ?? [], true);
    }
}