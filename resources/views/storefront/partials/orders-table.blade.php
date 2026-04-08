<table class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th>Đặt hàng</th>
            <th>Ngày</th>
            <th>Hoàn thành lúc</th>
            <th>Trạng thái</th>
            <th>Tổng cộng</th>
            <th>Hoạt động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>#{{ $order->id }}</td>
            <td>{{ $order->created_at->format('d/m/Y') }}</td>
            <td>
                @if($order->status === \App\Enums\OrderStatus::Completed)
                {{ optional($order->completed_at ?? $order->updated_at)->format('d/m/Y H:i') }}
                @else
                <span class="text-muted">Chưa hoàn thành</span>
                @endif
            </td>
            <td>
                <span class="badge bg-{{ $order->status->color() ?? 'secondary' }}">
                    {{ $order->status->label() ?? $order->status }}
                </span>
            </td>
            <td>{{ number_format($order->total_price) }} VND</td>
            <td>
                <a href="{{ route('customer.orders.show', $order->id) }}" class="check-btn sqr-btn">Xem</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Không tìm thấy đơn đặt hàng nào.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@php
$currentPage = $orders->currentPage();
$lastPage = $orders->lastPage();
$startPage = max(1, $currentPage - 2);
$endPage = min($lastPage, $currentPage + 2);
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 pt-2 border-top dashboard-pagination">
    <div class="small text-muted js-dashboard-summary">
        @if($orders->total() > 0)
        Hiển thị {{ $orders->firstItem() }} đến {{ $orders->lastItem() }} của {{ $orders->total() }} đơn hàng
        @else
        Không có đơn hàng
        @endif
    </div>

    <div class="dashboard-pagination__actions" role="group" aria-label="Orders pagination">
        <button type="button" class="btn btn-sm btn-outline-secondary js-dashboard-page-link dashboard-pagination__btn" data-page="{{ max(1, $currentPage - 1) }}" {{ $currentPage <= 1 ? 'disabled' : '' }}>&lt;</button>
        @for($page = $startPage; $page <= $endPage; $page++)
            <button type="button" class="btn btn-sm {{ $page === $currentPage ? 'btn-primary' : 'btn-outline-secondary' }} js-dashboard-page-link dashboard-pagination__btn" data-page="{{ $page }}">{{ $page }}</button>
            @endfor
            <button type="button" class="btn btn-sm btn-outline-secondary js-dashboard-page-link dashboard-pagination__btn" data-page="{{ min($lastPage, $currentPage + 1) }}" {{ $currentPage >= $lastPage ? 'disabled' : '' }}>&gt;</button>
    </div>
</div>