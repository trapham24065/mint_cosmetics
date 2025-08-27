@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        {{-- Display All Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <h5 class="alert-title">Please fix the following errors:</h5>
                <ul>@foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Actions</h4></div>
                        <div class="card-body">
                            <p class="text-muted">Update the product information and click save.</p>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="{{ route('admin.products.index') }}"
                                   class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8">
                    @php
                        $productType = old('product_type', $product->variants->count() > 1 ? 'variable' : 'simple');
                    @endphp
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Product Type</h4></div>
                        <div class="card-body">
                            <input type="radio" class="btn-check" name="product_type" id="type_simple"
                                   value="simple" @checked($productType === 'simple')>
                            <label class="btn btn-outline-primary" for="type_simple">Simple Product</label>
                            <input type="radio" class="btn-check" name="product_type" id="type_variable"
                                   value="variable" @checked($productType === 'variable')>
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
                                           value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="product-category" class="form-label">Category</label>
                                    <select id="product-category" name="category_id"
                                            class="form-select @error('category_id') is-invalid @enderror" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @selected(old('category_id', $product->category_id) === $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="product-brand" class="form-label">Brand (Optional)</label>
                                    <select id="product-brand" name="brand_id"
                                            class="form-select @error('brand_id') is-invalid @enderror">
                                        <option value="">Choose a brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                @selected(old('brand_id', $product->brand_id) == $brand->id)>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
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
                                        <label class="form-check-label" for="product-active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php $firstVariant = $product->variants->first(); @endphp
                    <div id="simple-product-fields">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Pricing & Stock</h4></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="any" name="price" class="form-control"
                                               value="{{ old('price', $firstVariant->price ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Stock</label>
                                        <input type="number" name="stock" class="form-control"
                                               value="{{ old('stock', $firstVariant->stock ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="variable-product-fields">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Product Variants</h4></div>
                            <div class="card-body">
                                <div id="attributes-container" class="mb-3"></div>
                                <button type="button" id="generate-variants-btn" class="btn btn-secondary">Generate New
                                    Variants
                                </button>
                                <hr>
                                <label class="form-label fw-bold">Existing Variants:</label>
                                <div id="variants-container">
                                    @foreach($product->variants as $variant)
                                        @php
                                            // Prepare the unique key for this variant's combination of attribute values
                                            $combinationIds = $variant->attributeValues->pluck('id')->sort()->join(',');
                                        @endphp
                                        <div class="card mb-3 bg-light-subtle variant-form-row"
                                             data-combination-ids="{{ $combinationIds }}"
                                             data-variant-id="{{ $variant->id }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="card-title mb-0">
                                                        {{-- Generate the variant name from its attribute values --}}
                                                        {{ $variant->attributeValues->map(fn($val) => $val->attribute->name . ': ' . $val->value)->implode(' / ') ?: 'Default Variant' }}
                                                    </h6>
                                                    {{-- This button is handled by JavaScript to mark the variant for deletion --}}
                                                    <button type="button" class="btn-close" aria-label="Close"></button>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Price</label>
                                                        <input type="number" step="any"
                                                               name="variants[{{ $variant->id }}][price]"
                                                               class="form-control @error('variants.'.$variant->id.'.price') is-invalid @enderror"
                                                               value="{{ old('variants.'.$variant->id.'.price', $variant->price) }}"
                                                               required>
                                                        @error('variants.'.$variant->id.'.price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Discount Price</label>
                                                        <input type="number" step="any"
                                                               name="variants[{{ $variant->id }}][discount_price]"
                                                               class="form-control @error('variants.'.$variant->id.'.discount_price') is-invalid @enderror"
                                                               value="{{ old('variants.'.$variant->id.'.discount_price', $variant->discount_price) }}">
                                                        @error('variants.'.$variant->id.'.discount_price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Stock</label>
                                                        <input type="number" name="variants[{{ $variant->id }}][stock]"
                                                               class="form-control @error('variants.'.$variant->id.'.stock') is-invalid @enderror"
                                                               value="{{ old('variants.'.$variant->id.'.stock', $variant->stock) }}"
                                                               required>
                                                        @error('variants.'.$variant->id.'.stock')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="new-variants-container"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Product Images</h4></div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Current Main Image</label>
                                <div>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                             class="img-thumbnail mb-2" width="150">
                                    @else
                                        <p class="text-muted">No main image.</p>
                                    @endif
                                </div>
                                <label for="main-image" class="form-label">Upload New Main Image (Replaces old)</label>
                                <input type="file" id="main-image" name="image"
                                       class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label">Current Gallery Images</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @if(!empty($product->list_image))
                                        @foreach($product->list_image as $galleryImage)
                                            <img src="{{ asset('storage/' . $galleryImage) }}"
                                                 alt="{{ $product->name }} gallery" class="img-thumbnail" width="100">
                                        @endforeach
                                    @else
                                        <p class="text-muted">No gallery images.</p>
                                    @endif
                                </div>
                                <label for="gallery-images" class="form-label mt-3">Upload New Gallery Images (Replaces
                                    all old ones)</label>
                                <input type="file" id="gallery-images" name="list_image[]"
                                       class="form-control @error('list_image.*') is-invalid @enderror" multiple>
                                @error('list_image.*')
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
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // --- Pass data from PHP to JavaScript ---
                    const allAttributesForCategory = @json($allAttributesForCategory);
                    const selectedAttributeValueIds = @json($selectedAttributeValueIds);

                    // --- Get DOM Elements ---
                    const simpleFields = document.getElementById('simple-product-fields');
                    const variableFields = document.getElementById('variable-product-fields');
                    const categorySelect = document.getElementById('product-category');
                    const attributesContainer = document.getElementById('attributes-container');
                    const generateBtn = document.getElementById('generate-variants-btn');
                    const variantsContainer = document.getElementById('variants-container');
                    const newVariantsContainer = document.getElementById('new-variants-container');
                    const mainForm = document.querySelector('form');

                    // --- UI Toggle Logic ---
                    function toggleProductTypeFields() {
                        if (document.getElementById('type_simple').checked) {
                            simpleFields.style.display = 'block';
                            variableFields.style.display = 'none';
                        } else {
                            simpleFields.style.display = 'none';
                            variableFields.style.display = 'block';
                        }
                    }
                    document.querySelectorAll('input[name="product_type"]').forEach(radio => radio.addEventListener('change', toggleProductTypeFields));
                    toggleProductTypeFields(); // Set the initial state

                    // --- Attribute & Variant Logic ---
                    function renderAttributes(attributes) {
                        attributesContainer.innerHTML = '';
                        if (attributes.length === 0) {
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
                        // 1. Get all selected attribute values, grouped by attribute
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
                        if (attributesWithOptions.length === 0) {
                            return; // Do nothing if no attributes are selected
                        }

                        // 2. Get all combinations that ALREADY EXIST on the page
                        const existingCombinations = new Set();
                        document.querySelectorAll('.variant-form-row').forEach(row => {
                            if (row.dataset.combinationIds) {
                                existingCombinations.add(row.dataset.combinationIds);
                            }
                        });

                        // 3. Calculate all possible new combinations (Cartesian Product)
                        const cartesian = (...a) => a.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())));
                        const allPossibleCombinations = cartesian(...attributesWithOptions);

                        const newVariantsContainer = document.getElementById('new-variants-container');

                        // 4. Filter and Render ONLY the truly new combinations
                        allPossibleCombinations.forEach((combo, index) => {
                            const comboArray = Array.isArray(combo) ? combo : [combo];
                            const valueIds = comboArray.map(v => v.id);
                            const combinationKey = [...valueIds].sort().join(','); // Create a sorted key for comparison

                            // Check if this combination already exists
                            if (existingCombinations.has(combinationKey)) {
                                return; // Skip if it already exists
                            }

                            // If it's a new combination, render the form for it
                            const variantName = comboArray.map(v => v.text).join(' / ');
                            const formHtml = `
            <div class="card mb-3 bg-success-subtle new-variant-form-row">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title"><span class="badge bg-success">NEW</span> ${variantName}</h6>
                        <button type="button" class="btn-close" onclick="this.closest('.new-variant-form-row').remove()"></button>
                    </div>

                    {{-- Use 'new_variants' for the name attribute --}}
                            <input type="hidden" name="new_variants[${index}][attribute_value_ids]" value="${valueIds.join(',')}">

                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Price</label>
                            <input type="number" step="any" name="new_variants[${index}][price]" class="form-control" placeholder="Price" required>
                        </div>
                         <div class="col-md-4">
                            <label class="form-label">Discount Price</label>
                            <input type="number" step="any" name="new_variants[${index}][discount_price]" class="form-control" placeholder="Discount Price">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock</label>
                            <input type="number" name="new_variants[${index}][stock]" class="form-control" placeholder="Stock" required>
                        </div>
                    </div>
                </div>
            </div>
        `;
                            newVariantsContainer.insertAdjacentHTML('beforeend', formHtml);

                            // Add the newly rendered combination to the set to avoid duplicating it if the button is clicked again
                            existingCombinations.add(combinationKey);
                        });
                    });

                    // --- Variant Deletion Logic ---
                    variantsContainer.addEventListener('click', function(event) {
                        if (event.target.classList.contains('btn-close')) {
                            const variantRow = event.target.closest('.variant-form-row');
                            const variantId = variantRow.dataset.variantId;

                            // Create a hidden input to mark for deletion
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'deleted_variants[]';
                            hiddenInput.value = variantId;
                            mainForm.appendChild(hiddenInput);

                            // Hide the row from the UI
                            variantRow.style.display = 'none';
                        }
                    });

                    // Initial render for the pre-selected category's attributes
                    renderAttributes(allAttributesForCategory);
                });
            </script>
        <!-- @formatter:on -->
    @endpush
@endsection
