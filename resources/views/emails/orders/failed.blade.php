<h1>Đơn hàng của bạn không thành công</h1>
<p>Xin chào {{ $order->first_name }},</p>
<p>Chúng tôi rất tiếc phải thông báo rằng đơn hàng #{{ $order->id }} của bạn đã được đánh dấu là <strong>không thành công</strong>.</p>
<p>Nguyên nhân có thể do thanh toán không hoàn tất, không liên hệ được với khách hàng, hoặc một sự cố trong quá trình xử lý.</p>
<p>Nếu bạn cho rằng đây là nhầm lẫn hoặc vẫn muốn nhận sản phẩm, vui lòng liên hệ với chúng tôi để được hỗ trợ đặt lại đơn hàng.</p>
<p>Trân trọng,<br>{{ config('app.name') }}</p>
