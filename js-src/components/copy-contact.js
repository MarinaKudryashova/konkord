/**
 * Desktop only: click on mailto:/tel: copies plain text from href
 * (after "mailto:" / "tel:") to clipboard for paste into messengers/docs.
 * Mobile/tablet: native call / mail — script does not intercept.
 */
(() => {
  const DESKTOP_MQ = "(hover: hover) and (pointer: fine) and (min-width: 1025px)";

  let toastTimer = null;

  const isDesktop = () => {
    try {
      return window.matchMedia(DESKTOP_MQ).matches;
    } catch {
      return window.innerWidth >= 1025;
    }
  };

  const findContactLink = (node) => {
    let el = node;
    while (el && el !== document && el !== document.documentElement) {
      if (el.tagName === "A") {
        const href = (el.getAttribute("href") || "").trim();
        if (/^mailto:/i.test(href) || /^tel:/i.test(href)) {
          return el;
        }
      }
      el = el.parentNode;
      if (el?.nodeType === Node.DOCUMENT_FRAGMENT_NODE && el.host) {
        el = el.host;
      }
    }
    return null;
  };

  /** Только текст из href: после mailto: или tel: */
  const extractCopyValue = (link) => {
    const href = (link.getAttribute("href") || "").trim();
    if (/^mailto:/i.test(href)) {
      return decodeURIComponent(href.replace(/^mailto:/i, "").split("?")[0].trim());
    }
    if (/^tel:/i.test(href)) {
      return href.replace(/^tel:/i, "").trim();
    }
    return "";
  };

  const showToast = (message) => {
    let toast = document.querySelector(".copy-contact-toast");
    if (!toast) {
      toast = document.createElement("div");
      toast.className = "copy-contact-toast";
      toast.setAttribute("role", "status");
      toast.setAttribute("aria-live", "polite");
      document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.add("is-visible");
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toast.classList.remove("is-visible");
    }, 1800);
  };

  const copyTextSync = (text) => {
    const ta = document.createElement("textarea");
    ta.value = text;
    ta.setAttribute("readonly", "");
    ta.style.cssText = "position:fixed;left:-9999px;top:0;";
    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    ta.setSelectionRange(0, ta.value.length);
    let ok = false;
    try {
      ok = document.execCommand("copy");
    } catch {
      ok = false;
    }
    ta.remove();
    return ok;
  };

  const copyText = async (text) => {
    if (copyTextSync(text)) {
      return;
    }
    if (navigator.clipboard?.writeText && window.isSecureContext) {
      await navigator.clipboard.writeText(text);
      return;
    }
    throw new Error("copy failed");
  };

  const onClick = (event) => {
    try {
      if (!isDesktop()) {
        return;
      }
      if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
        return;
      }

      const link = findContactLink(event.target);
      if (!link) {
        return;
      }

      const value = extractCopyValue(link);
      if (!value) {
        return;
      }

      event.preventDefault();
      event.stopPropagation();
      event.stopImmediatePropagation?.();

      const isMail = /^mailto:/i.test(link.getAttribute("href") || "");

      copyText(value)
        .then(() => {
          showToast(isMail ? "Почта скопирована" : "Телефон скопирован");
        })
        .catch(() => {
          showToast("Не удалось скопировать");
        });
    } catch {
      // Не роняем страницу из‑за копирования.
    }
  };

  const init = () => {
    document.addEventListener("click", onClick, true);
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
