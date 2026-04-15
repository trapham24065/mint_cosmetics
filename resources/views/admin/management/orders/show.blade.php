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
                                            class="badge bg-{{ $order->status->color() }}">{{ $order->status->label() }}</span>
                                    </h4>
                                    <p class="mb-0">Đơn hàng được đặt vào
                                        ngày: {{ $order->created_at->format('d/m/Y') }}
                                        lúc {{ $order->created_at->locale('vi')->isoFormat('HH:mm') }}</p>
                                </div>
                                <div>
                                    <a href="{{ route('admin.orders.invoice.download', $order) }}"
                                        class="btn btn-secondary" target="_blank">
                                        <i class="fa fa-print"></i> Tải xuống hóa đơn
                                    </a>
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Trở
                                        lại danh sách</a>
                                </div>
                            </div>

                            @php
                            $currentStep = $order->status->step();
                            $steps = [
                            1 => 'Chưa giải quyết',
                            2 => 'Xử lý',
                            3 => 'Đã vận chuyển',
                            4 => 'Đã giao hàng',
                            5 => 'Hoàn thành'
                            ];
                            @endphp

                            <div class="mt-4">
                                <h4 class="fw-medium text-dark">Tiến triển</h4>
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
                            <h4 class="card-title">Các sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 table-hover table-centered">
                                    <thead class="bg-light-subtle border-bottom">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Số lượng</th>
                                            <th>Giá</th>
                                            <th class="text-end">Tổng cộng</th>
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
                                    <tfoot class="bg-light-subtle border-top">
                                        @php
                                        $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
                                        @endphp
                                        <tr>
                                            <th colspan="3" class="text-end">Tổng phụ</th>
                                            <th class="text-end">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Phí vận chuyển GHN</th>
                                            <th class="text-end">{{ number_format((float) $order->shipping_fee, 0, ',', '.') }}
                                                VNĐ
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Tổng cộng</th>
                                            <th class="text-end">{{ number_format($order->total_price, 0, ',', '.') }}
                                                VNĐ
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cập nhật trạng thái</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái đơn hàng</label>
                            <select name="status" id="status" class="form-select">
                                @foreach($statuses as $status)
                                <option value="{{ $status->value }}" @selected($order->status === $status)>
                                    {{ $status->label() }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cập nhật trạng thái</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tóm tắt đơn hàng</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                @php
                                $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
                                @endphp
                                <tr>
                                    <td class="px-0">Tổng phụ :</td>
                                    <td class="text-end text-dark fw-medium px-0">{{ number_format($subtotal, 0, ',', '.') }}
                                        VNĐ
                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td class="px-0">
                                        <p class="fw-medium text-dark mb-0">Phí GHN</p>
                                    </td>
                                    <td class="text-end text-dark fw-medium px-0">{{ number_format((float) $order->shipping_fee, 0, ',', '.') }}
                                        VNĐ
                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td class="px-0">
                                        <p class="fw-medium text-dark mb-0">Tổng số tiền</p>
                                    </td>
                                    <td class="text-end">
                                        <p
                                            class="fw-medium text-dark mb-0">{{ number_format($order->total_price, 0, ',', '.') }}
                                            VNĐ</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông tin khách hàng</h4>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Tên:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                    <p class="mb-1"><strong>Email:</strong> <a href="mailto:{{ $order->email }}"
                            class="link-primary">{{ $order->email }}</a></p>
                    <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->phone }}</p>
                    <hr>
                    <h5 class="mt-3">Địa chỉ giao hàng</h5>
                    <p class="mb-1">{{ $order->address }}</p>
                    <p class="mb-1"><strong>Đơn vị vận
                            chuyển:</strong> {{ strtoupper($order->shipping_provider ?? 'N/A') }}</p>
                    <p class="mb-1"><strong>Mã đơn GHN:</strong> {{ $order->ghn_order_code ?? 'Chưa tạo' }}</p>
                    <p class="mb-1"><strong>Phí
                            GHN:</strong> {{ number_format((float) $order->shipping_fee, 0, ',', '.') }} VNĐ</p>
                    <p class="mb-1"><strong>GHN Mã Quận:</strong> {{ $order->shipping_district_id ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>GHN Mã Huyện:</strong> {{ $order->shipping_ward_code ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection