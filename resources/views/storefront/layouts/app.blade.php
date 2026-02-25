<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Brancy - Cosmetic & Beauty Salon Website </title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="Brancy - Cosmetic & Beauty Salon Website Template">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords"
        content="bootstrap, ecommerce, ecommerce html, beauty, cosmetic shop, beauty products, cosmetic, beauty shop, cosmetic store, shop, beauty store, spa, cosmetic, cosmetics, beauty salon" />
    <meta name="author" content="codecarnival" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/storefront/images/favicon.webp')}}">
    <!-- Font CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet">

    <!-- Vendor CSS (Bootstrap & Icon Font) -->
    <link rel="stylesheet" href="{{asset('assets/storefront/css/vendor/bootstrap.min.css')}}">

    <!-- Plugins CSS (All Plugins Files) -->
    <link rel="stylesheet" href="{{asset('assets/storefront/css/plugins/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/storefront/css/plugins/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/storefront/css/plugins/fancybox.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/storefront/css/plugins/range-slider.css')}}">
    <link rel="stylesheet" href="{{asset('assets/storefront/css/plugins/nice-select.css')}}">

    <!-- Style CSS -->
    <link rel="stylesheet" href="{{asset('assets/storefront/css/style.min.css')}}">

    <!-- SweetAlert2 CSS -->

</head>

