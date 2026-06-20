// const fileInput = document.getElementById("file-attachment");
// const fileLabel = document.querySelector('label[for="file-attachment"]');

// const defaultText = fileLabel.textContent;

// document.addEventListener('DOMContentLoaded', () => {
//    fileInput.addEventListener('change', () => {
//      if(fileInput.files.length > 0) {
//       fileLabel.textContent = fileInput.files[0].name;
//      } else {
//       fileLabel.textContent = defaultText;
//      }
//    })
// })

document.addEventListener('DOMContentLoaded', () => {
  
  // Bannerform
  const bannerForm = document.querySelector('.bannerform');
  if (bannerForm) {
    const input = bannerForm.querySelector('.form-field__input-file');
    const label = bannerForm.querySelector('.form-field__label--file');

    const defaultText = label.textContent;

    input.addEventListener('change', () => {
      label.textContent = input.files[0]?.name || defaultText;
    });
  }

  // Modalform
  const modalForm = document.querySelector('[data-graph-target="modalform"]');
  if (modalForm) {
    const input = modalForm.querySelector('.form-field__input-file');
    const label = modalForm.querySelector('.form-field__label--file');

    const defaultText = label.textContent;

    input.addEventListener('change', () => {
      label.textContent = input.files[0]?.name || defaultText;
    });
  }

  // Modalleadform
  const leadForm = document.querySelector('[data-graph-target="modal-leadform"]');
  if (leadForm) {
    const input = leadForm.querySelector('.form-field__input-file');
    const label = leadForm.querySelector('.form-field__label--file');

    const defaultText = label.textContent;

    input.addEventListener('change', () => {
      label.textContent = input.files[0]?.name || defaultText;
    });
  }

});