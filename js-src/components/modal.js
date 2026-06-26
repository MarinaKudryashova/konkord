import GraphModal from "graph-modal";

document.addEventListener("DOMContentLoaded", (event) => {
  const modal = new GraphModal();
  let modals = document.querySelectorAll("[data-graph-target]");

  document.addEventListener(
    "wpcf7mailsent",
    function (response) {
      modals.forEach((mod) => {
        mod.classList.remove("graph-modal-open");
        mod.classList.remove("animate-open");
      });
      new GraphModal().open("modal-send");
    },
    false,
  );
});
