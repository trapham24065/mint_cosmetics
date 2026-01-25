@extends('storefront.layouts.app')
@section('content')

    <!--== Start Page Header Area Wrapper ==-->
    <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="page-header-st3-content text-center text-md-start">
                        <ol class="breadcrumb justify-content-center justify-content-md-start">
                            <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-dark" href="{{ route('customer.dashboard') }}">My
                                    account
                                </a></li>
                            <li class="breadcrumb-item active text-dark" aria-current="page">Order
                                #{{ $order->id }}</li>
                        </ol>
                        <h2 class="page-header-title">Order details</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="order-details-area pt-50 pb-100">
        <div class="container">
            {{-- Header Đơn Hàng --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div
                            class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <div>
                                <h4 class="mb-1">Order #{{ $order->id }}</h4>
                                <p class="text-muted mb-0">Set date {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="mt-3 mt-md-0 text-md-end">
                                <span class="d-block text-muted mb-1">Status</span>
                                {{-- Hiển thị Badge trạng thái đẹp hơn --}}
                                <span class="badge rounded-pill bg-{{ $order->status->color() }} fs-6 px-3 py-2">
                                    {{ $order->status->label() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Cột Trái: Danh sách sản phẩm --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h5 class="mb-0">Product list</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4" style="width: 50%">Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end pe-4">Temporarily calculated
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    {{-- Giả sử bạn có quan hệ product trong OrderItem hoặc lưu ảnh --}}
                                                    <div class="flex-shrink-0 me-3">
                                                        @if($item->product && $item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                 alt="{{ $item->product_name }}"
                                                                 class="img-fluid rounded"
                                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                                        @else
                                                            <img
                                                                src="{{ asset('assets/storefront/images/shop/default.webp') }}"
                                                                alt="{{ $item->product_name }}"
                                                                class="img-fluid rounded"
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-dark">
                                                            <a href="{{ $item->product ? route('products.show', $item->product->slug) : '#' }}"
                                                               class="text-inherit text-decoration-none">
                                                                {{ $item->product_name }}
                                                            </a>
                                                        </h6>
                                                        {{-- Hiển thị thuộc tính nếu có (ví dụ màu, size) --}}
                                                        {{-- <small class="text-muted">Màu: Đỏ</small> --}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ number_format($item->price) }} ₫</td>
                                            <td class="text-center">x{{ $item->quantity }}</td>
                                            <td class="text-end pe-4 fw-bold">{{ number_format($item->price * $item->quantity) }}
                                                ₫
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Ghi chú đơn hàng (nếu có) --}}
                    @if($order->notes)
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-body p-4">
                                <h5 class="card-title fs-16">Ghi chú</h5>
                                <p class="mb-0 text-muted">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Cột Phải: Thông tin thanh toán & Địa chỉ --}}
                <div class="col-lg-4">
                    {{-- Tổng tiền --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Total</h5>

                            {{-- Bạn có thể thêm các dòng như Phí ship, Giảm giá ở đây --}}

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Temporarily calculated</span>
                                <span class="fw-bold">{{ number_format($order->total_price) }} ₫</span>
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fs-5 fw-bold text-dark">Total payment</span>
                                <span
                                    class="fs-4 fw-bold text-primary">{{ number_format($order->total_price) }} ₫</span>
                            </div>

                            @if($order->status === \App\Enums\OrderStatus::Pending)
                                <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('payment.show', $order->id) }}"
                                   class="btn btn-primary w-100 py-3 fw-bold shadow-sm">
                                    <i class="fa fa-credit-card me-2"></i> Paynow
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Thông tin khách hàng --}}
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Delivery information</h5>

                            <div class="mb-3">
                                <h6 class="mb-1 fs-14 text-dark">Receiver</h6>
                                <p class="text-muted mb-0">{{ $order->first_name }} {{ $order->last_name }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="mb-1 fs-14 text-dark">Contact</h6>
                                <p class="text-muted mb-0"><i class="fa fa-envelope me-2"></i> {{ $order->email }}</p>
                                <p class="text-muted mb-0"><i class="fa fa-phone me-2"></i> {{ $order->phone }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="mb-1 fs-14 text-dark">Delivery address</h6>
                                <p class="text-muted mb-0">
                                    <i class="fa fa-map-marker me-2"></i> {{ $order->address }}
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-top text-center">
                                <a href="{{ route('customer.dashboard') }}"
                                   class="text-decoration-none fw-bold text-muted">
                                    <i class="fa fa-arrow-left me-1"></i> Return to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom Styles for Order Detail */
        .card {
            transition: all 0.3s ease;
        }
        .text-inherit {
            color: inherit;
        }
        .text-inherit:hover {
            color: var(--bs-primary);
        }
        .table > :not(caption) > * > * {
            padding: 1rem 0.5rem;
        }
        /* Badge colors customization if needed */
        .bg-pending { background-color: #ffc107 !important; color: #000; }
        .bg-processing { background-color: #17a2b8 !important; color: #fff; }
        .bg-shipped { background-color: #0d6efd !important; color: #fff; }
        .bg-completed { background-color: #198754 !important; color: #fff; }
        .bg-cancelled { background-color: #dc3545 !important; color: #fff; }
    </style>
@endpush
