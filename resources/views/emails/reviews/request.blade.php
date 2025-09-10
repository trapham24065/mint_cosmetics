<h1>Review Your Product: {{ $orderItem->product_name }}</h1>
<p>Hi there,</p>
<p>Thank you for your recent purchase. Please take a moment to review the product you bought.</p>
<a href="{{ route('reviews.create', ['token' => $orderItem->review_token]) }}">Click here to write a review</a>
