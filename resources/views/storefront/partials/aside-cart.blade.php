<!--== Start Aside Cart ==-->
<aside class="aside-cart-wrapper offcanvas offcanvas-end" tabindex="-1" id="AsideOffcanvasCart"
       aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h1 class="d-none" id="offcanvasRightLabel">Shopping Cart</h1>
        <button class="btn-aside-cart-close" data-bs-dismiss="offcanvas" aria-label="Close">Shopping Cart <i
                class="fa fa-chevron-right"></i></button>
    </div>
    <div class="offcanvas-body">
        <ul class="aside-cart-product-list" id="aside-cart-product-list">
        </ul>
        <p class="cart-total"><span>Subtotal:</span><span class="amount" id="aside-cart-subtotal">0 VNĐ</span></p>
        <a class="btn-total" href="{{route('cart.index')}}">View cart</a>
        <a class="btn-total" href="{{route('checkout.index')}}">Checkout</a>
    </div>
</aside>
<!--== End Aside Cart ==-->
