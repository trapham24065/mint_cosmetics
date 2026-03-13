<!DOCTYPE html>
<html>
<head>

    <title>Xác nhận đơn hàng</title>
</head>
<body>
<h1>Cảm ơn bạn đã đặt hàng, {{ $order->first_name }}!</h1>
<p>Chúng tôi đã nhận được đơn hàng của bạn và đang chuẩn bị giao hàng. Chi tiết đơn hàng như sau:</p>
<p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
<p><strong>Tổng cộng:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
<p>Chúng tôi sẽ thông báo lại cho bạn khi đơn hàng được giao.</p>
<p>Cảm ơn bạn đã mua sắm tại cửa hàng chúng tôi!</p>
</body>
</html>
