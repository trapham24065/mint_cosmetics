<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    {{-- Trigger Button for Bootstrap Modal --}}
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        {{ __('Delete Account') }}
    </button>

    {{-- Bootstrap Modal --}}
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title text-lg font-medium text-gray-900 dark:text-gray-100"
                            id="confirmUserDeletionModalLabel">
                            {{ __('Are you sure you want to delete your account?') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mt-6">
                            {{-- REPLACED x-input-label --}}
                            <label for="password" class="visually-hidden">
                                {{ __('Password') }}
                            </label>

                            {{-- REPLACED x-text-input --}}
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="form-control mt-1 block w-3/4"
                                placeholder="{{ __('Password') }}"
                            />

                            {{-- REPLACED x-input-error --}}
                            @if($errors->userDeletion->get('password'))
                                <ul class="mt-2 text-sm text-danger space-y-1">
                                    @foreach ((array) $errors->userDeletion->get('password') as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        {{-- REPLACED x-secondary-button --}}
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>

                        {{-- REPLACED x-danger-button --}}
                        <button type="submit" class="btn btn-danger ms-3">
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- Script để tự động mở modal nếu có lỗi validation (giữ UX giống Breeze) --}}
@if($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
            myModal.show();
        });
    </script>
@endif
