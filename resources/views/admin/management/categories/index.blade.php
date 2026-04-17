@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">

    <div class="row">
        @foreach ($latestCategories as $category)
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body text-center">
                    <div
                        class="rounded bg-secondary-subtle d-flex align-items-center justify-content-center mx-auto"
                        style="height: 80px; width: 80px;">
                        <img
                            src="{{ $category->image ? asset('storage/' . $category->image) : asset('assets/admin/images/default.webp') }}"
                            alt="{{ $category->name }}" style="height: 80px; width: 80px;">
                        {{-- Placeholder Icon, you can add dynamic images later if available --}}

                    </div>
                    <h5 class="mt-3 mb-0">{{ $category->name }}</h5>
                    <small class="text-muted">{{ $category->created_at->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-1">
                    <h4 class="card-title flex-grow-1">Tất cả các danh mục ({{ $totalCategories }})</h4>

                    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Thêm danh mục
                    </a>
                </div>
                <div>
                    <div class="card-body">
                        <div id="bulk-actions-container" class="mb-3" style="display: none;">
                            <div class="d-flex align-items-center gap-2">
                                <span id="selected-count" class="fw-bold"></span>
                                <select id="bulk-action-select" class="form-select form-select-sm" style="width: 220px;">
                                    <option value="">Hãy chọn hành động...</option>
                                    <option value="activate">Kích hoạt mục đã chọn</option>
                                    <option value="deactivate">Vô hiệu hóa mục đã chọn</option>
                                </select>
                                <button id="apply-bulk-action-btn" class="btn btn-sm btn-secondary">Áp dụng</button>
                            </div>
                        </div>
                        <div id="table-data-categoies"></div>
                    </div>
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
            if (document.getElementById("table-data-categoies")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const grid = new gridjs.Grid({
                    columns: [
                        {
                            id: 'checkbox_select',
                            name: gridjs.html('<input type="checkbox" class="gridjs-checkbox-all"/>'),
                            formatter: (cell, row) => {
                                const categoryId = row.cells[1].data;
                                return gridjs.html(`<input type="checkbox" class="gridjs-checkbox-row" data-id="${categoryId}"/>`);
                            },
                            sort: false,
                            width: '40px'
                        },
                        { id: 'id', name: 'ID', width: '80px' },
                        {
                            id: 'image',
                            name: 'Hình ảnh',
                            width: '100px',
                            formatter: (cell) => {
                                const imageUrl = cell ? `{{ asset('storage') }}/${cell}` : `{{ asset('assets/admin/images/default.webp') }}`;
                                return gridjs.html(`<img src="${imageUrl}" alt="Category" class="avatar-sm">`);
                            }
                        },
                        { id: 'name', name: 'Tên' },
                        { id: 'slug', name: 'Slug' },
                        {
                            id: 'is_active',
                            name: 'Trạng thái',
                            formatter: (cell) => {
                                return cell
                                    ? gridjs.html('<span class="badge bg-success">Hoạt động</span>')
                                    : gridjs.html('<span class="badge bg-secondary">Không hoạt động</span>');
                            }
                        },
                        { id: 'created_at', name: 'Được tạo vào lúc' },
                        {
                            name: 'Hành động',
                            width: '80px',
                            sort: false,
                            formatter: (cell, row) => {
                                const categoryId = row.cells[1].data;
                                const categoryName = row.cells[3].data;

                                const showUrl = `{{ url('admin/categories') }}/${categoryId}`;
                                const editUrl = `{{ url('admin/categories') }}/${categoryId}/edit`;
                                const deleteUrl = `{{ url('admin/categories') }}/${categoryId}`;
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
                                                                       data-id="${categoryId}"
                                                                       data-name="${categoryName}"
                                                                       data-url="${deleteUrl}">
                                                                    <i class="bi bi-trash me-2"></i>Xóa
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>`
                                );
                            }
                        }
                    ],
                    server: {
                        url: '{{ route('admin.api.categories.data') }}',
                        then: results => results.data.map(category => [
                            null,
                            category.id,
                            category.image,
                            category.name,
                            category.slug,
                            category.is_active,
                            category.created_at,
                            null,
                        ]),
                    },
                    sort: true,
                    search: {
                        enabled: true,
                        selector: (cell, rowIndex, cellIndex) => {
                            if (cellIndex === 0 || cellIndex === 2 || cellIndex === 7) {
                                return '';
                            }
                            if (cellIndex === 5) {
                                return cell ? 'active' : 'inactive';
                            }
                            return cell ? cell.toString() : '';
                        }
                    },
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-data-categoies"));

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

                document.getElementById('table-data-categoies').addEventListener('change', function(e) {
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

                    fetch('{{ route('admin.categories.bulkUpdate') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({
                            action: 'change_status',
                            category_ids: selectedIds,
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
            confirmTitle: 'Xóa danh mục?',
            confirmText: 'Bạn sắp xóa danh mục:',
            successText: 'Danh mục đã được xóa thành công.',
            onSuccess: () => {
                location.reload();
            }
        });

    </script>
    <!-- @formatter:on -->

@endpush