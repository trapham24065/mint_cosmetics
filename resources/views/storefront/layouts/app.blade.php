<!DOCTYPE html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Brancy - Cosmetic & Beauty Salon Website Template</title>
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
@stack('scripts')

<!-- Test Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quickViewBtns = document.querySelectorAll('.action-btn-quick-view');

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

                quickViewContainer.innerHTML = '<p class="text-center">Loading product details...</p>';
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
                    quickViewContainer.innerHTML = '<p class="text-center text-danger">Could not load product details. Error: ' +
                        error.message + '</p>';
                });
                return;
            }

            // Handle Add to Cart - IMPROVED VERSION
            const addToCartButton = event.target.closest('.action-btn-cart');
            if (addToCartButton) {
                event.preventDefault();

                let variantId = addToCartButton.dataset.variantId;
                let quantity = 1; // Default quantity

                // Determine context and get appropriate quantity
                const context = addToCartButton.dataset.context || 'default';

                switch (context) {
                    case 'quick-view':
                        // Quick view modal context
                        const quickViewQuantityInput = document.getElementById('quickViewQuantity');
                        if (quickViewQuantityInput) {
                            quantity = parseInt(quickViewQuantityInput.value) || 1;
                        }
                        break;

                    case 'product-detail':
                        // Product detail page context
                        const productDetailQuantityInput = document.getElementById('productDetailQuantity');
                        if (productDetailQuantityInput) {
                            quantity = parseInt(productDetailQuantityInput.value) || 1;
                        }
                        break;

                    case 'product-card':
                        // Product card context - tìm quantity input trong cùng product-item
                        const productItem = addToCartButton.closest('.product-item');
                        if (productItem) {
                            const cardQuantityInput = productItem.querySelector('.quantity-input');
                            if (cardQuantityInput) {
                                quantity = parseInt(cardQuantityInput.value) || 1;
                            }
                        }
                        break;

                    default:
                        // Fallback: cố gắng lấy từ data-quantity cũ hoặc tìm input gần nhất
                        if (addToCartButton.dataset.quantity) {
                            quantity = parseInt(addToCartButton.dataset.quantity) || 1;
                        } else {
                            // Tìm input quantity gần nhất
                            const nearestQuantityInput = addToCartButton.closest('.product-item, .product-card')?.
                                querySelector('.quantity-input, input[name="quantity"]');
                            if (nearestQuantityInput) {
                                quantity = parseInt(nearestQuantityInput.value) || 1;
                            }
                        }
                }

                // Validate variant ID
                if (!variantId) {
                    console.error('Variant ID not found for add to cart');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to add product to cart. Please try again.',
                    });
                    return;
                }

                // Call add to cart function
                if (window.addToCart) {
                    window.addToCart(variantId, quantity, addToCartButton);
                } else {
                    console.error('addToCart function not found');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Cart function is not available. Please refresh the page.',
                    });
                }

                return;
            }
        });

        // Function to initialize quantity controls for a specific container
        function initializeQuantityControls (container) {
            const proQtyElements = container.querySelectorAll('.pro-qty');

            proQtyElements.forEach(proQtyElement => {
                // Skip if already initialized
                if (proQtyElement.querySelector('.qty-btn')) {
                    return;
                }

                const input = proQtyElement.querySelector('input[type="number"]');
                if (!input) {
                    return;
                }

                // Add quantity buttons
                proQtyElement.insertAdjacentHTML('beforeend', '<div class="dec qty-btn">-</div>');
                proQtyElement.insertAdjacentHTML('beforeend', '<div class="inc qty-btn">+</div>');

                // Add event listeners for quantity buttons
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

        // Initialize quantity controls when page loads
        initializeQuantityControls(document);

        // Render Quick View Content Function
        function renderQuickViewContent (product) {
            const defaultImageUrl = product.image
                ? `/storage/${product.image}`
                : '/assets/storefront/images/shop/1.webp';

            // 1. Group attributes and values from all variants
            const attributes = {};
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

            // 2. Render attribute selection UI
            let optionsHtml = '';
            for (const attrName in attributes) {
                optionsHtml += `<div class="product-details-qty-list mb-4"><h5 class="title">${attrName}</h5>`;
                for (const valueId in attributes[attrName].values) {
                    optionsHtml += `
                    <div class="qty-list-check">
                        <input class="form-check-input variant-option"
                               type="radio"
                               name="attribute_${attributes[attrName].id}"
                               id="qv_option_${valueId}"
                               value="${valueId}">
                        <label class="form-check-label" for="qv_option_${valueId}">
                            ${attributes[attrName].values[valueId]}
                        </label>
                    </div>`;
                }
                optionsHtml += `</div>`;
            }

            // 3. Render the modal content
            quickViewContainer.innerHTML = `
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-single-thumb">
                        <img id="quickViewImage"
                             src="${defaultImageUrl}"
                             alt="${product.name}"
                             style="width: 100%; height: auto;">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-details-content">
                        <h3 class="product-details-title">${product.name}</h3>
                        <div class="product-details-review mb-3">
                            <div class="product-review-icon">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <span class="ms-2">150 reviews</span>
                        </div>

                        <!-- Variant Options -->
                        <div id="quickview-variant-options-container" class="mb-4">
                            ${optionsHtml}
                        </div>

                        <p class="mb-3">${product.description || 'No description available'}</p>

                        <!-- Quantity with unique ID -->
                        <div class="product-details-pro-qty">
                            <div class="pro-qty">
                                <input type="number"
                                       id="quickViewQuantity"
                                       title="Quantity"
                                       value="1"
                                       min="1">
                            </div>
                        </div>

                        <!-- Price and Add to Cart -->
                        <div class="product-details-action">
                            <h6 class="price mb-3" id="quickViewPrice">Select options to see price</h6>
                            <button type="button"
                                    class="btn btn-primary action-btn-cart"
                                    id="quickViewAddToCart"
                                    data-context="quick-view"
                                    disabled>
                                Add to cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            // 4. Initialize quantity controls for this specific modal
            initializeQuantityControls(quickViewContainer);

            // 5. Add event listeners for variant options
            const optionRadios = quickViewContainer.querySelectorAll('.variant-option');
            optionRadios.forEach(radio => radio.addEventListener('change', updateQuickViewVariantDetails));

            // 6. Handle Simple Product case (no options to select)
            if (Object.keys(attributes).length === 0 && currentProductVariants.length === 1) {
                const simpleVariant = currentProductVariants[0];
                const priceEl = document.getElementById('quickViewPrice');
                const addToCartBtn = document.getElementById('quickViewAddToCart');

                let priceHtml = '';
                const price = parseFloat(simpleVariant.price);
                const discountPrice = simpleVariant.discount_price ? parseFloat(simpleVariant.discount_price) : null;

                if (discountPrice && discountPrice < price) {
                    priceHtml = `${discountPrice.toLocaleString(
                        'vi-VN')}Đ <span class="price-old">${price.toLocaleString('vi-VN')}Đ</span>`;
                } else {
                    priceHtml = `${price.toLocaleString('vi-VN')}Đ`;
                }

                priceEl.innerHTML = priceHtml;
                addToCartBtn.dataset.variantId = simpleVariant.id;
                addToCartBtn.disabled = false;
            }
        }

        // Update Quick View Variant Details Function
        function updateQuickViewVariantDetails () {
            const selectedOptions = Array.from(quickViewContainer.querySelectorAll('.variant-option:checked'));
            const priceEl = document.getElementById('quickViewPrice');
            const addToCartBtn = document.getElementById('quickViewAddToCart');

            // Get all attribute groups
            const allAttributeGroups = {};
            currentProductVariants.forEach(variant => {
                if (variant.attribute_values) {
                    variant.attribute_values.forEach(attrValue => {
                        allAttributeGroups[attrValue.attribute.id] = attrValue.attribute.name;
                    });
                }
            });

            // Check if all attribute groups have a selection
            if (selectedOptions.length !== Object.keys(allAttributeGroups).length) {
                priceEl.textContent = 'Please select all options';
                addToCartBtn.disabled = true;
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
                    priceHtml = `${discountPrice.toLocaleString(
                        'vi-VN')}Đ <span class="price-old">${price.toLocaleString('vi-VN')}Đ</span>`;
                } else {
                    priceHtml = `${price.toLocaleString('vi-VN')}Đ`;
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
    });
</script>

</body>


</html>
