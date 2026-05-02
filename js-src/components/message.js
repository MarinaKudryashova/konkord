 document.addEventListener("DOMContentLoaded", () => {
    // Элементы сообщений
    const messageSuccess = document.getElementById('messageSuccess');
    const messageError = document.getElementById('messageError');
    const timeCloseMessage = 5000; // 5 секунд

    // Функция для показа сообщения
    function showMessage(messageElement) {
      // Сначала скрываем все сообщения
      hideAllMessages();

      // Показываем нужное сообщение
      messageElement.classList.add('is-open');

      // Устанавливаем таймер для автоматического закрытия
      const closeMessage = setTimeout(() => {
        messageElement.classList.remove('is-open');
      }, timeCloseMessage);

      // Обработчик для кнопки закрытия
      const closeBtn = messageElement.querySelector('.message__btn');
      if (closeBtn) {
        // Удаляем старый обработчик, если есть
        closeBtn.replaceWith(closeBtn.cloneNode(true));

        // Добавляем новый обработчик
        messageElement.querySelector('.message__btn').addEventListener('click', () => {
          clearTimeout(closeMessage);
          messageElement.classList.remove('is-open');
        });
      }

      return closeMessage;
    }

    // Функция для скрытия всех сообщений
    function hideAllMessages() {
      messageSuccess.classList.remove('is-open');
      messageError.classList.remove('is-open');
    }

    // Обработчики событий Contact Form 7
    document.addEventListener('wpcf7mailsent', function(event) {
      showMessage(messageSuccess);
    }, false);

    document.addEventListener('wpcf7mailfailed', function(event) {
      showMessage(messageError);
    }, false);

    document.addEventListener('wpcf7invalid', function(event) {
      showMessage(messageError);
    }, false);
    
});
