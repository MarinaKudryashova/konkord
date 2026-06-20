const fileInput = document.getElementById("file-attachment");
const fileLabel = document.querySelector('label[for="file-attachment');

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
   fileInput.addEventListener('change', () => {
     if(fileInput.files.length > 0) {
      fileLabel.textContent = fileInput.files[0].name;
     } else {
      fileLabel.textContent = defaultText;
     }
   })
})