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
                        { id: 'id', name: 'ID' },
                        {
                            id: 'image', name: 'Image',
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
                            name: 'Actions', width: '150px',
                            formatter: (cell, row) => {
                                const productId = row.cells[0].data;
                                const showUrl = `/admin/products/${productId}`;
                                const editUrl = `/admin/products/${productId}/edit`;
                                const deleteUrl = `/admin/products/${productId}`;

                                return gridjs.html(`
                                <div class="d-flex gap-2">
                                    <a href="${showUrl}" class="btn btn-sm btn-soft-info"><iconify-icon icon="solar:eye-broken"
                                                                  class="align-middle fs-18"></iconify-icon></a>
                                    <a href="${editUrl}" class="btn btn-sm btn-primary"><iconify-icon icon="solar:pen-2-broken"
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
                        },
                        { id: 'id', name: 'ID', hidden: true }
                    ],
                    server: {
                        url: '{{ route('admin.api.products.data') }}',
                        then: results => results.data.map(product => [
                            product.id,
                            product.image,
                            product.name,
                            product.price,
                            product.stock,
                            product.category,
                            product.is_active,
                            null, // Placeholder cho Actions
                            product.id
                        ]),
                        total: results => results.total
                    },
                    sort: true,
                    search: true,
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-products-gridjs"));
            }
        });
    </script>
    <!-- @formatter:on -->

@endpush
