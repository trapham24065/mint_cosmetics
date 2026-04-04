@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        {{-- Header & Actions --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold py-3 mb-0">
                    <span class="text-muted fw-light">Nhà cung cấp /</span> {{ $supplier->name }}
                </h4>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-primary">
                    <i class="ri-edit-line me-1"></i> Chỉnh sửa nhà cung cấp
                </a>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">Quay lại danh sách</a>
            </div>
        </div>

        <div class="row">
            {{-- Cột Trái: Thông tin chi tiết --}}
            <div class="col-xl-4 col-lg-5 col-md-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Tổng quan</h5>

                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar bg-light-primary rounded me-3 p-2">
                                <i class="ri-building-line fs-24 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $supplier->name }}</h6>
                                <small class="text-muted">ID: #{{ $supplier->id }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="fw-bold d-block mb-1">Trạng thái:</span>
                            @if($supplier->is_active)
                                <span class="badge bg-success-subtle text-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Ngừng hoạt động</span>
                            @endif
                        </div>

                        <hr>

                        <div class="info-list">
                            <div class="mb-3">
                            <span class="fw-bold d-block mb-1">
                                <i class="ri-user-line me-1"></i> Người liên hệ:
                            </span>
                                <span>{{ $supplier->contact_person ?? 'Không có' }}</span>
                            </div>
                            <div class="mb-3">
                            <span class="fw-bold d-block mb-1">
                                <i class="ri-mail-line me-1"></i> Email:
                            </span>
                                <a href="mailto:{{ $supplier->email }}">{{ $supplier->email ?? 'Không có' }}</a>
                            </div>
                            <div class="mb-3">
                            <span class="fw-bold d-block mb-1">
                                <i class="ri-phone-line me-1"></i> Số điện thoại:
                            </span>
                                <span>{{ $supplier->phone ?? 'Không có' }}</span>
                            </div>
                            <div class="mb-3">
                            <span class="fw-bold d-block mb-1">
                                <i class="ri-map-pin-line me-1"></i> Địa chỉ:
                            </span>
                                <span>{{ $supplier->address ?? 'Không có' }}</span>
                            </div>
                        </div>

                        @if($supplier->note)
                            <div class="alert alert-light mt-3 mb-0 border">
                                <i class="ri-sticky-note-line me-1"></i> <strong>Ghi chú:</strong><br>
                                {{ $supplier->note }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Thống kê nhanh --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Thống kê</h5>
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <h4 class="fw-bold text-primary mb-0">{{ $purchaseOrdersCount }}</h4>
                                <small class="text-muted">Tổng đơn hàng</small>
                            </div>
                            <div class="col-6">
                                <h4 class="fw-bold text-success mb-0">{{ number_format($totalImportValue) }}</h4>
                                <small class="text-muted">Tổng giá trị (VND)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột Phải: Lịch sử nhập kho --}}
            <div class="col-xl-8 col-lg-7 col-md-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Lịch sử đơn nhập kho</h5>
                        <a href="{{ route('admin.inventory.create') }}?supplier_id={{ $supplier->id }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="ri-add-line"></i> Tạo đơn nhập mới
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="table-supplier-orders-gridjs"
                             data-url="{{ route('admin.api.suppliers.orders.data', $supplier) }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableEl = document.getElementById('table-supplier-orders-gridjs');
            if (!tableEl) {
                return;
            }
            const dataUrl = tableEl.dataset.url;

            new gridjs.Grid({
                columns: [
                    {
                        id: 'code',
                        name: 'Mã PO',
                        formatter: (cell, row) => gridjs.html(
                            `<a href="${row.cells[6].data}" class="fw-bold text-primary">${cell}</a>`),
                    },
                    {
                        id: 'created_at',
                        name: 'Ngày',
                    },
                    {
                        id: 'status',
                        name: 'Trạng thái',
                        formatter: (cell) => {
                            if (cell === 'completed') {
                                return gridjs.html(
                                    '<span class="badge bg-success-subtle text-success">Hoàn thành</span>');
                            }
                            if (cell === 'cancelled') {
                                return gridjs.html('<span class="badge bg-danger-subtle text-danger">Đã hủy</span>');
                            }
                            return gridjs.html('<span class="badge bg-warning-subtle text-warning">Đang chờ</span>');
                        },
                    },
                    {
                        id: 'items_count',
                        name: 'Số lượng mặt hàng',
                        formatter: (cell) => `${cell} mặt hàng`,
                    },
                    {
                        id: 'total_amount',
                        name: 'Tổng tiền',
                        formatter: (cell) => gridjs.html(
                            `<span class="fw-bold">${Number(cell).toLocaleString('vi-VN')}</span>`),
                    },
                    {
                        name: 'Hành động',
                        sort: false,
                        formatter: (cell, row) => gridjs.html(
                            `<a href="${row.cells[6].data}" class="btn btn-sm btn-outline-primary" title="Xem đơn hàng"><i class="bi bi-eye"></i> Xem</a>`),
                    },
                    {
                        id: 'show_url',
                        name: 'show_url',
                        hidden: true,
                    },
                ],
                server: {
                    url: dataUrl,
                    then: results => results.data,
                },
                search: true,
                sort: true,
                pagination: {
                    limit: 10,
                },
            }).render(tableEl);
        });
    </script>
@endpush
