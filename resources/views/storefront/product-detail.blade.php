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
                        <div class="product-details-thumb product-image-container">
                            <img id="product-main-image"
                                 src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/storefront/images/shop/product-details/1.webp') }}"
                                 width="570" height="693" alt="{{ $product->name }}">
                        </div>
                        {{-- START A NEW ADDITION TO THE GALLERY --}}
                        @if(!empty($product->list_image) && is_array($product->list_image))
                            <div id="product-thumbnails-gallery"
                                 class="product-details-nav-wrap d-flex flex-wrap gap-2 mt-3">
                                @if($product->image)
                                    <div class="nav-item">
                                        <img class="product-thumbnail-item"
                                             src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                             width="100" height="120" style="cursor: pointer;">
                                    </div>
                                @endif
                                {{-- Loop through images in gallery --}}
                                @foreach($product->list_image as $galleryImage)
                                    <div class="nav-item">
                                        <img class="product-thumbnail-item"
                                             src="{{ asset('storage/' . $galleryImage) }}"
                                             alt="{{ $product->name }} gallery image" width="100" height="120"
                                             style="cursor: pointer;">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        {{-- END OF NEW ADDITIONAL SECTION --}}
                    </div>
                    <div class="col-lg-6">
                        <div class="product-details-content">
                            <h5 class="product-details-collection">{{ $product->category->name ?? 'Collection' }}</h5>
                            <h3 class="product-details-title">{{ $product->name }}</h3>

                            <div class="product-details-review">
                                @php
                                    $averageRating = $product->averageRating();
                                    $reviewsCount = $product->approvedReviews->count();
                                @endphp
                                <div class="product-review-icon">
                                    @if ($reviewsCount > 0)
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $averageRating)
                                                <i class="fa fa-star"></i>
                                            @else
                                                <i class="fa fa-star-o"></i>
                                            @endif
                                        @endfor
                                    @else
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star-o"></i>
                                        @endfor
                                    @endif
                                </div>
                                <a href="#review-tab" class="product-review-show">{{ $reviewsCount }} reviews</a></div>
                            <div id="variant-options-container" class="mb-4"></div>

                            <div class="product-details-pro-qty">
                                <div class="pro-qty">
                                    <!-- Change ID from "quantity" to "productDetailQuantity" -->
                                    <input type="number" id="productDetailQuantity" title="Quantity" value="1" min="1">
                                </div>
                            </div>

                            <div class="product-details-action">
                                <h2 id="product-price">Select options</h2>
                                <div class="product-details-cart-wishlist">
                                    <button type="button" class="btn-wishlist action-btn-wishlist"
                                            data-product-id="{{ $product->id }}">
                                        <i class="fa fa-heart-o"></i>
                                    </button>
                                    <button type="button" class="btn action-btn-cart" id="add-to-cart-btn"
                                            data-context="product-detail" disabled>Add to cart
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
                            <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review"
                                    type="button">
                                Reviews ({{ $product->approvedReviews->count() }})
                            </button>
                        </div>
                        <div class="tab-content" id="product-details-nav-tabContent">
                            <div class="tab-pane fade show active" id="description-tab" role="tabpanel">
                                {!! nl2br(e($product->description)) !!}
                            </div>

                            {{-- NEW ADD: Tab for Reviews --}}
                            <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                                @forelse($product->approvedReviews as $review)
                                    <div class="product-review-item">
                                        <div class="product-review-top">
                                            <div class="product-review-content">
                                                <span class="product-review-name">{{ $review->reviewer_name }}</span>
                                                <span class="product-review-designation">Verified Buyer</span>
                                                <div class="product-review-icon">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <i class="fa fa-star"></i>
                                                        @else
                                                            <i class="fa fa-star-o"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <p class="desc">{{ $review->review }}</p>
                                        @if(!empty($review->media) && is_array($review->media))
                                            <div class="review-media-gallery d-flex flex-wrap gap-2 mt-3">
                                                {{-- Loop through array of image paths --}}
                                                @foreach($review->media as $mediaPath)
                                                    <a href="{{ asset('storage/' . $mediaPath) }}"
                                                       data-fancybox="review-{{ $review->id }}">
                                                        <img src="{{ asset('storage/' . $mediaPath) }}"
                                                             alt="Review image" class="img-thumbnail" width="80"
                                                             height="80" style="object-fit: cover;">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="product-review-item">
                                        <p>This product has no reviews yet.</p>
                                    </div>
                                @endforelse
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
        <style>
            .product-image-container #product-main-image {
                transition: opacity 0.4s ease-in-out;
            }
            .product-image-container #product-main-image.fade-out {
                opacity: 0;
            }
        </style>
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
                            priceHtml = `<h2>${discountPrice.toLocaleString(
                                'vi-VN')}Đ</h2> <p style="text-decoration-line: line-through;" class="price-old">${price.toLocaleString(
                                'vi-VN')}Đ</p>`;
                        } else {
                            priceHtml = `<h2 >${price.toLocaleString('vi-VN')}Đ</h2>`;
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
                        priceHtml = `<h2 >${discountPrice.toLocaleString(
                            'vi-VN')}Đ</h2> <p style="text-decoration-line: line-through;" class="price-old">${price.toLocaleString(
                            'vi-VN')}Đ</p>`;
                    } else {
                        priceHtml = `<h2 >${price.toLocaleString('vi-VN')}Đ</h2>`;
                    }
                    priceEl.innerHTML = priceHtml;
                    addToCartBtn.dataset.variantId = variants[0].id;
                    addToCartBtn.disabled = false;
                }
                // --- START NEW LOGIC FOR AUTOMATIC PHOTO GALLERY ---
                const mainImage = document.getElementById('product-main-image');
                const thumbnailsContainer = document.getElementById('product-thumbnails-gallery');

                if (mainImage && thumbnailsContainer) {
                    const thumbnails = thumbnailsContainer.querySelectorAll('.product-thumbnail-item');
                    let currentIndex = 0;
                    let slideshowInterval;

                    /**
                     * Function to change main image with fade effect.
                     * @param {string} newSrc - Path of new image.
                     */
                    function changeMainImage (newSrc) {
                        mainImage.classList.add('fade-out');

                        setTimeout(() => {
                            mainImage.setAttribute('src', newSrc);
                            mainImage.classList.remove('fade-out');
                        }, 400);
                    }

                    function startSlideshow () {
                        if (!slideshowInterval && thumbnails.length > 1) {
                            slideshowInterval = setInterval(() => {
                                currentIndex = (currentIndex + 1) % thumbnails.length;
                                const nextThumbnailSrc = thumbnails[currentIndex].getAttribute('src');
                                changeMainImage(nextThumbnailSrc);
                            }, 3000);
                        }
                    }

                    function stopSlideshow () {
                        clearInterval(slideshowInterval);
                        slideshowInterval = null;
                    }

                    thumbnailsContainer.addEventListener('click', function(event) {
                        if (event.target.classList.contains('product-thumbnail-item')) {
                            stopSlideshow();
                            const newImageSrc = event.target.getAttribute('src');
                            changeMainImage(newImageSrc);

                            thumbnails.forEach((thumb, index) => {
                                if (thumb.getAttribute('src') === newImageSrc) {
                                    currentIndex = index;
                                }
                            });
                        }
                    });

                    thumbnailsContainer.addEventListener('mouseenter', stopSlideshow);
                    thumbnailsContainer.addEventListener('mouseleave', startSlideshow);

                    startSlideshow();
                }
                // --- NEW LOGIC ENDING ---
            });
        </script>
    @endpush
@endsection
