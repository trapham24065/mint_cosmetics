@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img
                            src="{{ $category->image ? asset('storage/' . $category->image) : asset('assets/admin/images/default-category.png') }}"
                            alt="{{ $category->name }}" class="img-thumbnail rounded-circle avatar-lg mb-3">
                        <h4 class="card-title">{{ $category->name }}</h4>
                        <p class="text-muted">ID: {{ $category->id }}</p>

                        @if ($category->active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                    <div class="card-footer bg-light border-top">
                        <div class="row text-center">
                            <div class="col-6">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary w-100">Edit</a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100">Back
                                    to List</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header"><h4 class="card-title">Linked Attributes</h4></div>
                    <div class="card-body">
                        @forelse($category->productAttributes as $attribute)
                            <span class="badge bg-light text-dark">{{ $attribute->name }}</span>
                        @empty
                            <p class="text-muted mb-0">No attributes are linked to this category.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Products in '{{ $category->name }}' ({{ $products->total() }})</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            <img
                                                src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/admin/images/default-product.png') }}"
                                                alt="{{ $product->name }}" class="avatar-sm">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            @if ($product->active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No products found for this category.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            @if ($products->hasPages())
                                <div class="card-footer border-top">
                                    <nav>
                                        {{ $products->appends(request()->query())->links('vendor.pagination.admin-paginnation') }}
                                    </nav>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
