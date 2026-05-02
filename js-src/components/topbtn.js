function initTopButton() {
  const btnTop = document.querySelector(".topbtn");
  if (!btnTop) return;

  const trigger = document.createElement("div");
  trigger.style.position = "absolute";
  trigger.style.top = "100vh";
  trigger.style.height = "1px";
  trigger.style.pointerEvents = "none";
  document.body.appendChild(trigger);

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        btnTop.classList.toggle("is-visible", !entry.isIntersecting);
      });
    },
    {
      root: null,
      rootMargin: "0px",
      threshold: 0,
    },
  );

  observer.observe(trigger);

  btnTop.addEventListener("click", () => {
    window.scroll({
      top: 0,
      behavior: "smooth",
    });
  });

  return () => {
    observer.disconnect();
    if (trigger.parentNode) {
      trigger.parentNode.removeChild(trigger);
    }
  };
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initTopButton);
} else {
  initTopButton();
}
