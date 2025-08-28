<div class="row">
    <div class="col-12 mb-3">
        <label for="brand-name" class="form-label">Brand Name</label>
        <input type="text" id="brand-name" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $brand->name ?? '') }}" required>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mb-3">
        <label for="brand-logo" class="form-label">Logo</label>
        {{-- Show current logo on edit page --}}
        @if (isset($brand) && $brand->logo)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-thumbnail"
                     width="100">
            </div>
            <label for="brand-logo" class="form-label fst-italic">Upload new logo to replace</label>
        @endif
        <input class="form-control @error('logo') is-invalid @enderror" type="file" id="brand-logo" name="logo">
        @error('logo')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="brand-active"
                   name="is_active" value="1"
                @checked(old('is_active', $brand->is_active ?? true))>
            <label class="form-check-label" for="brand-active">Active</label>
        </div>
    </div>
</div>
