<x-auth-layout>
    <form class="my-4" method="POST" action="{{ route('password.store') }}" autocomplete="off">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="text" name="admin_reset_fake_username" autocomplete="username" class="d-none" tabindex="-1" aria-hidden="true">
        <input type="password" name="admin_reset_fake_password" autocomplete="new-password" class="d-none" tabindex="-1" aria-hidden="true">

        <div class="form-group mb-2">
            <label class="form-label" for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                id="email" name="email" value="{{ old('email', $request->email) }}"
                required autofocus>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-2">
            <label class="form-label" for="password">New password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" id="password" placeholder="Input new password"
                readonly onfocus="this.removeAttribute('readonly');"
                required autocomplete="new-password">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm password</label>
            <input type="password" class="form-control"
                name="password_confirmation" id="password_confirmation" placeholder="Re-enter new password"
                readonly onfocus="this.removeAttribute('readonly');"
                required autocomplete="new-password">
        </div>

        <div class="form-group mb-0 row">
            <div class="col-12">
                <div class="d-grid mt-3">
                    <button class="btn btn-primary" type="submit">
                        {{ __('Reset password') }} <i class="fas fa-key ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-auth-layout>