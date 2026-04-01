@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="card-title">Quản lý liên hệ khách hàng</h4>
            </div>

            <div class="card-body">

                {{-- BULK ACTION --}}
                <div id="bulk-actions-container" class="mb-3" style="display: none;">
                    <div class="d-flex align-items-center gap-2">
                        <span id="selected-count" class="fw-bold"></span>

                        <select id="bulk-action-select" class="form-select form-select-sm" style="width: 220px;">
                            <option value="">Chọn hành động...</option>
                            <option value="mark_processed">Đánh dấu đã xử lý</option>
                            <option value="delete">Xóa</option>
                        </select>

                        <button id="apply-bulk-action-btn" class="btn btn-sm btn-secondary">
                            Áp dụng
                        </button>
                    </div>
                </div>

                {{-- GRID --}}
                <div id="table-contacts-gridjs"></div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('table-contacts-gridjs')) {

                new gridjs.Grid({
                    columns: [
                        {
                            id: 'checkbox',
                            name: gridjs.html('<input type="checkbox" class="gridjs-checkbox-all"/>'),
                            formatter: (cell, row) => {
                                const id = row.cells[1].data;
                                return gridjs.html(
                                    `<input type="checkbox" class="gridjs-checkbox-row" data-id="${id}"/>`);
                            },
                            width: '40px',
                            sort: false,
                        },
                        { id: 'id', name: 'ID' },
                        { id: 'name', name: 'Khách hàng' },
                        { id: 'email', name: 'Email' },
                        {
                            id: 'message',
                            name: 'Nội dung',
                            formatter: (cell) => {
                                return gridjs.html(`<span title="${cell}">${cell}</span>`);
                            },
                        },
                        {
                            id: 'status',
                            name: 'Trạng thái',
                            formatter: (cell) => {
                                return cell === 'processed'
                                    ? gridjs.html('<span class="badge bg-success">Đã xử lý</span>')
                                    : gridjs.html('<span class="badge bg-warning text-dark">Chưa xử lý</span>');
                            },
                        },
                        { id: 'created_at', name: 'Gửi lúc' },
                        {
                            name: 'Hành động',
                            sort: false,
                            width: '80px',
                            formatter: (cell, row) => {
                                const id = row.cells[1].data;
                                const name = row.cells[2].data;

                                const showUrl = `{{ url('admin/contacts') }}/${id}`;
                                const deleteUrl = `{{ url('admin/contacts') }}/${id}`;

                                return gridjs.html(`
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <a class="dropdown-item" href="${showUrl}">
                                            <i class="bi bi-eye me-2 text-info"></i>Xem
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item mark-processed" href="#"
                                           data-id="${id}">
                                            <i class="bi bi-check-lg me-2 text-success"></i>Đánh dấu đã xử lý
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger delete-item"
                                           href="#"
                                           data-id="${id}"
                                           data-name="${name}"
                                           data-url="${deleteUrl}">
                                            <i class="bi bi-trash me-2"></i>Xóa bỏ
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        `);
                            },
                        },
                    ],

                    server: {
                        url: "{{ route('admin.api.contacts.data') }}",
                        then: data => data.data.map(item => [
                            null,
                            item.id,
                            item.name,
                            item.email,
                            item.message,
                            item.status,
                            item.created_at,
                            null,
                        ]),
                    },

                    search: true,
                    sort: true,
                    pagination: {
                        limit: 10,
                    },

                }).render(document.getElementById('table-contacts-gridjs'));

                // DELETE
                AdminCRUD.initDeleteHandler('.delete-item', {
                    confirmTitle: 'Xóa liên hệ?',
                    confirmText: 'Bạn sắp xóa liên hệ:',
                    successText: 'Đã xóa thành công.',
                });

            }
        });
    </script>
@endpush
