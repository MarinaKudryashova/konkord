import GraphModal from "graph-modal";
import { showSuccessMessage, showErrorMessage } from "./message_new.js";

function initFormHandlers() {
  const modal = new GraphModal();
  let modals = document.querySelectorAll("[data-graph-target]");

  function closeAllModals() {
    modals.forEach((mod) => {
      mod.classList.remove("graph-modal-open");
      mod.classList.remove("animate-open");
    });
    modal.close();
  }

  // Обработчики событий Contact Form 7
  document.addEventListener(
    "wpcf7mailsent",
    function (response) {
      const formElement = response.target;

      if (formElement.closest("[data-graph-target]")) {
        closeAllModals();
      }

      setTimeout(() => {
        showSuccessMessage();
      }, 300);
    },
    false,
  );

  document.addEventListener(
    "wpcf7mailfailed",
    function (response) {
      const formElement = response.target;

      if (formElement.closest("[data-graph-target]")) {
        closeAllModals();
      }

      setTimeout(() => {
        showErrorMessage();
      }, 300);
    },
    false,
  );

  document.addEventListener(
    "wpcf7invalid",
    function (response) {
      showErrorMessage("Пожалуйста, проверьте правильность заполнения формы");
    },
    false,
  );
}

// Универсальный запуск
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initFormHandlers);
} else {
  initFormHandlers();
}
