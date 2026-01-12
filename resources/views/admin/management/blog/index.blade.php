@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="card-title">Blog Management</h4>
                <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Blog
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
                <div id="table-blogs-gridjs"></div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

    <!-- @formatter:off -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById("table-blogs-gridjs")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        {
                            id: 'checkbox_select',
                            name: gridjs.html('<input type="checkbox" class="gridjs-checkbox-all"/>'),
                            formatter: (cell, row) => {
                                const blogId = row.cells[1].data;
                                return gridjs.html(`<input type="checkbox" class="gridjs-checkbox-row" data-id="${blogId}"/>`);
                            },
                            sort: false,
                            width: '40px'
                        },
                        { id: 'id', name: 'ID', hidden: true },
                        {
                            id: 'image',
                            name: 'Image',
                            formatter: (cell) => {
                                const imageUrl = cell ? `/storage/${cell}` : `{{ asset('assets/admin/images/default-blog.png') }}`;
                                return gridjs.html(`<img src="${imageUrl}" alt="Blog" class="avatar-sm">`);
                            }
                        },
                        { id: 'name', name: 'Name' },
                        {
                            id: 'is_active', name: 'Status',
                            formatter: (cell) => cell
                                ? gridjs.html('<span class="badge bg-success">Active</span>')
                                : gridjs.html('<span class="badge bg-secondary">Inactive</span>')
                        },
                        {
                            name: 'Actions',
                            width: '80px',
                            sort: false,
                            formatter: (cell, row) => {
                                const blogId = row.cells[1].data;
                                const blogName = row.cells[3].data;

                                const showUrl = `{{ url('admin/blogs') }}/${blogId}`;
                                const editUrl = `{{ url('admin/blogs') }}/${blogId}/edit`;
                                const deleteUrl = `{{ url('admin/blogs') }}/${blogId}`;

                                return gridjs.html(`
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item" href="${showUrl}">
                                                    <i class="bi bi-eye me-2 text-info"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="${editUrl}">
                                                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger delete-item" href="#"
                                                   data-id="${blogId}"
                                                   data-name="${blogName}"
                                                   data-url="${deleteUrl}">
                                                    <i class="bi bi-trash me-2"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                `);
                            }
                        },
                        { id: 'id', name: 'ID', hidden: true }
                    ],
                    server: {
                        url: '{{ route('admin.api.blogs.data') }}',
                        then: results => results.data.map(blog => [
                            null, // Placeholder cho checkbox
                            blog.id,
                            blog.image,
                            blog.title,
                            blog.is_published,
                            null, // Placeholder cho Actions
                        ])
                    },
                    search: true, // Bật tìm kiếm client-side
                    sort: true,
                    pagination: { limit: 10 }
                }).render(document.getElementById("table-blogs-gridjs"));

                // Initialize delete handler - CHỈ CẦN 1 DÒNG!
                AdminCRUD.initDeleteHandler('.delete-item', {
                    confirmTitle: 'Delete Blog?',
                    confirmText: 'You are about to delete blog:',
                    successText: 'Blog has been deleted successfully.'
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
                        selectedCountEl.textContent = `${selectedIds.length} item(s) selected`;
                    } else {
                        bulkActionsContainer.style.display = 'none';
                    }
                }

                document.getElementById('table-blogs-gridjs').addEventListener('change', function(e) {
                    if (e.target.classList.contains('gridjs-checkbox-row') || e.target.classList.contains('gridjs-checkbox-all')) {
                        if (e.target.classList.contains('gridjs-checkbox-all')) {
                            document.querySelectorAll('.gridjs-checkbox-row').forEach(checkbox => {
                                checkbox.checked = e.target.checked;
                            });
                        }
                        updateBulkUi();
                    }
                });

            }
        });

    </script>
    <!-- @formatter:on -->

@endpush
