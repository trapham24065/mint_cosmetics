/**
 * Admin CRUD Utilities
 * Reusable functions for Create, Read, Update, Delete operations
 */

let AdminCRUD = {
  /**
   * Initialize delete handlers for a specific selector
   * @param {string} selector - CSS selector for delete buttons (default: '.delete-item')
   * @param {object} options - Configuration options
   */
  initDeleteHandler(selector = '.delete-item', options = {}) {
    const defaults = {
      confirmTitle: 'Xác nhận xóa',
      confirmText: 'Bạn có chắc muốn xóa mục này?',
      confirmButtonText: 'Xóa',
      cancelButtonText: 'Hủy'
    };

    const config = { ...defaults, ...options };

    document.addEventListener('click', function(e) {
      const deleteBtn = e.target.closest(selector);
      if (!deleteBtn) return;

      e.preventDefault();

      const itemId = deleteBtn.dataset.id;
      const itemName = deleteBtn.dataset.name || 'vật phẩm này';
      const deleteUrl = deleteBtn.dataset.url;

      if (!deleteUrl) {
        console.error('Delete URL not found. Add data-url attribute.');
        return;
      }

      AdminCRUD.confirmDelete(deleteUrl, itemName, config);
    });
  },

  /**
   * Show confirmation dialog and delete if confirmed
   */
  confirmDelete(deleteUrl, itemName, config) {
    const modal = AdminCRUD.ensureDeleteModal();
    if (!modal) {
      return;
    }

    const titleEl = document.getElementById('admin-delete-modal-title');
    const textEl = document.getElementById('admin-delete-modal-text');
    const nameEl = document.getElementById('admin-delete-item-name');
    const confirmBtn = document.getElementById('admin-delete-confirm-btn');

    if (titleEl) titleEl.textContent = config.confirmTitle;
    if (textEl) textEl.textContent = config.confirmText;
    if (nameEl) nameEl.textContent = itemName;
    if (confirmBtn) confirmBtn.textContent = config.confirmButtonText;

    modal.show();

    if (confirmBtn) {
      confirmBtn.onclick = function () {
        modal.hide();
        AdminCRUD.performDelete(deleteUrl);
      };
    }
  },

  /**
   * Perform the actual delete operation
   */
  performDelete(deleteUrl) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
      console.error('CSRF token not found');
      return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = deleteUrl;

    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = csrfToken;
    form.appendChild(tokenInput);

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);

    document.body.appendChild(form);
    form.submit();
  },

  ensureDeleteModal() {
    const modalEl = document.getElementById('adminDeleteConfirmModal');
    if (!modalEl || typeof bootstrap === 'undefined') {
      return null;
    }

    return bootstrap.Modal.getOrCreateInstance(modalEl);
  },

  /**
   * Initialize bulk delete handler for checkboxes
   * @param {string} checkboxSelector - Selector for checkboxes
   * @param {string} bulkDeleteBtnSelector - Selector for bulk delete button
   * @param {string} deleteUrl - URL endpoint for bulk delete
   */
  initBulkDelete(checkboxSelector = '.gridjs-checkbox-row', bulkDeleteBtnSelector = '#bulk-delete-btn', deleteUrl) {
    const bulkDeleteBtn = document.querySelector(bulkDeleteBtnSelector);
    if (!bulkDeleteBtn) return;

    bulkDeleteBtn.addEventListener('click', function(e) {
      e.preventDefault();

      const selectedIds = [];
      document.querySelectorAll(`${checkboxSelector}:checked`).forEach(cb => {
        selectedIds.push(cb.dataset.id);
      });

      if (selectedIds.length === 0) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'warning',
            title: 'Chưa có mục nào được chọn.',
            text: 'Vui lòng chọn ít nhất một mục để xóa..'
          });
        } else {
          alert('Vui lòng chọn ít nhất một mục để xóa.');
        }
        return;
      }

      AdminCRUD.bulkDelete(deleteUrl, selectedIds);
    });
  },

  /**
   * Perform bulk delete
   */
  bulkDelete(deleteUrl, ids) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Xóa nhiều mục?',
        html: `Bạn sắp xóa <strong>${ids.length}</strong> mục.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Đúng vậy, hãy xóa chúng đi!'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Đang xóa...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
          });

          fetch(deleteUrl, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ids })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: data.message || 'Các mục đã được xóa thành công.',
                timer: 2000
              }).then(() => location.reload());
            } else {
              throw new Error(data.message);
            }
          })
          .catch(error => {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: error.message
            });
          });
        }
      });
    }
  },

  /**
   * Initialize "select all" checkbox functionality
   */
  initSelectAll(selectAllSelector = '.gridjs-checkbox-all', rowCheckboxSelector = '.gridjs-checkbox-row') {
    document.addEventListener('change', function(e) {
      const selectAll = e.target.closest(selectAllSelector);
      if (!selectAll) return;

      const isChecked = selectAll.checked;
      document.querySelectorAll(rowCheckboxSelector).forEach(cb => {
        cb.checked = isChecked;
      });
    });

    // Update "select all" when individual checkboxes change
    document.addEventListener('change', function(e) {
      const rowCheckbox = e.target.closest(rowCheckboxSelector);
      if (!rowCheckbox) return;

      const allCheckboxes = document.querySelectorAll(rowCheckboxSelector);
      const checkedCheckboxes = document.querySelectorAll(`${rowCheckboxSelector}:checked`);
      const selectAllCheckbox = document.querySelector(selectAllSelector);

      if (selectAllCheckbox) {
        selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
      }
    });
  },

  /**
   * Show toast notification
   */
  toast(message, type = 'success') {
    if (typeof Swal !== 'undefined') {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      });

      Toast.fire({
        icon: type,
        title: message
      });
    } else {
      alert(message);
    }
  },

  /**
   * Confirm action with custom callback
   */
  confirm(title, text, onConfirm, options = {}) {
    const defaults = {
      icon: 'warning',
      confirmButtonText: 'Yes',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33'
    };

    const config = { ...defaults, ...options };

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title,
        text,
        icon: config.icon,
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
        cancelButtonText: config.cancelButtonText
      }).then((result) => {
        if (result.isConfirmed && onConfirm) {
          onConfirm();
        }
      });
    } else {
      if (confirm(`${title}\n${text}`)) {
        if (onConfirm) onConfirm();
      }
    }
  }
};

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = AdminCRUD;
}

// Make available globally
window.AdminCRUD = AdminCRUD;
