@extends('storefront.layouts.app')

@section('content')
<section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="page-header-st3-content text-center text-md-start">
                    <h2 class="page-header-title">Mật khẩu mới</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="my-account-item-wrap">
                    <h3 class="title">Đặt lại mật khẩu</h3>
                    <div class="my-account-form">
                        <form method="POST" action="{{ route('customer.password.update') }}" autocomplete="off">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="text" name="customer_reset_fake_username" autocomplete="username"
                                class="d-none" tabindex="-1" aria-hidden="true">
                            <input type="password" name="customer_reset_fake_password" autocomplete="new-password"
                                class="d-none" tabindex="-1" aria-hidden="true">

                            <div class="form-group mb-6">
                                <label for="email">Địa chỉ email <sup>*</sup></label>
                                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}"
                                    required autofocus readonly>
                                @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-6">
                                <label for="password">Mật khẩu mới <sup>*</sup></label>
                                <input type="password" id="password" name="password"
                                    autocomplete="new-password" readonly
                                    onfocus="this.removeAttribute('readonly');" required>
                                @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-6">
                                <label for="password-confirm">Xác nhận mật khẩu <sup>*</sup></label>
                                <input type="password" id="password-confirm" name="password_confirmation"
                                    autocomplete="new-password" readonly
                                    onfocus="this.removeAttribute('readonly');" required>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn">Đặt lại mật khẩu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection