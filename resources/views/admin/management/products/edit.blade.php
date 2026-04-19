@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data"
              novalidate>
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Hành động</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Cập nhật thông tin sản phẩm và nhấn lưu.</p>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                <a href="{{ route('admin.products.index') }}"
                                   class="btn btn-outline-secondary">Hủy bỏ</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8">
                    @php
                        $hasVariantAttributes = $product->variants->contains(static function ($variant) {
                        return $variant->attributeValues->isNotEmpty();
                        });
                        $productType = old('product_type', $hasVariantAttributes ? 'variable' : 'simple');
                    @endphp
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Loại sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <input type="radio" class="btn-check" name="product_type" id="type_simple"
                                   value="simple" @checked($productType==='simple' )>
                            <label class="btn btn-outline-primary" for="type_simple">Sản phẩm đơn giản</label>
                            <input type="radio" class="btn-check" name="product_type" id="type_variable"
                                   value="variable" @checked($productType==='variable' )>
                            <label class="btn btn-outline-primary" for="type_variable">Sản phẩm biến thể</label>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thông tin sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="product-name" class="form-label">Tên sản phẩm</label>
                                    <input type="text" id="product-name" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="product-category" class="form-label">Loại</label>

                                    <select
                                        id="product-category"
                                        name="category_id"
                                        class="form-control @error('category_id') is-invalid @enderror"
                                        data-choices
                                        data-choices-search="true"
                                        data-choices-placeholder="true"
                                        required
                                    >
                                        <option value="">Chọn một danh mục...</option>

                                        @foreach($categories as $category)
                                            <option
                                                value="{{ $category->id }}"
                                                @selected((int) old('category_id', $product->category_id) === $category->id)
                                            >
                                                {{ $category->hierarchy_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <small class="text-muted">
                                        Chỉ danh mục con cuối cùng (danh mục lá) mới dùng cho sản phẩm.
                                    </small>

                                    @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="product-brand" class="form-label">
                                        Thương hiệu (Tùy chọn)
                                    </label>

                                    <select
                                        id="product-brand"
                                        name="brand_id"
                                        class="form-control @error('brand_id') is-invalid @enderror"
                                        data-choices
                                        data-choices-search="true"
                                        data-choices-placeholder="true"
                                    >
                                        <option value="">Chọn một thương hiệu</option>

                                        @foreach($brands as $brand)
                                            <option
                                                value="{{ $brand->id }}"
                                                @selected((int) old('brand_id', $product->brand_id) === $brand->id)
                                            >
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="description" class="form-label">Miêu tả</label>
                                    <textarea name="description" id="description"
                                              class="form-control @error('description') is-invalid @enderror"
                                              rows="5">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="product-active"
                                               name="active" value="1"
                                            @checked(old('active', $product->active))>
                                        <label class="form-check-label" for="product-active">Hoạt động</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php $firstVariant = $product->variants->first(); @endphp
                    <div id="simple-product-fields">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Giá cả </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    {{-- SKU Field (Moved here) --}}
                                    <div class="col-md-12 mb-3">
                                        <label for="sku" class="form-label">SKU <small class="text-muted">(Được tạo tự
                                                động, có thể chỉnh sửa)</small></label>
                                        <input type="text" id="sku" name="sku"
                                               class="form-control @error('sku') is-invalid @enderror"
                                               value="{{ old('sku', $firstVariant->sku ?? '') }}"
                                               placeholder="e.g. LAPTOP-DELL-XPS">
                                        @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Giá</label>
                                        <input type="number" step="any" id="edit-price" name="price"
                                               class="form-control @error('price') is-invalid @enderror"
                                               value="{{ old('price', $firstVariant->price ?? '') }}">
                                        @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tỷ lệ chiết khấu (%)</label>
                                        <input type="number" step="any" min="0" max="100" id="edit-discount-percentage"
                                               name="discount_percentage"
                                               class="form-control @error('discount_percentage') is-invalid @enderror"
                                               value="{{ old('discount_percentage', ($firstVariant && $firstVariant->price > 0 && $firstVariant->discount_price !== null) ? round((($firstVariant->price - $firstVariant->discount_price) / $firstVariant->price) * 100, 2) : 0) }}"
                                               placeholder="e.g., 15">
                                        @error('discount_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Giá khuyến mãi (Tự động tính toán)</label>
                                        <input type="number" step="any" id="edit-discount-price" name="discount_price"
                                               class="form-control @error('discount_price') is-invalid @enderror"
                                               value="{{ old('discount_price', $firstVariant->discount_price ?? '') }}"
                                               readonly>
                                        @error('discount_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="variable-product-fields">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Các biến thể sản phẩm</h4>
                            </div>
                            <div class="card-body">
                                <div id="attributes-container" class="mb-3"></div>
                                <button type="button" id="generate-variants-btn" class="btn btn-secondary">Tạo biến thể
                                    mới
                                </button>
                                <hr>
                                <label class="form-label fw-bold">Các biến thể hiện có:</label>
                                <div id="variants-container">
                                    @foreach($product->variants as $variant)
                                        @if($variant->attributeValues->isNotEmpty())
                                            @php
                                                $combinationIds = $variant->attributeValues->pluck('id')->sort()->join(',');
                                            @endphp
                                            <div class="card mb-3 bg-light-subtle variant-form-row"
                                                 data-combination-ids="{{ $combinationIds }}"
                                                 data-variant-id="{{ $variant->id }}">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="card-title mb-0">
                                                            {{ $variant->attributeValues->map(fn($val) => $val->attribute->name . ': ' . $val->value)->implode(' / ') }}
                                                        </h6>
                                                        <button type="button" class="btn-close"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="row">
                                                        {{-- Hidden variant id for proper validation/update --}}
                                                        <input type="hidden" name="variants[{{ $variant->id }}][id]"
                                                               value="{{ $variant->id }}">
                                                        {{-- Variant SKU --}}
                                                        <div class="col-md-12 mb-2">
                                                            <label class="form-label">SKU</label>
                                                            <input type="text" name="variants[{{ $variant->id }}][sku]"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ old('variants.'.$variant->id.'.sku', $variant->sku) }}"
                                                                   placeholder="Mã sản phẩm biến thể">
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label">Giá</label>
                                                            <input type="number" step="any"
                                                                   name="variants[{{ $variant->id }}][price]"
                                                                   class="form-control variant-price"
                                                                   value="{{ old('variants.'.$variant->id.'.price', $variant->price) }}"
                                                                   required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Tỷ lệ chiết khấu (%)</label>
                                                            <input type="number" step="any" min="0" max="100"
                                                                   name="variants[{{ $variant->id }}][discount_percentage]"
                                                                   class="form-control variant-discount-percentage"
                                                                   value="{{ old('variants.'.$variant->id.'.discount_percentage', ($variant->price > 0 && $variant->discount_price !== null) ? round((($variant->price - $variant->discount_price) / $variant->price) * 100, 2) : 0) }}"
                                                                   placeholder="e.g., 15">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Giá khuyến mãi (Tự động tính
                                                                toán)</label>
                                                            <input type="number" step="any"
                                                                   name="variants[{{ $variant->id }}][discount_price]"
                                                                   class="form-control variant-discount-price"
                                                                   value="{{ old('variants.'.$variant->id.'.discount_price', $variant->discount_price) }}"
                                                                   readonly>
                                                        </div>
                                                        {{-- Variant STOCK (Readonly) --}}
                                                        <div class="col-md-12 mt-2">
                                                            <label class="form-label">Số lượng</label>
                                                            <input type="number"
                                                                   name="variants[{{ $variant->id }}][stock]"
                                                                   class="form-control bg-light"
                                                                   value="{{ old('variants.'.$variant->id.'.stock', $variant->stock) }}"
                                                                   readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div id="new-variants-container"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Hình ảnh sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Ảnh chính hiện tại</label>
                                <div>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                             class="img-thumbnail mb-2" width="150">
                                    @else
                                        <p class="text-muted">Không có ảnh chính.</p>
                                    @endif
                                </div>
                                <label for="main-image" class="form-label">Tải lên ảnh chính mới (thay thế ảnh
                                    cũ)</label>
                                <input type="file" id="main-image" name="image"
                                       data-current-image-url="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                       data-current-image-name="{{ $product->image ? basename($product->image) : '' }}"
                                       class="form-control @error('image') is-invalid @enderror">
                                <input type="hidden" name="remove_current_image" id="remove-current-image" value="0">

                                <div id="edit-main-image-preview-wrapper" class="mt-3 d-none">
                                    <div class="d-flex align-items-start gap-3">
                                        <img id="edit-main-image-preview" src="#" alt="Main image preview"
                                             class="img-thumbnail"
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                        <div>
                                            <div id="edit-main-image-name" class="small text-muted"></div>
                                            <button type="button" id="remove-edit-main-image-btn"
                                                    class="btn btn-sm btn-outline-danger mt-2">Xóa ảnh đã chọn
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label mt-3">Bộ sưu tập hình ảnh</label>

                                @php
                                    $existingDropzoneImages = $galleryImageMeta ?? [];
                                @endphp

                                <div class="dropzone" id="product-dropzone"
                                     action="{{ route('admin.products.upload-image') }}"
                                     data-upload-url="{{ route('admin.products.upload-image') }}"
                                     data-existing-images='@json($existingDropzoneImages)'>
                                    <input type="file" id="dropzone-file-input" multiple accept="image/*"
                                           class="d-none" style="display: none;">
                                    <div class="dz-message needsclick">
                                        <i class="h1 bx bx-cloud-upload"></i>
                                        <h3>Kéo thả hoặc click để upload</h3>
                                        <span class="text-muted fs-13">
                                        PNG, JPG, GIF đều được hỗ trợ
                                    </span>
                                    </div>

                                    <div id="dropzone-preview" class="position-relative d-flex flex-wrap gap-3 mt-3"
                                         style="z-index: 2; pointer-events: none;"></div>
                                </div>

                                <div id="dropzone-preview-template" class="d-none">
                                    <div class="dz-preview dz-file-preview">
                                        <div class="position-relative">
                                            <div class="dz-image">
                                                <img data-dz-thumbnail alt="Dropzone Image" />
                                            </div>

                                        </div>
                                        <div class="dz-details mt-1">
                                            <div class="dz-size text-muted" data-dz-size></div>
                                            <div class="dz-filename" data-dz-name></div>
                                        </div>
                                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span>
                                        </div>
                                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                                    </div>
                                </div>

                                @php
                                    $initialGalleryJson = old('list_image', json_encode($galleryImages, JSON_UNESCAPED_UNICODE));
                                @endphp
                                <input type="hidden" name="list_image" id="list_image"
                                       value="{{ $initialGalleryJson }}">

                                @error('list_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <!-- @formatter:off -->
        <script src="{{ asset('assets/admin/js/tinymce-config.js') }}"></script>
    <script src="{{ asset('assets/admin/js/product-dropzone.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // --- MAIN IMAGE PREVIEW/REMOVE LOGIC (EDIT PAGE) ---
                const editMainImageInput = document.getElementById('main-image');
                const editMainImagePreviewWrapper = document.getElementById('edit-main-image-preview-wrapper');
                const editMainImagePreview = document.getElementById('edit-main-image-preview');
                const editMainImageName = document.getElementById('edit-main-image-name');
                const removeEditMainImageBtn = document.getElementById('remove-edit-main-image-btn');
                const removeCurrentImageInput = document.getElementById('remove-current-image');
                let editMainImageObjectUrl = null;
                const currentImageUrl = editMainImageInput ? (editMainImageInput.dataset.currentImageUrl || '') : '';
                const currentImageName = editMainImageInput ? (editMainImageInput.dataset.currentImageName || '') : '';

                function showEditMainImagePreview(imageUrl, imageName, isCurrentImage) {
                    if (!editMainImagePreviewWrapper || !editMainImagePreview) {
                        return;
                    }

                    editMainImagePreview.src = imageUrl || '#';
                    if (editMainImageName) {
                        editMainImageName.textContent = imageName || '';
                        editMainImageName.title = imageName || '';
                    }

                    if (removeEditMainImageBtn) {
                        removeEditMainImageBtn.textContent = isCurrentImage ? 'Xóa ảnh hiện tại' : 'Xóa ảnh đã chọn';
                    }

                    editMainImagePreviewWrapper.classList.remove('d-none');
                }

                function resetEditMainImageSelection() {
                    if (editMainImageObjectUrl) {
                        URL.revokeObjectURL(editMainImageObjectUrl);
                        editMainImageObjectUrl = null;
                    }

                    if (editMainImageInput) {
                        editMainImageInput.value = '';
                    }

                    if (editMainImagePreview) {
                        editMainImagePreview.src = '#';
                    }

                    if (editMainImageName) {
                        editMainImageName.textContent = '';
                    }

                    if (removeCurrentImageInput) {
                        removeCurrentImageInput.value = '0';
                    }

                    if (currentImageUrl) {
                        showEditMainImagePreview(currentImageUrl, currentImageName, true);
                    } else if (editMainImagePreviewWrapper) {
                        editMainImagePreviewWrapper.classList.add('d-none');
                    }
                }

                if (editMainImageInput) {
                    editMainImageInput.addEventListener('change', function () {
                        const file = this.files && this.files[0] ? this.files[0] : null;

                        if (!file || !file.type.startsWith('image/')) {
                            resetEditMainImageSelection();
                            return;
                        }

                        if (editMainImageObjectUrl) {
                            URL.revokeObjectURL(editMainImageObjectUrl);
                        }

                        editMainImageObjectUrl = URL.createObjectURL(file);
                        if (removeCurrentImageInput) {
                            removeCurrentImageInput.value = '0';
                        }
                        showEditMainImagePreview(editMainImageObjectUrl, file.name, false);
                    });
                }

                if (removeEditMainImageBtn) {
                    removeEditMainImageBtn.addEventListener('click', function () {
                        const hasNewSelectedFile = !!(editMainImageInput && editMainImageInput.files && editMainImageInput.files.length > 0);

                        if (hasNewSelectedFile) {
                            resetEditMainImageSelection();
                            return;
                        }

                        if (currentImageUrl) {
                            if (removeCurrentImageInput) {
                                removeCurrentImageInput.value = '1';
                            }
                            if (editMainImagePreviewWrapper) {
                                editMainImagePreviewWrapper.classList.add('d-none');
                            }
                            return;
                        }

                        resetEditMainImageSelection();
                    });
                }

                if (currentImageUrl) {
                    showEditMainImagePreview(currentImageUrl, currentImageName, true);
                }

                // --- 0. AUTO SKU LOGIC ---
                function generateSkuFromName(text) {
                    return text.toString().toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '')
                    .toUpperCase();
                }

                // Logic cho SKU của Simple Product
                const nameInput = document.getElementById('product-name');
                const skuInput = document.getElementById('sku');
                // Kiểm tra xem đã có SKU chưa (khi edit thì thường đã có)
                // Chỉ tự động điền nếu SKU đang trống
                let isSimpleSkuManuallyEdited = (skuInput && skuInput.value.trim() !== '');

                if (nameInput && skuInput) {
                    skuInput.addEventListener('input', function() {
                        isSimpleSkuManuallyEdited = this.value.trim() !== '';
                    });

                    nameInput.addEventListener('input', function() {
                        // Chỉ tự động sinh nếu SKU đang trống (hoặc chưa được edit tay từ lúc load trang)
                        if (!isSimpleSkuManuallyEdited || skuInput.value.trim() === '') {
                            const name = this.value;
                            const sku = generateSkuFromName(name);
                            skuInput.value = sku;
                        }
                    });
                }

                // --- 1. Pass data from PHP to JavaScript ---
                const allAttributesForCategory = @json($allAttributesForCategory, JSON_THROW_ON_ERROR);
                const selectedAttributeValueIds = @json($selectedAttributeValueIds, JSON_THROW_ON_ERROR);

                // --- 2. Get All Necessary DOM Elements ---
                const simpleFields = document.getElementById('simple-product-fields');
                const variableFields = document.getElementById('variable-product-fields');
                const typeRadios = document.querySelectorAll('input[name="product_type"]');
                const categorySelect = document.getElementById('product-category');
                const attributesContainer = document.getElementById('attributes-container');
                const generateBtn = document.getElementById('generate-variants-btn');
                const variantsContainer = document.getElementById('variants-container');
                const newVariantsContainer = document.getElementById('new-variants-container');
                const mainForm = document.querySelector('form');

                // --- 3. UI Toggle Logic ---
                function toggleProductTypeFields() {
                    const isSimple = document.getElementById('type_simple').checked;
                    simpleFields.style.display = isSimple ? 'block' : 'none';
                    variableFields.style.display = isSimple ? 'none' : 'block';
                    simpleFields.querySelectorAll('input, select, textarea').forEach(input => input.disabled = !isSimple);
                    variableFields.querySelectorAll('input, select, textarea, button').forEach(input => input.disabled = isSimple);
                }

                // --- 4. Event Listener for Product Type Change ---
                typeRadios.forEach(radio => {
                    radio.addEventListener('change', function () {
                        toggleProductTypeFields();
                        if (this.value === 'variable') {
                            newVariantsContainer.innerHTML = '';
                            categorySelect.dispatchEvent(new Event('change'));
                        }
                    });
                });

                // --- 5. Attribute & Variant Logic ---
                categorySelect.addEventListener('change', async function() {
                    const categoryId = this.value;
                    attributesContainer.innerHTML = '<p>Đang tải thuộc tính...</p>';
                    generateBtn.style.display = 'none';

                    if (!categoryId) {
                        attributesContainer.innerHTML = '';
                        return;
                    }

                    try {
                        const response = await fetch(`/admin/categories/${categoryId}/attributes`);
                        if (!response.ok) throw new Error('Network response was not ok');
                        const attributes = await response.json();
                        renderAttributes(attributes);
                    } catch (error) {
                        attributesContainer.innerHTML = '<p class="text-danger">Không thể tải thuộc tính.</p>';
                        console.error('Error fetching attributes:', error);
                    }
                });

                function renderAttributes(attributes) {
                    attributesContainer.innerHTML = '';
                    if (attributes.length === 0) {
                        attributesContainer.innerHTML = '<p class="text-muted">Danh mục này không có thuộc tính nào được liên kết.</p>';
                        generateBtn.style.display = 'none';
                        return;
                    }

                    attributes.forEach(attr => {
                        let valuesHtml = attr.values.map(val => {
                            const isChecked = selectedAttributeValueIds.includes(val.id) ? 'checked' : '';
                            return `
                <div class="form-check form-check-inline">
                    <input class="form-check-input attribute-value-check" type="checkbox" id="value-${val.id}"
                           data-attribute-id="${attr.id}" value="${val.id}" ${isChecked}>
                    <label class="form-check-label" for="value-${val.id}">${val.value}</label>
                </div>`;
                        }).join('');

                        attributesContainer.innerHTML += `<div class="mb-3 p-3 border rounded"><h6>${attr.name}</h6>${valuesHtml}</div>`;
                    });
                    generateBtn.style.display = 'block';
                }

                generateBtn.addEventListener('click', function() {
                    let selectedAttributeValues = {};
                    document.querySelectorAll('.attribute-value-check:checked').forEach(checkbox => {
                        const attrId = checkbox.dataset.attributeId;
                        if (!selectedAttributeValues[attrId]) {
                            selectedAttributeValues[attrId] = [];
                        }
                        selectedAttributeValues[attrId].push({
                            id: checkbox.value,
                            text: checkbox.nextElementSibling.textContent.trim()
                        });
                    });

                    const attributesWithOptions = Object.values(selectedAttributeValues);
                    if (attributesWithOptions.length === 0) return;

                    const existingCombinations = new Set();
                    document.querySelectorAll('.variant-form-row').forEach(row => {
                        if (row.dataset.combinationIds && row.style.display !== 'none') {
                            existingCombinations.add(row.dataset.combinationIds);
                        }
                    });

                    const cartesian = (...a) => a.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())));
                    const allPossibleCombinations = cartesian(...attributesWithOptions);

                    newVariantsContainer.innerHTML = '';

                    allPossibleCombinations.forEach((combo, index) => {
                        const comboArray = Array.isArray(combo) ? combo : [combo];
                        const valueIds = comboArray.map(v => v.id);
                        const combinationKey = [...valueIds].sort().join(',');

                        if (existingCombinations.has(combinationKey)) return;

                        const variantName = comboArray.map(v => v.text).join(' / ');

                        // TẠO SKU TỰ ĐỘNG CHO BIẾN THỂ MỚI
                        const productName = document.getElementById('product-name').value;
                        const variantSlug = generateSkuFromName(variantName);
                        const productSlug = generateSkuFromName(productName);
                        const variantSku = productSlug + '-' + variantSlug;

                        const formHtml = `
            <div class="card mb-3 bg-success-subtle new-variant-form-row">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title"><span class="badge bg-success">NEW</span> ${variantName}</h6>
                        <button type="button" class="btn-close" onclick="this.closest('.new-variant-form-row').remove()"></button>
                    </div>
                    <input type="hidden" name="new_variants[${index}][attribute_value_ids]" value="${valueIds.join(',')}">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                             <label class="form-label">SKU</label>
                             <input type="text" name="new_variants[${index}][sku]" class="form-control form-control-sm" value="${variantSku}" placeholder="Biến thể SKU">
                        </div>
                        <div class="col-md-4"><label class="form-label">Giá</label><input type="number" step="any" name="new_variants[${index}][price]" class="form-control variant-price" placeholder="Giá" required></div>
                        <div class="col-md-4"><label class="form-label">Tỷ lệ chiết khấu (%)</label><input type="number" step="any" min="0" max="100" name="new_variants[${index}][discount_percentage]" class="form-control variant-discount-percentage" value="0" placeholder="e.g., 15"></div>
                        <div class="col-md-4"><label class="form-label">Giá khuyến mãi (Tự động tính toán)</label><input type="number" step="any" name="new_variants[${index}][discount_price]" class="form-control variant-discount-price" placeholder="Giá khuyến mãi" readonly></div>
                        <div class="col-md-12 mt-2"><label class="form-label">Tồn kho</label><input type="number" name="new_variants[${index}][stock]" class="form-control bg-light" value="0" readonly></div>
                    </div>
                </div>
            </div>`;
                        newVariantsContainer.insertAdjacentHTML('beforeend', formHtml);
                    });
                });

                // --- 6. Variant Deletion Logic ---
                variantsContainer.addEventListener('click', function(event) {
                    const closeBtn = event.target.closest('.btn-close');
                    if (!closeBtn) {
                        return;
                    }

                    const variantRow = closeBtn.closest('.variant-form-row');
                    if (!variantRow) {
                        return;
                    }

                    const variantId = variantRow.dataset.variantId;
                    if (!variantId) {
                        return;
                    }

                    // Avoid appending duplicated deleted_variants[] for the same variant.
                    const existingDeletedInput = mainForm.querySelector(`input[name="deleted_variants[]"][value="${variantId}"]`);
                    if (!existingDeletedInput) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'deleted_variants[]';
                        hiddenInput.value = variantId;
                        mainForm.appendChild(hiddenInput);
                    }

                    // Prevent removed row fields from being submitted as variants payload.
                    variantRow.querySelectorAll('input, select, textarea, button').forEach(el => {
                        el.disabled = true;
                    });
                    variantRow.classList.add('d-none');
                });

                function normalizePercentageValue(rawValue) {
                    let percentage = parseFloat(rawValue);

                    if (isNaN(percentage)) {
                        percentage = 0;
                    }

                    if (percentage < 0) {
                        percentage = 0;
                    }

                    if (percentage > 100) {
                        percentage = 100;
                    }

                    return percentage;
                }

                function calculateSimpleDiscountPrice() {
                    const priceInput = document.getElementById('edit-price');
                    const percentageInput = document.getElementById('edit-discount-percentage');
                    const discountPriceInput = document.getElementById('edit-discount-price');

                    if (!priceInput || !percentageInput || !discountPriceInput) {
                        return;
                    }

                    const price = parseFloat(priceInput.value);
                    const percentage = normalizePercentageValue(percentageInput.value);
                    percentageInput.value = percentage;

                    if (!isNaN(price) && price > 0) {
                        const finalPrice = price - (price * (percentage / 100));
                        discountPriceInput.value = finalPrice.toFixed(0);
                    } else {
                        discountPriceInput.value = '';
                    }
                }

                function calculateVariantDiscount(row) {
                    const variantPriceInput = row.querySelector('.variant-price');
                    const variantPercentageInput = row.querySelector('.variant-discount-percentage');
                    const variantDiscountPriceInput = row.querySelector('.variant-discount-price');

                    if (!variantPriceInput || !variantPercentageInput || !variantDiscountPriceInput) {
                        return;
                    }

                    const price = parseFloat(variantPriceInput.value);
                    const percentage = normalizePercentageValue(variantPercentageInput.value);
                    variantPercentageInput.value = percentage;

                    if (!isNaN(price) && price > 0) {
                        const finalPrice = price - (price * (percentage / 100));
                        variantDiscountPriceInput.value = finalPrice.toFixed(0);
                    } else {
                        variantDiscountPriceInput.value = '';
                    }
                }

                document.addEventListener('input', function(event) {
                    if (event.target.id === 'edit-price' || event.target.id === 'edit-discount-percentage') {
                        calculateSimpleDiscountPrice();
                        return;
                    }

                    if (!event.target.classList.contains('variant-price') && !event.target.classList.contains('variant-discount-percentage')) {
                        return;
                    }

                    const variantRow = event.target.closest('.card-body');
                    if (variantRow) {
                        calculateVariantDiscount(variantRow);
                    }
                });

                document.addEventListener('blur', function(event) {
                    if (event.target.id === 'edit-discount-percentage') {
                        if (event.target.value === '') {
                            event.target.value = 0;
                        }
                        calculateSimpleDiscountPrice();
                        return;
                    }

                    if (!event.target.classList.contains('variant-discount-percentage')) {
                        return;
                    }

                    if (event.target.value === '') {
                        event.target.value = 0;
                    }

                    const variantRow = event.target.closest('.card-body');
                    if (variantRow) {
                        calculateVariantDiscount(variantRow);
                    }
                }, true);

                // --- 7. INITIALIZATION ---
                toggleProductTypeFields();
                calculateSimpleDiscountPrice();
                document.querySelectorAll('.variant-form-row .card-body, .new-variant-form-row .card-body').forEach(row => {
                    calculateVariantDiscount(row);
                });
                if (document.getElementById('type_variable').checked && attributesContainer.children.length === 0) {
                    categorySelect.dispatchEvent(new Event('change'));
                }
            });
        </script>
        <!-- @formatter:on -->
    @endpush
@endsection
