@csrf
<div class="row">
    <div class="col-lg-8">
        <div class="card  border-0 rounded-10 mb-4">
            <div class="card-body p-4">
                <h3 class="fs-18 mb-4 border-bottom pb-3">Thông tin cơ bản</h3>

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Tên nhà cung cấp<span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                           value="{{ old('name', $supplier->name ?? '') }}" placeholder="Enter supplier company name"
                           required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-semibold">Địa chỉ email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                               name="email"
                               value="{{ old('email', $supplier->email ?? '') }}" placeholder="contact@supplier.com">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                               name="phone"
                               value="{{ old('phone', $supplier->phone ?? '') }}" placeholder="+1 234 567 890">
                        @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold">Địa chỉ</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                           name="address"
                           value="{{ old('address', $supplier->address ?? '
') }}" placeholder="Địa chỉ đầy đủ">
                    @error('address')
                    <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="card  border-0 rounded-10 mb-4">
            <div class="card-body p-4">
                <h3 class="fs-18 mb-4 border-bottom pb-3">Thông tin chi tiết bổ sung</h3>

                {{-- Contact Person --}}
                <div class="mb-3">
                    <label for="contact_person" class="form-label fw-semibold">Người liên hệ</label>
                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                           id="contact_person" name="contact_person"
                           value="{{ old('contact_person', $supplier->contact_person ?? '') }}"
                           placeholder="Tên người đại diện">
                    @error('contact_person')
                    <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Note --}}
                <div class="mb-3">
                    <label for="note" class="form-label fw-semibold">Ghi chú</label>
                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="4"
                              placeholder="Mọi thông tin bổ sung...">{{ old('note', $supplier->note ?? '') }}</textarea>
                    @error('note')
                    <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card  border-0 rounded-10 mb-4">
            <div class="card-body p-4">
                <h3 class="fs-18 mb-4 border-bottom pb-3">Trạng thái & Hành động</h3>

                {{-- Status --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Trạng thái</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active"
                               value="1"
                            {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Hoạt động</label>
                    </div>
                    <small class="text-muted">Không thể chọn các nhà cung cấp không hoạt động cho đơn đặt hàng.</small>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold">
                        <i class="ri-save-line me-1"></i> {{ $buttonText ?? 'Lưu nhà cung cấp' }}
                    </button>
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary py-2">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
