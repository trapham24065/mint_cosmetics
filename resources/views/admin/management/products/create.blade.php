@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        {{-- Display All Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <h5 class="alert-title">Please fix the following errors:</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Actions</h4></div>
                        <div class="card-body">
                            <p class="text-muted">Fill in all required fields, generate variants if needed, and then
                                click create.</p>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Create Product</button>
                                <a href="{{ route('admin.products.index') }}"
                                   class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Product Type</h4></div>
                        <div class="card-body">
                            <input type="radio" class="btn-check" name="product_type" id="type_simple" value="simple"
                                   autocomplete="off" @if(old('product_type', 'simple') === 'simple') checked @endif>
                            <label class="btn btn-outline-primary" for="type_simple">Simple Product</label>

                            <input type="radio" class="btn-check" name="product_type" id="type_variable"
                                   value="variable" autocomplete="off"
                                   @if(old('product_type') === 'variable') checked @endif>
                            <label class="btn btn-outline-primary" for="type_variable">Variable Product</label>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Product Information</h4></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="product-name" class="form-label">Product Name</label>
                                    <input type="text" id="product-name" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="product-category" class="form-label">Category</label>
                                    <select id="product-category" name="category_id"
                                            class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">Choose a category...</option>
                                        @foreach($categories as $category)
                                            <option
                                                value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="product-brand" class="form-label">Brand (Optional)</label>
                                    <select id="product-brand" name="brand_id"
                                            class="form-select @error('brand_id') is-invalid @enderror">
                                        <option value="">Choose a brand</option>
                                        @foreach($brands as $brand)
                                            <option
                                                value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description"
                                              class="form-control @error('description') is-invalid @enderror"
                                              rows="5">{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="product-active"
                                               name="active" value="1" @checked(old('active', true))>
                                        <label class="form-check-label" for="product-active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="simple-product-fields">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Pricing & Stock</h4></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="number" step="any" id="price" name="price"
                                               class="form-control @error('price') is-invalid @enderror"
                                               value="{{ old('price') }}">
                                        @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="discount-percentage" class="form-label">Discount Percentage
                                            (%)</label>
                                        <input type="number" step="any" id="discount-percentage" class="form-control"
                                               placeholder="e.g., 15">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="discount-price" class="form-label">Discount Price
                                            (Auto-calculated)</label>
                                        <input type="number" step="any" id="discount-price" name="discount_price"
                                               class="form-control @error('discount_price') is-invalid @enderror"
                                               value="{{ old('discount_price') }}" readonly>
                                        @error('discount_price')
                                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="stock" class="form-label">Stock Quantity</label>
                                        <input type="number" id="stock" name="stock"
                                               class="form-control @error('stock') is-invalid @enderror"
                                               value="{{ old('stock') }}">
                                        @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="variable-product-fields" style="display: none;">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Product Variants</h4></div>
                            <div class="card-body">
                                <p class="text-muted">Select a category to load its attributes, then choose values and
                                    generate variants.</p>
                                <div id="attributes-container" class="mb-3"></div>
                                <button type="button" id="generate-variants-btn" class="btn btn-secondary"
                                        style="display: none;">Generate Variants
                                </button>
                                <hr>
                                <div id="variants-container"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Product Images</h4></div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="main-image" class="form-label">Main Image</label>
                                <input type="file" id="main-image" name="image"
                                       class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="gallery-images" class="form-label">Image Gallery (Optional, can select
                                    multiple)</label>
                                <input type="file" id="gallery-images" name="list_image[]"
                                       class="form-control @error('list_image.*') is-invalid @enderror" multiple>
                                @error('list_image.*')
                                <div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <!-- @formatter:off -->

        <script>

            document.addEventListener('DOMContentLoaded', function () {

                // --- UI Toggle Logic ---
                const simpleFields = document.getElementById('simple-product-fields');
                const variableFields = document.getElementById('variable-product-fields');
                const typeRadios = document.querySelectorAll('input[name="product_type"]');

                function toggleProductTypeFields() {
                    if (document.getElementById('type_simple').checked) {
                        simpleFields.style.display = 'block';
                        variableFields.style.display = 'none';
                    } else {
                        simpleFields.style.display = 'none';
                        variableFields.style.display = 'block';
                    }
                }

                typeRadios.forEach(radio => radio.addEventListener('change', toggleProductTypeFields));

                // Set initial state based on old input if validation fails, otherwise default to 'simple'
                const initialType = '{{ old('product_type', 'simple') }}';
                document.getElementById('type_' + initialType).checked = true;
                toggleProductTypeFields();

                // --- Dynamic Variant Generation Logic ---
                const categorySelect = document.getElementById('product-category');
                const attributesContainer = document.getElementById('attributes-container');
                const generateBtn = document.getElementById('generate-variants-btn');
                const variantsContainer = document.getElementById('variants-container');

                // Automatically trigger change if a category was already selected (on validation error)
                if (categorySelect.value) {
                    categorySelect.dispatchEvent(new Event('change'));
                }

                categorySelect.addEventListener('change', async function () {
                    const categoryId = this.value;
                    attributesContainer.innerHTML = '<p>Loading attributes...</p>';
                    variantsContainer.innerHTML = '';
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
                        attributesContainer.innerHTML = '<p class="text-danger">Failed to load attributes.</p>';
                        console.error('Error fetching attributes:', error);
                    }
                });

                function renderAttributes(attributes) {
                    attributesContainer.innerHTML = '';
                    if (attributes.length === 0) {
                        attributesContainer.innerHTML = '<p class="text-muted">This category has no attributes linked.</p>';
                        generateBtn.style.display = 'none';
                        return;
                    }

                    attributes.forEach(attr => {
                        let valuesHtml = attr.values.map(val => `
                <div class="form-check form-check-inline">
                    <input class="form-check-input attribute-value-check" type="checkbox" id="value-${val.id}"
                           data-attribute-id="${attr.id}" value="${val.id}">
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
                    if(attributesWithOptions.length === 0) {
                        variantsContainer.innerHTML = '<div class="alert alert-warning">Please select at least one attribute value to generate variants.</div>';
                        return;
                    }

                    const cartesian = (...a) => a.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())));
                    const combinations = cartesian(...attributesWithOptions);

                    renderVariantForms(combinations);
                });

                function renderVariantForms(combinations) {
                    variantsContainer.innerHTML = '';
                    if (combinations.length === 0 || combinations[0].length === 0) return;

                    combinations.forEach((combo, index) => {
                        const comboArray = Array.isArray(combo) ? combo : [combo];
                        const variantName = comboArray.map(v => v.text).join(' / ');
                        const valueIds = comboArray.map(v => v.id);

                        const formHtml = `
                <div class="card mb-3 bg-light-subtle">
                    <div class="card-body">
                        <h6 class="card-title">${variantName}</h6>
                        <input type="hidden" name="variants[${index}][attribute_value_ids]" value="${valueIds.join(',')}">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Price</label>
                                <input type="number" step="any" name="variants[${index}][price]" class="form-control" placeholder="Price" required>
                            </div>
                             <div class="col-md-4">
                                <label class="form-label">Discount Price</label>
                                <input type="number" step="any" name="variants[${index}][discount_price]" class="form-control" placeholder="Discount Price">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stock</label>
                                <input type="number" name="variants[${index}][stock]" class="form-control" placeholder="Stock" required>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                        variantsContainer.innerHTML += formHtml;
                    });
                }
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const priceInput = document.getElementById('price');
                const percentageInput = document.getElementById('discount-percentage');
                const discountPriceInput = document.getElementById('discount-price');

                function calculateDiscountPrice() {
                    const price = parseFloat(priceInput.value);
                    const percentage = parseFloat(percentageInput.value);

                    if (!isNaN(price) && price > 0 && !isNaN(percentage) && percentage >= 0) {
                        const finalPrice = price - (price * (percentage / 100));

                        discountPriceInput.value = finalPrice.toFixed(0);
                    } else {
                        discountPriceInput.value = '';
                    }
                }

                priceInput.addEventListener('input', calculateDiscountPrice);
                percentageInput.addEventListener('input', calculateDiscountPrice);
            });
        </script>
    @endpush
    <!-- @formatter:off -->
@endsection
