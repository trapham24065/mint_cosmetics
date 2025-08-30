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

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/storefront/images/favicon.webp')}}">

    <!-- CSS (Font, Vendor, Icon, Plugins & Style CSS files) -->

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
@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quickViewModalElement = document.getElementById('action-QuickViewModal');
        if (!quickViewModalElement) {
            return;
        }

        const quickViewModal = new bootstrap.Modal(quickViewModalElement);
        const quickViewContainer = document.getElementById('quick-view-container');
        let currentProductVariants = [];

        document.body.addEventListener('click', function(event) {
            const quickViewButton = event.target.closest('.action-btn-quick-view');

            if (quickViewButton) {
                event.preventDefault();
                const productId = quickViewButton.dataset.productId;

                quickViewContainer.innerHTML = '<p class="text-center">Loading product details...</p>';
                quickViewModal.show();

                fetch(`/products/${productId}/quick-view`).then(response => response.json()).then(product => {
                    currentProductVariants = product.variants;
                    renderQuickViewContent(product);
                }).catch(error => {
                    console.error('Quick View Error:', error);
                    quickViewContainer.innerHTML = '<p class="text-center text-danger">Could not load product details.</p>';
                });
            }
        });

        function renderQuickViewContent (product) {
            const defaultImageUrl = product.image
                ? `/storage/${product.image}`
                : '/assets/storefront/images/shop/quick-view1.webp';

            const attributes = {};
            product.variants.forEach(variant => {
                variant.attribute_values.forEach(attrValue => {
                    const attrName = attrValue.attribute.name;
                    if (!attributes[attrName]) {
                        attributes[attrName] = { id: attrValue.attribute.id, values: {} };
                    }
                    attributes[attrName].values[attrValue.id] = attrValue.value;
                });
            });

            let optionsHtml = '';
            const isVariableProduct = Object.keys(attributes).length > 0;

            if (isVariableProduct) {
                for (const attrName in attributes) {
                    optionsHtml += `<div class="product-details-selection mb-3"><h5>${attrName}</h5><div class="d-flex flex-wrap gap-2">`;
                    for (const valueId in attributes[attrName].values) {
                        optionsHtml += `
                        <div class="form-check">
                            <input class="form-check-input variant-option" type="radio" name="attribute_${attributes[attrName].id}" id="option_${valueId}" value="${valueId}">
                            <label class="form-check-label" for="option_${valueId}">${attributes[attrName].values[valueId]}</label>
                        </div>`;
                    }
                    optionsHtml += `</div></div>`;
                }
            }

            quickViewContainer.innerHTML = `
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-single-thumb">
                        <img id="quickViewImage" src="${defaultImageUrl}" alt="${product.name}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-details-content">
                        <h3 class="product-details-title">${product.name}</h3>
                                            <div class="product-details-review mb-5">
                                                <div class="product-review-icon">
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-half-o"></i>
                                                </div>
                                                <button type="button" class="product-review-show">150 reviews</button>
                                            </div>
                        ${optionsHtml}
                                            <p class="mb-6">${product.description}</p>
                                            <div class="product-details-pro-qty my-4">
                                                <label class="form-label">Quantity:</label>
                                                <div class="pro-qty">
                                                    <div class="dec qty-btn">-</div>
                                                    <input type="number" readonly id="quickViewQuantity" title="Quantity" value="1" min="1">
                                                    <div class="inc qty-btn">+</div>
                                                </div>
                                            </div>
                        <div class="product-details-action">
                            <h4 class="price" id="quickViewPrice">Select options to see price</h4>
                            <div class="product-details-cart-wishlist">
                                <button type="button" class="btn" id="quickViewAddToCart" disabled>Add to cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

            if (isVariableProduct) {
                quickViewContainer.querySelectorAll('.variant-option').forEach(radio => {
                    radio.addEventListener('change', updateVariantDetails);
                });
            } else {
                // **FIX FOR SIMPLE PRODUCT**: If not a variable product, show the first (and only) variant's details immediately.
                const simpleVariant = currentProductVariants.length > 0 ? currentProductVariants[0] : null;
                if (simpleVariant) {
                    const priceEl = document.getElementById('quickViewPrice');
                    const addToCartBtn = document.getElementById('quickViewAddToCart');
                    const price = parseFloat(simpleVariant.price).
                        toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
                    priceEl.textContent = price;
                    addToCartBtn.disabled = false;
                }
            }
        }

        function updateVariantDetails () {
            const selectedOptions = Array.from(quickViewContainer.querySelectorAll('.variant-option:checked'));
            const selectedValueIds = selectedOptions.map(input => input.value).sort();

            // Find a variant that matches ALL selected options
            const matchedVariant = currentProductVariants.find(variant => {
                const variantValueIds = variant.attribute_values.map(v => v.id.toString()).sort();
                return selectedValueIds.length === variantValueIds.length &&
                    selectedValueIds.every((id, index) => id === variantValueIds[index]);
            });

            const priceEl = document.getElementById('quickViewPrice');
            const addToCartBtn = document.getElementById('quickViewAddToCart');

            if (matchedVariant) {
                const price = parseFloat(matchedVariant.price).
                    toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
                priceEl.textContent = price;
                addToCartBtn.disabled = false;
                // You can also update the image here: document.getElementById('quickViewImage').src = ...
            } else {
                priceEl.textContent = 'Selection not available';
                addToCartBtn.disabled = true;
            }
        }

        document.body.addEventListener('click', function(event) {
            // Check if a quantity button was clicked inside the quick view modal
            if (event.target.classList.contains('qty-btn') && quickViewModalElement.contains(event.target)) {
                const button = event.target;
                const input = button.parentElement.querySelector('input[type="number"]');
                if (!input) {
                    return;
                }

                let currentValue = parseInt(input.value, 10);
                const minValue = parseInt(input.min, 10) || 1;

                if (button.classList.contains('inc')) {
                    currentValue++;
                } else if (button.classList.contains('dec')) {
                    if (currentValue > minValue) {
                        currentValue--;
                    }
                }
                input.value = currentValue;
            }
        });
    });
</script>
</body>


</html>
