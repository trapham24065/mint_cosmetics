@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="card-title">Suppliers Management</h4>
                <a href="#" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Suppliers
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
                        { id: 'name', name: 'Full Name' },
                        { id: 'email', name: 'Email' },
                        { id: 'phone', name: 'Phone Number' },
                        { id: 'contact_person', name: 'Contact Person' },
                        {
                            id: 'is_active', name: 'Status',
                            formatter: (cell) => cell
                                ? gridjs.html('<span class="badge bg-success">Active</span>')
                                : gridjs.html('<span class="badge bg-secondary">Inactive</span>')
                        },
                        { id: 'create_at', name: 'Create At' },
                        {
                            name: 'Actions',
                            width: '100px',
                            sort: false,
                            formatter: (cell, row) => {
                                const supplierId = row.cells[1].data;
                                const showUrl = `{{ url('admin/suppliers') }}/${supplierId}`;
                                const editUrl = `{{ url('admin/suppliers') }}/${supplierId}/edit`;
                                const deleteUrl = `{{ url('admin/suppliers') }}/${supplierId}`;

                                return gridjs.html(`
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <li>
                                                <a class="dropdown-item" href="${showUrl}">
                                                    <i class="bi bi-eye me-2 text-info"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="${editUrl}">
                                                    <i class="bi bi-pencil me-2 text-primary"></i>Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger delete-item" href="#"
                                                   data-id="${supplierId}"
                                                   data-url="${deleteUrl}">
                                                    <i class="bi bi-trash me-2"></i>Delete
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
                        url: '{{ route('admin.api.suppliers.data') }}',
                        then: results => results.data.map(supplier => [
                            null,
                            supplier.id,
                            supplier.name,
                            supplier.email,
                            supplier.phone,
                            supplier.contact_person,
                            supplier.is_active,
                            supplier.created_at,
                            null
                        ])
                    },
                    search: true,
                    sort: true,
                    pagination: { limit: 10 }
                }).render(document.getElementById("table-customers-gridjs"));

                document.addEventListener('click', function(e) {
                    if (e.target && e.target.closest('.delete-item')) {
                        e.preventDefault();
                        const btn = e.target.closest('.delete-item');
                        const url = btn.dataset.url;

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "This supplier will be deleted permanently!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = url;
                                const csrf = document.createElement('input');
                                csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = csrfToken;
                                const method = document.createElement('input');
                                method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
                                form.appendChild(csrf); form.appendChild(method);
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                    }
                });

                // --- BULK ACTIONS ---
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

                document.getElementById('table-suppliers-gridjs').addEventListener('change', function(e) {
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
                        Swal.fire('Warning', 'Please select items and an action.', 'warning');
                        return;
                    }

                    Swal.fire({
                        title: 'Confirm Bulk Action',
                        text: `Apply '${action}' to ${selectedIds.length} items?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, apply'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('{{ route("admin.suppliers.bulkUpdate") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ action: action, ids: selectedIds })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(data.success) {
                                    Swal.fire('Success', data.message, 'success');
                                    grid.forceRender();
                                    bulkActionsContainer.style.display = 'none';
                                    document.querySelector('.gridjs-checkbox-all').checked = false;
                                } else {
                                    Swal.fire('Error', data.message, 'error');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire('Error', 'Something went wrong.', 'error');
                            });
                        }
                    });
                });
            }
        });
    </script>
    <!-- @formatter:on -->
@endpush
