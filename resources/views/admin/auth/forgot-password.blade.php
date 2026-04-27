@extends('components.auth-layout')
@section('content')
    <div class="my-4">
        <div class="mb-4 text-sm text-muted">
            Quên mật khẩu? Không sao. Hãy cho chúng tôi biết địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn một liên kết để đặt lại mật khẩu.
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group mb-2">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email" placeholder="Nhập địa chỉ email"
                    value="{{ old('email') }}" required autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-0 row">
                <div class="col-12">
                    <div class="d-grid mt-3">
                        <button class="btn btn-primary" type="submit">
                            Gửi liên kết đặt lại mật khẩu <i class="fas fa-paper-plane ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="text-center mb-2">
        <p class="text-muted">Nhớ mật khẩu? <a href="{{ route('admin.login') }}" class="text-primary ms-2">Đăng nhập tại đây</a></p>
    </div>
@endsection