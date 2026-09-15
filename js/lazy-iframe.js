document.addEventListener("DOMContentLoaded", () => {
  const iframes = document.querySelectorAll("iframe.js-lazy-iframe[data-src]");
  if (!iframes.length || !("IntersectionObserver" in window)) {
    iframes.forEach((frame) => {
      const src = frame.getAttribute("data-src");
      if (src) {
        frame.src = src;
      }
    });
    return;
  }

  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const frame = entry.target;
        const src = frame.getAttribute("data-src");
        if (src) {
          frame.src = src;
          frame.removeAttribute("data-src");
        }
        io.unobserve(frame);
      });
    },
    {
      rootMargin: "200px 0px",
      threshold: 0.01,
    },
  );

  iframes.forEach((frame) => io.observe(frame));
});
