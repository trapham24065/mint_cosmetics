@extends('storefront.layouts.app')

@section('content')
    <main class="main-content">
        <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="page-header-st3-content text-center text-md-start">
                            <ol class="breadcrumb justify-content-center justify-content-md-start">
                                <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Trang
                                        chủ</a>
                                </li>
                                <li class="breadcrumb-item"><a class="text-dark" href="{{ route('shop') }}">Các sản
                                        phẩm</a>
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
                                 src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/storefront/images/shop/default.webp') }}"
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
                                            @if ($i <=$averageRating)
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
                                <a href="#review-tab" class="product-review-show">{{ $reviewsCount }} đánh giá
                                </a>
                            </div>

                            {{-- SKU Display --}}
                            <div class="product-details-sku mb-3">
                                <span class="fw-bold">SKU:</span> <span id="product-sku" class="text-muted">N/A</span>
                            </div>

                            <div id="variant-options-container" class="mb-4"></div>

                            {{-- Price Display Section --}}
                            <div class="product-price-wrapper mb-4">
                                <div id="product-price" class="d-flex align-items-center gap-3 flex-wrap">
                                    <span class="text-muted">Chọn tùy chọn</span>
                                </div>
                            </div>

                            {{-- Quantity Section --}}
                            <div class="product-details-pro-qty mb-4">
                                <label class="fw-bold mb-2" style="font-size: 14px;">Số lượng:</label>
                                <div class="pro-qty">
                                    <input type="number" id="productDetailQuantity" title="Quantity" value="1" min="1">
                                </div>
                            </div>

                            {{-- Action Buttons Section --}}
                            <div class="product-details-action-buttons d-flex gap-3 mb-3 flex-wrap">
                                <button type="button" class="btn btn-primary flex-fill" id="buy-now-btn"
                                        data-context="product-detail" disabled style="min-width: 200px;">
                                    <i class="fa fa-bolt me-2"></i>Mua ngay
                                </button>
                                <button type="button" class="btn btn-outline-dark flex-fill" id="add-to-cart-btn"
                                        data-context="product-detail" disabled style="min-width: 200px;">
                                    <i class="fa fa-shopping-cart me-2"></i>Thêm vào giỏ hàng
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-wishlist action-btn-wishlist"
                                        data-product-id="{{ $product->id }}" style="min-width: 50px;">
                                    <i class="fa fa-heart-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="nav product-details-nav" id="product-details-nav-tab" role="tablist">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#description-tab"
                                    type="button">Mô tả
                            </button>
                            <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review"
                                    type="button">
                                Đánh giá ({{ $product->approvedReviews->count() }})
                            </button>
                        </div>
                        <div class="tab-content product-details-tab-content" id="product-details-tab-content">
                            {{-- Tab Mô tả --}}
                            <div class="tab-pane fade show active" id="description-tab" role="tabpanel">
                                <x-product-description>
                                    {!! $product->description !!}
                                </x-product-description>
                            </div>
                            {{-- Tab Đánh giá --}}
                            <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                                {{-- Tạo một thẻ DIV bọc ngoài có ID cố định để thay thế nội dung bằng AJAX --}}
                                <div id="ajax-reviews-container">
                                    {{-- Nhúng giao diện review lần đầu tiên --}}
                                    @include('storefront.partials.reviews_list', ['reviews' => $reviews])
                                </div>
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
                                <h2 class="title">Sản phẩm liên quan</h2>
                                <p class="m-0">Xem thêm các sản phẩm khác cùng loại.</p>
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

            /* Product Price Styling */
            .product-price-wrapper {
                padding: 20px;
                background: #f8f9fa;
                border-radius: 8px;
                border-left: 4px solid #FF6565;
            }

            /* Action Buttons Styling */
            .product-details-action-buttons .btn {
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .product-details-action-buttons .btn:hover:not(:disabled) {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .product-details-action-buttons .btn:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }

            /* Responsive adjustments */
            @media (max-width: 576px) {
                .product-details-action-buttons {
                    flex-direction: column;
                }

                .product-details-action-buttons .btn {
                    width: 100%;
                    min-width: auto !important;
                }
            }
        </style>
        <!-- @formatter:off -->
        <script>
            $(document).ready(function() {
                // Lắng nghe sự kiện click vào các nút phân trang (trong khu vực review)
                $(document).on('click', '.review-pagination-container a', function(e) {
                    e.preventDefault(); // Chặn hành vi chuyển trang mặc định của trình duyệt

                    // Lấy đường link của trang mới (ví dụ: ?page=2)
                    var url = $(this).attr('href');

                    // Làm mờ khu vực review để báo hiệu đang tải dữ liệu
                    $('#ajax-reviews-container').css('opacity', '0.5');

                    // Gọi AJAX để tải nội dung HTML mới
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            // Nhét đoạn HTML vừa tải được vào thẻ DIV
                            $('#ajax-reviews-container').html(data);
                            $('#ajax-reviews-container').css('opacity', '1');

                            // Cuộn màn hình nhẹ nhàng lên đầu khu vực review để khách dễ đọc
                            $('html, body').animate({
                                scrollTop: $("#review-tab").offset().top - 50
                            }, 500);
                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi tải đánh giá. Vui lòng thử lại.');
                            $('#ajax-reviews-container').css('opacity', '1');
                        }
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productData = @json($product);
                const variants = productData.variants;
                const optionsContainer = document.getElementById('variant-options-container');
                const priceEl = document.getElementById('product-price');
                const addToCartBtn = document.getElementById('add-to-cart-btn');
                const buyNowBtn = document.getElementById('buy-now-btn');
                const skuEl = document.getElementById('product-sku'); // Get SKU element

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
                    optionsHtml += `
                    <div class="variant-group mb-3">
                        <h6 class="fw-bold mb-2" style="font-size: 14px; color: #333;">${attrName}:</h6>
                        <div class="d-flex flex-wrap gap-2">`;

                    for (const valueId in attributes[attrName].values) {
                        optionsHtml += `
                        <div class="form-check p-0">
                            <input class="btn-check variant-option" type="radio"
                                   name="attribute_${attributes[attrName].id}"
                                   id="qv_option_${valueId}"
                                   value="${valueId}" autocomplete="off">
                            <label class="btn btn-outline-dark btn-sm rounded-0 px-3" for="qv_option_${valueId}">
                                ${attributes[attrName].values[valueId]}
                            </label>
                        </div>`;
                    }
                    optionsHtml += `</div></div>`;
                }
                optionsContainer.innerHTML = optionsHtml;

                // 3. Add event listeners and update the UI based on selection
                const optionRadios = optionsContainer.querySelectorAll('.variant-option');
                optionRadios.forEach(radio => radio.addEventListener('change', updateVariantDetails));

                function updateVariantDetails () {
                    const selectedOptions = Array.from(optionsContainer.querySelectorAll('.variant-option:checked'));

                    // Check if all attribute groups have a selection
                    if (selectedOptions.length !== Object.keys(attributes).length) {
                        priceEl.innerHTML = '<span class="text-muted">Vui lòng chọn tất cả các tùy chọn</span>';
                        addToCartBtn.disabled = true;
                        buyNowBtn.disabled = true;
                        if(skuEl) skuEl.textContent = 'N/A'; // Reset SKU
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
                            priceHtml = `
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <h2 class="mb-0 text-danger fw-bold" style="font-size: 2rem;">${discountPrice.toLocaleString('vi-VN')}₫</h2>
                                    <span class="text-muted text-decoration-line-through" style="font-size: 1.2rem;">${price.toLocaleString('vi-VN')}₫</span>
                                    <span class="badge bg-danger">-${Math.round((1 - discountPrice/price) * 100)}%</span>
                                </div>
                            `;
                        } else {
                            priceHtml = `<h2 class="mb-0 text-dark fw-bold" style="font-size: 2rem;">${price.toLocaleString('vi-VN')}₫</h2>`;
                        }
                        priceEl.innerHTML = priceHtml;
                        addToCartBtn.dataset.variantId = matchedVariant.id;
                        buyNowBtn.dataset.variantId = matchedVariant.id;
                        addToCartBtn.disabled = false;
                        buyNowBtn.disabled = false;

                        // Update SKU
                        if(skuEl) {
                            skuEl.textContent = matchedVariant.sku || 'N/A';
                        }

                    } else {
                        priceEl.innerHTML = '<span class="text-danger">Sự kết hợp này không khả dụng.</span>';
                        addToCartBtn.dataset.variantId = '';
                        buyNowBtn.dataset.variantId = '';
                        addToCartBtn.disabled = true;
                        buyNowBtn.disabled = true;
                        if(skuEl) skuEl.textContent = 'N/A';
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
                        priceHtml = `
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <h2 class="mb-0 text-danger fw-bold" style="font-size: 2rem;">${discountPrice.toLocaleString('vi-VN')}₫</h2>
                                <span class="text-muted text-decoration-line-through" style="font-size: 1.2rem;">${price.toLocaleString('vi-VN')}₫</span>
                                <span class="badge bg-danger">-${Math.round((1 - discountPrice/price) * 100)}%</span>
                            </div>
                        `;
                    } else {
                        priceHtml = `<h2 class="mb-0 text-dark fw-bold" style="font-size: 2rem;">${price.toLocaleString('vi-VN')}₫</h2>`;
                    }
                    priceEl.innerHTML = priceHtml;
                    addToCartBtn.dataset.variantId = variants[0].id;
                    buyNowBtn.dataset.variantId = variants[0].id;
                    addToCartBtn.disabled = false;
                    buyNowBtn.disabled = false;

                    // Update SKU for simple product
                    if(skuEl) {
                        skuEl.textContent = simpleVariant.sku || 'N/A';
                    }
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

                // --- BUY NOW BUTTON LOGIC ---
                if (buyNowBtn) {
                    buyNowBtn.addEventListener('click', function() {
                        const variantId = buyNowBtn.dataset.variantId;
                        const quantityInput = document.getElementById('productDetailQuantity');
                        const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;

                        if (!variantId) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Vui lòng chọn các tùy chọn sản phẩm trước.',
                            });
                            return;
                        }

                        // Disable button and show loading
                        const originalText = buyNowBtn.innerHTML;
                        buyNowBtn.disabled = true;
                        buyNowBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Đang xử lý...';

                        // Add to cart first
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        fetch('/cart/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                variant_id: variantId,
                                quantity: quantity,
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update cart count if exists
                                const cartCountEl = document.getElementById('cart-count');
                                if (cartCountEl && data.cartCount) {
                                    cartCountEl.textContent = `(${data.cartCount})`;
                                }
                                // Redirect to checkout (route is protected, will redirect to login if needed)
                                window.location.href = '{{ route("customer.checkout.index") }}';
                            } else {
                                Swal.fire('Lỗi', data.message || 'Không thể thêm sản phẩm vào giỏ hàng.', 'error');
                                buyNowBtn.disabled = false;
                                buyNowBtn.innerHTML = originalText;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Lỗi', 'Đã xảy ra lỗi không mong muốn.', 'error');
                            buyNowBtn.disabled = false;
                            buyNowBtn.innerHTML = originalText;
                        });
                    });
                }
                // --- END BUY NOW BUTTON LOGIC ---
            });
        </script>
        <!-- @formatter:on -->

    @endpush
@endsection
