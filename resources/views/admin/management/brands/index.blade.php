@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">All Brands List</h4>
                        <a href="{{ route('admin.brands.create') }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus"></i> New Brand
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="table-data-brands"></div>
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
            if (document.getElementById("table-data-brands")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        { id: 'id', name: 'ID', width: '80px' },
                        {
                            id: 'logo',
                            name: 'Logo',
                            width: '100px',
                            formatter: (cell) => {
                                const imageUrl = cell ? `{{ asset('storage') }}/${cell}` : `{{ asset('assets/admin/images/default-brand.png') }}`;
                                return gridjs.html(`<img src="${imageUrl}" alt="Logo" class="avatar-sm">`);
                            }
                        },
                        { id: 'name', name: 'Name' },
                        { id: 'slug', name: 'Slug' },
                        {
                            id: 'is_active',
                            name: 'Status',
                            formatter: (cell) => {
                                return cell
                                    ? gridjs.html('<span class="badge bg-success">Active</span>')
                                    : gridjs.html('<span class="badge bg-secondary">Inactive</span>');
                            }
                        },
                        {
                            name: 'Actions',
                            width: '80px',
                            sort: false,
                            formatter: (cell, row) => {
                                const brandName=row.cells[2].data;
                                const brandId = row.cells[0].data;
                                const showUrl = `{{ route('admin.brands.index') }}/${brandId}`;
                                const editUrl = `{{ route('admin.brands.index') }}/${brandId}/edit`;
                                const deleteUrl = `{{ route('admin.brands.index') }}/${brandId}`;
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
                                                                       data-id="${brandId}"
                                                                       data-name="${brandName}"
                                                                       data-url="${deleteUrl}">
                                                                    <i class="bi bi-trash me-2"></i>Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>`
                                );
                            }
                        }
                    ],
                    server: {
                        url: '{{ route('admin.api.brands.data') }}',
                        then: results => results.data,
                    },
                    sort: true,
                    search: true,
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-data-brands"));
            }
        });

        AdminCRUD.initDeleteHandler('.delete-item', {
            confirmTitle: 'Delete Brand?',
            confirmText: 'You are about to delete brand:',
            successText: 'Brand deleted successfully.',
            onSuccess: () => {
                // Custom callback nếu cần
                console.log('Brand deleted!');
                location.reload();
            }
        });
    </script>
    <!-- @formatter:on -->

@endpush
