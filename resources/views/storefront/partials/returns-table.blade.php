<table class="table table-bordered">
    <thead class="bg-light">
        <tr>
            <th>Đơn hàng</th>
            <th>Lý do</th>
            <th>Trạng thái</th>
            <th>Ngày gửi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($returns as $return)
        @php
        $statusValue = $return->status instanceof \App\Enums\ReturnStatus
        ? $return->status->value
        : (string) $return->status;
        $statusLabel = $return->status instanceof \App\Enums\ReturnStatus
        ? $return->status->label()
        : ucfirst($statusValue);
        $statusClass = [
        'pending' => 'warning',
        'approved' => 'info',
        'rejected' => 'danger',
        'refunded' => 'success',
        'cancelled' => 'secondary',
        ][$statusValue] ?? 'secondary';
        @endphp
        <tr>
            <td>
                @if($return->order)
                <a href="{{ route('customer.orders.show', $return->order->id) }}" class="text-decoration-none">
                    #{{ $return->order->id }}
                </a>
                @else
                <span class="text-muted">Không tồn tại</span>
                @endif
            </td>
            <td class="reason-cell">
                <div
                    class="fw-semibold reason-text"
                    data-bs-toggle="modal"
                    data-bs-target="#reasonModal"
                    data-reason="{{ $return->reason }}"
                    data-description="{{ $return->description }}"
                    data-images='@json($return->evidence_images, JSON_THROW_ON_ERROR)'>
                    {{ $return->reason }}
                </div>

                @if($return->description)
                <small
                    class="text-muted reason-text"
                    data-bs-toggle="modal"
                    data-bs-target="#reasonModal"
                    data-reason="{{ $return->reason }}"
                    data-description="{{ $return->description }}"
                    data-images='@json($return->evidence_images, JSON_THROW_ON_ERROR)'>
                    {{ $return->description }}
                </small>
                @endif
            </td>
            <td>
                <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
            </td>
            <td class="pe-4">
                {{ $return->created_at->format('d/m/Y H:i') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center text-muted py-5">
                Bạn chưa có yêu cầu trả hàng nào.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@php
$currentPage = $returns->currentPage();
$lastPage = $returns->lastPage();
$startPage = max(1, $currentPage - 2);
$endPage = min($lastPage, $currentPage + 2);
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 pt-2 border-top dashboard-pagination">
    <div class="small text-muted js-dashboard-summary">
        @if($returns->total() > 0)
        Hiển thị {{ $returns->firstItem() }} đến {{ $returns->lastItem() }} của {{ $returns->total() }} yêu cầu
        @else
        Không có yêu cầu trả hàng
        @endif
    </div>

    <div class="dashboard-pagination__actions" role="group" aria-label="Returns pagination">
        <button type="button" class="btn btn-sm btn-outline-secondary js-dashboard-page-link dashboard-pagination__btn" data-page="{{ max(1, $currentPage - 1) }}" {{ $currentPage <= 1 ? 'disabled' : '' }}>&lt;</button>
        @for($page = $startPage; $page <= $endPage; $page++)
            <button type="button" class="btn btn-sm {{ $page === $currentPage ? 'btn-primary' : 'btn-outline-secondary' }} js-dashboard-page-link dashboard-pagination__btn" data-page="{{ $page }}">{{ $page }}</button>
            @endfor
            <button type="button" class="btn btn-sm btn-outline-secondary js-dashboard-page-link dashboard-pagination__btn" data-page="{{ min($lastPage, $currentPage + 1) }}" {{ $currentPage >= $lastPage ? 'disabled' : '' }}>&gt;</button>
    </div>
</div>