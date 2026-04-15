window.initSingleImagePreview = function initSingleImagePreview(config) {
  const input = document.getElementById(config.inputId);
  const wrapper = document.getElementById(config.previewWrapperId);
  const previewImage = document.getElementById(config.previewImageId);
  const previewName = document.getElementById(config.previewNameId);
  const removeBtn = document.getElementById(config.removeButtonId);
  const removeFlagInput = config.removeFlagInputId
    ? document.getElementById(config.removeFlagInputId)
    : null;

  if (!input || !wrapper || !previewImage || !previewName || !removeBtn) {
    return;
  }

  const currentImageUrl = (config.currentImageUrl || "").trim();
  const currentImageName = (config.currentImageName || "").trim();
  const removeCurrentLabel = config.removeCurrentLabel || "Xóa ảnh hiện tại";
  const removeSelectedLabel = config.removeSelectedLabel || "Xóa ảnh đã chọn";
  let selectedObjectUrl = null;

  function revokeSelectedUrl() {
    if (selectedObjectUrl) {
      URL.revokeObjectURL(selectedObjectUrl);
      selectedObjectUrl = null;
    }
  }

  function showPreview(imageUrl, imageName, isCurrent) {
    previewImage.src = imageUrl || "#";
    previewName.textContent = imageName || "";
    previewName.title = imageName || "";
    removeBtn.textContent = isCurrent ? removeCurrentLabel : removeSelectedLabel;
    wrapper.classList.remove("d-none");
  }

  function hidePreview() {
    previewImage.src = "#";
    previewName.textContent = "";
    previewName.title = "";
    wrapper.classList.add("d-none");
  }

  function restoreCurrent() {
    revokeSelectedUrl();
    input.value = "";

    if (removeFlagInput) {
      removeFlagInput.value = "0";
    }

    if (currentImageUrl) {
      showPreview(currentImageUrl, currentImageName, true);
    } else {
      hidePreview();
    }
  }

  input.addEventListener("change", function () {
    const file = this.files && this.files[0] ? this.files[0] : null;

    if (!file || !file.type.startsWith("image/")) {
      restoreCurrent();
      return;
    }

    revokeSelectedUrl();
    selectedObjectUrl = URL.createObjectURL(file);

    if (removeFlagInput) {
      removeFlagInput.value = "0";
    }

    showPreview(selectedObjectUrl, file.name, false);
  });

  removeBtn.addEventListener("click", function () {
    const hasSelectedFile = !!(input.files && input.files.length > 0);

    if (hasSelectedFile) {
      restoreCurrent();
      return;
    }

    if (currentImageUrl) {
      if (removeFlagInput) {
        removeFlagInput.value = "1";
      }
      hidePreview();
      return;
    }

    restoreCurrent();
  });

  restoreCurrent();
};
