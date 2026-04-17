@extends('admin.layouts.app')
@section('content')
<div class="container-xxl">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <div class="fw-semibold mb-1">Không thể tạo sản phẩm. Vui lòng kiểm tra lại:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Hành động</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Điền đầy đủ các trường bắt buộc, tạo biến thể nếu cần, rồi nhấp vào
                            tạo.</p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Tạo sản phẩm</button>
                            <a href="{{ route('admin.products.index') }}"
                                class="btn btn-outline-secondary">Hủy bỏ</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Loại sản phẩm</h4>
                    </div>
                    <div class="card-body">
                        <input type="radio" class="btn-check" name="product_type" id="type_simple" value="simple"
                            autocomplete="off" @if(old('product_type', 'simple' )==='simple' ) checked @endif>
                        <label class="btn btn-outline-primary" for="type_simple">Sản phẩm đơn giản</label>

                        <input type="radio" class="btn-check" name="product_type" id="type_variable"
                            value="variable" autocomplete="off"
                            @if(old('product_type')==='variable' ) checked @endif>
                        <label class="btn btn-outline-primary" for="type_variable">Sản phẩm biến đổi</label>
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
                                    value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="product-category" class="form-label">Danh mục</label>
                                <select id="product-category" name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Chọn một danh mục...</option>
                                    @foreach($categories as $category)
                                    <option
                                        value="{{ $category->id }}" @selected((int) old('category_id')===$category->id)>{{ $category->hierarchy_name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Chỉ danh mục con cuối cùng (danh mục lá) mới dùng để tạo sản phẩm.</small>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="product-brand" class="form-label">Thương hiệu (Tùy chọn)</label>
                                <select id="product-brand" name="brand_id"
                                    class="form-select @error('brand_id') is-invalid @enderror">
                                    <option value="">Chọn một thương hiệu</option>
                                    @foreach($brands as $brand)
                                    <option
                                        value="{{ $brand->id }}" @selected(old('brand_id')===$brand->id)>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Miêu tả</label>
                                <textarea name="description" id="description"
                                    class="tinymce-editor @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="product-active"
                                        name="active" value="1" @checked(old('active', true))>
                                    <label class="form-check-label" for="product-active">Hoạt động</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SIMPLE PRODUCT FIELDS --}}
                <div id="simple-product-fields">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Giá cả
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- SKU Field (Moved here for better visibility) --}}
                                <div class="col-md-12 mb-3">
                                    <label for="sku" class="form-label">SKU <small class="text-muted">(Được tạo tự
                                            động, có thể chỉnh sửa)</small></label>
                                    <input type="text" id="sku" name="sku"
                                        class="form-control @error('sku') is-invalid @enderror"
                                        value="{{ old('sku') }}" placeholder="e.g. LAPTOP-DELL-XPS">
                                    @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="price" class="form-label">Giá</label>
                                    <input type="number" step="any" id="price" name="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price') }}">
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="discount-percentage" class="form-label">Tỷ lệ chiết khấu
                                        (%)</label>
                                    <input type="number" step="any" min="0" max="100" id="discount-percentage" name="discount_percentage" class="form-control"
                                        value="{{ old('discount_percentage', 0) }}"
                                        placeholder="e.g., 15">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="discount-price" class="form-label">Giá khuyến mãi
                                        (Tự động tính toán)</label>
                                    <input type="number" step="any" id="discount-price" name="discount_price"
                                        class="form-control @error('discount_price') is-invalid @enderror"
                                        value="{{ old('discount_price') }}" readonly>
                                    @error('discount_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                {{-- VARIABLE PRODUCT FIELDS --}}
                <div id="variable-product-fields" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Các biến thể sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Chọn một danh mục để tải các thuộc tính của nó, sau đó chọn các
                                giá trị và
                                tạo các biến thể.</p>
                            @if($errors->any() && old('product_type') === 'variable')
                            <div class="alert alert-danger" role="alert">
                                <div class="fw-semibold mb-1">Có lỗi khi tạo sản phẩm biến thể:</div>
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div id="attributes-container" class="mb-3"></div>
                            <button type="button" id="generate-variants-btn" class="btn btn-secondary"
                                style="display: none;">Tạo biến thể
                            </button>
                            <hr>
                            <div id="variants-container"></div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Hình ảnh sản phẩm</h4>
                    </div>

                    <div class="card-body">

                        <!-- ẢNH CHÍNH -->
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh chính</label>
                            <input type="file" id="main-image-input" name="image" accept="image/*"
                                class="form-control @error('image') is-invalid @enderror">

                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div id="main-image-preview-wrapper" class="mt-3 d-none">
                                <div class="d-flex align-items-start gap-3">
                                    <img id="main-image-preview" src="#" alt="Main image preview"
                                        class="img-thumbnail"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                    <div>
                                        <div id="main-image-name" class="small text-muted"></div>
                                        <button type="button" id="remove-main-image-btn"
                                            class="btn btn-sm btn-outline-danger mt-2">Xóa ảnh đã chọn
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DROPZONE -->
                        <div class="mb-3">
                            <label class="form-label">Bộ sưu tập hình ảnh</label>

                            <div class="dropzone" id="product-dropzone"
                                action="{{ route('admin.products.upload-image') }}"
                                data-upload-url="{{ route('admin.products.upload-image') }}">
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
                        </div>

                        <!-- hidden -->
                        <input type="hidden" name="list_image" id="list_image" value='{{ old('list_image', '[]') }}'>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="{{ asset('assets/admin/js/tinymce-config.js') }}"></script>
<script src="{{ asset('assets/admin/js/product-dropzone.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- MAIN IMAGE PREVIEW/REMOVE LOGIC ---
        const mainImageInput = document.getElementById('main-image-input');
        const mainImagePreviewWrapper = document.getElementById('main-image-preview-wrapper');
        const mainImagePreview = document.getElementById('main-image-preview');
        const mainImageName = document.getElementById('main-image-name');
        const removeMainImageBtn = document.getElementById('remove-main-image-btn');
        let mainImageObjectUrl = null;

        function resetMainImageSelection() {
            if (mainImageObjectUrl) {
                URL.revokeObjectURL(mainImageObjectUrl);
                mainImageObjectUrl = null;
            }

            if (mainImageInput) {
                mainImageInput.value = '';
            }

            if (mainImagePreview) {
                mainImagePreview.src = '#';
            }

            if (mainImageName) {
                mainImageName.textContent = '';
            }

            if (mainImagePreviewWrapper) {
                mainImagePreviewWrapper.classList.add('d-none');
            }
        }

        if (mainImageInput) {
            mainImageInput.addEventListener('change', function() {
                const file = this.files && this.files[0] ? this.files[0] : null;

                if (!file || !file.type.startsWith('image/')) {
                    resetMainImageSelection();
                    return;
                }

                if (mainImageObjectUrl) {
                    URL.revokeObjectURL(mainImageObjectUrl);
                }

                mainImageObjectUrl = URL.createObjectURL(file);
                mainImagePreview.src = mainImageObjectUrl;
                mainImageName.textContent = file.name;
                mainImagePreviewWrapper.classList.remove('d-none');
            });
        }

        if (removeMainImageBtn) {
            removeMainImageBtn.addEventListener('click', resetMainImageSelection);
        }

        // --- AUTO SKU GENERATION LOGIC ---
        const nameInput = document.getElementById('product-name');
        const skuInput = document.getElementById('sku');
        let isSkuManuallyEdited = false;

        // Nếu có giá trị cũ (do validation error), coi như đã edit, không tự động đè
        if (skuInput && skuInput.value.trim() !== '') {
            isSkuManuallyEdited = true;
        }

        if (nameInput && skuInput) {
            // Nếu người dùng tự sửa SKU, ta sẽ dừng việc tự động sinh
            skuInput.addEventListener('input', function() {
                isSkuManuallyEdited = this.value.trim() !== '';
            });

            nameInput.addEventListener('input', function() {
                if (!isSkuManuallyEdited) {
                    const name = this.value;
                    // Tạo slug từ tên: "Tên Sản Phẩm" -> "TEN-SAN-PHAM"
                    const sku = generateSkuFromName(name);
                    skuInput.value = sku;
                }
            });
        }

        function generateSkuFromName(text) {
            return text.toString().toLowerCase().normalize('NFD') // Chuyển ký tự có dấu
                .replace(/[\u0300-\u036f]/g, '') // Xóa dấu
                .replace(/\s+/g, '-') // Thay khoảng trắng bằng -
                .replace(/[^\w\-]+/g, '') // Xóa ký tự đặc biệt
                .replace(/\-\-+/g, '-') // Xóa dấu - lặp
                .replace(/^-+/, '') // Xóa - đầu
                .replace(/-+$/, '') // Xóa - cuối
                .toUpperCase(); // Chuyển thành IN HOA
        }

        // -------------------------------

        // --- UI Toggle Logic ---
        const simpleFields = document.getElementById('simple-product-fields');
        const variableFields = document.getElementById('variable-product-fields');
        const typeRadios = document.querySelectorAll('input[name="product_type"]');

        function setSectionEnabled(section, enabled) {
            if (!section) {
                return;
            }

            section.querySelectorAll('input, select, textarea, button').forEach(el => {
                el.disabled = !enabled;
            });
        }

        function toggleProductTypeFields() {
            if (document.getElementById('type_simple').checked) {
                simpleFields.style.display = 'block';
                variableFields.style.display = 'none';
                setSectionEnabled(simpleFields, true);
                setSectionEnabled(variableFields, false);
            } else {
                simpleFields.style.display = 'none';
                variableFields.style.display = 'block';
                setSectionEnabled(simpleFields, false);
                setSectionEnabled(variableFields, true);
            }
        }

        typeRadios.forEach(radio => radio.addEventListener('change', toggleProductTypeFields));

        const initialType = "{{ old('product_type', 'simple') }}";
        document.getElementById('type_' + initialType).checked = true;
        toggleProductTypeFields();

        // --- Dynamic Variant Generation Logic ---
        const categorySelect = document.getElementById('product-category');
        const attributesContainer = document.getElementById('attributes-container');
        const generateBtn = document.getElementById('generate-variants-btn');
        const variantsContainer = document.getElementById('variants-container');
        const oldVariantsRaw = @json(old('variants', []));
        const oldVariants = Array.isArray(oldVariantsRaw) ? oldVariantsRaw : Object.values(oldVariantsRaw || {});
        const oldSelectedValueIds = new Set(
            oldVariants
            .flatMap(variant => {
                const ids = variant?.attribute_value_ids;
                if (Array.isArray(ids)) {
                    return ids.map(id => String(id));
                }

                if (typeof ids === 'string') {
                    return ids.split(',').map(id => id.trim()).filter(Boolean);
                }

                return [];
            })
        );
        let hasRestoredOldVariants = false;

        if (categorySelect.value) {
            const changeEvent = document.createEvent('HTMLEvents');
            changeEvent.initEvent('change', true, false);
            categorySelect.dispatchEvent(changeEvent);
        }

        categorySelect.addEventListener('change', async function() {
            const categoryId = this.value;
            attributesContainer.innerHTML = '<p>Đang tải thuộc tính...</p>';
            variantsContainer.innerHTML = '';
            generateBtn.style.display = 'none';

            if (!categoryId) {
                attributesContainer.innerHTML = '';
                return;
            }

            try {
                const response = await fetch(`/admin/categories/${categoryId}/attributes`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                const attributes = Array.isArray(data) ? data : (data.attributes || []);
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
                let valuesHtml = attr.values.map(val => `
                            <div class="form-check form-check-inline">
                                <input class="form-check-input attribute-value-check" type="checkbox" id="value-${val.id}"
                                    data-attribute-id="${attr.id}" value="${val.id}" ${oldSelectedValueIds.has(String(val.id)) ? 'checked' : ''}>
                                <label class="form-check-label" for="value-${val.id}">${val.value}</label>
                            </div>
                        `).join('');
                attributesContainer.innerHTML += `
                            <div class="mb-3 p-3 border rounded">
                                <h6>${attr.name}</h6>
                                ${valuesHtml}
                            </div>
                        `;
            });
            generateBtn.style.display = 'block';

            if (!hasRestoredOldVariants && initialType === 'variable' && oldVariants.length > 0) {
                renderOldVariantForms(attributes);
                hasRestoredOldVariants = true;
            }
        }

        function renderOldVariantForms(attributes) {
            const valueLabelMap = new Map();
            attributes.forEach(attr => {
                (attr.values || []).forEach(val => {
                    valueLabelMap.set(String(val.id), val.value);
                });
            });

            variantsContainer.innerHTML = '';

            oldVariants.forEach((variant, index) => {
                const rawIds = variant?.attribute_value_ids;
                const valueIds = Array.isArray(rawIds) ?
                    rawIds.map(id => String(id)) :
                    (typeof rawIds === 'string' ? rawIds.split(',').map(id => id.trim()).filter(Boolean) : []);

                const variantName = valueIds
                    .map(id => valueLabelMap.get(String(id)) || `#${id}`)
                    .join(' / ') || `Biến thể ${index + 1}`;

                const price = variant?.price ?? '';
                const discountPrice = variant?.discount_price ?? '';
                const discountPercentage = variant?.discount_percentage ?? (
                    Number(price) > 0 && Number(discountPrice) >= 0 ?
                    (((Number(price) - Number(discountPrice)) / Number(price)) * 100).toFixed(2) :
                    ''
                );
                const sku = variant?.sku ?? '';

                const formHtml = `
                            <div class="card mb-3 bg-light-subtle variant-form-row">
                                <div class="card-body">
                                    <h6 class="card-title">${variantName}</h6>
                                    <input type="hidden" name="variants[${index}][attribute_value_ids]" value="${valueIds.join(',')}">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">SKU</label>
                                            <input type="text" name="variants[${index}][sku]" class="form-control form-control-sm" value="${sku}" placeholder="Biến thể SKU">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Giá</label>
                                            <input type="number" step="any" name="variants[${index}][price]" class="form-control variant-price" value="${price}" placeholder="Giá" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tỷ lệ chiết khấu (%)</label>
                                            <input type="number" step="any" min="0" max="100" name="variants[${index}][discount_percentage]" class="form-control variant-discount-percentage" value="${discountPercentage}" placeholder="e.g., 15">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Giá khuyến mãi (Tự động tính toán)</label>
                                            <input type="number" step="any" name="variants[${index}][discount_price]" class="form-control variant-discount-price" value="${discountPrice}" placeholder="Giá khuyến mãi" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                variantsContainer.innerHTML += formHtml;
            });
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
                    text: checkbox.nextElementSibling.textContent.trim(),
                });
            });

            const attributesWithOptions = Object.values(selectedAttributeValues);
            if (attributesWithOptions.length === 0) {
                variantsContainer.innerHTML = '<div class="alert alert-warning">Vui lòng chọn ít nhất một giá trị thuộc tính để tạo biến thể.</div>';
                return;
            }

            const cartesian = (...a) => a.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())));
            const combinations = cartesian(...attributesWithOptions);

            renderVariantForms(combinations);
        });

        function renderVariantForms(combinations) {
            variantsContainer.innerHTML = '';
            if (combinations.length === 0 || combinations[0].length === 0) {
                return;
            }

            combinations.forEach((combo, index) => {
                const comboArray = Array.isArray(combo) ? combo : [combo];
                const variantName = comboArray.map(v => v.text).join(' / ');
                const valueIds = comboArray.map(v => v.id);

                // Tạo SKU cho từng variant dựa trên tên SP + tên variant
                const productName = document.getElementById('product-name').value;
                const variantSlug = generateSkuFromName(variantName);
                const productSlug = generateSkuFromName(productName);
                const variantSku = productSlug + '-' + variantSlug;

                const formHtml = `
                            <div class="card mb-3 bg-light-subtle">
                                <div class="card-body">
                                    <h6 class="card-title">${variantName}</h6>
                                    <input type="hidden" name="variants[${index}][attribute_value_ids]" value="${valueIds.join(
                            ',')}">
                                    <div class="row">
                                        {{-- Variant SKU --}}
                        <div class="col-md-12 mb-2">
                                     <label class="form-label">SKU</label>
                                     <input type="text" name="variants[${index}][sku]" class="form-control form-control-sm" value="${variantSku}" placeholder="Biến thể SKU">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Giá</label>
                                            <input type="number" step="any" name="variants[${index}][price]" class="form-control variant-price" placeholder="Giá" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tỷ lệ chiết khấu (%)</label>
                                            <input type="number" step="any" min="0" max="100" name="variants[${index}][discount_percentage]" class="form-control variant-discount-percentage" value="0" placeholder="e.g., 15">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Giá khuyến mãi (Tự động tính toán)</label>
                                            <input type="number" step="any" name="variants[${index}][discount_price]" class="form-control variant-discount-price" placeholder="Giá khuyến mãi" readonly>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        `;
                variantsContainer.innerHTML += formHtml;
            });
        }
    });

    // Discount calculation logic
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.getElementById('price');
        const percentageInput = document.getElementById('discount-percentage');
        const discountPriceInput = document.getElementById('discount-price');

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

        if (priceInput && percentageInput && discountPriceInput) {
            function calculateDiscountPrice() {
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

            priceInput.addEventListener('input', calculateDiscountPrice);
            percentageInput.addEventListener('input', calculateDiscountPrice);
            percentageInput.addEventListener('blur', function() {
                if (this.value === '') {
                    this.value = 0;
                }
                calculateDiscountPrice();
            });

            if (percentageInput.value === '') {
                percentageInput.value = 0;
            }

            calculateDiscountPrice();
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
            if (!event.target.classList.contains('variant-price') && !event.target.classList.contains('variant-discount-percentage')) {
                return;
            }

            const variantRow = event.target.closest('.card-body');
            if (variantRow) {
                calculateVariantDiscount(variantRow);
            }
        });

        document.addEventListener('blur', function(event) {
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

        document.querySelectorAll('.variant-form-row .card-body, .card.mb-3.bg-light-subtle .card-body').forEach(row => {
            calculateVariantDiscount(row);
        });
    });
</script>
@endpush
@endsection