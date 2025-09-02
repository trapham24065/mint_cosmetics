@extends('storefront.layouts.app')

@section('content')
    <main class="main-content">
        <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="page-header-st3-content text-center text-md-start">
                            <ol class="breadcrumb justify-content-center justify-content-md-start">
                                <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a class="text-dark" href="{{ route('shop') }}">Products</a>
                                </li>
                                <li class="breadcrumb-item active text-dark"
                                    aria-current="page">{{ $product->name }}</li>
                            </ol>
                            <h2 class="page-header-title">{{ $product->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-space">
            <div class="container">
                <div class="row product-details">
                    <div class="col-lg-6">
                        <div class="product-details-thumb">
                            <img id="product-main-image"
                                 src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/storefront/images/shop/product-details/1.webp') }}"
                                 width="570" height="693" alt="{{ $product->name }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="product-details-content">
                            <h5 class="product-details-collection">{{ $product->category->name ?? 'Collection' }}</h5>
                            <h3 class="product-details-title">{{ $product->name }}</h3>

                            <div class="product-details-review">
                                <div class="product-review-icon">
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-half-o"></i>
                                </div>
                                <button type="button" class="product-review-show">150 reviews</button>
                            </div>
                            <div id="variant-options-container" class="mb-4"></div>

                            <div class="product-details-pro-qty">
                                <div class="pro-qty">
                                    <input type="number" id="quantity" title="Quantity" value="1" min="1">
                                </div>
                            </div>

                            <div class="product-details-action">
                                <h6 class="price" id="product-price">Select options to see price</h6>
                                <div class="product-details-cart-wishlist">
                                    <button type="button" class="btn-wishlist" data-bs-toggle="modal"
                                            data-bs-target="#action-WishlistModal"><i class="fa fa-heart-o"></i>
                                    </button>
                                    <button type="button" class="btn action-btn-cart" id="add-to-cart-btn" disabled>Add
                                        to cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="nav product-details-nav" id="product-details-nav-tab" role="tablist">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#description-tab"
                                    type="button">Description
                            </button>
                            {{-- You can add tabs for specification and reviews later --}}
                        </div>
                        <div class="tab-content" id="product-details-nav-tabContent">
                            <div class="tab-pane fade show active" id="description-tab" role="tabpanel">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @if($relatedProducts->isNotEmpty())
            <section class="section-space">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title">
                                <h2 class="title">Related Products</h2>
                                <p class="m-0">Check out other products in the same category.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-n10">
                        <div class="col-12">
                            <div class="swiper related-product-slide-container">
                                <div class="swiper-wrapper">
                                    @foreach($relatedProducts as $related)
                                        <div class="swiper-slide mb-10">
                                            {{-- Reuse the same component here --}}
                                            <x-product-card :product="$related" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productData = @json($product);
                const variants = productData.variants;
                const optionsContainer = document.getElementById('variant-options-container');
                const priceEl = document.getElementById('product-price');
                const addToCartBtn = document.getElementById('add-to-cart-btn');

                // 1. Group attributes and values from all variants
                const attributes = {};
                variants.forEach(variant => {
                    variant.attribute_values.forEach(attrValue => {
                        const attrName = attrValue.attribute.name;
                        if (!attributes[attrName]) {
                            attributes[attrName] = {
                                id: attrValue.attribute.id,
                                values: {},
                            };
                        }
                        attributes[attrName].values[attrValue.id] = attrValue.value;
                    });
                });

                // 2. Render attribute selection UI
                let optionsHtml = '';
                for (const attrName in attributes) {
                    optionsHtml += `<div class="product-details-qty-list mb-4"><h5 class="title">${attrName}</h5>`;
                    for (const valueId in attributes[attrName].values) {
                        optionsHtml += `
                <div class="qty-list-check">
                    <input class="form-check-input variant-option" type="radio" name="attribute_${attributes[attrName].id}" id="option_${valueId}" value="${valueId}">
                    <label class="form-check-label" for="option_${valueId}">${attributes[attrName].values[valueId]}</label>
                </div>`;
                    }
                    optionsHtml += `</div>`;
                }
                optionsContainer.innerHTML = optionsHtml;

                // 3. Add event listeners and update the UI based on selection
                const optionRadios = optionsContainer.querySelectorAll('.variant-option');
                optionRadios.forEach(radio => radio.addEventListener('change', updateVariantDetails));

                function updateVariantDetails () {
                    const selectedOptions = Array.from(optionsContainer.querySelectorAll('.variant-option:checked'));

                    // Check if all attribute groups have a selection
                    if (selectedOptions.length !== Object.keys(attributes).length) {
                        priceEl.textContent = 'Please select all options';
                        addToCartBtn.disabled = true;
                        return;
                    }

                    const selectedValueIds = selectedOptions.map(input => input.value).sort();

                    const matchedVariant = variants.find(variant => {
                        const variantValueIds = variant.attribute_values.map(v => v.id.toString()).sort();
                        return selectedValueIds.length === variantValueIds.length &&
                            selectedValueIds.every((id, index) => id === variantValueIds[index]);
                    });

                    if (matchedVariant) {
                        let priceHtml = '';
                        const price = parseFloat(matchedVariant.price);
                        const discountPrice = matchedVariant.discount_price ?
                            parseFloat(matchedVariant.discount_price) :
                            null;

                        if (discountPrice && discountPrice < price) {
                            priceHtml = `<p class="price">${discountPrice.toLocaleString(
                                'vi-VN')}Đ</p> <p class="price-old">${price.toLocaleString(
                                'vi-VN')}Đ</p>`;
                        } else {
                            priceHtml = `<span class="price">${price.toLocaleString('vi-VN')}Đ</span>`;
                        }
                        priceEl.innerHTML = priceHtml;
                        addToCartBtn.dataset.variantId = matchedVariant.id;
                        addToCartBtn.disabled = false;
                    } else {
                        priceEl.textContent = 'This combination is not available';
                        addToCartBtn.dataset.variantId = '';
                        addToCartBtn.disabled = true;
                    }
                }

                // Handle Simple Product case (no options to select)
                if (Object.keys(attributes).length === 0 && variants.length === 1) {
                    const simpleVariant = variants[0];
                    let priceHtml = '';
                    const price = parseFloat(simpleVariant.price);
                    const discountPrice = simpleVariant.discount_price ?
                        parseFloat(simpleVariant.discount_price) :
                        null;

                    if (discountPrice && discountPrice < price) {
                        priceHtml = `<p class="price">${discountPrice.toLocaleString(
                            'vi-VN')}Đ</p> <p class="price-old">${price.toLocaleString('vi-VN')}Đ</p>`;
                    } else {
                        priceHtml = `<p class="price">${price.toLocaleString('vi-VN')}Đ</p>`;
                    }
                    priceEl.innerHTML = priceHtml;
                    addToCartBtn.dataset.variantId = variants[0].id;
                    addToCartBtn.disabled = false;
                }

                // Add event listener for add to cart button
                addToCartBtn.addEventListener('click', function() {
                    const variantId = this.dataset.variantId;
                    const quantity = document.getElementById('quantity').value || 1;

                    if (variantId && window.addToCart) {
                        window.addToCart(variantId, quantity, this);
                    }
                });
            });
        </script>
    @endpush
@endsection
