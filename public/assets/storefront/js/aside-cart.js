// --- START: ASIDE CART LOGIC ---
document.addEventListener('DOMContentLoaded', function () {
    const asideCart = document.getElementById('AsideOffcanvasCart');
    const asideCartList = document.getElementById('aside-cart-product-list');
    const asideCartSubtotal = document.getElementById('aside-cart-subtotal');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!asideCart) return;

    /**
     * Fetches cart data from the server and updates the aside cart UI.
     */
    async function updateAsideCart() {
        try {
            // Use a static URL because this is a .js file
            const response = await fetch('/cart/contents');
            if (!response.ok) return;

            const cartData = await response.json();

            // Clear old items
            asideCartList.innerHTML = '';

            // Render new items
            if (Object.keys(cartData.items).length > 0) {
                for (const key in cartData.items) {
                    const item = cartData.items[key];
                    const itemHtml = `
                        <li class="aside-product-list-item">
                                <a href="javascript:void(0);" class="remove action-btn-remove-cart-item" data-variant-id="${item.variant_id}">×</a>                            <a href="/products/${item.slug}">
                                <img src="${item.image ? '/storage/' + item.image : '/assets/storefront/images/shop/cart1.webp'}" width="68" height="84" alt="${item.product_name}">
                                <span class="product-title">${item.product_name}</span>
                            </a>
                            <span class="product-price">${item.quantity} × ${item.price.toLocaleString('vi-VN')} VNĐ</span>
                        </li>
                    `;
                    asideCartList.insertAdjacentHTML('beforeend', itemHtml);
                }
            } else {
                asideCartList.innerHTML = '<li class="aside-product-list-item text-center">Your cart is empty.</li>';
            }

            // Update subtotal
            asideCartSubtotal.textContent = cartData.subtotal.toLocaleString('vi-VN') + ' VNĐ';

        } catch (error) {
            console.error("Failed to update aside cart:", error);
        }
    }

    // Make the function globally accessible so other scripts can call it
    window.updateAsideCart = updateAsideCart;

    // Listen for the event when the aside cart is about to be shown
    asideCart.addEventListener('show.bs.offcanvas', function () {
        updateAsideCart();
    });
    asideCart.addEventListener('click', function(event) {
        const removeButton = event.target.closest('.action-btn-remove-cart-item');
        if (removeButton) {
            event.preventDefault();
            const variantId = removeButton.dataset.variantId;

            Swal.fire({
                title: 'Are you sure?',
                text: "Remove this item from your cart?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    handleRemoveItem(variantId);
                }
            });
        }
    });
    async function handleRemoveItem(variantId) {
        try {
            const response = await fetch(`/cart/remove/${variantId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) throw new Error('Server responded with an error.');

            const data = await response.json();

            if (data.success) {
                // Update mini cart
                updateAsideCart();

                // Update quantity on header
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) {
                    const newCount = Object.keys(data.cart.items).length;
                    cartCountEl.textContent = newCount > 0 ? `${newCount}` : '';
                }
            } else {
                Swal.fire('Error', 'Could not remove the item.', 'error');
            }
        } catch (error) {
            console.error('Remove item error:', error);
            Swal.fire('Error', 'An unexpected error occurred.', 'error');
        }
    }
});
