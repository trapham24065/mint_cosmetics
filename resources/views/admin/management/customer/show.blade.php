@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
    {{-- Header & Actions --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Khách hàng /</span> Thông tin khách hàng
        </h4>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.customers.toggle-status', $customer) }}" method="POST">
                @csrf
                @method('PUT')
                @if($customer->status)
                <button type="submit" class="btn btn-outline-warning">
                    <i class="bi bi-lock me-1"></i> Chặn khách hàng
                </button>
                @else
                <button type="submit" class="btn btn-outline-success">
                    <i class="bi bi-unlock me-1"></i>Kích hoạt khách hàng
                </button>
                @endif
            </form>

            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                Quay lại danh sách</a>
        </div>
    </div>

    <div class="row">
        {{-- Cột Trái: Thông tin cá nhân --}}
        <div class="col-xl-4 col-lg-5 col-md-5">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ asset('assets/storefront/images/blog/default-avatar.png') }}"
                        alt="user-avatar"
                        class="rounded-circle img-fluid mb-3" style="width: 100px;">
                    <h5 class="mb-1">{{ $customer->full_name }}</h5>
                    <p class="text-muted mb-3">Khách hàng
                        #{{ $customer->id }}</p>

                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-{{ $customer->status ? 'success' : 'danger' }} px-3 py-2">
                            {{ $customer->status ? 'Active' : 'Blocked' }}
                        </span>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="info-container">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <span class="fw-bold me-2">Email:</span>
                                <span>{{ $customer->email }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-bold me-2">Điện thoại:</span>
                                <span>{{ $customer->phone ?? 'N/A' }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-bold me-2">Tham gia vào lúc:</span>
                                <span>{{ $customer->created_at->format('d M, Y') }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-bold me-2">Tổng chi tiêu:</span>
                                <span class="text-primary fw-bold">{{ number_format($totalSpent) }} VND</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Địa chỉ (Nếu có) --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Địa chỉ</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1">{{ $customer->address ?? 'Không cung cấp địa chỉ.' }}</p>
                    <p class="mb-0">{{ $customer->city }}</p>
                </div>
            </div>
        </div>

        {{-- Cột Phải: Lịch sử đơn hàng --}}
        <div class="col-xl-8 col-lg-7 col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Lịch sử đơn hàng ({{ $ordersCount }})</h5>
                </div>
                <div class="card-body">
                    <div id="table-customer-orders-gridjs" data-url="{{ route('admin.api.customers.orders.data', $customer) }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableEl = document.getElementById('table-customer-orders-gridjs');
        if (!tableEl) return;
        const dataUrl = tableEl.dataset.url;

        new gridjs.Grid({
            columns: [{
                    id: 'id',
                    name: 'Mã đơn hàng',
                    formatter: (cell, row) => gridjs.html(`<a href="${row.cells[6].data}" class="fw-bold text-primary">#${cell}</a>`)
                },
                {
                    id: 'created_at',
                    name: 'Ngày'
                },
                {
                    id: 'status',
                    name: 'Trạng thái',
                    formatter: (cell) => gridjs.html(`<span class="badge bg-${cell.color}">${cell.label}</span>`)
                },
                {
                    id: 'items_count',
                    name: 'Mặt hàng',
                    formatter: (cell) => `${cell} items`
                },
                {
                    id: 'total_price',
                    name: 'Tổng cộng',
                    formatter: (cell) => `${Number(cell).toLocaleString('vi-VN')} VND`
                },
                {
                    name: 'Hoạt động',
                    sort: false,
                    formatter: (cell, row) => gridjs.html(`<a href="${row.cells[6].data}" class="btn btn-sm btn-light text-primary" title="Xem đơn hàng"><i class="bi bi-eye"></i></a>`)
                },
                {
                    id: 'show_url',
                    name: 'show_url',
                    hidden: true
                }
            ],
            server: {
                url: dataUrl,
                then: results => results.data
            },
            search: true,
            sort: true,
            pagination: {
                limit: 10
            }
        }).render(tableEl);
    });
</script>
@endpush