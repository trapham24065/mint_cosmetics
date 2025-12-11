/**
 * TinyMCE Editor Configuration
 */
document.addEventListener('DOMContentLoaded', function () {
  // Check if there is a textarea with ID description
  if (document.getElementById('description')) {
    initTinyMCE();
  }
});

function initTinyMCE() {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const uploadUrl = document.getElementById('description')?.dataset.uploadUrl || '/admin/products/upload-tinymce-image';

  tinymce.init({
    selector: '#description',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    height: 500,
    menubar: 'file edit view insert format tools table help',
    branding: false,
    promotion: false,
    automatic_uploads: true,


    images_upload_handler: function (blobInfo, progress) {
      return new Promise(function (resolve, reject) {
        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        // fetch với CSRF header
        fetch(uploadUrl, {
          method: 'POST',
          headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {},
          body: formData,
          credentials: 'same-origin'
        })
        .then(function (response) {
          if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
          }
          return response.json();
        })
        .then(function (json) {
          if (json && typeof json.location === 'string') {
            resolve(json.location);
          } else {
            reject('Invalid JSON response from server: ' + JSON.stringify(json));
          }
        })
        .catch(function (err) {
          reject('Image upload failed: ' + err.message);
        });
      });
    },

    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],

    content_style: `
      body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; line-height: 1.6; }
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

  // Add CSRF token
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
 * Clean up TinyMCE on page exit
 */
window.addEventListener('beforeunload', function() {
  if (tinymce.get('description')) {
    tinymce.get('description').remove();
  }
});
