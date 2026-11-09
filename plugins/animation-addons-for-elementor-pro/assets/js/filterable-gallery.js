(function ($) {
  // Filterable Gallery

  const filter_gallery = function ($scope) {
    const $buttons = $scope.find(".wcf--filterable-gallery .gallery-filter li");
    const $items = $scope.find(".wcf--filterable-gallery .gallery-item");
    $buttons.on("click", function () {
      const filter = $(this).data("filter");
      $(this).addClass("mixitup-control-active").siblings().removeClass("mixitup-control-active");
      portfolioFilterItems(filter);
    });
    function portfolioFilterItems(filter) {
      const state = Flip.getState($items.toArray());
      let filtered = filter.replace(".", "");
      $items.each(function () {
        const $item = $(this);
        if (filtered === "all" || $item.hasClass(filtered)) {
          $item.show();
        } else {
          $item.hide();
        }
      });
      Flip.from(state, {
        duration: 0.5,
        ease: "power1.inOut",
        stagger: 0.1
      });
    }
  };

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--filterable-gallery.default`, filter_gallery);
  });
})(jQuery);