document.addEventListener("DOMContentLoaded", function () {
  const mapContainerArr = document.querySelectorAll(".sec-contacts__map");

  if (mapContainerArr) {
    mapContainerArr.forEach((mapContainer) => {
      if (!mapContainer) {
        console.log("Элемент карты не найден");
        return;
      }

      let center = mapContainer.dataset.center.split(",").map((coord) => parseFloat(coord.trim()));

      function init() {
        let map = new ymaps.Map(mapContainer, {
          center: center,
          zoom: 16,
        });

        let placemark = new ymaps.Placemark(center);

        map.controls.remove("searchControl");
        map.controls.remove("trafficControl");
        map.controls.remove("typeSelector");
        map.controls.remove("fullscreenControl");
        map.controls.remove("rulerControl");
        map.behaviors.disable(["scrollZoom"]);
        map.geoObjects.add(placemark);
      }
      ymaps.ready(init);
    });
  }
});
