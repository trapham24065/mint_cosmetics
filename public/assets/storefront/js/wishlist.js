document.addEventListener('DOMContentLoaded', function() {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Update status icon heard when the page is downloaded
  async function updateWishlistIcons() {
    try {
      const response = await fetch('/wishlist/ids');
      const wishlistedIds = await response.json();
      document.querySelectorAll('.action-btn-wishlist').forEach(button => {
        const productId = button.dataset.productId;
        const icon = button.querySelector('i');
        if (wishlistedIds.includes(parseInt(productId))) {
          icon.classList.replace('fa-heart-o', 'fa-heart');
          button.classList.add('active');
        } else {
          icon.classList.replace('fa-heart', 'fa-heart-o');
          button.classList.remove('active');
        }
      });
    } catch (error) { console.error('Could not fetch wishlist IDs:', error); }
  }
  updateWishlistIcons();

  // listen to general events
  document.body.addEventListener('click', async function(event) {
    const wishlistButton = event.target.closest('.action-btn-wishlist');
    if (wishlistButton) {
      event.preventDefault();
      const productId = wishlistButton.dataset.productId;
      if (!productId) return;

      try {
        const response = await fetch('/wishlist/toggle', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ product_id: productId })
        });
        const data = await response.json();

        if (data.success) {
          updateWishlistIcons();

          const wishlistCountEl = document.getElementById('wishlist-count');
          if (wishlistCountEl) {
            wishlistCountEl.textContent = data.count > 0 ? `${data.count}` : '';
          }

          if (document.body.classList.contains('page-wishlist')) {
            wishlistButton.closest('.tbody-item').remove();
          }
          const modalImage = document.getElementById('modal-wishlist-image');
          const modalProductName = document.getElementById('modal-product-name');
          const imageUrl = data.addedItem.image ? `/storage/${data.addedItem.image}` : '/assets/storefront/images/shop/modal1.webp';

          if (modalImage) modalImage.src = imageUrl;
          if (modalProductName) modalProductName.textContent = data.addedItem.product_name;

          const wishlistModalEl = document.getElementById('action-WishlistModal');
          const wishlistModal = bootstrap.Modal.getInstance(wishlistModalEl) || new bootstrap.Modal(wishlistModalEl);
          wishlistModal.show();

          setTimeout(() => {
            wishlistModal.hide();
          }, 2000);
        }
      } catch (error) { console.error('Wishlist toggle error:', error); }
    }
  });
});
