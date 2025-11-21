/**
 * Admin CRUD Utilities
 * Reusable functions for Create, Read, Update, Delete operations
 */

const AdminCRUD = {
  /**
   * Initialize delete handlers for a specific selector
   * @param {string} selector - CSS selector for delete buttons (default: '.delete-item')
   * @param {object} options - Configuration options
   */
  initDeleteHandler(selector = '.delete-item', options = {}) {
    const defaults = {
      confirmTitle: 'Are you sure?',
      confirmText: 'You won\'t be able to revert this!',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      successTitle: 'Deleted!',
      successText: 'Item has been deleted successfully.',
      errorTitle: 'Error!',
      errorText: 'An error occurred while deleting.',
      onSuccess: () => location.reload(),
      onError: null,
      useSwal: typeof Swal !== 'undefined'
    };

    const config = { ...defaults, ...options };

    document.addEventListener('click', function(e) {
      const deleteBtn = e.target.closest(selector);
      if (!deleteBtn) return;

      e.preventDefault();

      const itemId = deleteBtn.dataset.id;
      const itemName = deleteBtn.dataset.name || 'this item';
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
    if (config.useSwal) {
      Swal.fire({
        title: config.confirmTitle,
        html: `${config.confirmText}<br><strong>${itemName}</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: config.confirmButtonText,
        cancelButtonText: config.cancelButtonText
      }).then((result) => {
        if (result.isConfirmed) {
          AdminCRUD.performDelete(deleteUrl, itemName, config);
        }
      });
    } else {
      if (confirm(`Are you sure you want to delete: ${itemName}?`)) {
        AdminCRUD.performDelete(deleteUrl, itemName, config);
      }
    }
  },

  /**
   * Perform the actual delete operation
   */
  performDelete(deleteUrl, itemName, config) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
      console.error('CSRF token not found');
      return;
    }

    // Show loading
    if (config.useSwal) {
      Swal.fire({
        title: 'Deleting...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }

    fetch(deleteUrl, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    })
    .then(response => {
      if (!response.ok) {
        return response.json().then(err => {
          throw new Error(err.message || `HTTP Error: ${response.status}`);
        });
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        if (config.useSwal) {
          Swal.fire({
            icon: 'success',
            title: config.successTitle,
            text: data.message || config.successText,
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            if (config.onSuccess) config.onSuccess(data);
          });
        } else {
          alert(data.message || config.successText);
          if (config.onSuccess) config.onSuccess(data);
        }
      } else {
        throw new Error(data.message || 'Delete operation failed');
      }
    })
    .catch(error => {
      console.error('Delete Error:', error);

      if (config.useSwal) {
        Swal.fire({
          icon: 'error',
          title: config.errorTitle,
          text: error.message || config.errorText
        });
      } else {
        alert(error.message || config.errorText);
      }

      if (config.onError) config.onError(error);
    });
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
            title: 'No items selected',
            text: 'Please select at least one item to delete.'
          });
        } else {
          alert('Please select at least one item to delete.');
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
        title: 'Delete Multiple Items?',
        html: `You are about to delete <strong>${ids.length}</strong> item(s).`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete them!'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Deleting...',
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
                text: data.message || 'Items deleted successfully.',
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
