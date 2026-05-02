import { disableScroll } from "../functions/disable-scroll";
import { enableScroll } from "../functions/enable-scroll";

document.addEventListener("DOMContentLoaded", () => {
  const btnDropdowns = document?.querySelectorAll("[data-dropdown-open]");
  const dropdowns = document?.querySelectorAll("[data-dropdown]");
  const menuLinks = document?.querySelectorAll("[data-menu-item]");
  const btnBurger = document.querySelector("[data-burger]");
  const menu = document.querySelector("[data-menu]");
  const menuList = document.querySelector("[data-menu] > ul");

  // Функция для закрытия всех dropdown на любом уровне
  const closeAllDropdowns = (exceptElement = null) => {
    dropdowns.forEach((drop) => {
      if (exceptElement && drop === exceptElement) return;
      drop?.classList.remove("is-open");

      // Закрываем все дочерние dropdowns
      const childDropdowns = drop.querySelectorAll("[data-dropdown]");
      childDropdowns.forEach((child) => {
        child.classList.remove("is-open");
      });
    });

    btnDropdowns?.forEach((btnDropdown) => {
      btnDropdown.setAttribute("aria-expanded", "false");
      btnDropdown.setAttribute("aria-label", "Открыть подменю");
    });
  };

  // Функция для закрытия конкретного dropdown
  const closeDropdown = (dropdown) => {
    dropdown.classList.remove("is-open");
    const btn = dropdown.querySelector("[data-dropdown-open]");
    if (btn) {
      btn.setAttribute("aria-expanded", "false");
      btn.setAttribute("aria-label", "Открыть подменю");
    }

    // Закрываем все дочерние dropdowns
    const childDropdowns = dropdown.querySelectorAll("[data-dropdown]");
    childDropdowns.forEach((child) => {
      child.classList.remove("is-open");
      const childBtn = child.querySelector("[data-dropdown-open]");
      if (childBtn) {
        childBtn.setAttribute("aria-expanded", "false");
        childBtn.setAttribute("aria-label", "Открыть подменю");
      }
    });
  };

  // Функция для открытия dropdown
  const openDropdown = (dropdown) => {
    dropdown.classList.add("is-open");
    const btn = dropdown.querySelector("[data-dropdown-open]");
    if (btn) {
      btn.setAttribute("aria-expanded", "true");
      btn.setAttribute("aria-label", "Закрыть подменю");
    }
  };

  const closeBurger = () => {
    btnBurger?.classList.remove("is-open");
    menu?.classList.remove("is-open");
    closeAllDropdowns();
    enableScroll();
  };

  if (window.innerWidth < 1201) {
    // Обработка клика по кнопкам открытия подменю
    btnDropdowns?.forEach((btnDropdown) => {
      btnDropdown?.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        const parentLi = e.target.closest("[data-dropdown]");
        if (!parentLi) return;

        // Проверяем, открыт ли текущий dropdown
        const isOpen = parentLi.classList.contains("is-open");

        if (isOpen) {
          // Если открыт - закрываем его и все дочерние
          closeDropdown(parentLi);
        } else {
          // Если закрыт - закрываем все другие dropdowns на этом же уровне
          const parentMenu = parentLi.closest("ul");
          if (parentMenu) {
            const siblings = parentMenu.querySelectorAll(":scope > [data-dropdown]");
            siblings.forEach((sibling) => {
              if (sibling !== parentLi) {
                closeDropdown(sibling);
              }
            });
          }

          // Открываем текущий dropdown
          openDropdown(parentLi);
        }
      });
    });

    // Обработка бургера
    if (btnBurger) {
      btnBurger.addEventListener("click", (e) => {
        e.stopPropagation();
        if (btnBurger.classList.contains("is-open")) {
          closeBurger();
        } else {
          closeAllDropdowns();
          disableScroll();
          btnBurger.classList.add("is-open");
          menu.classList.add("is-open");
        }
      });
    }

    // Закрытие меню при клике на ссылки
    menuLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        const parentDropdown = link.closest("[data-dropdown]");

        // Если это ссылка в dropdown, закрываем этот dropdown
        if (parentDropdown) {
          closeDropdown(parentDropdown);
        }

        // Закрываем бургер
        closeBurger();
      });
    });

    // Закрытие при клике вне меню
    document.addEventListener("click", (e) => {
      if (menu && btnBurger && menu.classList.contains("is-open")) {
        if (!menu.contains(e.target) && !btnBurger.contains(e.target)) {
          closeBurger();
        }
      }
    });

    // Закрытие по Escape
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        if (menu?.classList.contains("is-open")) {
          closeBurger();
        } else {
          // Если бургер закрыт, закрываем все открытые dropdowns
          closeAllDropdowns();
        }
      }
    });

    // Закрытие dropdown при клике вне его
    document.addEventListener("click", (e) => {
      if (!e.target.closest("[data-dropdown]")) {
        closeAllDropdowns();
      }
    });
  } else {
    // Десктоп версия (оставляем без изменений)
    dropdowns?.forEach((dropdown) => {
      let timeoutId;
      let isSubmenuHovered = false;

      const closeDropdown = (targetDropdown) => {
        targetDropdown.classList.remove("is-open");
        const btn = targetDropdown.querySelector("[data-dropdown-open]");
        if (btn) {
          btn.setAttribute("aria-expanded", "false");
          btn.setAttribute("aria-label", "Открыть подменю");
        }

        const childDropdowns = targetDropdown.querySelectorAll("[data-dropdown]");
        childDropdowns.forEach((child) => {
          child.classList.remove("is-open");
          const childBtn = child.querySelector("[data-dropdown-open]");
          if (childBtn) {
            childBtn.setAttribute("aria-expanded", "false");
            childBtn.setAttribute("aria-label", "Открыть подменю");
          }
        });
      };

      const openDropdown = (targetDropdown) => {
        clearTimeout(timeoutId);

        const parent = targetDropdown.parentElement;
        if (parent) {
          const siblings = parent.querySelectorAll(":scope > [data-dropdown]");
          siblings.forEach((sibling) => {
            if (sibling !== targetDropdown) {
              closeDropdown(sibling);
            }
          });
        }

        targetDropdown.classList.add("is-open");
        const btn = targetDropdown.querySelector("[data-dropdown-open]");
        if (btn) {
          btn.setAttribute("aria-expanded", "true");
          btn.setAttribute("aria-label", "Закрыть подменю");
        }
      };

      dropdown?.addEventListener("mouseenter", (e) => {
        e.stopPropagation();
        openDropdown(e.currentTarget);
      });

      dropdown?.addEventListener("mouseleave", (e) => {
        e.stopPropagation();
        const currentDropdown = e.currentTarget;

        timeoutId = setTimeout(() => {
          const submenu = currentDropdown.querySelector("ul");
          if (submenu && !submenu.matches(":hover") && !isSubmenuHovered) {
            closeDropdown(currentDropdown);
          }
        }, 300);
      });

      const submenu = dropdown?.querySelector("ul");
      if (submenu) {
        submenu.addEventListener("mouseenter", (e) => {
          e.stopPropagation();
          clearTimeout(timeoutId);
          isSubmenuHovered = true;
        });

        submenu.addEventListener("mouseleave", (e) => {
          e.stopPropagation();
          isSubmenuHovered = false;

          if (!dropdown.matches(":hover")) {
            timeoutId = setTimeout(() => {
              closeDropdown(dropdown);
            }, 300);
          }
        });
      }
    });

    menuLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        const parentDropdown = link.closest("[data-dropdown]");

        if (parentDropdown) {
          const closeCurrentAndChildren = (dropdown) => {
            dropdown.classList.remove("is-open");
            const btn = dropdown.querySelector("[data-dropdown-open]");
            if (btn) {
              btn.setAttribute("aria-expanded", "false");
              btn.setAttribute("aria-label", "Открыть подменю");
            }

            const childDropdowns = dropdown.querySelectorAll("[data-dropdown]");
            childDropdowns.forEach((child) => {
              child.classList.remove("is-open");
              const childBtn = child.querySelector("[data-dropdown-open]");
              if (childBtn) {
                childBtn.setAttribute("aria-expanded", "false");
                childBtn.setAttribute("aria-label", "Открыть подменю");
              }
            });
          };

          closeCurrentAndChildren(parentDropdown);

          dropdowns.forEach((drop) => {
            if (drop !== parentDropdown) {
              closeCurrentAndChildren(drop);
            }
          });
        } else {
          closeAllDropdowns();
        }
      });
    });

    document.addEventListener("click", (e) => {
      if (!e.target.closest("[data-dropdown]")) {
        closeAllDropdowns();
      }
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        closeAllDropdowns();
      }
    });
  }
});
