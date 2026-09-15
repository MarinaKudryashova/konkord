import AOS from "aos";

AOS.init({
  easing: "ease-in-out",
  duration: 350,
  offset: 50,
  once: true,
  disable: function () {
    return window.innerWidth < 768;
  },
});
