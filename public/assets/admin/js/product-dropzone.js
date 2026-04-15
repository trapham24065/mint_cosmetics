if (typeof window.Dropzone !== "undefined") {
  window.Dropzone.autoDiscover = false;
}

document.addEventListener("DOMContentLoaded", function () {
  if (typeof Dropzone === "undefined") {
    console.error("Dropzone chưa được tải");
    return;
  }

  Dropzone.autoDiscover = false;

  const element = document.querySelector("#product-dropzone");
  if (!element) {
    return;
  }

  if (element.dropzone && typeof element.dropzone.destroy === "function") {
    element.dropzone.destroy();
  }

  const previewTemplateNode = document.querySelector("#dropzone-preview-template .dz-preview");
  const previewTemplate = previewTemplateNode ? previewTemplateNode.outerHTML : undefined;

  const csrfMeta = document.querySelector('meta[name="csrf-token"]');
  const listImageInput = document.querySelector("#list_image");
  const uploadedFiles = [];
  const messageNode = element.querySelector(".dz-message");
  const overlayInput = document.querySelector("#dropzone-file-input");
  const existingImagesRaw = element.getAttribute("data-existing-images");

  if (listImageInput && listImageInput.value) {
    try {
      const initialPaths = JSON.parse(listImageInput.value);
      if (Array.isArray(initialPaths)) {
        initialPaths.forEach(function (path) {
          if (typeof path === "string" && path.trim() !== "") {
            uploadedFiles.push(path);
          }
        });
      }
    } catch (_e) {
      // Keep empty initial list when existing value is malformed JSON.
    }
  }

  function syncHiddenList() {
    if (listImageInput) {
      listImageInput.value = JSON.stringify(uploadedFiles);
    }
  }

  function syncMessageVisibility(dropzoneInstance) {
    if (!messageNode) {
      return;
    }

    const activeFiles = (dropzoneInstance.files || []).filter(function (f) {
      return f.status !== "canceled";
    });

    messageNode.style.display = activeFiles.length > 0 ? "none" : "block";
  }

  function ensureRemoveButton(file, dropzoneInstance) {
    if (!file.previewElement) {
      return;
    }

    const dzImage = file.previewElement.querySelector(".dz-image");
    const dzThumb = file.previewElement.querySelector("[data-dz-thumbnail]");
    if (dzImage) {
      dzImage.style.width = "120px";
      dzImage.style.height = "120px";
      dzImage.style.overflow = "hidden";
      dzImage.style.borderRadius = "0.75rem";
      dzImage.style.background = "#f8f9fa";
      dzImage.style.display = "flex";
      dzImage.style.alignItems = "center";
      dzImage.style.justifyContent = "center";
    }
    if (dzThumb) {
      dzThumb.style.width = "100%";
      dzThumb.style.height = "100%";
      dzThumb.style.objectFit = "cover";
      dzThumb.style.display = "block";
    }

    file.previewElement.style.pointerEvents = "auto";
    file.previewElement.style.position = "relative";
    file.previewElement.style.zIndex = "3";
    file.previewElement.style.width = "120px";

    const nameNode = file.previewElement.querySelector("[data-dz-name]");
    if (nameNode) {
      nameNode.style.display = "block";
      nameNode.style.maxWidth = "120px";
      nameNode.style.whiteSpace = "nowrap";
      nameNode.style.overflow = "hidden";
      nameNode.style.textOverflow = "ellipsis";
      if (file.name) {
        nameNode.setAttribute("title", file.name);
      }
    }

    const sizeNode = file.previewElement.querySelector("[data-dz-size]");
    if (sizeNode) {
      sizeNode.style.display = "block";
    }

    let removeBtn = file.previewElement.querySelector("[data-dz-remove]");
    if (!removeBtn) {
      removeBtn = document.createElement("button");
      removeBtn.type = "button";
      removeBtn.textContent = "Xóa";
      removeBtn.setAttribute("data-dz-remove", "");
      removeBtn.className = "btn btn-sm btn-danger dz-remove mt-1";
      file.previewElement.appendChild(removeBtn);
    }

    removeBtn.style.pointerEvents = "auto";
    removeBtn.style.position = "relative";
    removeBtn.style.zIndex = "4";

    if (removeBtn.dataset.bound !== "1") {
      removeBtn.addEventListener("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
        dropzoneInstance.removeFile(file);
      });
      removeBtn.dataset.bound = "1";
    }
  }

  function wireOverlayInput(dropzoneInstance) {
    if (!overlayInput || overlayInput.dataset.bound === "1") {
      return;
    }

    overlayInput.addEventListener("change", function () {
      const files = Array.from(this.files || []);
      files.forEach(function (file) {
        dropzoneInstance.addFile(file);
      });

      this.value = "";
    });

    overlayInput.dataset.bound = "1";
  }

  function bindDropzoneClickOpenPicker() {
    if (!overlayInput || element.dataset.clickBound === "1") {
      return;
    }

    element.style.cursor = "pointer";
    element.addEventListener("click", function (event) {
      if (event.target.closest(".dz-preview, [data-dz-remove], button, a, input, label")) {
        return;
      }

      event.preventDefault();
      overlayInput.click();
    });

    element.dataset.clickBound = "1";
  }

  function preloadExistingPreviews(dropzoneInstance) {
    if (!existingImagesRaw) {
      return;
    }

    let existingImages = [];
    try {
      const parsed = JSON.parse(existingImagesRaw);
      existingImages = Array.isArray(parsed) ? parsed : [];
    } catch (_e) {
      existingImages = [];
    }

    function normalizePath(path) {
      return String(path || "")
        .replace(/\\/g, "/")
        .replace(/^\/?storage\//, "")
        .trim();
    }

    function toStorageUrl(path) {
      const normalized = normalizePath(path);
      if (!normalized) {
        return null;
      }
      return window.location.origin + "/storage/" + encodeURI(normalized);
    }

    existingImages.forEach(function (image, index) {
      const rawPath = typeof image === "string"
        ? image
        : (image && typeof image.path === "string" ? image.path : null);
      const path = normalizePath(rawPath);
      const url = toStorageUrl(path);

      if (!path || !url) {
        return;
      }

      if (!uploadedFiles.includes(path)) {
        uploadedFiles.push(path);
      }

      const fileName = (image && typeof image.name === "string" && image.name.trim() !== "")
        ? image.name
        : (path.split("/").pop() || ("image-" + (index + 1)));
      const fileSize = (image && typeof image.size === "number" && image.size > 0) ? image.size : 1;

      const mockFile = {
        name: fileName,
        size: fileSize,
        accepted: true,
        status: Dropzone.SUCCESS,
        uploadedName: path,
      };

      if (typeof dropzoneInstance.displayExistingFile === "function") {
        dropzoneInstance.displayExistingFile(mockFile, url, null, "anonymous", false);
      } else {
        dropzoneInstance.emit("addedfile", mockFile);
        dropzoneInstance.emit("thumbnail", mockFile, url);
      }
      dropzoneInstance.emit("complete", mockFile);
      if (!dropzoneInstance.files.includes(mockFile)) {
        dropzoneInstance.files.push(mockFile);
      }

      ensureRemoveButton(mockFile, dropzoneInstance);
    });

    syncHiddenList();
    syncMessageVisibility(dropzoneInstance);
  }

  const uploadUrl = element.getAttribute("data-upload-url") || element.getAttribute("action") || "/admin/products/upload-image";

  const dropzone = new Dropzone(element, {
    url: uploadUrl,
    method: "post",
    paramName: "file",
    previewsContainer: "#dropzone-preview",
    previewTemplate: previewTemplate,
    clickable: false,
    addRemoveLinks: false,
    thumbnailWidth: 120,
    thumbnailHeight: 120,
    headers: csrfMeta ? { "X-CSRF-TOKEN": csrfMeta.content } : {},
    init: function () {
      this.on("addedfile", function (file) {
        ensureRemoveButton(file, this);
        syncMessageVisibility(this);
      });

      this.on("success", function (file, response) {
        const uploadedName = (response && (response.file_name || response.filename || response.path || response.url)) || null;
        file.uploadedName = uploadedName;

        if (uploadedName) {
          uploadedFiles.push(uploadedName);
          syncHiddenList();
        }

        syncMessageVisibility(this);
      });

      this.on("removedfile", function (file) {
        if (!file.uploadedName) {
          syncMessageVisibility(this);
          return;
        }

        const index = uploadedFiles.indexOf(file.uploadedName);
        if (index > -1) {
          uploadedFiles.splice(index, 1);
          syncHiddenList();
        }

        syncMessageVisibility(this);
      });

      this.on("error", function (_file, message) {
        console.error("Lỗi upload ảnh:", message);
      });

      syncMessageVisibility(this);
    }
  });

  wireOverlayInput(dropzone);
  bindDropzoneClickOpenPicker();
  preloadExistingPreviews(dropzone);

  if (messageNode) {
    messageNode.style.position = "relative";
    messageNode.style.zIndex = "1";
    messageNode.style.pointerEvents = "none";
  }

  element.style.position = "relative";
  element.style.pointerEvents = "auto";
  syncMessageVisibility(dropzone);
  syncHiddenList();
});
