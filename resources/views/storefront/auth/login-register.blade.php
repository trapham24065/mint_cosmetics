@extends('storefront.layouts.app')
@section('content')
    <main class="main-content">

        <!--== Start Page Header Area Wrapper ==-->
        <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="page-header-st3-content text-center text-md-start">
                            <ol class="breadcrumb justify-content-center justify-content-md-start">
                                <li class="breadcrumb-item"><a class="text-dark" href="{{route('home')}}">Trang chủ</a>
                                </li>
                                <li class="breadcrumb-item active text-dark" aria-current="page">Tài khoản</li>
                            </ol>
                            <h2 class="page-header-title">Tài khoản</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Page Header Area Wrapper ==-->

        <!--== Start Account Area Wrapper ==-->
        <section class="section-space">
            <div class="container">
                <div class="row mb-n8">
                    {{-- LOGIN FORM --}}
                    <div class="col-lg-6 mb-8">
                        <div class="my-account-item-wrap">
                            <h3 class="title">Đăng nhập</h3>
                            <div class="my-account-form">
                                <form action="{{ route('customer.login.submit') }}" method="post" autocomplete="on">
                                    @csrf
                                    <div class="form-group mb-6">
                                        <label for="login_email">Địa chỉ email <sup>*</sup></label>
                                        <input type="email" id="login_email" name="email" value="{{ old('email') }}"
                                               required autocomplete="username">
                                        @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="login_pwsd">Mật khẩu <sup>*</sup></label>
                                        <input type="password" id="login_pwsd" name="password" required
                                               autocomplete="current-password">
                                        @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group d-flex align-items-center mb-14">
                                        <button type="submit" class="btn">Đăng nhập</button>
                                        <div class="form-check ms-3">
                                            <input type="checkbox" class="form-check-input" id="remember_pwsd"
                                                   name="remember">
                                            <label class="form-check-label" for="remember_pwsd">Ghi nhớ tôi</label>
                                        </div>
                                    </div>
                                    <a class="lost-password" href="{{ route('customer.password.request') }}">Bạn quên
                                        mật khẩu?</a></form>
                            </div>
                        </div>
                    </div>

                    {{-- REGISTER FORM --}}
                    <div class="col-lg-6 mb-8">
                        <div class="my-account-item-wrap">
                            <h3 class="title">Đăng ký</h3>
                            <div class="my-account-form">
                                <form action="{{ route('customer.register.submit') }}" method="post" autocomplete="off">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-6">
                                                <label for="register_firstname">Tên <sup>*</sup></label>
                                                <input type="text" id="register_firstname" name="first_name"
                                                       value="{{ old('first_name') }}" required>
                                                @error('first_name')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-6">
                                                <label for="register_lastname">Họ <sup>*</sup></label>
                                                <input type="text" id="register_lastname" name="last_name"
                                                       value="{{ old('last_name') }}" required>
                                                @error('last_name')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="register_phone">Điện thoại <sup>*</sup></label>
                                        <input type="text" id="register_phone" name="phone" value="{{ old('phone') }}"
                                               required>
                                        @error('phone')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="register_email">Địa chỉ email <sup>*</sup></label>
                                        <input type="email" id="register_email" name="email" value="{{ old('email') }}"
                                               required autocomplete="new-email">
                                        @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="register_pwsd">Mật khẩu <sup>*</sup></label>
                                        <input type="password" id="register_pwsd" name="password" required
                                               autocomplete="new-password">
                                        @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="register_pwsd_confirm">Xác nhận mật khẩu <sup>*</sup></label>
                                        <input type="password" id="register_pwsd_confirm" name="password_confirmation"
                                               required autocomplete="new-password">
                                    </div>

                                    <div class="form-group">
                                        <p class="desc mb-4">Dữ liệu cá nhân của bạn sẽ được sử dụng để hỗ trợ trải
                                            nghiệm của bạn
                                            trên toàn bộ trang web này, để quản lý quyền truy cập vào tài khoản của bạn
                                            và cho các mục đích khác
                                            được mô tả trong chính sách bảo mật của chúng tôi.</p>
                                        <button type="submit" class="btn">Đăng ký</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Account Area Wrapper ==-->

    </main>
@endsection
