@extends('storefront.layouts.app')
@section('content')
    <main class="main-content">
        <nav aria-label="breadcrumb" class="breadcrumb-style1">
            <div class="container">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cart</li>
                </ol>
            </div>
        </nav>

        <section class="section-space">
            <div class="container">
                <div class="shopping-cart-form table-responsive">
                    <table class="table text-center">
                        <thead>
                        <tr>
                            <th class="product-remove">&nbsp;</th>
                            <th class="product-thumbnail">&nbsp;</th>
                            <th class="product-name">Product</th>
                            <th class="product-price">Price</th>
                            <th class="product-quantity">Quantity</th>
                            <th class="product-subtotal">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($items as $variantId => $item)
                            <tr class="tbody-item" data-variant-id="{{ $variantId }}">
                                <td class="product-remove">
                                    <a class="remove" href="javascript:void(0)">×</a>
                                </td>
                                <td class="product-thumbnail">
                                    <div class="thumb">
                                        <a href="{{-- route('products.show', $item['product_slug']) --}}">
                                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : '' }}"
                                                 width="68" height="84" alt="Image">
                                        </a>
                                    </div>
                                </td>
                                <td class="product-name">
                                    <a class="title"
                                       href="{{-- route('products.show', $item['product_slug']) --}}">{{ $item['product_name'] }}</a>
                                    @if($item['variant_name'])
                                        <br><small>{{ $item['variant_name'] }}</small>
                                    @endif
                                </td>
                                <td class="product-price">
                                    <span class="price">{{ number_format($item['price'], 0, ',', '.') }} VNĐ</span>
                                </td>
                                <td class="product-quantity">
                                    <div class="pro-qty">
                                        <input type="number" class="quantity" title="Quantity"
                                               value="{{ $item['quantity'] }}" min="1">
                                    </div>
                                </td>
                                <td class="product-subtotal">
                                    <span class="price subtotal-price">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VNĐ</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">Your cart is empty.</td>
                            </tr>
                        @endforelse

                        <tr class="tbody-item-actions">
                            <td colspan="6">
                                <button type="button" id="update-cart-btn" class="btn-update-cart disabled" disabled>
                                    Update cart
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="coupon-wrap">
                            <h4 class="title">Coupon</h4>
                            <p class="desc">Enter your coupon code if you have one.</p>
                            <input type="text" class="form-control" placeholder="Coupon code">
                            <button type="button" class="btn-coupon">Apply coupon</button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="cart-totals-wrap">
                            <h2 class="title">Cart totals</h2>
                            <table>
                                <tbody>
                                <tr class="cart-subtotal">
                                    <th>Subtotal</th>
                                    <td>
                                        <span class="amount" id="cart-subtotal">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span>
                                    </td>
                                </tr>
                                <tr class="order-total">
                                    <th>Total</th>
                                    <td>
                                        <span class="amount" id="cart-total">{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="text-end">
                                <a href="{{route('checkout.index')}}" class="checkout-button">Proceed to checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const updateCartBtn = document.getElementById('update-cart-btn');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Enable update button when quantity changes
                document.querySelectorAll('.quantity').forEach(input => {
                    input.addEventListener('change', () => {
                        updateCartBtn.classList.remove('disabled');
                        updateCartBtn.disabled = false;
                    });
                });

                // Handle cart update
                updateCartBtn.addEventListener('click', function() {
                    const updates = {};
                    document.querySelectorAll('.tbody-item').forEach(row => {
                        const variantId = row.dataset.variantId;
                        const quantity = row.querySelector('.quantity').value;
                        if (variantId) {
                            updates[variantId] = quantity;
                        }
                    });

                    fetch('{{ route('cart.update') }}', {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ updates: updates }),
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            updateCartView(data.cart);
                            Swal.fire(
                                { toast: true, icon: 'success', title: 'Cart updated!', position: 'top-end', showConfirmButton: false, timer: 3000 });
                            updateCartBtn.classList.add('disabled');
                            updateCartBtn.disabled = true;
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                });

                // Handle item removal
                document.querySelectorAll('.product-remove .remove').forEach(button => {
                    button.addEventListener('click', function() {
                        const row = this.closest('.tbody-item');
                        const variantId = row.dataset.variantId;

                        fetch(`/cart/remove/${variantId}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrfToken },
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                row.remove();
                                updateCartView(data.cart);
                                const cartCountEl = document.getElementById('cart-count');
                                if (cartCountEl) {
                                    cartCountEl.textContent = Object.keys(data.cart.items).length;
                                }
                            }
                        });
                    });
                });

                function updateCartView (cart) {
                    document.querySelectorAll('.tbody-item').forEach(row => {
                        const variantId = row.dataset.variantId;
                        if (cart.items[variantId]) {
                            const item = cart.items[variantId];
                            const subtotalEl = row.querySelector('.subtotal-price');
                            subtotalEl.textContent = (item.price * item.quantity).toLocaleString('vi-VN') + ' VNĐ';
                        }
                    });
                    document.getElementById('cart-subtotal').textContent = cart.subtotal.toLocaleString('vi-VN') +
                        ' VNĐ';
                    document.getElementById('cart-total').textContent = cart.total.toLocaleString('vi-VN') + ' VNĐ';
                }
            });
        </script>
    @endpush
@endsection