<body>
    <!--== Wrapper Start ==-->
    <div class="wrapper">
        @include('storefront.partials.header')
        @include('storefront.partials.aside-search')
        @include('storefront.partials.aside-cart')
        @include('storefront.partials.aside-menu')
        @include('storefront.layouts.quick-view-modal')
        @include('storefront.layouts.quick-add-cart-modal')
        @include('storefront.layouts.quick-wishlist-modal')
        @yield('content')
        <!--== Scroll Top Button ==-->
        <div id="scroll-to-top" class="scroll-to-top"><span class="fa fa-angle-up"></span></div>
        @include('storefront.partials._chat-widget')
        @include('storefront.partials.footer')

    </div>

    <script src="{{asset('assets/storefront/js/vendor/modernizr-3.11.7.min.js')}}"></script>
    <script src="{{asset('assets/storefront/js/vendor/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('assets/storefront/js/vendor/jquery-migrate-3.3.2.min.js')}}"></script>
    <script src="{{asset('assets/storefront/js/vendor/bootstrap.bundle.min.js')}}"></script>

    <!-- Plugins JS -->
    <script src="{{asset('assets/storefront/js/plugins/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('assets/storefront/js/plugins/fancybox.min.js')}}"></script>
    <script src="{{asset('assets/storefront/js/plugins/range-slider.js')}}"></script>
    <script src="{{asset('assets/storefront/js/plugins/jquery.nice-select.min.js')}}"></script>

    <!-- Custom Main JS -->
    <script src="{{asset('assets/storefront/js/main.js')}}"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('assets/storefront/js/cart.js')}}"></script>
    <script src="{{ asset('assets/storefront/js/aside-cart.js') }}"></script>
    <script src="{{ asset('assets/storefront/js/chatbot.js') }}"></script>
    <script src="{{ asset('assets/storefront/js/wishlist.js') }}"></script>
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // === QUICK VIEW LOGIC ===
            const quickViewModalElement = document.getElementById('action-QuickViewModal');
            if (!quickViewModalElement) {
                return;
            }

            const quickViewModal = new bootstrap.Modal(quickViewModalElement);
            const quickViewContainer = document.getElementById('quick-view-container');
            let currentProductVariants = [];

            // Quick View Event Listener
            document.body.addEventListener('click', function(event) {
                // Handle Quick View
                const quickViewButton = event.target.closest('.action-btn-quick-view');
                if (quickViewButton) {
                    event.preventDefault();
                    const productId = quickViewButton.dataset.productId;

                    if (!productId) {
                        alert('Product ID not found');
                        return;
                    }

                    // Show loading spinner
                    quickViewContainer.innerHTML = '<div class="d-flex justify-content-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                    quickViewModal.show();

                    fetch(`/products/${productId}/quick-view`).then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    }).then(product => {
                        if (!product || !product.name) {
                            throw new Error('Invalid product data received');
                        }
                        currentProductVariants = product.variants || [];
                        renderQuickViewContent(product);
                    }).catch(error => {
                        console.error('Quick View Error:', error);
                        quickViewContainer.innerHTML = '<p class="text-center text-danger py-5">Could not load product details. Please try again later.</p>';
                    });
                    return;
                }

                // ... (Handle Add to Cart logic remains the same) ...
                const addToCartButton = event.target.closest('.action-btn-cart');
                if (addToCartButton) {
                    event.preventDefault();

                    let variantId = addToCartButton.dataset.variantId;
                    let quantity = 1;

                    const context = addToCartButton.dataset.context || 'default';

                    switch (context) {
                        case 'quick-view':
                            const quickViewQuantityInput = document.getElementById('quickViewQuantity');
                            if (quickViewQuantityInput) {
                                quantity = parseInt(quickViewQuantityInput.value) || 1;
                            }
                            break;
                            // ... other cases
                    }

                    if (!variantId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Please select product options first.'
                        });
                        return;
                    }

                    if (window.addToCart) {
                        window.addToCart(variantId, quantity, addToCartButton);
                    }
                }
            });

            // Initialize quantity controls function
            function initializeQuantityControls(container) {
                const proQtyElements = container.querySelectorAll('.pro-qty');
                proQtyElements.forEach(proQtyElement => {
                    if (proQtyElement.querySelector('.qty-btn')) {
                        return;
                    }
                    const input = proQtyElement.querySelector('input[type="number"]');
                    if (!input) {
                        return;
                    }

                    // Add buttons
                    const decBtn = document.createElement('div');
                    decBtn.className = 'dec qty-btn';
                    decBtn.textContent = '-';

                    const incBtn = document.createElement('div');
                    incBtn.className = 'inc qty-btn';
                    incBtn.textContent = '+';

                    proQtyElement.prepend(decBtn);
                    proQtyElement.append(incBtn);

                    const qtyButtons = proQtyElement.querySelectorAll('.qty-btn');
                    qtyButtons.forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            const inputField = this.parentElement.querySelector('input[type="number"]');
                            if (!inputField) {
                                return;
                            }
                            const oldValue = parseInt(inputField.value) || 1;
                            let newVal;
                            if (this.classList.contains('inc')) {
                                newVal = oldValue + 1;
                            } else {
                                newVal = oldValue > 1 ? oldValue - 1 : 1;
                            }
                            inputField.value = newVal;
                        });
                    });
                });
            }

            // Helper to format price
            function formatPrice(price) {
                return parseFloat(price).toLocaleString('vi-VN') + 'đ';
            }

            // Render Quick View Content Function
            function renderQuickViewContent(product) {
                const defaultImageUrl = product.image ?
                    `/storage/${product.image}` :
                    '/assets/storefront/images/shop/1.webp';

                // 1. Group attributes
                const attributes = {};
                if (Array.isArray(currentProductVariants)) {
                    currentProductVariants.forEach(variant => {
                        if (variant.attribute_values) {
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
                        }
                    });
                }

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

                // --- REVIEW STARS LOGIC ---
                let starsHtml = '';
                const rating = Math.round(product.rating || 0);
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml += '<i class="fa fa-star"></i>';
                    } else {
                        starsHtml += '<i class="fa fa-star-o"></i>';
                    }
                }

                // Check discount badge logic - only show if discount_price < price
                const hasDiscount = (product.variants && product.variants.length > 0 && product.variants[0].discount_price && parseFloat(product.variants[0].discount_price) < parseFloat(product.variants[0].price));

                // 3. Render the modal content
                quickViewContainer.innerHTML = `
            <div class="row">
                <div class="col-lg-5 col-md-6 mb-4 mb-md-0">
                    <div class="product-single-thumb border rounded shadow-sm overflow-hidden position-relative">
                        <img id="quickViewImage"
                             src="${defaultImageUrl}"
                             alt="${product.name}"
                             class="img-fluid w-100"
                             style="object-fit: cover; min-height: 300px;">

                         ${hasDiscount
                ?
                `<span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 rounded small fw-bold">Sale</span>`
                : ''}
                    </div>
                </div>

                <div class="col-lg-7 col-md-6">
                    <div class="product-details-content ps-lg-3">
                        <h3 class="product-details-title fw-bold mb-2" style="font-size: 1.5rem;">${product.name}</h3>

                        <!-- REVIEWS SECTION -->
                        <div class="product-details-review d-flex align-items-center mb-3">
                            <div class="product-review-icon text-warning me-2" style="font-size: 0.9rem;">
                                ${starsHtml}
                            </div>
                            <span class="text-muted small">(${product.reviews_count || 0} reviews)</span>
                        </div>

                        <p class="mb-4 text-muted small border-bottom pb-3">
                            ${product.description ? product.description.replace(/(<([^>]+)>)/gi, '').substring(0, 150) +
                '...' : ''}
                        </p>

                        <div id="quickview-variant-options-container" class="mb-4">
                            ${optionsHtml}
                        </div>

                        <!-- KHÔI PHỤC LAYOUT CŨ CHO PRICE & ACTION -->
                        <div class="product-details-pro-qty">
                            <div class="pro-qty">
                                <input type="number" title="Quantity" id="quickViewQuantity" value="1" min="1">
                            </div>
                        </div>

                        <div class="product-details-action">
                            <h4 class="price mb-0" id="quickViewPrice" style="color: #e53637; font-size: 1.5rem; margin-bottom: 15px !important;">
                                ${currentProductVariants.length === 1
                ? formatPrice(currentProductVariants[0].price)
                : 'Select options to see price'}
                            </h4>

                            <div class="product-details-cart-wishlist">
                                <button type="button" class="btn action-btn-cart" id="quickViewAddToCart" data-context="quick-view" disabled>Add to cart</button>
                            </div>
                        </div>
                        <!-- KẾT THÚC PHẦN KHÔI PHỤC -->

                        <div class="product-details-meta mt-4 pt-3 border-top">
                            <ul class="list-unstyled text-muted small mb-0">
                                <li class="mb-1"><span class="fw-bold text-dark">SKU:</span> <span id="quickViewSku">${currentProductVariants.length ===
            1 ? (currentProductVariants[0].sku || 'N/A') : 'N/A'}</span></li>
                                <li><span class="fw-bold text-dark">Category:</span> ${product.category
                ? product.category.name
                : 'Uncategorized'}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            `;

                initializeQuantityControls(quickViewContainer);

                const optionRadios = quickViewContainer.querySelectorAll('.variant-option');
                optionRadios.forEach(radio => radio.addEventListener('change', updateQuickViewVariantDetails));

                // Initial check if simple product logic is needed inside render
                if (currentProductVariants.length === 1) {
                    const variant = currentProductVariants[0];
                    const priceEl = document.getElementById('quickViewPrice');
                    if (variant.discount_price) {
                        priceEl.innerHTML = `${formatPrice(
                        variant.discount_price)} <span class="text-muted text-decoration-line-through fs-6 ms-2">${formatPrice(
                        variant.price)}</span>`;
                    }

                    // Enable button for simple product
                    const addToCartBtn = document.getElementById('quickViewAddToCart');
                    addToCartBtn.dataset.variantId = variant.id;
                    addToCartBtn.disabled = false;
                }
            }

            // Update Quick View Variant Details Function
            function updateQuickViewVariantDetails() {
                const selectedOptions = Array.from(quickViewContainer.querySelectorAll('.variant-option:checked'));
                const priceEl = document.getElementById('quickViewPrice');
                const addToCartBtn = document.getElementById('quickViewAddToCart');
                const skuEl = document.getElementById('quickViewSku');
                const imageEl = document.getElementById('quickViewImage');

                // Get all attribute groups
                const allAttributeGroups = {};
                currentProductVariants.forEach(variant => {
                    if (variant.attribute_values) {
                        variant.attribute_values.forEach(attrValue => {
                            allAttributeGroups[attrValue.attribute.id] = true;
                        });
                    }
                });

                if (selectedOptions.length !== Object.keys(allAttributeGroups).length) {
                    // Keep current price or show range if not selected
                    return;
                }

                const selectedValueIds = selectedOptions.map(input => input.value).sort();

                const matchedVariant = currentProductVariants.find(variant => {
                    if (!variant.attribute_values) {
                        return false;
                    }
                    const variantValueIds = variant.attribute_values.map(v => v.id.toString()).sort();
                    return selectedValueIds.length === variantValueIds.length &&
                        selectedValueIds.every((id, index) => id === variantValueIds[index]);
                });

                if (matchedVariant) {
                    let priceHtml = '';
                    const price = parseFloat(matchedVariant.price);
                    const discountPrice = matchedVariant.discount_price ? parseFloat(matchedVariant.discount_price) : null;

                    if (discountPrice && discountPrice < price) {
                        priceHtml = `${formatPrice(
                        discountPrice)} <span class="text-muted text-decoration-line-through fs-6 ms-2">${formatPrice(
                        price)}</span>`;
                    } else {
                        priceHtml = `${formatPrice(price)}`;
                    }

                    priceEl.innerHTML = priceHtml;
                    if (skuEl) {
                        skuEl.textContent = matchedVariant.sku || 'N/A';
                    }

                    // Update image if variant has specific image
                    if (matchedVariant.image) {
                        imageEl.src = `/storage/${matchedVariant.image}`;
                    }

                    addToCartBtn.dataset.variantId = matchedVariant.id;
                    addToCartBtn.disabled = false;
                    addToCartBtn.innerHTML = 'Add to cart';

                    if (matchedVariant.stock <= 0) {
                        addToCartBtn.disabled = true;
                        addToCartBtn.innerHTML = 'Out of Stock';
                    }

                } else {
                    priceEl.textContent = 'Unavailable';
                    addToCartBtn.dataset.variantId = '';
                    addToCartBtn.disabled = true;
                    addToCartBtn.innerHTML = 'Unavailable';
                }
            }
        });
    </script>

</body>


</html>