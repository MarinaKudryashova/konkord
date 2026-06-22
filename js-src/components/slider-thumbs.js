import Swiper from "./init-slider.js";

function initThumbsSlider(slider) {
  const mainThumbsSlider = slider.querySelector(".slider-thumbs-main");
  const navsThumbsSlider = slider.querySelector(".slider-thumbs-navs");

  if (!mainThumbsSlider || !navsThumbsSlider) return false;

  const navsSlides = navsThumbsSlider.querySelectorAll(".swiper-slide");
  if (navsSlides.length < 2) return false;

  const navsSlider = new Swiper(navsThumbsSlider, {
    direction: "vertical",
    loop: navsSlides.length > 5,
    spaceBetween: 15,
    slidesPerView: "auto",
    watchSlidesProgress: true,
    breakpoints: {
      320: {
        direction: "horizontal",
        spaceBetween: 4,
      },
      576: {
        direction: "vertical",
      },
      768: {
        direction: "horizontal",
      },
      992: {
        direction: "vertical",
        spaceBetween: 8,
      },
    },
  });

  const mainSlider = new Swiper(mainThumbsSlider, {
    loop: true,
    spaceBetween: 15,
    slidesPerView: 1,
    thumbs: {
      swiper: navsSlider,
    },
  });

  return { main: mainSlider, navs: navsSlider };
}

// Инициализация
const thumbsSliders = document.querySelectorAll(".slider-thumbs");

if (thumbsSliders.length) {
  thumbsSliders.forEach((slider) => {
    if (window.requestIdleCallback) {
      requestIdleCallback(() => initThumbsSlider(slider));
    } else {
      setTimeout(() => initThumbsSlider(slider), 100);
    }
  });
}
