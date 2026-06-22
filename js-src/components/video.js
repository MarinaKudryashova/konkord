document.addEventListener("DOMContentLoaded", (event) => {
  const video = document.querySelector(".videoblock__video");
  const videoBox = document.querySelector(".videoblock__content");
  const playBtn = document.querySelector(".videoblock__play");

  if (video) {
    playBtn.addEventListener("click", () => {
      videoBox.classList.add("played");
      video.play();
      video.controls = true;
      playBtn.classList.add("played");
    });

    video.onpause = function () {
      videoBox.classList.remove("played");
      video.controls = false;
      playBtn.classList.remove("played");
    };

    // Простая проверка на мобильные устройства
    if (window.innerWidth < 768) {
      const videoObserver = new IntersectionObserver(
        ([entry]) => {
          const videoBg = entry.target || {};
          // проверяем, что видео в принципе запускалось
          if (videoBg.currentTime !== 0) {
            // Если видео вне viewport или видимо только на 20%
            if (!entry.isIntersecting || entry.intersectionRatio <= 0.2) {
              // жмем паузу
              videoBg.pause();
            }
          }
        },
        {
          // Триггер сработает при выходе как верхней, так и нижней границы
          threshold: [0.4, 0.8],
        },
      );

      document.querySelectorAll(".videoblock__video").forEach((videoBg) => {
        videoObserver.observe(videoBg);
      });
    }
  }
});
