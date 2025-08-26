<!--== Start Aside Cart ==-->
<aside class="aside-cart-wrapper offcanvas offcanvas-end" tabindex="-1" id="AsideOffcanvasCart"
       aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h1 class="d-none" id="offcanvasRightLabel">Shopping Cart</h1>
        <button class="btn-aside-cart-close" data-bs-dismiss="offcanvas" aria-label="Close">Shopping Cart <i
                class="fa fa-chevron-right"></i></button>
    </div>
    <div class="offcanvas-body">
        <ul class="aside-cart-product-list">
            <li class="aside-product-list-item">
                <a href="#/" class="remove">×</a>
                <a href="product-details.html">
                    <img src="assets/images/shop/cart1.webp" width="68" height="84" alt="Image">
                    <span class="product-title">Leather Mens Slipper</span>
                </a>
                <span class="product-price">1 × £69.99</span>
            </li>
            <li class="aside-product-list-item">
                <a href="#/" class="remove">×</a>
                <a href="product-details.html">
                    <img src="assets/images/shop/cart2.webp" width="68" height="84" alt="Image">
                    <span class="product-title">Quickiin Mens shoes</span>
                </a>
                <span class="product-price">1 × £20.00</span>
            </li>
        </ul>
        <p class="cart-total"><span>Subtotal:</span><span class="amount">£89.99</span></p>
        <a class="btn-total" href="{{route('cart')}}">View cart</a>
        <a class="btn-total" href="{{route('checkout')}}">Checkout</a>
    </div>
</aside>
<!--== End Aside Cart ==-->
