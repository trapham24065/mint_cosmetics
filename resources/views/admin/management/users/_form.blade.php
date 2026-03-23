@csrf
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 rounded-10 mb-4">
            <div class="card-body p-4">
                <h3 class="fs-18 mb-4 border-bottom pb-3">Thông tin cơ bản</h3>

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Tên đầy đủ<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name', $user->name ?? '') }}" placeholder="Nhập tên đầy đủ" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email<span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                        value="{{ old('email', $user->email ?? '') }}" placeholder="admin@example.com" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">
                        Mật khẩu
                        @if(isset($user))
                        <small class="text-muted">(Để trống nếu không muốn thay đổi)</small>
                        @else
                        <span class="text-danger">*</span>
                        @endif
                    </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="••••••••" {{ !isset($user) ? 'required' : '' }}>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password Confirmation --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">
                        Xác nhận mật khẩu
                        @if(!isset($user))
                        <span class="text-danger">*</span>
                        @endif
                    </label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        placeholder="••••••••" {{ !isset($user) ? 'required' : '' }}>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 rounded-10 mb-4">
            <div class="card-body p-4">
                <h3 class="fs-18 mb-4 border-bottom pb-3">Vai trò & Trạng thái</h3>

                {{-- Role --}}
                <div class="mb-4">
                    <label for="role" class="form-label fw-semibold">Vai trò<span class="text-danger">*</span></label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                        <option value="">Chọn vai trò...</option>
                        @foreach($roles as $role)
                        <option value="{{ $role }}"
                            {{ old('role', $user->role ?? '') == $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-2">
                        <strong>Admin:</strong> Toàn quyền<br>
                        <strong>Sale:</strong> Bán hàng, Nội dung<br>
                        <strong>Warehouse:</strong> Kho hàng
                    </small>
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Trạng thái</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="status" name="status"
                            value="1"
                            {{ old('status', $user->status ?? 1) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Hoạt động</label>
                    </div>
                    <small class="text-muted">Tài khoản không hoạt động sẽ không thể đăng nhập.</small>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold">
                        <i class="ri-save-line me-1"></i> {{ $buttonText ?? 'Lưu tài khoản' }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary py-2">
                        Hủy bỏ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!isset($user))
<input type="hidden" name="status" value="1">
@else
@if(old('status', $user->status ?? 1) != 1)
<input type="hidden" name="status" value="0">
@endif
@endif