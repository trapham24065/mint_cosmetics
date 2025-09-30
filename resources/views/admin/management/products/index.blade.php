@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="card-title">Product Management</h4>
                <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Product
                </a>
            </div>
            <div class="card-body">
                <div id="bulk-actions-container" class="mb-3" style="display: none;">
                    <div class="d-flex align-items-center gap-2">
                        <span id="selected-count" class="fw-bold"></span>
                        <select id="bulk-action-select" class="form-select form-select-sm" style="width: 200px;">
                            <option value="">Choose action...</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                        </select>
                        <button id="apply-bulk-action-btn" class="btn btn-sm btn-secondary">Apply</button>
                    </div>
                </div>
                {{-- Grid.js will render the table here --}}
                <div id="table-products-gridjs"></div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

    <!-- @formatter:off -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById("table-products-gridjs")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        {
                            id: 'checkbox_select',
                            name: gridjs.html('<input type="checkbox" class="gridjs-checkbox-all"/>'),
                            formatter: (cell, row) => {
                                const productId = row.cells[1].data;
                                return gridjs.html(`<input type="checkbox" class="gridjs-checkbox-row" data-id="${productId}"/>`);
                            },
                            sort: false,
                            width: '40px'
                        },
                        { id: 'id', name: 'ID', hidden: true },
                        {
                            id: 'image',
                            name: 'Image',
                            formatter: (cell) => {
                                const imageUrl = cell ? `/storage/${cell}` : `{{ asset('assets/admin/images/default-product.png') }}`;
                                return gridjs.html(`<img src="${imageUrl}" alt="Product" class="avatar-sm">`);
                            }
                        },
                        { id: 'name', name: 'Name' },
                        {
                            id: 'price', name: 'Price',
                            formatter: (cell) => cell ? `${parseFloat(cell).toLocaleString('vi-VN')} VNĐ` : 'N/A'
                        },
                        { id: 'stock', name: 'Stock' },
                        { id: 'category', name: 'Category' },
                        {
                            id: 'is_active', name: 'Status',
                            formatter: (cell) => cell
                                ? gridjs.html('<span class="badge bg-success">Active</span>')
                                : gridjs.html('<span class="badge bg-secondary">Inactive</span>')
                        },
                        {
                            name: 'Actions',
                            width: '150px',
                            formatter: (cell, row) => {
                                const productId = row.cells[1].data;
                                const showUrl = `/admin/products/${productId}`;
                                const editUrl = `/admin/products/${productId}/edit`;
                                const deleteUrl = `/admin/products/${productId}`;

                                return gridjs.html(`
                        <div class="d-flex gap-2">
                            <a href="${showUrl}" class="btn btn-sm btn-soft-info" aria-label="View product ${productId}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="${editUrl}" class="btn btn-sm btn-primary" aria-label="Edit product ${productId}">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger" aria-label="Delete product ${productId}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>`);
                            }
                        },
                        { id: 'id', name: 'ID', hidden: true }
                    ],
                    server: {
                        url: '{{ route('admin.api.products.data') }}',
                        then: results => results.data.map(product => [
                            null, // Placeholder cho checkbox
                            product.id,
                            product.image,
                            product.name,
                            product.price,
                            product.stock,
                            product.category,
                            product.is_active,
                            null, // Placeholder cho Actions
                        ])
                    },
                    search: true, // Bật tìm kiếm client-side
                    sort: true,
                    pagination: { limit: 10 }
                }).render(document.getElementById("table-products-gridjs"));

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
                        selectedCountEl.textContent = `${selectedIds.length} item(s) selected`;
                    } else {
                        bulkActionsContainer.style.display = 'none';
                    }
                }

                // Lắng nghe sự kiện trên toàn bộ bảng để bắt sự kiện từ checkbox
                document.getElementById('table-products-gridjs').addEventListener('change', function(e) {
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
                        alert('Please select items and an action.');
                        return;
                    }

                    let value;
                    if (action === 'activate') value = true;
                    if (action === 'deactivate') value = false;

                    fetch('{{ route('admin.products.bulkUpdate') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({
                            action: 'change_status',
                            product_ids: selectedIds,
                            value: value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire('Success', data.message, 'success');
                            grid.forceRender(); // Tải lại dữ liệu bảng
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
