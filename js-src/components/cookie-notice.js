document.addEventListener('DOMContentLoaded', function() {
  const cookieNotice = document.getElementById('cookie-notice');
  const cookieAcceptBtn = document.getElementById('cookie-accept');

  // Проверяем, приняты ли уже cookies
  function checkCookieAcceptance() {
    return localStorage.getItem('cookieAccepted') === 'true';
  }

  // Показываем плашку с анимацией
  function showCookieNotice() {
    if (!checkCookieAcceptance()) {
      cookieNotice.style.display = 'block';
      // Задержка для применения display: block перед анимацией
      setTimeout(() => {
        cookieNotice.style.opacity = '1';
        cookieNotice.style.transform = 'translateY(0)';
      }, 10);
    }
  }

  // Скрываем плашку с анимацией
  function hideCookieNotice() {
    cookieNotice.style.opacity = '0';
    cookieNotice.style.transform = 'translateY(100%)';

    // После завершения анимации скрываем полностью
    setTimeout(() => {
      cookieNotice.style.display = 'none';
    }, 300);
  }

  // Инициализация
  if (!checkCookieAcceptance()) {
    // Настройки анимации перед показом
    cookieNotice.style.opacity = '0';
    cookieNotice.style.transform = 'translateY(100%)';
    cookieNotice.style.transition = 'all 0.3s ease-out';

    // Показываем с задержкой для лучшего UX
    setTimeout(showCookieNotice, 1000);
  }

  // Обработчик кнопки
  cookieAcceptBtn.addEventListener('click', function() {
    localStorage.setItem('cookieAccepted', 'true');
    hideCookieNotice();

    // Можно добавить отправку события в Google Analytics
    if (typeof gtag === 'function') {
      gtag('event', 'cookie_accept');
    }
  });
});
