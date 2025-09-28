<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
<h1>Thank you for your order, {{ $order->first_name }}!</h1>
<p>We have received your order and are getting it ready for you. Here are the details:</p>
<p><strong>Order ID:</strong> #{{ $order->id }}</p>
<p><strong>Total Amount:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
<p>We will notify you again once your order has been shipped.</p>
<p>Thank you for shopping with us!</p>
</body>
</html>
