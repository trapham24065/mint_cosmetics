@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-9 col-lg-8">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <div>
                                        <h4 class="fw-medium text-dark d-flex align-items-center gap-2">
                                            #{{ $order->id }}
                                            <span
                                                class="badge bg-{{ $order->status->color() }}">{{ $order->status->name }}</span>
                                        </h4>
                                        <p class="mb-0">Order placed on: {{ $order->created_at->format('F d, Y') }}
                                            at {{ $order->created_at->format('g:i a') }}</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.orders.invoice.download', $order) }}"
                                           class="btn btn-secondary" target="_blank">
                                            <i class="fa fa-print"></i> Download Invoice
                                        </a>
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Back
                                            to List</a>
                                    </div>
                                </div>

                                @php
                                    $currentStep = $order->status->step();
                                    $steps = [
                                        1 => 'Pending',
                                        2 => 'Processing',
                                        3 => 'Shipped',
                                        4 => 'Delivered',
                                        5 => 'Completed'
                                    ];
                                @endphp

                                <div class="mt-4">
                                    <h4 class="fw-medium text-dark">Progress</h4>
                                </div>
                                <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1">
                                    @foreach($steps as $step => $label)
                                        <div class="col">
                                            <div class="progress mt-3" style="height: 10px;">
                                                <div
                                                    class="progress-bar progress-bar-striped @if($currentStep >= $step) progress-bar-animated bg-success @else bg-light @endif"
                                                    role="progressbar"
                                                    style="width: 100%">
                                                </div>
                                            </div>
                                            <p class="mb-0 mt-2">{{ $label }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Products</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0 table-hover table-centered">
                                        <thead class="bg-light-subtle border-bottom">
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                                <td class="text-end">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                    VNĐ
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Update Status</h4></div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status" class="form-label">Order Status</label>
                                <select name="status" id="status" class="form-select">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->value }}" @selected($order->status === $status)>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Status</button>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                <tr>
                                    <td class="px-0">Sub Total :</td>
                                    <td class="text-end text-dark fw-medium px-0">{{ number_format($order->total_price, 0, ',', '.') }}
                                        VNĐ
                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td class="px-0"><p class="fw-medium text-dark mb-0">Total Amount</p></td>
                                    <td class="text-end"><p
                                            class="fw-medium text-dark mb-0">{{ number_format($order->total_price, 0, ',', '.') }}
                                            VNĐ</p></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Customer Details</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                        <p class="mb-1"><strong>Email:</strong> <a href="mailto:{{ $order->email }}"
                                                                   class="link-primary">{{ $order->email }}</a></p>
                        <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                        <hr>
                        <h5 class="mt-3">Shipping Address</h5>
                        <p class="mb-1">{{ $order->address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
