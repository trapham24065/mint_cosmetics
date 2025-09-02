// Make the addToCart function globally accessible
window.addToCart = function (variantId, quantity, buttonElement) {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  console.log('Add to cart called with:', { variantId, quantity, buttonElement });

  if (!variantId || !quantity) {
    console.error('Missing variantId or quantity:', { variantId, quantity });
    alert('Could not determine product variant or quantity.');
    return;
  }

  // Disable button and show loading state
  const originalText = buttonElement.innerHTML;
  buttonElement.disabled = true;
  buttonElement.innerHTML = 'Adding...';

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
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Update cart count in the header
        const cartCountEl = document.getElementById('cart-count');
        if (cartCountEl) {
          cartCountEl.textContent = data.cartCount > 0 ? `${data.cartCount}` : '';
        }

        // Populate and show the success modal
        const modalImage = document.getElementById('modal-product-image');
        const modalProductName = document.getElementById('modal-product-name');
        const imageUrl = data.addedItem.image
          ? `/storage/${data.addedItem.image}`
          : '/assets/storefront/images/shop/modal1.webp';

        if (modalImage) modalImage.src = imageUrl;
        if (modalProductName) modalProductName.textContent = data.addedItem.product_name;
          document.querySelectorAll('.modal.show').forEach(openModal => {
              const instance = bootstrap.Modal.getInstance(openModal);
              if (instance) instance.hide();
          });

          const cartModalEl = document.getElementById('action-CartAddModal');
          let cartModal = bootstrap.Modal.getInstance(cartModalEl);
          if (!cartModal) {
              cartModal = new bootstrap.Modal(cartModalEl);
          }
          cartModal.show();
      } else {
        Swal.fire('Error', data.message || 'Could not add product to cart.', 'error');
      }
    })
    .catch((error) => {
      console.error('Error:', error);
      Swal.fire('Error', 'An unexpected error occurred.', 'error');
    })
    .finally(() => {
      // Restore button state
      buttonElement.disabled = false;
      buttonElement.innerHTML = originalText;
    });
};
