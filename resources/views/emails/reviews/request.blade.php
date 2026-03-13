<h1>Đánh giá sản phẩm của bạn: {{ $orderItem->product_name }}</h1>
<p>Chào bạn,</p>
<p>Cảm ơn bạn đã mua hàng. Vui lòng dành chút thời gian để đánh giá sản phẩm bạn đã mua.</p>
<a href="{{ route('reviews.create', ['token' => $orderItem->review_token]) }}">Nhấp vào đây để viết đánh giá</a>
