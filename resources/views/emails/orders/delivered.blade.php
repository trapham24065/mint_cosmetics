<!DOCTYPE html>
<html>
<head>
    <title>Order Delivered</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #28a745; }
    </style>
</head>
<body>
<div class="container">
    <h1>Your Order Has Been Delivered!</h1>
    <p>Hi {{ $order->first_name }},</p>
    <p>Great news! Your order #{{ $order->id }} from Mint Cosmetics has been successfully delivered to your address:</p>
    <p><strong>{{ $order->address }}</strong></p>
    <p>We hope you enjoy your products. If you have any questions, feel free to contact us.</p>
    <p>Thank you for shopping with us!</p>
</div>
</body>
</html>
