<!DOCTYPE html>
<html>
<head>
    <title>Order Completed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #17a2b8; }
    </style>
</head>
<body>
<div class="container">
    <h1>Your Order is Complete!</h1>
    <p>Hi {{ $order->first_name }},</p>
    <p>Your order #{{ $order->id }} has now been marked as completed. We hope you had a great shopping experience with
        Mint Cosmetics.</p>
    <p>We would love to hear your feedback! Please consider leaving a review for the products you purchased.</p>
    <p>We look forward to seeing you again soon!</p>
</div>
</body>
</html>
