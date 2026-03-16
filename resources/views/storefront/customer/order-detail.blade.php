@extends('storefront.layouts.app')

@section('content')

    <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="page-header-st3-content text-center text-md-start">
                        <ol class="breadcrumb justify-content-center justify-content-md-start">
                            <li class="breadcrumb-item">
                                <a class="text-dark" href="{{ route('home') }}">Trang chủ</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-dark" href="{{ route('customer.dashboard') }}">
                                    Tài khoản của tôi
                                </a>
                            </li>
                            <li class="breadcrumb-item active text-dark">
                                Đặt hàng #{{ $order->id }}
                            </li>
                        </ol>

                        <h2 class="page-header-title">Chi tiết đơn hàng</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="order-details-area pt-50 pb-100">
        <div class="container">

            {{-- ORDER HEADER --}}
            <div class="row mb-4">
                <div class="col-12">

                    <div class="card border-0 shadow-sm rounded-3">
                        <div
                            class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">

                            <div>
                                <h4 class="mb-1">Đặt hàng #{{ $order->id }}</h4>
                                <p class="text-muted mb-0">
                                    Đặt ngày {{ $order->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="mt-3 mt-md-0 text-md-end">
                                <span class="d-block text-muted mb-1">Trạng thái</span>

                                <span class="badge rounded-pill bg-{{ $order->status->color() }} fs-6 px-3 py-2">
                                    {{ $order->status->label() }}
                                </span>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row">

                {{-- LEFT SIDE --}}
                <div class="col-lg-8">

                    <div class="card border-0 shadow-sm rounded-3 mb-4">

                        <div class="card-header border-bottom-0 pt-4 px-4 bg-white">
                            <h5 class="mb-0">Danh sách sản phẩm</h5>
                        </div>

                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-hover align-middle mb-0">

                                    <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4" style="width:50%">Sản phẩm</th>
                                        <th class="text-center">Giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-end pe-4">Thành tiền</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    @foreach($order->items as $item)

                                        {{-- PRODUCT ROW --}}
                                        <tr>

                                            <td class="ps-4 py-3">

                                                <div class="d-flex">

                                                    <div class="flex-shrink-0 me-3">

                                                        @if($item->product && $item->product->image)

                                                            <img
                                                                src="{{ asset('storage/'.$item->product->image) }}"
                                                                class="img-fluid rounded border"
                                                                style="width:70px;height:70px;object-fit:cover;"
                                                                alt="{{ $item->product_name }}"
                                                            >

                                                        @else

                                                            <img
                                                                src="{{ asset('assets/storefront/images/shop/default.webp') }}"
                                                                class="img-fluid rounded border"
                                                                style="width:70px;height:70px;object-fit:cover;"
                                                            >

                                                        @endif

                                                    </div>

                                                    <div class="flex-grow-1">

                                                        <h6 class="mb-1 text-dark">

                                                            <a
                                                                href="{{ $item->product ? route('products.show',$item->product->slug) : '#' }}"
                                                                class="text-inherit text-decoration-none"
                                                            >

                                                                {{ str_replace(' ()','',$item->product_name) }}

                                                            </a>

                                                        </h6>

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="text-center align-middle">
                                                {{ number_format($item->price) }}₫
                                            </td>

                                            <td class="text-center align-middle">
                                                x{{ $item->quantity }}
                                            </td>

                                            <td class="text-end pe-4 fw-bold text-primary align-middle">
                                                {{ number_format($item->price * $item->quantity) }} ₫
                                            </td>

                                        </tr>

                                        {{-- REVIEW ROW --}}
                                        @if($order->status === \App\Enums\OrderStatus::Completed)

                                            <tr class="review-row">

                                                <td colspan="4" class="ps-4 pe-4 pb-4">

                                                    @if($item->review)

                                                        <div class="review-box">

                                                            <div class="review-header">

                                                                <div class="review-stars">
                                                                    {{-- FIX: Ép kiểu int và dùng style inline để tránh lỗi css --}}
                                                                    @php $rating = (int) ($item->review->rating ?? 0); @endphp
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <i class="fa fa-star{{ $i <= $rating ? '' : '-o' }}"
                                                                           style="color: {{ $i <= $rating ? '#ffc107' : '#ddd' }}; font-size: 16px;"></i>
                                                                    @endfor
                                                                </div>

                                                                <span class="review-date">
                                                                    {{ $item->review->created_at->format('d/m/Y') }}
                                                                </span>

                                                            </div>

                                                            <p class="review-text">
                                                                {{ $item->review->review }}
                                                            </p>

                                                            @if(!empty($item->review->media) && is_array($item->review->media))

                                                                <div class="review-images">

                                                                    @foreach($item->review->media as $media)

                                                                        <img src="{{ asset('storage/'.$media) }}">

                                                                    @endforeach

                                                                </div>

                                                            @endif

                                                        </div>

                                                    @else

                                                        @if(isset($item->review_token))

                                                            <div class="review-action">

                                                                <a
                                                                    href="{{ route('reviews.create',['token'=>$item->review_token]) }}"
                                                                    class="btn btn-warning btn-sm"
                                                                >

                                                                    <i class="fa fa-star me-1"></i>
                                                                    Đánh giá sản phẩm

                                                                </a>

                                                            </div>

                                                        @endif

                                                    @endif

                                                </td>

                                            </tr>

                                        @endif

                                    @endforeach

                                    </tbody>

                                </table>

                            </div>
                        </div>
                    </div>

                    @if($order->notes)

                        <div class="card border-0 shadow-sm rounded-3 mb-4">

                            <div class="card-body p-4">

                                <h5 class="card-title fs-16">
                                    <i class="fa fa-file-text-o me-2 text-muted"></i>
                                    Ghi chú của bạn
                                </h5>

                                <p class="mb-0 text-dark p-3 bg-light rounded">
                                    {{ $order->notes }}
                                </p>

                            </div>

                        </div>

                    @endif

                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm rounded-3 mb-4">

                        <div class="card-body p-4">

                            <h5 class="card-title mb-4">Tổng thanh toán</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tạm tính</span>
                                <span class="fw-bold">{{ number_format($order->total_price) }} ₫</span>
                            </div>

                            <hr class="my-3 border-dashed">

                            <div class="d-flex justify-content-between mb-4">

                                <span class="fs-5 fw-bold">Thành tiền</span>

                                <span class="fs-4 fw-bold text-primary">
                                    {{ number_format($order->total_price) }} ₫
                                </span>

                            </div>

                            @if($order->status === \App\Enums\OrderStatus::Pending)

                                <a
                                    href="{{ \Illuminate\Support\Facades\URL::signedRoute('payment.show',$order->id) }}"
                                    class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-pill"
                                >

                                    <i class="fa fa-qrcode me-2"></i>
                                    Thanh toán ngay

                                </a>

                            @endif

                        </div>

                    </div>

                    <div class="card border-0 shadow-sm rounded-3">

                        <div class="card-body p-4">

                            <h5 class="card-title mb-4">Thông tin giao hàng</h5>

                            <div class="mb-3">
                                <h6 class="mb-1">Người nhận hàng</h6>
                                <p class="text-muted mb-0">
                                    {{ $order->first_name }} {{ $order->last_name }}
                                </p>
                            </div>

                            <div class="mb-3">

                                <h6 class="mb-1">Liên hệ</h6>

                                <p class="text-muted mb-0">
                                    <i class="fa fa-envelope-o me-2"></i>
                                    {{ $order->email }}
                                </p>

                                <p class="text-muted mb-0">
                                    <i class="fa fa-phone me-2"></i>
                                    {{ $order->phone }}
                                </p>

                            </div>

                            <div class="mb-3">

                                <h6 class="mb-1">Địa chỉ giao hàng</h6>

                                <p class="text-muted mb-0">
                                    <i class="fa fa-map-marker me-2"></i>
                                    {{ $order->address }}
                                </p>

                            </div>

                            <div class="mt-4 pt-3 border-top text-center">
                                <a href="{{ route('customer.dashboard') }}" class="btn">
                                    <i class="fa fa-arrow-left me-1"></i> Trở lại
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

        .review-row td {
            background: #fafafa;
            border-top: 0;
        }

        .review-box {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 15px;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        /* Đã cấu hình màu trực tiếp trên thẻ i nên phần này có thể bỏ bớt nhưng cứ để dự phòng */
        .review-stars {
            font-size: 14px;
        }

        .review-date {
            font-size: 12px;
            color: #999;
        }

        .review-text {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .review-images {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .review-images img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .review-action {
            padding: 10px 0;
        }

        .card {
            transition: all .3s ease;
        }

        .border-dashed {
            border-top: 1px dashed #dee2e6;
        }

        .table > :not(caption) > * > * {
            padding: 1.25rem .5rem;
        }

        .bg-pending {background: #ffc107 !important;color: #000;}
        .bg-processing {background: #17a2b8 !important;color: #fff;}
        .bg-shipped {background: #0d6efd !important;color: #fff;}
        .bg-completed {background: #198754 !important;color: #fff;}
        .bg-cancelled {background: #dc3545 !important;color: #fff;}

    </style>

@endpush
