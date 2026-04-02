@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <img
                        src="{{ $category->image ? asset('storage/' . $category->image) : asset('assets/admin/images/default.webp') }}"
                        alt="{{ $category->name }}" class="img-thumbnail rounded-circle avatar-lg mb-3">
                    <h4 class="card-title">{{ $category->name }}</h4>
                    <p class="text-muted">ID: {{ $category->id }}</p>

                    @if ($category->active)
                    <span class="badge bg-success">Hoạt động</span>
                    @else
                    <span class="badge bg-secondary">Không hoạt động</span>
                    @endif
                </div>
                <div class="card-footer bg-light border-top">
                    <div class="row text-center">
                        <div class="col-6">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary w-100">Chỉnh sửa</a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100">Trở lại danh sách</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">Thuộc tính liên kết</h4>
                </div>
                <div class="card-body">
                    @forelse($category->productAttributes as $attribute)
                    <span class="badge bg-light text-dark">{{ $attribute->name }}</span>
                    @empty
                    <p class="text-muted mb-0">Không có thuộc tính nào được liên kết với danh mục này.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Sản phẩm trong '{{ $category->name }}' ({{ $productsCount }})</h4>
                </div>
                <div class="card-body">
                    <div id="table-category-products-gridjs" data-url="{{ route('admin.api.categories.products.data', $category) }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableEl = document.getElementById('table-category-products-gridjs');
        if (!tableEl) return;
        const dataUrl = tableEl.dataset.url;

        new gridjs.Grid({
            columns: [{
                    id: 'id',
                    name: 'ID'
                },
                {
                    id: 'image',
                    name: 'Hình ảnh',
                    formatter: (cell) => {
                        const imageUrl = cell ? `{{ asset('storage') }}/${cell}` : `{{ asset('assets/admin/images/default-product.png') }}`;
                        return gridjs.html(`<img src="${imageUrl}" alt="Product" class="avatar-sm">`);
                    }
                },
                {
                    id: 'name',
                    name: 'Tên sản phẩm',
                    formatter: (cell, row) => gridjs.html(`<a href="${row.cells[4].data}" class="fw-semibold text-primary">${cell}</a>`)
                },
                {
                    id: 'active',
                    name: 'Trạng thái',
                    formatter: (cell) => cell ?
                        gridjs.html('<span class="badge bg-success">Hoạt động</span>') : gridjs.html('<span class="badge bg-secondary">Không hoạt động</span>')
                },
                {
                    id: 'show_url',
                    name: 'show_url',
                    hidden: true
                }
            ],
            server: {
                url: dataUrl,
                then: results => results.data
            },
            search: true,
            sort: true,
            pagination: {
                limit: 10
            }
        }).render(tableEl);
    });
</script>
@endpush