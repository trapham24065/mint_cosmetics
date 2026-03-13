<!DOCTYPE html>
<html>
<head>

    <title>Đơn hàng đã được giao</title>

    <style>

        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }

        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }

        h1 { color: #28a745; }

    </style>

</head>
<body>
<div class="container">

    <h1>Đơn hàng của bạn đã được giao!</h1>

    <p>Chào {{ $order->first_name }},</p>

    <p>Tin tuyệt vời!</p> Đơn hàng của bạn #{{ $order->id }} từ {{ setting('site_name', 'Shop') }} đã được giao thành
    công đến địa chỉ của
    bạn:</p>

    <p><strong>{{ $order->address }}</strong></p>

    <p>Chúng tôi hy vọng bạn hài lòng với sản phẩm. Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.</p>

    <p>Cảm ơn bạn đã mua sắm với chúng tôi!</p>

</div>
</body>
</html>
