@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="card">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="card-title">Quản lý khách hàng ({{ $totalCustomers }})</h4>
        </div>
        <div class="card-body">
            <div id="bulk-actions-container" class="mb-3" style="display: none;">
                <div class="d-flex align-items-center gap-2">
                    <span id="selected-count" class="fw-bold"></span>
                    <select id="bulk-action-select" class="form-select form-select-sm" style="width: 200px;">
                        <option value="">Hãy chọn hành động...</option>
                        <option value="activate">Kích hoạt mục đã chọn</option>
                        <option value="deactivate">Vô hiệu hóa mục đã chọn</option>
                    </select>
                    <button id="apply-bulk-action-btn" class="btn btn-sm btn-secondary">Áp dụng</button>
                </div>
            </div>
            {{-- Grid.js will render the table here --}}
            <div id="table-customers-gridjs"></div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<!-- @formatter:off -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById("table-customers-gridjs")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        {
                            id: 'checkbox_select',
                            name: gridjs.html('<input type="checkbox" class="gridjs-checkbox-all"/>'),
                            formatter: (cell, row) => {
                                const customerId = row.cells[1].data;
                                return gridjs.html(`<input type="checkbox" class="gridjs-checkbox-row" data-id="${customerId}"/>`);
                            },
                            sort: false,
                            width: '40px'
                        },
                        { id: 'id', name: 'ID'},
                        { id: 'name', name: 'Họ và tên đầy đủ' },
                        { id: 'email', name: 'Email' },
                        { id: 'phone', name: 'Số điện thoại' },
                        {
                            id: 'is_active', name: 'Trạng thái',
                            formatter: (cell) => cell
                                ? gridjs.html('<span class="badge bg-success">Hoạt động</span>')
                                : gridjs.html('<span class="badge bg-secondary">Không hoạt động</span>')
                        },
                        { id: 'create_at', name: 'Thời gian tạo' },
                        {
                            name: 'Hành động',
                            width: '80px',
                            sort: false,
                            formatter: (cell, row) => {
                                const customerId = row.cells[1].data;
                                const customerName = row.cells[3].data;

                                const showUrl = `{{ url('admin/customers') }}/${customerId}`;
                                const editUrl = `{{ url('admin/customers') }}/${customerId}/edit`;
                                const deleteUrl = `{{ url('admin/customers') }}/${customerId}`;

                                return gridjs.html(`
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item" href="${showUrl}">
                                                    <i class="bi bi-eye me-2 text-info"></i>Xem chi tiết
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="${editUrl}">
                                                    <i class="bi bi-pencil-square me-2 text-primary"></i>Chỉnh sửa
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger delete-item" href="#"
                                                   data-id="${customerId}"
                                                   data-name="${customerName}"
                                                   data-url="${deleteUrl}">
                                                    <i class="bi bi-trash me-2"></i>Xóa bỏ
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                `);
                            }
                        },
                        { id: 'id', name: 'ID', hidden: true },
                    ],
                    server: {
                        url: '{{ route('admin.api.customers.data') }}',
                        then: results => results.data.map(customer => [
                            null,
                            customer.id,
                            customer.full_name,
                            customer.email,
                            customer.phone,
                            customer.status,
                            customer.created_at,
                            null,
                        ])
                    },
                    search: true, // Bật tìm kiếm client-side
                    sort: true,
                    pagination: { limit: 10 }
                }).render(document.getElementById("table-customers-gridjs"));

                // Initialize delete handler - CHỈ CẦN 1 DÒNG!
                AdminCRUD.initDeleteHandler('.delete-item', {
                    confirmTitle: 'Xóa khách hàng?',
                    confirmText: 'Bạn sắp xóa khách hàng:',
                    successText: 'Khách hàng đã được xóa thành công.'
                });

                // Initialize select all checkbox
                AdminCRUD.initSelectAll();

                // --- LOGIC CHO BULK ACTIONS ---
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

                // Lắng nghe sự kiện trên toàn bộ bảng để bắt sự kiện từ checkbox
                document.getElementById('table-customers-gridjs').addEventListener('change', function(e) {
                    if (e.target.classList.contains('gridjs-checkbox-row') || e.target.classList.contains('gridjs-checkbox-all')) {
                        if (e.target.classList.contains('gridjs-checkbox-all')) {
                            document.querySelectorAll('.gridjs-checkbox-row').forEach(checkbox => {
                                checkbox.checked = e.target.checked;
                            });
                        }
                        updateBulkUi();
                    }
                });

                // Xử lý khi nhấn nút "Apply"
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

                    fetch('#', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({
                            action: 'change_status',
                            customer_ids: selectedIds,
                            value: value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire('Success', data.message, 'success');
                            grid.forceRender();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                });
            }
        });

    </script>
    <!-- @formatter:on -->

@endpush