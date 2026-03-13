<!DOCTYPE html>
<html>
<head>
    <title>Đơn hàng đang chờ xử lý</title>
</head>
<body>
<h1>Cảm ơn bạn đã đặt hàng!</h1>
<p>Xin chào {{ $order->first_name }},</p>
<p>Đơn hàng của bạn #{{ $order->id }} đã nhận được và hiện đang chờ xử lý.</p>

<h3>Chi tiết đơn hàng:</h3>
<ul>
    @foreach($order->items as $item)
        <li>
            {{ $item->product_name }} x {{ $item->quantity }} - {{ number_format($item->price) }} VND
        </li>
    @endforeach
</ul>

<p><strong>Tổng cộng: {{ number_format($order->total_price) }} VND</strong></p>

<p>Chúng tôi sẽ thông báo cho bạn ngay khi đơn hàng được xác nhận.</p>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
</body>
</html>
