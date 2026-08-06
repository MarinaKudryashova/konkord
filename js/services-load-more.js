(function ($) {
  if (typeof konkord_ajax === "undefined") return;

  var $list = $("#services-list");
  var $loader = $("#services-loader");
  var trigger = document.getElementById("services-trigger");

  if (!$list.length || !trigger) return;

  var currentPage = parseInt(konkord_ajax.services.initial_page) || 1;
  var maxPages = parseInt(konkord_ajax.services.max_pages) || 1;
  var isLoading = false;

  if (currentPage >= maxPages) {
    trigger.style.display = "none";
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      if (entries[0].isIntersecting && !isLoading && currentPage < maxPages) {
        loadMore();
      }
    },
    { rootMargin: "0px 0px 100px 0px" },
  );

  observer.observe(trigger);

  function loadMore() {
    isLoading = true;
    $loader.show();

    var nextPage = currentPage + 1;
    $.ajax({
      url: konkord_ajax.ajax_url,
      type: "POST",
      data: {
        action: "load_more_services",
        nonce: konkord_ajax.nonce,
        page: nextPage,
        cat_slug: konkord_ajax.services.cat_slug || "",
        page_id: konkord_ajax.services.page_id || 0,
      },
      success: function (response) {
        if (response.success && response.data.html) {
          $list.append(response.data.html);
          currentPage = response.data.current_page;
          maxPages = response.data.max_pages;

          if (currentPage >= maxPages) {
            observer.unobserve(trigger);
            $loader.hide();
            trigger.style.display = "none";
          } else {
            $loader.hide();
          }

          if (typeof AOS !== "undefined") {
            AOS.refresh();
          }
        } else {
          $loader.hide();
        }
      },
      error: function () {
        $loader.hide();
      },
      complete: function () {
        isLoading = false;
      },
    });
  }
})(jQuery);
