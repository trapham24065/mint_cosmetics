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
                <form method="GET" action="{{ route('admin.products.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Search by product name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="category_id" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->id }}" @selected(request('category_id') === $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="1" @selected(request('status') === '1')>Active</option>
                                <option value="0" @selected(request('status') === '0')>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <img
                                        src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/admin/images/default-product.png') }}"
                                        alt="{{ $product->name }}" class="avatar-sm">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>
                                    @if($product->variants->isNotEmpty())
                                        @php
                                            $minPrice = $product->variants->min('discount_price');
                                            $maxPrice = $product->variants->max('discount_price');
                                        @endphp

                                        @if ($minPrice === $maxPrice)
                                            {{-- If min and max price are the same, show only one price --}}
                                            {{ number_format($minPrice, 0, ',', '.') }} VNĐ
                                        @else
                                            {{-- Otherwise, show the price range --}}
                                            {{ number_format($minPrice, 0, ',', '.') }}
                                            - {{ number_format($maxPrice, 0, ',', '.') }} VNĐ
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $product->variants->sum('stock') }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($product->active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.show', $product) }}"
                                       class="btn btn-sm btn-soft-info">
                                        <iconify-icon icon="solar:eye-broken"
                                                      class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn btn-sm btn-soft-primary">
                                        <iconify-icon icon="solar:pen-2-broken"
                                                      class="align-middle fs-18"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('[MSG-P8] Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-soft-danger">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                          class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center"> No products found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{-- Append query strings to pagination links --}}
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
@endsection
