document.addEventListener("DOMContentLoaded", () => {
  const links = document.querySelectorAll(".select_geo_city");
  const isNN = location.pathname.includes("/nizhnij-novgorod/");

  links.forEach((link) => {
    link.classList.toggle("is-active", isNN ? link.dataset.name === "nizhnij-novgorod" : link.dataset.name === "dzerzhinsk");
  });

  links.forEach((link) => {
    link.onclick = (e) => {
      e.preventDefault();
      links.forEach((l) => l.classList.remove("is-active"));
      link.classList.add("is-active");
    };
  });
});
