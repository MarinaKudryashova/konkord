/**
 * Переключатель городов в шапке.
 * Перехватываем клик раньше Belingo, чтобы:
 * 1) редирект оставался в схеме текущей страницы (http↔https);
 * 2) на *.local не уходить на https (Super Cache .gz → «Без названия.gz»).
 */
document.addEventListener(
  "click",
  (e) => {
    const link = e.target.closest(".header__switcher .select_geo_city");
    if (!link || typeof belingoGeo === "undefined" || typeof jQuery === "undefined") {
      return;
    }

    e.preventDefault();
    e.stopImmediatePropagation();

    const links = document.querySelectorAll(".header__switcher .select_geo_city");
    links.forEach((l) => l.classList.remove("is-active"));
    link.classList.add("is-active");

    const data = {
      action: "write_city_cookie",
      city_name: link.dataset.name,
      city_name_orig: link.dataset.nameOrig,
      object_id: belingoGeo.object_id,
      object: belingoGeo.object,
      back_url: belingoGeo.backurl,
    };

    jQuery.post(belingoGeo.ajaxurl, data, (response) => {
      if (!response || !response.redirect) {
        return;
      }

      let next = response.redirect;
      try {
        const u = new URL(next, location.href);
        u.protocol = location.protocol;
        // Локально всегда http — иначе OpenServer отдаёт кэш .gz как файл.
        if (/\.local$/i.test(u.hostname)) {
          u.protocol = "http:";
        }
        next = u.href;
      } catch (_) {
        /* keep server redirect */
      }

      location.href = next;
    });
  },
  true
);

document.addEventListener("DOMContentLoaded", () => {
  const links = document.querySelectorAll(".header__switcher .select_geo_city");
  if (!links.length) return;

  const activeSlug = location.pathname.includes("/nizhnij-novgorod/")
    ? "nizhnij-novgorod"
    : "dzerzhinsk-2";

  links.forEach((link) => {
    link.classList.toggle("is-active", link.dataset.name === activeSlug);
  });
});
