<x-auth-layout>
    <div class="my-4">
        <div class="mb-4 text-sm text-muted">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group mb-2">
                <label class="form-label" for="email">{{ __('Email') }}</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" placeholder="Enter email address"
                       value="{{ old('email') }}" required autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-0 row">
                <div class="col-12">
                    <div class="d-grid mt-3">
                        <button class="btn btn-primary" type="submit">
                            {{ __('Send password reset link') }} <i class="fas fa-paper-plane ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="text-center mb-2">
        <p class="text-muted">Remember your password ? <a href="{{ route('login') }}" class="text-primary ms-2">Login
                here
            </a></p>
    </div>
</x-auth-layout>
