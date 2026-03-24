@extends('storefront.layouts.app')

@section('content')
<section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="page-header-st3-content text-center text-md-start">
                    <ol class="breadcrumb justify-content-center justify-content-md-start">
                        <li class="breadcrumb-item">
                            <a class="text-dark" href="{{ route('home') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="text-dark" href="{{ route('customer.dashboard') }}">Tài khoản của tôi</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="text-dark" href="{{ route('customer.orders.show', $order) }}">Đơn hàng
                                #{{ $order->id }}</a>
                        </li>
                        <li class="breadcrumb-item active text-dark">Tạo yêu cầu trả hàng</li>
                    </ol>
                    <h2 class="page-header-title">Yêu cầu trả hàng</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="order-details-area pt-50 pb-100">
    <div class="container">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h5 class="mb-3">Đơn hàng #{{ $order->id }}</h5>
                <p class="text-muted mb-4">Vui lòng điền thông tin bên dưới để gửi yêu cầu trả hàng.</p>

                <form action="{{ route('customer.returns.store', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="reason" class="form-label fw-semibold">Lý do trả hàng <span class="text-danger">*</span></label>
                        <textarea id="reason" name="reason"
                            class="form-control @error('reason') is-invalid @enderror" rows="3"
                            required>{{ old('reason') }}</textarea>
                        @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="details" class="form-label">Mô tả chi tiết</label>
                        <textarea id="details" name="details"
                            class="form-control @error('details') is-invalid @enderror"
                            rows="4">{{ old('details') }}</textarea>
                        @error('details')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="evidence_images" class="form-label">Ảnh bằng chứng (tùy chọn)</label>
                        <input id="evidence_images" type="file" name="evidence_images[]" class="form-control @error('evidence_images') is-invalid @enderror @error('evidence_images.*') is-invalid @enderror" accept="image/png,image/jpeg,image/webp" multiple>
                        <small class="text-muted">Toi da 5 anh, moi anh khong vuot qua 4MB.</small>
                        @error('evidence_images')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('evidence_images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-secondary">Quay
                            lại đơn hàng</a>
                        <button type="submit" class="btn btn-warning">Gửi yêu cầu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection