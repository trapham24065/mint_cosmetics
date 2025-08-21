<x-auth-layout>
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
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" id="password" placeholder="Enter password" required>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group row mt-3">
            <div class="col-sm-6">
                <div class="form-check form-switch form-switch-success">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">Remember me</label>
                </div>
            </div>
            <div class="col-sm-6 text-end">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-muted font-13"><i class="dripicons-lock"></i>
                        Forgot Password?</a>
                @endif
            </div>
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
</x-auth-layout>
