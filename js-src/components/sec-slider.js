import Swiper from "./init-slider.js";

const fotogallerySectionSliders = document.querySelectorAll("[data-id='sec-fotogallery'] .sec-slider__content");

if (fotogallerySectionSliders) {
  fotogallerySectionSliders.forEach((slider) => {
    const btnNextSectionSlider = slider.parentNode.querySelector(".sec-slider__btn-next");
    const btnPrevSectionSlider = slider.parentNode.querySelector(".sec-slider__btn-prev");

    const swiper_currentSlider = new Swiper(slider, {
      loop: true,
      lazy: true,
      spaceBetween: 8,
      slidesPerView: 1,
      slidesPerGroup: 1,
      navigation: {
        nextEl: btnNextSectionSlider,
        prevEl: btnPrevSectionSlider,
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 8,
        },
        360: {
          slidesPerView: 1.5,
          spaceBetween: 8,
        },
        576: {
          slidesPerView: 2,
          spaceBetween: 8,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1200: {
          slidesPerView: 2.1,
          spaceBetween: 20,
        },
        1560: {
          slidesPerView: 3,
          spaceBetween: 20,
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
      spaceBetween: 8,
      slidesPerView: 1,
      slidesPerGroup: 1,
      navigation: {
        nextEl: btnNextSectionSlider,
        prevEl: btnPrevSectionSlider,
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 8,
        },
        360: {
          slidesPerView: 1.3,
          spaceBetween: 8,
        },
        576: {
          slidesPerView: 2,
          spaceBetween: 8,
        },
        768: {
          slidesPerView: 2.3,
          spaceBetween: 20,
        },
        992: {
          slidesPerView: 3.3,
          spaceBetween: 20,
        },
        1190: {
          slidesPerView: 4,
          spaceBetween: 20,
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
      spaceBetween: 8,
      slidesPerView: 1,
      slidesPerGroup: 1,
      navigation: {
        nextEl: btnNextSectionSlider,
        prevEl: btnPrevSectionSlider,
      },
      breakpoints: {
        360: {
          slidesPerView: 1.5,
          spaceBetween: 8,
        },
        576: {
          slidesPerView: 2,
          spaceBetween: 8,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        992: {
          slidesPerView: 3,
          spaceBetween: 20,
        },
        1440: {
          slidesPerView: 3,
          spaceBetween: 20,
        },
      },
    });
  });
}

const categorySectionSliders = document.querySelectorAll("[data-id='sec-category'] .sec-slider__content");

if (categorySectionSliders) {
  categorySectionSliders.forEach((slider) => {
    const btnNextSectionSlider = slider.parentNode.querySelector(".sec-slider__btn-next");
    const btnPrevSectionSlider = slider.parentNode.querySelector(".sec-slider__btn-prev");

    const swiper_currentSlider = new Swiper(slider, {
      loop: true,
      lazy: true,
      spaceBetween: 8,
      slidesPerView: 1,
      slidesPerGroup: 1,
      navigation: {
        nextEl: btnNextSectionSlider,
        prevEl: btnPrevSectionSlider,
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 8,
        },
        360: {
          slidesPerView: 1.5,
          spaceBetween: 8,
        },
        576: {
          slidesPerView: 2,
          spaceBetween: 12,
        },
        768: {
          slidesPerView: 2.5,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 20,
        }
      },
    });
  });
}
