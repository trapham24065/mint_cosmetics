// Make the addToCart function globally accessible
window.addToCart = function (variantId, quantity, buttonElement) {

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  if (!variantId || !quantity) {
    alert('Could not determine product variant or quantity.');
    return;
  }

  const originalText = buttonElement.innerHTML;
  buttonElement.disabled = true;
  buttonElement.innerHTML = 'Adding...';

  //Variable to save success status
  let isSuccess = false;

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
      isSuccess = true; // Mark as successful
      // Update quantity on cart icon
      const cartCountEl = document.getElementById('cart-count');
      if (cartCountEl) {
        cartCountEl.textContent = data.cartCount > 0 ? `(${data.cartCount})` : '';
      }

      // Fill in the modal
      const modalImage = document.getElementById('modal-product-image');
      const modalProductName = document.getElementById('modal-product-name');
      const imageUrl = data.addedItem.image ? `/storage/${data.addedItem.image}` : '/assets/storefront/images/shop/modal1.webp';

      if (modalImage) modalImage.src = imageUrl;
      if (modalProductName) modalProductName.textContent = data.addedItem.product_name;

    } else {
      // If there is an error from the server, display a message
      Swal.fire('Error', data.message || 'Could not add product to cart.', 'error');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    Swal.fire('Error', 'An unexpected error occurred.', 'error');
  })
  .finally(() => {
    // Restore button state
    buttonElement.disabled = false;
    buttonElement.innerHTML = originalText;

    // SHOW MODAL ONLY IF SUCCESSFUL
    if (isSuccess) {
      const cartModalEl = document.getElementById('action-CartAddModal');
      const cartModal = bootstrap.Modal.getInstance(cartModalEl) || new bootstrap.Modal(cartModalEl);
      cartModal.show();

      setTimeout(() => {
        cartModal.hide();
      }, 2000);
    }
  });
};
