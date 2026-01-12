@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <!-- Profile & Avatar -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img
                            src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/admin/images/users/avatar-1.jpg') }}"
                            alt="avatar" class="rounded-circle img-fluid"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="my-3">{{ Auth::user()->name }}</h5>
                        <p class="text-muted mb-1">{{ Auth::user()->email }}</p>
                        <p class="text-muted mb-4">Administrator</p>
                    </div>
                </div>
            </div>

            <!-- Form edit -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Profile Details</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-3" id="profileTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab"
                                        data-bs-target="#info" type="button" role="tab">Personal Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-tab" data-bs-toggle="tab"
                                        data-bs-target="#password" type="button" role="tab">Change Password
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
                                            <label class="form-label">Full Name</label>
                                            <input type="text" class="form-control" name="name"
                                                   value="{{ old('name', $admin->name) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email Address</label>
                                            <input type="email" class="form-control" name="email"
                                                   value="{{ old('email', $admin->email) }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Avatar</label>
                                        <input type="file" class="form-control" name="avatar">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>

                            <!-- Tab 2: change password -->
                            <div class="tab-pane fade" id="password" role="tabpanel">
                                <form action="{{ route('admin.profile.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" class="form-control" name="current_password">
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">New Password</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning">Update Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
