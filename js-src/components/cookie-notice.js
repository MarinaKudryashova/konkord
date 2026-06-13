document.addEventListener("DOMContentLoaded", function () {
  const cookieNotice = document.getElementById("cookie-notice");
  const cookieAcceptBtn = document.getElementById("cookie-accept");

  if (!cookieNotice || !cookieAcceptBtn) {
    console.warn("Cookie notice elements not found");
    return;
  }

  function isCookieAccepted() {
    try {
      return localStorage.getItem("cookieAccepted") === "true";
    } catch (e) {
      console.error("localStorage read error:", e);
      return false;
    }
  }

  function acceptCookie() {
    try {
      localStorage.setItem("cookieAccepted", "true");
      return true;
    } catch (e) {
      console.error("localStorage write error:", e);
      document.cookie = "cookieAccepted=true; path=/; max-age=" + 365 * 24 * 60 * 60;
      return false;
    }
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
    cookieNotice.style.display = "block"; // Явно устанавливаем display
    setTimeout(showCookieNotice, 1000);
  } else {
    cookieNotice.style.display = "none";
    cookieNotice.style.opacity = "0";
    cookieNotice.style.transform = "translateY(100%)";
  }

  // Обработчик кнопки
  cookieAcceptBtn.addEventListener("click", function () {
    acceptCookie();
    hideCookieNotice();

    if (typeof gtag === "function") {
      gtag("event", "cookie_accept");
    }
  });
});
