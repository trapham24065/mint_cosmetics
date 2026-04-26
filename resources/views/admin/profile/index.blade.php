@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row">
        <!-- Profile & Avatar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img
                        src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/admin/images/users/dummy-avatar.jpg') }}"
                        alt="avatar" class="rounded-circle img-fluid"
                        style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="my-3">{{ auth()->user()->name }}</h5>
                    <p class="text-muted mb-1">{{ auth()->user()->email }}</p>
                    <p class="text-muted mb-4">Quản trị viên</p>
                </div>
            </div>
        </div>

        <!-- Form edit -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin hồ sơ</h5>
                </div>
                <div class="card-body">

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-3" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab"
                                data-bs-target="#info" type="button" role="tab">Thông tin cá nhân
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab"
                                data-bs-target="#password" type="button" role="tab">Thay đổi mật khẩu
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabContent">
                        <!-- Tab 1: Thông tin cá nhân -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <form action="{{ route('admin.profile.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Họ và tên đầy đủ</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $admin->name) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Địa chỉ email</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $admin->email) }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ảnh đại diện</label>
                                    <input type="file" class="form-control" id="profile-avatar" name="avatar"
                                        accept="image/*"
                                        data-current-image-url="{{ old('remove_current_avatar') ? '' : ($admin->avatar ? asset('storage/' . $admin->avatar) : '') }}"
                                        data-current-image-name="{{ $admin->avatar ? basename($admin->avatar) : '' }}">
                                    <input type="hidden" name="remove_current_avatar" id="remove-current-avatar" value="{{ old('remove_current_avatar', '0') }}">

                                    <div id="profile-avatar-preview-wrapper" class="mt-3 d-none">
                                        <div class="d-flex align-items-start gap-3">
                                            <img id="profile-avatar-preview" src="#" alt="Avatar preview"
                                                class="img-thumbnail"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                            <div>
                                                <div id="profile-avatar-name" class="small text-muted"></div>
                                                <button type="button" id="remove-profile-avatar-btn"
                                                    class="btn btn-sm btn-outline-danger mt-2">Xóa ảnh hiện tại</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            </form>
                        </div>

                        <!-- Tab 2: change password -->
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <form action="{{ route('admin.profile.password') }}" method="POST" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <input type="text" name="admin_profile_fake_username" autocomplete="username"
                                    class="d-none" tabindex="-1" aria-hidden="true">
                                <input type="password" name="admin_profile_fake_password" autocomplete="new-password"
                                    class="d-none" tabindex="-1" aria-hidden="true">
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" class="form-control" id="current_password"
                                        name="current_password" autocomplete="current-password"
                                        readonly onfocus="this.removeAttribute('readonly');">
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Mật khẩu mới</label>
                                        <input type="password" class="form-control" id="new_password"
                                            name="password" autocomplete="new-password"
                                            readonly onfocus="this.removeAttribute('readonly');">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Xác nhận mật khẩu mới</label>
                                        <input type="password" class="form-control" id="new_password_confirmation"
                                            name="password_confirmation" autocomplete="new-password"
                                            readonly onfocus="this.removeAttribute('readonly');">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-warning">Cập nhật mật khẩu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/single-image-preview.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('profile-avatar');
        initSingleImagePreview({
            inputId: 'profile-avatar',
            previewWrapperId: 'profile-avatar-preview-wrapper',
            previewImageId: 'profile-avatar-preview',
            previewNameId: 'profile-avatar-name',
            removeButtonId: 'remove-profile-avatar-btn',
            removeFlagInputId: 'remove-current-avatar',
            currentImageUrl: input ? input.dataset.currentImageUrl : '',
            currentImageName: input ? input.dataset.currentImageName : '',
            removeCurrentLabel: 'Xóa ảnh hiện tại',
            removeSelectedLabel: 'Xóa ảnh đã chọn'
        });
    });
</script>
@endpush