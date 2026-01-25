<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            {{-- REPLACED x-input-label --}}
            <label for="update_password_current_password"
                   class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                {{ __('Current Password') }}
            </label>
            {{-- REPLACED x-text-input --}}
            <input id="update_password_current_password" name="current_password" type="password"
                   class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                   autocomplete="current-password" />
            {{-- REPLACED x-input-error --}}
            @if($errors->updatePassword->get('current_password'))
                <ul class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">
                    @foreach ((array) $errors->updatePassword->get('current_password') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div>
            {{-- REPLACED x-input-label --}}
            <label for="update_password_password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                {{ __('New Password') }}
            </label>
            {{-- REPLACED x-text-input --}}
            <input id="update_password_password" name="password" type="password"
                   class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                   autocomplete="new-password" />
            {{-- REPLACED x-input-error --}}
            @if($errors->updatePassword->get('password'))
                <ul class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">
                    @foreach ((array) $errors->updatePassword->get('password') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div>
            {{-- REPLACED x-input-label --}}
            <label for="update_password_password_confirmation"
                   class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                {{ __('Confirm Password') }}
            </label>
            {{-- REPLACED x-text-input --}}
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                   autocomplete="new-password" />
            {{-- REPLACED x-input-error --}}
            @if($errors->updatePassword->get('password_confirmation'))
                <ul class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">
                    @foreach ((array) $errors->updatePassword->get('password_confirmation') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="flex items-center gap-4">
            {{-- REPLACED x-primary-button --}}
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
