<div class="row">
    <div class="col-12 mb-3">
        <label for="brand-name" class="form-label">Tên thương hiệu</label>
        <input type="text" id="brand-name" name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $brand->name ?? '') }}" required>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mb-3">
        <label for="brand-logo" class="form-label">Logo</label>
        <input class="form-control @error('logo') is-invalid @enderror" type="file" id="brand-logo" name="logo"
            accept="image/*"
            data-current-image-url="{{ old('remove_current_logo') ? '' : ((isset($brand) && $brand->logo) ? asset('storage/' . $brand->logo) : '') }}"
            data-current-image-name="{{ (isset($brand) && $brand->logo) ? basename($brand->logo) : '' }}">
        <input type="hidden" name="remove_current_logo" id="remove-current-brand-logo" value="{{ old('remove_current_logo', '0') }}">

        <div id="brand-logo-preview-wrapper" class="mt-3 d-none">
            <div class="d-flex align-items-start gap-3">
                <img id="brand-logo-preview" src="#" alt="Brand logo preview"
                    class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                <div>
                    <div id="brand-logo-name" class="small text-muted"></div>
                    <button type="button" id="remove-brand-logo-btn"
                        class="btn btn-sm btn-outline-danger mt-2">Xóa ảnh đã chọn</button>
                </div>
            </div>
        </div>

        @error('logo')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="brand-active"
                name="is_active" value="1"
                @checked(old('is_active', $brand->is_active ?? true))>
            <label class="form-check-label" for="brand-active">Hoạt động</label>
        </div>
    </div>
</div>