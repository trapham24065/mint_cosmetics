@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data"
        novalidate>
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mt-3">
                            <h4>Danh mục chỉnh sửa</h4>
                            <h5 class="text-primary">{{ $category->name }}</h5>
                            <p class="text-muted">ID: {{ $category->id }}</p>
                            <p class="text-muted">Tạo: {{ $category->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary w-100">Cập nhật danh mục</button>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{ route('admin.categories.index') }}"
                                    class="btn btn-outline-secondary w-100">Hủy bỏ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin chung</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Category Name --}}
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="category-name" class="form-label">Tên danh mục</label>
                                    <input type="text" id="category-name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Nhập tên danh mục"
                                        value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Category Image --}}
                            <div class="col-12 mb-3">
                                <label for="category-image" class="form-label">Hình ảnh theo danh mục</label>
                                <input class="form-control @error('image') is-invalid @enderror" type="file"
                                    id="category-image" name="image" accept="image/*"
                                    data-current-image-url="{{ old('remove_current_image') ? '' : ($category->image ? asset('storage/' . $category->image) : '') }}"
                                    data-current-image-name="{{ $category->image ? basename($category->image) : '' }}">
                                <input type="hidden" name="remove_current_image" id="remove-current-category-image" value="{{ old('remove_current_image', '0') }}">
                                @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div id="category-image-preview-wrapper" class="mt-3 d-none">
                                    <div class="d-flex align-items-start gap-3">
                                        <img id="category-image-preview" src="#" alt="Category image preview"
                                            class="img-thumbnail"
                                            style="width: 120px; height: 120px; object-fit: cover;">
                                        <div>
                                            <div id="category-image-name" class="small text-muted"></div>
                                            <button type="button" id="remove-category-image-btn"
                                                class="btn btn-sm btn-outline-danger mt-2">Xóa ảnh hiện tại</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Active Status --}}
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input" type="checkbox" id="category-active"
                                            name="active" value="1"
                                            @checked(old('active', $category->active))>
                                        <label class="form-check-label" for="category-active">Hoạt động</label>
                                    </div>
                                    @error('active')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Attributes Selection --}}
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="attribute-select" class="form-label">Thuộc tính liên kết</label>
                                    <select class="form-control" id="attribute-select" name="attribute_ids[]"
                                        data-choices multiple>
                                        <option value="">Chọn các thuộc tính...</option>
                                        @foreach ($attributes as $attribute)
                                        <option value="{{ $attribute->id }}"
                                            @selected(in_array($attribute->id, old('attribute_ids', $selectedAttributeIds), true))>
                                            {{ $attribute->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('attribute_ids')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/single-image-preview.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('category-image');
        initSingleImagePreview({
            inputId: 'category-image',
            previewWrapperId: 'category-image-preview-wrapper',
            previewImageId: 'category-image-preview',
            previewNameId: 'category-image-name',
            removeButtonId: 'remove-category-image-btn',
            removeFlagInputId: 'remove-current-category-image',
            currentImageUrl: input ? input.dataset.currentImageUrl : '',
            currentImageName: input ? input.dataset.currentImageName : '',
            removeCurrentLabel: 'Xóa ảnh hiện tại',
            removeSelectedLabel: 'Xóa ảnh đã chọn'
        });
    });
</script>
@endpush