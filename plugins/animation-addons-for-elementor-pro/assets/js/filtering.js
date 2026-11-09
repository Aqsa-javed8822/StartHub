(function ($) {
  const AAE_Filtering = function ($scope, $) {
    const $buttons = $scope.find(".posts-filter li");
    const $items = $scope.find(".wcf-post");
    $buttons.on("click", function () {
      const filter = $(this).data("filter");
      $(this).addClass('active').siblings().removeClass('active');
      AAE_FilterItems(filter);
    });
    function AAE_FilterItems(filter) {
      $items.each(function () {
        const $item = $(this);
        const isMatching = filter === "all" || $item.hasClass(filter);
        gsap.set($item, {
          scale: 0.1,
          opacity: 0
        });
        if (isMatching) {
          if ($item.css('display') === 'none') {
            $item.css('display', 'flex');
          }
          ScrollTrigger.refresh();
        } else {
          $item.css('display', 'none');
          ScrollTrigger.refresh();
        }
      });
      requestAnimationFrame(() => {
        gsap.to($items, {
          opacity: 1,
          scale: 1,
          duration: 0.6,
          ease: "power4",
          stagger: .1
        });
      });
      ScrollTrigger.refresh();
    }
  };
  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/wcf--posts-pro.default', AAE_Filtering);
    ScrollTrigger.refresh();
  });
})(jQuery);