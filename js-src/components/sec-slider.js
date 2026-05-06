import Swiper from "./init-slider.js";

const fotogallerySectionSliders = document.querySelectorAll("[data-id='sec-fotogallery'] .sec-slider__content");

if (fotogallerySectionSliders) {
  fotogallerySectionSliders.forEach((slider) => {
    const btnNextSectionSlider = slider.parentNode.querySelector(".sec-slider__btn-next");
    const btnPrevSectionSlider = slider.parentNode.querySelector(".sec-slider__btn-prev");

    const swiper_currentSlider = new Swiper(slider, {
      loop: true,
      lazy: true,
      spaceBetween: 20,
      slidesPerView: 1,
      slidesPerGroup: 1,
      navigation: {
        nextEl: btnNextSectionSlider,
        prevEl: btnPrevSectionSlider,
      },
      breakpoints: {
        360: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1440: {
          slidesPerView: 2,
        },
      },
    });
  });
}

const employeesSectionSliders = document.querySelectorAll("[data-id='sec-employees'] .sec-slider__content");

if (employeesSectionSliders) {
  employeesSectionSliders.forEach((slider) => {
    const btnNextSectionSlider = slider.parentNode.querySelector(".sec-slider__btn-next");
    const btnPrevSectionSlider = slider.parentNode.querySelector(".sec-slider__btn-prev");

    const swiper_currentSlider = new Swiper(slider, {
      loop: true,
      lazy: true,
      spaceBetween: 20,
      slidesPerView: 1,
      slidesPerGroup: 1,
      navigation: {
        nextEl: btnNextSectionSlider,
        prevEl: btnPrevSectionSlider,
      },
      breakpoints: {
        360: {
          slidesPerView: 1.3,
        },
        576: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 2.3,
        },
        992: {
          slidesPerView: 3.3,
        },
        1400: {
          slidesPerView: 4,
        },
      },
    });
  });
}

const newsSectionSliders = document.querySelectorAll("[data-id='sec-news'] .sec-slider__content");

if (newsSectionSliders) {
  newsSectionSliders.forEach((slider) => {
    const btnNextSectionSlider = slider.parentNode.querySelector(".sec-slider__btn-next");
    const btnPrevSectionSlider = slider.parentNode.querySelector(".sec-slider__btn-prev");

    const swiper_currentSlider = new Swiper(slider, {
      loop: true,
      lazy: true,
      spaceBetween: 20,
      slidesPerView: 1,
      slidesPerGroup: 1,
      navigation: {
        nextEl: btnNextSectionSlider,
        prevEl: btnPrevSectionSlider,
      },
      breakpoints: {
        360: {
          slidesPerView: 1,
        },
        576: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 2,
        },
        992: {
          slidesPerView: 3,
        },
        1440: {
          slidesPerView: 3,
        },
      },
    });
  });
}
