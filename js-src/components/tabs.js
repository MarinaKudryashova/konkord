document.addEventListener("DOMContentLoaded", function () {
  const container = document.querySelector('[data-tabs="contacts-tabs"]');
  if (!container) return;

  const btns = container.querySelectorAll(".tabs__nav-btn");
  const panels = container.querySelectorAll(".tabs__panel");

  function switchTab(index) {
    btns.forEach((btn) => btn.classList.remove("tabs__nav-btn--active"));
    if (btns[index]) btns[index].classList.add("tabs__nav-btn--active");

    panels.forEach((panel) => panel.classList.remove("tabs__panel--active"));
    if (panels[index]) panels[index].classList.add("tabs__panel--active");
  }

  btns.forEach((btn, index) => {
    btn.addEventListener("click", () => switchTab(index));
  });
});
