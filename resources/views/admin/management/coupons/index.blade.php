@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">

        <div class="row">
            <div class="col-md-6 col-xl-4">
                <div class="card bg-primary-subtle">
                    <div class="card-body">
                        <h4 class="mb-1">{{ $totalCoupons }} Tổng số phiếu giảm giá</h4>
                        <p>Tất cả các phiếu giảm giá được tạo trong hệ thống.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card bg-success-subtle">
                    <div class="card-body">
                        <h4 class=" mb-1">{{ $activeCoupons }} Mã giảm giá đang hoạt động</h4>
                        <p class="">Các phiếu giảm giá hiện đang có hiệu lực.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card bg-danger-subtle">
                    <div class="card-body">
                        <h4 class=" mb-1">{{ $expiredCoupons }} Phiếu giảm giá đã hết hạn</h4>
                        <p class="">Các phiếu giảm giá đã hết hạn.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">Danh sách tất cả phiếu giảm giá</h4>
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus"></i> Mã giảm giá mới
                        </a>
                    </div>
                    <div class="card-body">

                        <div id="table-coupons-gridjs"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
@push('scripts')
    <!-- @formatter:off -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById("table-coupons-gridjs")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        { id: 'id', name: 'ID' },
                        { id: 'code', name: 'Mã',
                            attributes: () => ({
                                style: ' min-width: 100px; max-width: 100px;'
                            }) },
                        { id: 'type', name: 'Type' },
                        {
                            id: 'value',
                            name: 'Giá trị',
                            formatter: (cell, row) => {
                                const type = row.cells[2].data;
                                if (type === 'percentage') {
                                    return `${cell}%`;
                                }
                                return `${parseFloat(cell).toLocaleString('vi-VN')} VNĐ`;
                            }
                        },
                        { id: 'usage', name: 'Mức sử dụng (Đã sử dụng/Tối đa)',
                            attributes: () => ({
                                style: ' min-width: 40px; max-width: 40px;'
                            })
                        },
                        { id: 'dates', name: 'Ngày có hiệu lực' },
                        {
                            id: 'is_active',
                            name: 'Trạng thái',
                            formatter: (cell) => cell
                                ? gridjs.html('<span class="badge bg-success">Hoạt động</span>')
                                : gridjs.html('<span class="badge bg-secondary">Không hoạt động</span>')
                        },
                        {
                            name: 'Hành động',
                            width: '80px',
                            sort: false,
                            formatter: (cell, row) => {
                                const couponId = row.cells[0].data;
                                const couponName=row.cells[2].data;

                                const editUrl = `/admin/coupons/${couponId}/edit`;
                                const deleteUrl = `/admin/coupons/${couponId}`;
                                return gridjs.html(`
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                            <li>
                                                                <a class="dropdown-item" href="${editUrl}">
                                                                    <i class="bi bi-pencil-square me-2 text-primary"></i>Chỉnh sửa
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item text-danger delete-item" href="#"
                                                                       data-id="${couponId}"
                                                                       data-name="${couponName}"
                                                                       data-url="${deleteUrl}">
                                                                    <i class="bi bi-trash me-2"></i>Xóa
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>`
                                );
                            }
                        },
                    ],
                    server: {
                        url: '{{ route('admin.api.coupons.data') }}',
                        then: results => results.data,
                    },
                    sort: true,
                    search: true,
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-coupons-gridjs"));
            }
        });

        AdminCRUD.initDeleteHandler('.delete-item', {
            confirmTitle: 'Xóa mã giảm giá?',
            confirmText: 'Bạn sắp xóa mã giảm giá:',
            successText: 'Mã giảm giá đã được xóa thành công.',
            onSuccess: () => {
                location.reload();
            }
        });
    </script>
    <!-- @formatter:on -->
@endpush
