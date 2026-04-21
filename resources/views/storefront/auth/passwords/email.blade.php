@extends('storefront.layouts.app')

@section('content')
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
                        <h2 class="page-header-title">Đặt lại mật khẩu</h2>
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
                        <h3 class="title">Quên mật khẩu</h3>
                        <div class="my-account-form">
                            <form method="POST" action="{{ route('customer.password.email') }}">
                                @csrf
                                <div class="form-group mb-6">
                                    <label for="email">Địa chỉ email <sup>*</sup></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                           autofocus>
                                    @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn">Gửi liên kết đặt lại mật khẩu</button>
                                    <a href="{{ route('customer.login') }}" class="btn-link ms-3">Quay lại trang đăng
                                        nhập</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
