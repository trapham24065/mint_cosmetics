@extends('components.auth-layout')
@section('content')
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form class="my-4" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group mb-2">
            <label class="form-label" for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" placeholder="Enter email"
                   value="{{ old('email') }}" required autofocus>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" id="password" placeholder="Enter password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye-slash"></i>
                </button>
            </div>
            @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-0 row">
            <div class="col-12">
                <div class="d-grid mt-3">
                    <button class="btn btn-primary" type="submit">Login<i class="fas fa-sign-in-alt ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
    @push('scripts')
        <script>
            console.log('Hello from the script tag!');
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password');
                const togglePasswordBtn = document.getElementById('togglePassword');

                if (passwordInput) {
                    passwordInput.addEventListener('keydown', function(event) {
                        if (event.code === 'Space' || event.key === ' ') {
                            event.preventDefault();
                        }
                    });

                    passwordInput.addEventListener('paste', function(event) {
                        const pastedData = (event.clipboardData || window.clipboardData).getData('text');
                        if (/\s/.test(pastedData)) {
                            event.preventDefault();
                        }
                    });
                }

                if (togglePasswordBtn) {
                    togglePasswordBtn.addEventListener('click', function() {
                        const icon = this.querySelector('i');
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);

                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    });
                }
            });
        </script>
    @endpush
@endsection
