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
                    <h4 class="card-title">Danh sách tất cả phiếu giảm giá ({{ $totalCoupons }})</h4>
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-plus"></i> Mã giảm giá mới
                    </a>
                </div>
                <div class="card-body">
                    <div id="bulk-actions-container" class="mb-3" style="display: none;">
                        <div class="d-flex align-items-center gap-2">
                            <span id="selected-count" class="fw-bold"></span>
                            <select id="bulk-action-select" class="form-select form-select-sm"
                                style="width: 220px;">
                                <option value="">Hãy chọn hành động...</option>
                                <option value="activate">Kích hoạt mục đã chọn</option>
                                <option value="deactivate">Vô hiệu hóa mục đã chọn</option>
                            </select>
                            <button id="apply-bulk-action-btn" class="btn btn-sm btn-secondary">Áp dụng</button>
                        </div>
                    </div>

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

                const grid = new gridjs.Grid({
                    columns: [
                        {
                            id: 'checkbox_select',
                            name: gridjs.html('<input type="checkbox" class="gridjs-checkbox-all"/>'),
                            formatter: (cell, row) => {
                                const couponId = row.cells[1].data;
                                return gridjs.html(`<input type="checkbox" class="gridjs-checkbox-row" data-id="${couponId}"/>`);
                            },
                            sort: false,
                            width: '40px'
                        },
                        { id: 'id', name: 'ID' },
                        { id: 'code', name: 'Mã',
                            attributes: () => ({
                                style: ' min-width: 100px; max-width: 100px;'
                            }) },
                        { id: 'type_label', name: 'Loại' },
                        { id: 'type', hidden: true },
                        {
                            id: 'value',
                            name: 'Giá trị',
                            formatter: (cell, row) => {

                                const type = row.cells[4].data;

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
                                const couponId = row.cells[1].data;
                                const couponName = row.cells[2].data;

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
                        then: results => results.data.map(coupon => ({
                            checkbox_select: null,
                            id: coupon.id,
                            code: coupon.code,
                            type_label: coupon.type_label,
                            type: coupon.type,
                            value: coupon.value,
                            usage: coupon.usage,
                            dates: coupon.dates,
                            is_active: coupon.is_active,
                        })),
                    },
                    sort: true,
                    search: true,
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-coupons-gridjs"));

                const bulkActionsContainer = document.getElementById('bulk-actions-container');
                const selectedCountEl = document.getElementById('selected-count');
                const applyBtn = document.getElementById('apply-bulk-action-btn');
                const actionSelect = document.getElementById('bulk-action-select');

                function getSelectedIds() {
                    const selected = [];
                    document.querySelectorAll('.gridjs-checkbox-row:checked').forEach(checkbox => {
                        selected.push(checkbox.dataset.id);
                    });
                    return selected;
                }

                function updateBulkUi() {
                    const selectedIds = getSelectedIds();
                    if (selectedIds.length > 0) {
                        bulkActionsContainer.style.display = 'block';
                        selectedCountEl.textContent = `${selectedIds.length} mục đã chọn`;
                    } else {
                        bulkActionsContainer.style.display = 'none';
                    }
                }

                document.getElementById('table-coupons-gridjs').addEventListener('change', function(e) {
                    if (e.target.classList.contains('gridjs-checkbox-row') || e.target.classList.contains('gridjs-checkbox-all')) {
                        if (e.target.classList.contains('gridjs-checkbox-all')) {
                            document.querySelectorAll('.gridjs-checkbox-row').forEach(checkbox => {
                                checkbox.checked = e.target.checked;
                            });
                        }
                        updateBulkUi();
                    }
                });

                applyBtn.addEventListener('click', function() {
                    const selectedIds = getSelectedIds();
                    const action = actionSelect.value;

                    if (selectedIds.length === 0 || !action) {
                        alert('Vui lòng chọn các mục và hành động.');
                        return;
                    }

                    let value;
                    if (action === 'activate') value = true;
                    if (action === 'deactivate') value = false;

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        },
                    });

                    fetch('{{ route('admin.coupons.bulkUpdate') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({
                            action: 'change_status',
                            coupon_ids: selectedIds,
                            value: value,
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Toast.fire({ icon: 'success', title: data.message });
                            grid.forceRender();
                        } else {
                            Toast.fire({ icon: 'error', title: data.message });
                        }
                    })
                    .catch(() => {
                        Toast.fire({ icon: 'error', title: 'Đã xảy ra lỗi.' });
                    });
                });
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