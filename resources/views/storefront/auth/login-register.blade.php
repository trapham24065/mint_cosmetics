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
                                <li class="breadcrumb-item"><a class="text-dark" href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item active text-dark" aria-current="page">Account</li>
                            </ol>
                            <h2 class="page-header-title">Account</h2>
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
                            <h3 class="title">Login</h3>
                            <div class="my-account-form">
                                <form action="{{ route('customer.login.submit') }}" method="post">
                                    @csrf
                                    <div class="form-group mb-6">
                                        <label for="login_email">Email Address <sup>*</sup></label>
                                        <input type="email" id="login_email" name="email" value="{{ old('email') }}"
                                               required>
                                        @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="login_pwsd">Password <sup>*</sup></label>
                                        <input type="password" id="login_pwsd" name="password" required>
                                        @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group d-flex align-items-center mb-14">
                                        <button type="submit" class="btn">Login</button>
                                        <div class="form-check ms-3">
                                            <input type="checkbox" class="form-check-input" id="remember_pwsd"
                                                   name="remember">
                                            <label class="form-check-label" for="remember_pwsd">Remember Me</label>
                                        </div>
                                    </div>
                                    <a class="lost-password" href="{{ route('customer.password.request') }}">Lost your
                                        Password?</a></form>
                            </div>
                        </div>
                    </div>

                    {{-- REGISTER FORM --}}
                    <div class="col-lg-6 mb-8">
                        <div class="my-account-item-wrap">
                            <h3 class="title">Register</h3>
                            <div class="my-account-form">
                                <form action="{{ route('customer.register.submit') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-6">
                                                <label for="register_firstname">First Name <sup>*</sup></label>
                                                <input type="text" id="register_firstname" name="first_name"
                                                       value="{{ old('first_name') }}" required>
                                                @error('first_name')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-6">
                                                <label for="register_lastname">Last Name <sup>*</sup></label>
                                                <input type="text" id="register_lastname" name="last_name"
                                                       value="{{ old('last_name') }}" required>
                                                @error('last_name')
                                                <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="register_phone">Phone <sup>*</sup></label>
                                        <input type="text" id="register_phone" name="phone" value="{{ old('phone') }}"
                                               required>
                                        @error('phone')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="register_email">Email Address <sup>*</sup></label>
                                        <input type="email" id="register_email" name="email" value="{{ old('email') }}"
                                               required>
                                        @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="register_pwsd">Password <sup>*</sup></label>
                                        <input type="password" id="register_pwsd" name="password" required>
                                        @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-6">
                                        <label for="register_pwsd_confirm">Confirm Password <sup>*</sup></label>
                                        <input type="password" id="register_pwsd_confirm" name="password_confirmation"
                                               required>
                                    </div>

                                    <div class="form-group">
                                        <p class="desc mb-4">Your personal data will be used to support your experience
                                            throughout this website, to manage access to your account, and for other
                                            purposes described in our privacy policy.</p>
                                        <button type="submit" class="btn">Register</button>
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
