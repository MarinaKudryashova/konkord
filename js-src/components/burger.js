import { disableScroll } from "../functions/disable-scroll";
import { enableScroll } from "../functions/enable-scroll";


document.addEventListener("DOMContentLoaded", () => {
  const menuLinks = document?.querySelectorAll("[data-menu-item]");
  const btnBurger = document.querySelector("[data-burger]");
  const menu = document.querySelector("[data-menu]");
  const menuList = document.querySelector("[data-menu] > ul");
  const btnDropdowns = document?.querySelectorAll("[data-dropdown-open]");
  const dropdowns = document?.querySelectorAll("[data-dropdown]");
  const burgerCloseBtn = document.querySelector('[data-close-burger]');

  if(!btnBurger || !menu) return

  const closeBurger = () => {
    btnBurger?.classList.remove("is-open");
    menu?.classList.remove("is-open");
    enableScroll();
  };

  const openBurger = () => {
    btnBurger?.classList.add("is-open");
    menu?.classList.add("is-open");
    disableScroll();
  }

  btnBurger.addEventListener("click", () => {
    if (menu.classList.contains("is-open")) {
      closeBurger();
    } else {
      openBurger();
    }
  });

    // Закрытие по клику на ссылку
  menuLinks.forEach((link) => {
    link.addEventListener("click", () => {
      closeBurger();
    });
  });

   // Закрытие по клику вне меню
  document.addEventListener("click", (e) => {
    const isMenu = menu.contains(e.target);
    const isBurger = btnBurger.contains(e.target);

    if (
      menu.classList.contains("is-open") &&
      !isMenu &&
      !isBurger
    ) {
      closeBurger();
    }
  });

  // Закрытие по ESC
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeBurger();
    }
  });

  btnDropdowns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();

      const dropdown = btn.closest("[data-dropdown]");
      if(!dropdown) return;

      const isOpen = dropdown.classList.contains("is-open")

      if(isOpen) {
        dropdown.classList.remove("is-open");
        btn.setAttribute("aria-expanded", "false")
        btn.setAttribute("aria-label", "Открыть подменю")
      } else {
        dropdown.classList.add("is-open");
        btn.setAttribute("aria-expanded", "true")
        btn.setAttribute("aria-label", "Закрыть подменю")
      }
    })
  })

  burgerCloseBtn.addEventListener('click', () => {
    closeBurger();
  })
});


