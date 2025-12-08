@extends('storefront.layouts.app')

@section('content')
    <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="page-header-st3-content text-center text-md-start">
                        <h2 class="page-header-title">Reset Password</h2>
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
                        <h3 class="title">Forgot Password</h3>
                        <div class="my-account-form">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('customer.password.email') }}">
                                @csrf
                                <div class="form-group mb-6">
                                    <label for="email">Email Address <sup>*</sup></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                           autofocus>
                                    @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn">Send Password Reset Link</button>
                                    <a href="{{ route('customer.login') }}" class="btn-link ms-3">Back to Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
