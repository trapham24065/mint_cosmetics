@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img
                            src="{{ $brand->logo ? asset('storage/' . $brand->logo) : asset('assets/admin/images/default-brand.png') }}"
                            alt="{{ $brand->name }}" class="img-thumbnail rounded-circle avatar-lg mb-3">
                        <h4 class="card-title">{{ $brand->name }}</h4>
                        <p class="text-muted">ID: {{ $brand->id }}</p>

                        @if ($brand->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                    <div class="card-footer bg-light border-top">
                        <div class="row text-center">
                            <div class="col-6">
                                <a href="{{ route('admin.brands.edit', $brand) }}"
                                   class="btn btn-primary w-100">Edit</a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.brands.index') }}"
                                   class="btn btn-outline-secondary w-100">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Products using '{{ $brand->name }}' ({{ $products->total() }})</h4>
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
                                        <td colspan="4" class="text-center">No products found for this brand.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
