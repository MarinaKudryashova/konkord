document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".form-field__file").forEach((container, index) => {
    const fileInput = container.querySelector('input[type="file"]');
    const fileLabel = container.querySelector(".form-field__label--file");
    const form = container.closest("form");

    if (fileInput && fileLabel) {
      const defaultText = fileLabel.textContent;

      fileInput.addEventListener("change", function () {
        fileLabel.textContent = this.files.length > 0 ? this.files[0].name : defaultText;
      });

      if (form) {
        form.addEventListener("submit", function (e) {
          fileInput.value = "";
          fileLabel.textContent = defaultText;
        });
      }
    }
  });
});
