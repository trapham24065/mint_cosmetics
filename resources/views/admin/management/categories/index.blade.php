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
                                    src="{{ $category->image ? asset('storage/' . $category->image) : asset('assets/admin/images/default-category.png') }}"
                                    alt="{{ $category->name }}" style="height: 80px; width: 80px;">
                                {{-- Placeholder Icon, you can add dynamic images later if available --}}

                            </div>
                            <h5 class="mt-3 mb-0">{{ $category->name }}</h5>
                            <small class="text-muted">{{ $category->created_at->format('Y-m-d') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">All Categories ({{ $totalCategories }})</h4>

                        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Category
                        </a>
                    </div>
                    <div>
                        <div class="card-body">
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

                new gridjs.Grid({
                    columns: [
                        { id: 'id', name: 'ID', width: '80px' },
                        {
                            id: 'image',
                            name: 'Image',
                            width: '100px',
                            formatter: (cell) => {
                                const imageUrl = cell ? `{{ asset('storage') }}/${cell}` : `{{ asset('assets/admin/images/default-category.png') }}`;
                                return gridjs.html(`<img src="${imageUrl}" alt="Category" class="avatar-sm">`);
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
                        { id: 'created_at', name: 'Created At' },
                        {
                            name: 'Actions',
                            width: '150px',
                            formatter: (cell, row) => {
                                const categoryId = row.cells[0].data;
                                const showUrl = `{{ route('admin.categories.index') }}/${categoryId}`;
                                const editUrl = `{{ route('admin.categories.index') }}/${categoryId}/edit`;
                                const deleteUrl = `{{ route('admin.categories.index') }}/${categoryId}`;

                                return gridjs.html(`
                                <div class="d-flex gap-2">
                                    <a href="${showUrl}" class="btn btn-sm btn-soft-info"><iconify-icon icon="solar:eye-broken"
                                                                  class="align-middle fs-18"></iconify-icon></a>
                                    <a href="${editUrl}" class="btn btn-sm btn-primary"> <iconify-icon icon="solar:pen-2-broken"
                                                                  class="align-middle fs-18"></iconify-icon></a>

                                    <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-danger"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                      class="align-middle fs-18"></iconify-icon></button>
                                    </form>
                                </div>`
                                );
                            }
                        }
                    ],
                    server: {
                        url: '{{ route('admin.api.categories.data') }}',
                        then: results => results.data,
                    },
                    sort: true,
                    search: true,
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-data-categoies"));
            }
        });
    </script>
    <!-- @formatter:on -->

@endpush
