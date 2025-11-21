<!DOCTYPE html>
<html>
<head>
    <title>Order Pending</title>
</head>
<body>
<h1>Thank you for your order!</h1>
<p>Hello {{ $order->first_name }},</p>
<p>Your order #{{ $order->id }} has been received and is currently pending processing.</p>

<h3>Order Details:</h3>
<ul>
    @foreach($order->items as $item)
        <li>
            {{ $item->product_name }} x {{ $item->quantity }} - {{ number_format($item->price) }} VND
        </li>
    @endforeach
</ul>

<p><strong>Total: {{ number_format($order->total_price) }} VND</strong></p>

<p>We will notify you once your order is confirmed.</p>

<p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>
