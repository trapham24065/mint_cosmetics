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
                    {{-- Hiệu ứng chữ 404 siêu to --}}
                    <h1 class="display-1 fw-bolder text-primary mb-0"
                        style="font-size: 8rem; text-shadow: 4px 4px 0px rgba(13, 110, 253, 0.1);">
                        404
                    </h1>

                    {{-- Icon khóc hoặc Oops --}}
                    <div class="mb-4 text-warning" style="font-size: 3rem;">
                        <i class="fa fa-frown-o"></i>
                    </div>

                    <h2 class="h1 fw-bold text-dark mb-3">Oops! Không tìm thấy trang.</h2>

                    <p class="text-muted fs-5 mb-5 px-md-5">
                        Trang bạn đang tìm kiếm có thể đã bị xóa, đổi tên, hoặc tạm thời không thể truy cập. Đừng lo
                        lắng, hãy quay lại trang chủ để tiếp tục {{ $isAdmin ? 'làm việc' : 'mua sắm' }} nhé!
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        {{-- Nút quay lại trang trước --}}
                        <button onclick="history.back()"
                            class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center justify-content-center">
                            <i class="fa fa-arrow-left me-2"></i> Quay lại
                        </button>

                        {{-- Nút về trang chủ --}}
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