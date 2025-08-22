<x-auth-layout>
    <div class="my-4">
        <div class="mb-4 text-sm text-muted">
            {{ __('This is an application-wide area. Please confirm your password before continuing.') }}
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="password">{{ __('Password') }}</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" id="password" placeholder="Enter your password"
                       required autocomplete="current-password">

                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-0 row">
                <div class="col-12">
                    <div class="d-grid mt-3">
                        <button class="btn btn-primary" type="submit">
                            {{ __('Confirm') }} <i class="fas fa-check ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-auth-layout>
