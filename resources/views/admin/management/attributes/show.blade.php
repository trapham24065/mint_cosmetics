@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Chi tiết thuộc tính</h4>
                </div>
                <div class="card-body">
                    <h5>{{ $attribute->name }}</h5>
                    <p class="text-muted">ID: {{ $attribute->id }}</p>

                    <hr>
                    <strong>Values:</strong>
                    <p>
                        @forelse($attribute->values as $value)
                        <span class="badge bg-light text-dark">{{ $value->value }}</span>
                        @empty
                        <span class="text-muted">Chưa có giá trị nào được xác định.</span>
                        @endforelse
                    </p>

                    <strong>Liên kết với {{ $attribute->categories->count() }} danh mục:</strong>
                    <ul>
                        @forelse($attribute->categories as $category)
                        <li>{{ $category->name }}</li>
                        @empty
                        <li>Không liên kết với bất kỳ danh mục nào.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary w-100">Trở lại
                        danh sách</a>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Sản phẩm sử dụng '{{ $attribute->name }}' ({{ $productsCount }})</h4>
                </div>
                <div class="card-body">
                    <div id="table-attribute-products-gridjs" data-url="{{ route('admin.api.attributes.products.data', $attribute) }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableEl = document.getElementById('table-attribute-products-gridjs');
        if (!tableEl) return;
        const dataUrl = tableEl.dataset.url;

        new gridjs.Grid({
            columns: [{
                    id: 'id',
                    name: 'ID'
                },
                {
                    id: 'name',
                    name: 'Tên sản phẩm',
                    formatter: (cell, row) => gridjs.html(`<a href="${row.cells[4].data}" class="fw-semibold text-primary">${cell}</a>`)
                },
                {
                    id: 'category',
                    name: 'Danh mục'
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