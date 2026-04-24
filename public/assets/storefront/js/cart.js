// Make the addToCart function globally accessible
window.addToCart = function (variantId, quantity, buttonElement) {

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer);
      toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
  });

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  if (!variantId || !quantity) {
    Toast.fire({
      icon: 'error',
      title: 'Vui lòng chọn các tùy chọn sản phẩm trước.',
    });
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
      const imageUrl = data.addedItem.image ? `/storage/${data.addedItem.image}` : '/assets/storefront/images/shop/default.webp';

      if (modalImage) modalImage.src = imageUrl;
      if (modalProductName) modalProductName.textContent = data.addedItem.product_name;

    } else {
      // If there is an error from the server, display a message
      Toast.fire({
        icon: 'error',
        title: data.message || 'Không thể thêm sản phẩm vào giỏ hàng.',
      });
    }
  })
  .catch(error => {
    console.error('Error:', error);
    Toast.fire({
      icon: 'error',
      title: 'Đã xảy ra lỗi không mong muốn.',
    });
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
