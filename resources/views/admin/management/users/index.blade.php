@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="card-title">Quản lý tài khoản quản trị</h4>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tạo tài khoản mới
                </a>
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
                <div id="table-users-gridjs"></div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

    <!-- @formatter:off -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById("table-users-gridjs")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        {
                            id: 'checkbox_select',
                            name: gridjs.html('<input type="checkbox" class="gridjs-checkbox-all"/>'),
                            formatter: (cell, row) => {
                                const userId = row.cells[1].data;
                                return gridjs.html(`<input type="checkbox" class="gridjs-checkbox-row" data-id="${userId}"/>`);
                            },
                            sort: false,
                            width: '40px'
                        },
                        { id: 'id', name: 'ID'},
                        { id: 'name', name: 'Tên' },
                        { id: 'email', name: 'Email' },
                        { id: 'role', name: 'Vai trò',
                            formatter: (cell) => {
                                const roleColors = {
                                    'admin': 'danger',
                                    'sale': 'primary',
                                    'warehouse': 'success'
                                };
                                const roleLabels = {
                                    'admin': 'Admin',
                                    'sale': 'Sale',
                                    'warehouse': 'Warehouse'
                                };
                                const color = roleColors[cell] || 'secondary';
                                const label = roleLabels[cell] || cell;
                                return gridjs.html(`<span class="badge bg-${color}">${label}</span>`);
                            }
                        },
                        {
                            id: 'status', name: 'Trạng thái',
                            formatter: (cell) => cell === 'active'
                                ? gridjs.html('<span class="badge bg-success">Hoạt động</span>')
                                : gridjs.html('<span class="badge bg-secondary">Không hoạt động</span>')
                        },
                        { id: 'created_at', name: 'Thời gian tạo' },
                        {
                            name: 'Hành động',
                            width: '80px',
                            sort: false,
                            formatter: (cell, row) => {
                                const userId = row.cells[1].data;
                                const userName = row.cells[2].data;

                                const editUrl = `{{ url('admin/users') }}/${userId}/edit`;
                                const deleteUrl = `{{ url('admin/users') }}/${userId}`;

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
                                                   data-id="${userId}"
                                                   data-name="${userName}"
                                                   data-url="${deleteUrl}">
                                                    <i class="bi bi-trash me-2"></i>Xóa bỏ
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                `);
                            }
                        },
                    ],
                    server: {
                        url: '{{ route('admin.api.users.data') }}',
                        then: results => results.data.map(user => [
                            null,
                            user.id,
                            user.name,
                            user.email,
                            user.role,
                            user.status,
                            user.created_at,
                            null,
                        ])
                    },
                    search: true,
                    sort: true,
                    pagination: { limit: 10 }
                }).render(document.getElementById("table-users-gridjs"));

                // Initialize delete handler
                AdminCRUD.initDeleteHandler('.delete-item', {
                    confirmTitle: 'Xóa tài khoản?',
                    confirmText: 'Bạn sắp xóa tài khoản:',
                    successText: 'Tài khoản đã được xóa thành công.'
                });
            }
        });
    </script>
    <!-- @formatter:on -->
@endpush

