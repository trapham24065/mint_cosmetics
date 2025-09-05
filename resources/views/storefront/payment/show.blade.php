@extends('storefront.layouts.app')

@section('content')
    <div class="container section-space text-center">
        <h2>Scan QR to Pay</h2>
        <p>Please scan the QR code below to complete your payment for order #{{ $order->id }}.</p>

        <div class="my-4 d-flex justify-content-center">
            <canvas id="qr-canvas"></canvas>
        </div>

        {{-- Show countdown time --}}
        <div class="mb-3">
            <strong>Time remaining: <span id="countdown">15:00</span></strong>
        </div>

        <div id="payment-status">
            Current Status: <span class="badge bg-warning text-dark">{{ $order->status }}</span>
        </div>
        <p class="text-muted mt-3">This page will automatically update once payment is confirmed.</p>
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

                // Polling thanh toán
                const pollInterval = setInterval(function() {
                    fetch(`/order/${orderId}/status`).then(response => response.json()).then(data => {
                        if (data.status !== 'pending') {
                            clearInterval(pollInterval);
                            clearInterval(countdownInterval);
                            statusElement.innerHTML = `<h3><span class="badge bg-success">Payment Confirmed!</span></h3><p>You will be redirected shortly...</p>`;
                            setTimeout(() => window.location.href = `/order/${orderId}/thank-you`, 3000);
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

                const countdownInterval = setInterval(() => {
                    const now = new Date().getTime();
                    const distance = expirationTime - now;

                    if (distance < 0) {
                        // If time is up
                        clearInterval(countdownInterval);
                        clearInterval(pollInterval);
                        alert('QR code has expired. You will be redirected to the home page.');
                        countdownElement.textContent = 'Expired';
                        qrContainer.innerHTML = '<h3><span class="badge bg-danger">QR Code Expired</span></h3>';
                        statusElement.innerHTML = '<a href="{{ route('cart.index') }}" class="btn btn-primary mt-2">Create a new order</a>';
                        window.location.href = "{{ url('/') }}";
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
