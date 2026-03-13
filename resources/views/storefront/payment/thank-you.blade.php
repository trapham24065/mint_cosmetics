@extends('storefront.layouts.app')
@section('content')
    <div class="container section-space text-center">
        <h2 class="text-success">Cảm ơn bạn đã đặt hàng!</h2>
        <p>Đơn đặt hàng của bạn đã được xác nhận và một email xác nhận đã được gửi đến bạn.</p>
        <div class="card my-4 mx-auto" style="max-width: 600px;">
            <div class="card-header">
                <h4>Tóm tắt đơn hàng - #{{ $order->id }}</h4>
            </div>
            <div class="card-body text-start">
                <p><strong>Khách hàng:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                <p><strong>Tổng số tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
                <p><strong>Trạng thái:</strong> <span class="badge bg-success">{{ $order->status->name }}</span></p>
                <hr>
                <h6>Mặt hàng:</h6>
                <ul class="list-group list-group-flush">
                    @foreach($order->items as $item)
                        <li class="list-group-item">{{ $item->product_name }} (x{{ $item->quantity }})</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <a href="{{ route('shop') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
    </div>
@endsection
