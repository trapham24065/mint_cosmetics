/**
 * TinyMCE Editor Configuration
 */
document.addEventListener('DOMContentLoaded', function () {
  // Kiểm tra xem có textarea với ID description không
  if (document.getElementById('description')) {
    initTinyMCE();
  }
});

function initTinyMCE() {
  // Lấy CSRF token từ meta tag
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  // Lấy upload URL từ data attribute hoặc dùng default
  const uploadUrl = document.getElementById('description')?.dataset.uploadUrl || '/admin/upload-tinymce-image';

  tinymce.init({
    selector: '#description',

    // Plugins
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    // Toolbar
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',

    // Cấu hình chung
    height: 500,
    menubar: 'file edit view insert format tools table help',
    branding: false,
    promotion: false,

    // Cấu hình upload ảnh
    images_upload_url: uploadUrl,
    automatic_uploads: true,

    // Custom upload handler với CSRF token
    images_upload_handler: function (blobInfo, success, failure, progress) {
      uploadImage(blobInfo, success, failure, progress, uploadUrl, csrfToken);
    },

    // TinyComments
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',

    // Merge tags
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],

    // AI Assistant (nếu có)
    ai_request: (request, respondWith) => respondWith.string(() =>
      Promise.reject('See docs to implement AI Assistant')
    ),

    // Uploadcare public key
    uploadcare_public_key: '439ce5a2d363615fe4ce',

    // Content style
    content_style: `
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                font-size: 14px;
                line-height: 1.6;
            }
            img { max-width: 100%; height: auto; }
            table { border-collapse: collapse; width: 100%; }
            table td, table th { border: 1px solid #ddd; padding: 8px; }
        `
  });
}

/**
 * Custom image upload handler
 */
function uploadImage(blobInfo, success, failure, progress, uploadUrl, csrfToken) {
  const xhr = new XMLHttpRequest();
  xhr.withCredentials = false;
  xhr.open('POST', uploadUrl);

  // Thêm CSRF token
  if (csrfToken) {
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  }

  // Progress tracking
  xhr.upload.onprogress = function (e) {
    progress(e.loaded / e.total * 100);
  };

  // Handle response
  xhr.onload = function() {
    if (xhr.status === 403) {
      failure('HTTP Error: ' + xhr.status, { remove: true });
      return;
    }

    if (xhr.status < 200 || xhr.status >= 300) {
      failure('HTTP Error: ' + xhr.status);
      return;
    }

    try {
      const json = JSON.parse(xhr.responseText);

      if (!json || typeof json.location !== 'string') {
        failure('Invalid JSON: ' + xhr.responseText);
        return;
      }

      success(json.location);
    } catch (e) {
      failure('Invalid response: ' + xhr.responseText);
    }
  };

  // Handle errors
  xhr.onerror = function () {
    failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
  };

  // Send form data
  const formData = new FormData();
  formData.append('file', blobInfo.blob(), blobInfo.filename());
  xhr.send(formData);
}

/**
 * Cleanup TinyMCE khi rời trang
 */
window.addEventListener('beforeunload', function() {
  if (tinymce.get('description')) {
    tinymce.get('description').remove();
  }
});
