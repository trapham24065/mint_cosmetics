@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-12">
                <h4 class="mb-4">Kết quả tìm kiếm cho: "<span class="text-primary">{{ $query }}</span>"</h4>
            </div>
        </div>

        @if($products->isEmpty() && $orders->isEmpty() && $customers->isEmpty() && $categories->isEmpty() && $brands->isEmpty())
            <div class="alert alert-warning" role="alert">
                Không tìm thấy kết quả nào phù hợp với từ khóa của bạn.
            </div>
        @else
            <div class="row">
                {{-- PRODUCTS --}}
                @if($products->isNotEmpty())
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    Sản phẩm ({{ $products->count() }})</h5>
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach($products as $product)
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                       class="list-group-item list-group-item-action d-flex align-items-center">
                                        <img
                                            src="{{ $product->image ? asset('storage/' . $product->image) : asset('dashboard_assets/images/no-image.png') }}"
                                            alt="img" class="rounded me-3" width="40" height="40"
                                            style="object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                                            <small class="text-muted">{{ $product->variants->count() }} biến thể</small>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ORDERS --}}
                @if($orders->isNotEmpty())
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Đặt hàng ({{ $orders->count() }})</h5>
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach($orders as $order)
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 fw-bold">#{{ $order->id }}</h6>
                                            <small class="text-muted">{{ $order->created_at->format('d/m/Y') }}</small>
                                        </div>
                                        <p class="mb-1">Khách: {{ $order->first_name }} {{ $order->last_name }}</p>
                                        <span
                                            class="badge bg-{{ $order->status->color() }}">{{ $order->status->label() }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- CUSTOMERS --}}
                @if($customers->isNotEmpty())
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Khách hàng ({{ $customers->count() }})</h5>
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach($customers as $customer)
                                    <a href="{{ route('admin.customers.show', $customer->id) }}"
                                       class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 fw-bold">{{ $customer->full_name }}</h6>
                                        </div>
                                        <small class="text-muted">{{ $customer->email }}</small> <br>
                                        <small class="text-muted">{{ $customer->phone }}</small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- CATEGORIES & BRANDS --}}
                @if($categories->isNotEmpty() || $brands->isNotEmpty())
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Danh mục & Thương hiệus</h5>
                            </div>
                            <div class="card-body">
                                @if($categories->isNotEmpty())
                                    <h6>Loại:</h6>
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach($categories as $category)
                                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                               class="btn btn-sm btn-outline-secondary">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                @if($brands->isNotEmpty())
                                    <h6>Nhãn hiệu:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($brands as $brand)
                                            <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                               class="btn btn-sm btn-outline-info">
                                                {{ $brand->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
