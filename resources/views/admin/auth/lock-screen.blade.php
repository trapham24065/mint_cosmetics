<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Lock Screen | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Lock Screen" name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/admin/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('assets/admin/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Config js -->
    <script src="{{ asset('assets/admin/js/config.js') }}"></script>
</head>

<body class="authentication-bg">

<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-lg-5">
                <div class="card">
                    <!-- Logo -->
                    <div class="card-header pt-4 pb-4 text-center bg-primary">
                        <a href="{{ route('admin.dashboard') }}">
                            <span><img src="{{ asset('assets/admin/images/logo.png') }}" alt="" height="18"></span>
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto">
                            {{-- Hiển thị Avatar Admin --}}
                            <img
                                src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/admin/images/users/avatar-1.jpg') }}"
                                height="88" alt="user-image" class="rounded-circle shadow-sm img-thumbnail"
                                style="width: 88px; height: 88px; object-fit: cover;">

                            <h4 class="text-dark-50 text-center mt-3 fw-bold">Hi ! {{ auth()->user()->name }}</h4>
                            <p class="text-muted mb-4">Enter your password to access the admin.</p>
                        </div>

                        <form action="{{ route('admin.unlock') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input class="form-control @error('password') is-invalid @enderror"
                                       type="password" required id="password" name="password"
                                       placeholder="Enter your password">

                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="mb-0 text-center">
                                <button class="btn btn-primary w-100" type="submit">Unlock</button>
                            </div>
                        </form>
                    </div> <!-- end card-body-->
                </div>
                <!-- end card-->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        {{-- Nút đăng xuất (Logout) nếu không phải tài khoản này --}}
                        <p class="text-muted">Not you? return
                            <a href="{{ route('admin.logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form-lock').submit();"
                               class="text-muted ms-1"><b>Sign In</b></a>
                        </p>
                        <form id="logout-form-lock" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->

<footer class="footer footer-alt">
    <script>document.write(new Date().getFullYear());</script>
    © {{ config('app.name') }}
</footer>

<!-- Vendor js -->
<script src="{{ asset('assets/admin/js/vendor.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/app.min.js') }}"></script>

</body>
</html>
