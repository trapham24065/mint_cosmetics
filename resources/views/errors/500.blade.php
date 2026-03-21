@php
// Detect if this is an admin request
$isAdmin = request()->is('admin/*') || request()->is('admin');
$layout = $isAdmin ? 'admin.layouts.app' : 'storefront.layouts.app';
$homeRoute = $isAdmin ? 'admin.dashboard' : 'home';
@endphp

@extends($layout)

@section('content')
<section class="error-page-area py-5"
    style="background-color: #f8f9fa; min-height: 70vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="error-content">
                    {{-- Hiệu ứng chữ 500 --}}
                    <h1 class="display-1 fw-bolder text-danger mb-0"
                        style="font-size: 8rem; text-shadow: 4px 4px 0px rgba(220, 53, 69, 0.1);">
                        500
                    </h1>

                    {{-- Icon cờ lê / sửa chữa --}}
                    <div class="mb-4 text-danger" style="font-size: 3rem;">
                        <i class="fa fa-cogs"></i>
                    </div>

                    <h2 class="h1 fw-bold text-dark mb-3">Hệ thống đang gặp sự cố!</h2>

                    <p class="text-muted fs-5 mb-5 px-md-5">
                        Rất xin lỗi bạn, máy chủ của chúng tôi đang gặp một chút sự cố kỹ thuật. Đội ngũ IT đã nhận
                        được thông báo và đang khắc phục ngay lập tức. Vui lòng quay lại sau ít phút nhé!
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <button onclick="location.reload()"
                            class="btn btn-outline-danger px-4 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center justify-content-center">
                            <i class="fa fa-refresh me-2"></i> Thử lại
                        </button>

                        <a href="{{ route($homeRoute) }}"
                            class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center justify-content-center">
                            <i class="fa fa-home me-2"></i> {{ $isAdmin ? 'Về Dashboard' : 'Về Trang Chủ' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .error-page-area {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-3px);
    }
</style>
@endpush