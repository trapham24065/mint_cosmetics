@extends('storefront.layouts.app')

@section('content')
<div class="container section-space text-center" id="payment-page"
    data-qr-string="{{ e($qrString ?? '') }}"
    data-order-id="{{ $order->id }}"
    data-thank-you-url="{{ route('order.thankyou', ['order' => $order->id, 'token' => $order->payment_token]) }}"
    data-cart-index-url="{{ route('cart.index') }}"
    data-home-url="{{ url('/') }}"
    data-order-created-at="{{ $order->created_at->toISOString() }}">
    <h2>Quét mã QR để thanh toán</h2>
    <p>Vui lòng quét mã QR bên dưới để hoàn tất thanh toán đơn hàng. #{{ $order->id }}.</p>

    <div id="qr-code-container" class="my-4 d-flex justify-content-center">
        <canvas id="qr-canvas"></canvas>
    </div>

    <div class="mb-3">
        <strong>Thời gian còn lại: <span id="countdown">15:00</span></strong>
    </div>

    <div id="payment-status">
        Tình trạng hiện tại: <span
            class="badge bg-{{ $order->status->color() }}">{{ $order->status->label() }}</span>
    </div>
    <p class="text-muted mt-3">Trang này sẽ tự động cập nhật sau khi thanh toán được xác nhận.</p>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentPage = document.getElementById('payment-page');
        const qrCanvas = document.getElementById('qr-canvas');
        const qrString = paymentPage?.dataset.qrString || '';
        const orderId = paymentPage?.dataset.orderId || '';
        const thankYouUrl = paymentPage?.dataset.thankYouUrl || '';
        const cartIndexUrl = paymentPage?.dataset.cartIndexUrl || '';
        const homeUrl = paymentPage?.dataset.homeUrl || '';
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
        });

        if (qrCanvas && qrString) {
            new QRious({
                element: qrCanvas,
                value: qrString,
                size: 300,
                padding: 15,
                background: 'white',
                foreground: 'black',
            });
        }

        const statusElement = document.getElementById('payment-status');

        const pollInterval = setInterval(function() {
            fetch(`/order/${orderId}/status`).then(response => response.json()).then(data => {
                if (data.status !== 'pending') {
                    clearInterval(pollInterval);
                    clearInterval(countdownInterval);
                    statusElement.innerHTML = `<h3><span class="badge bg-success">Thanh toán đã được xác nhận!</span></h3><p>Bạn sẽ được chuyển hướng trong giây lát...</p>`;
                    setTimeout(() => {
                        window.location.href = thankYouUrl;
                    }, 3000);
                }
            }).catch(error => {
                console.error('Polling error:', error);
                clearInterval(pollInterval);
            });
        }, 5000);

        const orderCreatedAt = new Date(paymentPage?.dataset.orderCreatedAt || new Date().toISOString());
        const expirationMinutes = 15;
        const expirationTime = new Date(orderCreatedAt.getTime() + expirationMinutes * 60 * 1000);

        const countdownElement = document.getElementById('countdown');
        const qrContainer = document.getElementById('qr-code-container');

        function stopAllIntervals() {
            clearInterval(pollInterval);
            clearInterval(countdownInterval);
        }

        const countdownInterval = setInterval(() => {
            const now = new Date().getTime();
            const distance = expirationTime - now;

            if (distance < 0) {
                stopAllIntervals();
                countdownElement.textContent = 'Hết hạn';
                qrContainer.innerHTML = '<h3><span class="badge bg-danger">Mã QR đã hết hạn</span></h3>';
                statusElement.innerHTML = `<a href="${cartIndexUrl}" class="btn btn-primary mt-2">Create a new order</a>`;
                Toast.fire({
                    icon: 'warning',
                    title: 'Mã QR đã hết hạn. Sẽ chuyển hướng về trang chủ.',
                });
                setTimeout(() => {
                    window.location.href = homeUrl;
                }, 2500);
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            countdownElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).
                    padStart(2, '0')}`;
        }, 1000);
    });
</script>
@endpush