@extends('storefront.layouts.app')
@section('content')
    <div class="container section-space text-center">
        <h2 class="text-success">Thank You For Your Order!</h2>
        <p>Your order has been confirmed and a confirmation email has been sent to you.</p>
        <div class="card my-4 mx-auto" style="max-width: 600px;">
            <div class="card-header">
                <h4>Order Summary - #{{ $order->id }}</h4>
            </div>
            <div class="card-body text-start">
                <p><strong>Customer:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                <p><strong>Total Amount:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
                <p><strong>Status:</strong> <span class="badge bg-success">{{ $order->status->name }}</span></p>
                <hr>
                <h6>Items:</h6>
                <ul class="list-group list-group-flush">
                    @foreach($order->items as $item)
                        <li class="list-group-item">{{ $item->product_name }} (x{{ $item->quantity }})</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <a href="{{ route('shop') }}" class="btn btn-primary">Continue Shopping</a>
    </div>
@endsection
