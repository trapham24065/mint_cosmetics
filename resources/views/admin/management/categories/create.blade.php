@extends('admin.layouts.app')
<style>
    .choices__list--dropdown {
        top: 100% !important;
        bottom: auto !important;
    }
</style>
@section('content')
    <div class="container-xxl">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mt-3">
                                <h4>Danh mục mới</h4>
                                <p class="text-muted">
                                    Hãy điền thông tin vào bên phải để tạo danh mục sản phẩm mới.
                                </p>
                            </div>
                        </div>
                        <div class="card-footer border-top">
                            <div class="row g-2">
                                <div class="col-lg-6">
                                    {{-- The 'submit' button for the form --}}
                                    <button type="submit" class="btn btn-primary w-100">Khởi tạo</button>
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
                                               placeholder="Nhập tên danh mục" value="{{ old('name') }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="parent-category" class="form-label">
                                            Danh mục cha (tuỳ chọn)
                                        </label>

                                        <select
                                            id="parent-category"
                                            name="parent_id"
                                            class="form-control @error('parent_id') is-invalid @enderror"
                                            data-choices
                                            data-choices-search="true"
                                            data-choices-placeholder="true"
                                        >
                                            <option value="">
                                                Không có danh mục cha (danh mục gốc)
                                            </option>

                                            @foreach ($parentCategories as $parentCategory)
                                                <option
                                                    value="{{ $parentCategory->id }}"
                                                    @selected((int) old('parent_id') === $parentCategory->id)
                                                >
                                                    {{ $parentCategory->hierarchy_name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- Category Image --}}
                                <div class="col-12 mb-3">
                                    <label for="category-image" class="form-label">Hình ảnh theo danh mục</label>
                                    <input class="form-control @error('image') is-invalid @enderror" type="file"
                                           id="category-image" name="image" accept="image/*">
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
                                                        class="btn btn-sm btn-outline-danger mt-2">Xóa ảnh đã chọn
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Active Status --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <div class="form-check form-switch form-switch-success">
                                            <input class="form-check-input" type="checkbox" id="category-active"
                                                   name="active" value="1" checked>
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
                                                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
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
            initSingleImagePreview({
                inputId: 'category-image',
                previewWrapperId: 'category-image-preview-wrapper',
                previewImageId: 'category-image-preview',
                previewNameId: 'category-image-name',
                removeButtonId: 'remove-category-image-btn',
            });
        });
    </script>
@endpush
