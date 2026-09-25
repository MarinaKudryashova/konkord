function scrollActiveCategoryIntoView() {
  const nav = document.querySelector(".categories-nav");
  if (!nav) return;

  const active = nav.querySelector(".categories-nav__link.is-active");
  if (!active) return;

  if (nav.scrollWidth <= nav.clientWidth + 1) return;

  const navRect = nav.getBoundingClientRect();
  const activeRect = active.getBoundingClientRect();
  const target =
    nav.scrollLeft +
    (activeRect.left - navRect.left) -
    (nav.clientWidth - activeRect.width) / 2;

  nav.scrollTo({
    left: Math.max(0, target),
    behavior: "smooth",
  });
}

document.addEventListener("DOMContentLoaded", () => {
  requestAnimationFrame(scrollActiveCategoryIntoView);
  window.addEventListener("load", scrollActiveCategoryIntoView, { once: true });
});
