document.addEventListener("DOMContentLoaded", function () {
  function isCookieAccepted() {
    try {
      if (localStorage.getItem("cookieAccepted") === "true") {
        return true;
      }
    } catch (e) {
      console.error("localStorage read error:", e);
    }
    return /(?:^|;\s*)cookieAccepted=true(?:;|$)/.test(document.cookie);
  }

  function acceptCookie() {
    try {
      localStorage.setItem("cookieAccepted", "true");
    } catch (e) {
      console.error("localStorage write error:", e);
    }
    document.cookie = "cookieAccepted=true; path=/; max-age=" + 365 * 24 * 60 * 60;
  }

  /**
   * Включает скрипты аналитики: type="text/plain" → исполняемый script.
   * Смена type на месте браузер не перезапускает — создаём новый узел.
   */
  function activateConsentScripts() {
    const nodes = document.querySelectorAll(
      'script[type="text/plain"][data-cookie-consent]'
    );
    nodes.forEach(function (el) {
      const s = document.createElement("script");
      s.type = "text/javascript";
      Array.prototype.forEach.call(el.attributes, function (attr) {
        if (attr.name === "type" || attr.name === "data-cookie-consent") {
          return;
        }
        s.setAttribute(attr.name, attr.value);
      });
      if (el.src) {
        s.src = el.src;
        s.async = el.async;
      } else {
        s.text = el.textContent;
      }
      el.parentNode.insertBefore(s, el.nextSibling);
      el.remove();
    });
  }

  if (isCookieAccepted()) {
    activateConsentScripts();
  }

  const cookieNotice = document.getElementById("cookie-notice");
  const cookieAcceptBtn = document.getElementById("cookie-accept");

  if (!cookieNotice || !cookieAcceptBtn) {
    console.warn("Cookie notice elements not found");
    return;
  }

  function showCookieNotice() {
    if (!isCookieAccepted()) {
      cookieNotice.style.display = "block";
      setTimeout(() => {
        cookieNotice.style.opacity = "1";
        cookieNotice.style.transform = "translateY(0)";
      }, 10);
    }
  }

  function hideCookieNotice() {
    cookieNotice.style.opacity = "0";
    cookieNotice.style.transform = "translateY(100%)";
    setTimeout(() => {
      cookieNotice.style.display = "none";
    }, 300);
  }

  const accepted = isCookieAccepted();

  if (!accepted) {
    cookieNotice.style.opacity = "0";
    cookieNotice.style.transform = "translateY(100%)";
    cookieNotice.style.transition = "all 0.3s ease-out";
    cookieNotice.style.display = "block";
    setTimeout(showCookieNotice, 1000);
  } else {
    cookieNotice.style.display = "none";
    cookieNotice.style.opacity = "0";
    cookieNotice.style.transform = "translateY(100%)";
  }

  cookieAcceptBtn.addEventListener("click", function () {
    acceptCookie();
    activateConsentScripts();
    hideCookieNotice();

    if (typeof gtag === "function") {
      gtag("event", "cookie_accept");
    }
  });
});
