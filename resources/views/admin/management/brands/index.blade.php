@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">All Brands List</h4>
                        <a href="{{ route('admin.brands.create') }}" class="btn btn-sm btn-success">
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
                            width: '150px',
                            formatter: (cell, row) => {
                                const brandId = row.cells[0].data;
                                const showUrl = `{{ route('admin.brands.index') }}/${brandId}`;
                                const editUrl = `{{ route('admin.brands.index') }}/${brandId}/edit`;
                                const deleteUrl = `{{ route('admin.brands.index') }}/${brandId}`;

                                return gridjs.html(`
                                <div class="d-flex gap-2">
                                    <a href="${showUrl}" class="btn btn-sm btn-soft-info" aria-label="View brand ${brandId}"><i class="bi bi-eye"></i></a>
                                    <a href="${editUrl}" class="btn btn-sm btn-primary" aria-label="Edit brand ${brandId}"> <i class="bi bi-pencil-square"></i></iconify-icon></a>

                                    <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-danger" aria-label="Delete brand ${brandId}"><i class="bi bi-trash"></i></button>
                                    </form>
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
    </script>
    <!-- @formatter:on -->

@endpush
