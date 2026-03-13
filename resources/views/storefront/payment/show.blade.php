@extends('storefront.layouts.app')

@section('content')
    <div class="container section-space text-center">
        <h2>Quét mã QR để thanh toán</h2>
        <p>Vui lòng quét mã QR bên dưới để hoàn tất thanh toán đơn hàng. #{{ $order->id }}.</p>

        <div id="qr-code-container" class="my-4 d-flex justify-content-center">
            <canvas id="qr-canvas"></canvas>
        </div>

        {{-- Show countdown time --}}
        <div class="mb-3">
            <strong>Thời gian còn lại: <span id="countdown">15:00</span></strong>
        </div>

        <div id="payment-status">
            Tình trạng hiện tại: <span class="badge bg-warning text-dark">{{ $order->status }}</span>
        </div>
        <p class="text-muted mt-3">Trang này sẽ tự động cập nhật sau khi thanh toán được xác nhận.</p>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const qrCanvas = document.getElementById('qr-canvas');
                const qrString = @json($qrString ?? '', JSON_THROW_ON_ERROR);

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
                const orderId = {{ $order->id }};

                const pollInterval = setInterval(function() {
                    fetch(`/order/${orderId}/status`).then(response => response.json()).then(data => {
                        if (data.status !== 'pending') {
                            clearInterval(pollInterval);
                            clearInterval(countdownInterval);
                            statusElement.innerHTML = `<h3><span class="badge bg-success">Thanh toán đã được xác nhận!</span></h3><p>Bạn sẽ được chuyển hướng trong giây lát...</p>`;
                            setTimeout(() => window.location.href = `/order/${orderId}/thank-you`, 3000);
                            setTimeout(() => {
                                window.location.href = '{{ URL::signedRoute('order.thankyou', ['order' => $order->id]) }}';
                            }, 3000);
                        }
                    }).catch(error => {
                        console.error('Polling error:', error);
                        clearInterval(pollInterval);
                    });
                }, 5000);

                // Countdown timer (15 minutes = 300 seconds)
                const orderCreatedAt = new Date('{{ $order->created_at->toISOString() }}');
                const expirationMinutes = 15;
                const expirationTime = new Date(orderCreatedAt.getTime() + expirationMinutes * 60 * 1000);

                const countdownElement = document.getElementById('countdown');
                const qrContainer = document.getElementById('qr-code-container');

                function stopAllIntervals () {
                    clearInterval(pollInterval);
                    clearInterval(countdownInterval);
                }

                const countdownInterval = setInterval(() => {
                    const now = new Date().getTime();
                    const distance = expirationTime - now;

                    if (distance < 0) {
                        // If time is up
                        stopAllIntervals();
                        countdownElement.textContent = 'Expired';
                        qrContainer.innerHTML = '<h3><span class="badge bg-danger">Mã QR đã hết hạn</span></h3>';
                        statusElement.innerHTML = '<a href="{{ route('cart.index') }}" class="btn btn-primary mt-2">Create a new order</a>';
                        Swal.fire({
                            title: 'Mã QR đã hết hạn',
                            text: 'Bạn sẽ được chuyển hướng đến trang chủ..',
                            icon: 'warning',
                            confirmButtonText: 'OK',
                        }).then(() => {
                            window.location.href = "{{ url('/') }}";
                        });
                        return;
                    }
                    // Calculate and display remaining time
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    countdownElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).
                        padStart(2, '0')}`;
                }, 1000);
            });
        </script>
    @endpush
@endsection
