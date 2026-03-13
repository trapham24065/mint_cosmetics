<!DOCTYPE html>
<html>
<head>
    <title>Đơn hàng đã hoàn tất</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #17a2b8; }
    </style>
</head>
<body>
<div class="container">
    <h1>Đơn hàng của bạn đã hoàn tất!</h1>
    <p>CHÀO {{ $order->first_name }},</p>
    <p>Đơn hàng của bạn #{{ $order->id }} đã được đánh dấu là hoàn thành. Chúng tôi hy vọng bạn đã có trải nghiệm mua
        sắm tuyệt vời tại
        {{ setting('site_name', 'Shop') }}".</p>
    <p>Chúng tôi rất mong nhận được phản hồi của bạn! Vui lòng để lại đánh giá về các sản phẩm bạn đã mua.</p>
    <p>Chúng tôi mong sớm được gặp lại bạn!</p>
</div>
</body>
</html>
