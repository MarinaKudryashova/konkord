document.addEventListener("DOMContentLoaded", () => {
  const resetFileField = (container) => {
    const fileInput = container.querySelector('input[type="file"]');
    const fileLabel = container.querySelector(".form-field__label--file");
    const defaultText = container.dataset.fileLabelDefault;

    if (fileInput) {
      fileInput.value = "";
    }
    if (fileLabel && defaultText) {
      fileLabel.textContent = defaultText;
    }
  };

  document.querySelectorAll(".form-field__file").forEach((container) => {
    const fileInput = container.querySelector('input[type="file"]');
    const fileLabel = container.querySelector(".form-field__label--file");

    if (!fileInput || !fileLabel) {
      return;
    }

    container.dataset.fileLabelDefault = fileLabel.textContent.trim();

    fileInput.addEventListener("change", function () {
      fileLabel.textContent =
        this.files.length > 0 ? this.files[0].name : container.dataset.fileLabelDefault;
    });
  });

  const resetFormFiles = (form) => {
    if (!form) {
      return;
    }
    form.querySelectorAll(".form-field__file").forEach(resetFileField);
  };

  document.addEventListener("wpcf7mailsent", (event) => {
    resetFormFiles(event.target);
  });

  document.addEventListener("wpcf7reset", (event) => {
    resetFormFiles(event.target);
  });
});
