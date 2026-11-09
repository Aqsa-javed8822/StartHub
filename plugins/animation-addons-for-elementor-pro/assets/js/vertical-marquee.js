(function ($) {
  const AAEVerticalMarquee = function ($scope, $) {
    let wrapper = $scope.find('.aae--vl-marquee-wrapper')[0];
    const marqueeDuration = wrapper.getAttribute('data-duration');
    const marqueePause = wrapper.getAttribute('data-pause');

    // Clone children for looping
    const cloneItems = (container, times = 1) => {
      const items = Array.from(container.children);
      for (let i = 0; i < times; i++) {
        items.forEach(item => {
          container.appendChild(item.cloneNode(true));
        });
      }
    };

    // Calculate total height of top N children
    const getTotalHeight = (container, limit = 4, spacing = 32) => {
      return Array.from(container.children).slice(0, limit).reduce((total, child) => total + child.scrollHeight + spacing, 0);
    };

    // Marquee Animation
    const marqueeAnimation = ({
      selector,
      direction = "up",
      clone = false
    }) => {
      const $container = $scope.find(selector);
      const container = $container[0];
      if (!container) return;
      if (clone) cloneItems(container, 1);
      const totalHeight = getTotalHeight(container);
      const animation = gsap.to(container, {
        y: direction === "up" ? -totalHeight : totalHeight,
        repeat: -1,
        duration: marqueeDuration,
        ease: "linear"
      });
      if ('yes' === marqueePause) {
        $container.on("mouseenter", () => animation.pause());
        $container.on("mouseleave", () => animation.resume());
      }
    };

    // Init forward and backward marquee
    marqueeAnimation({
      selector: ".marquee-forward",
      direction: "up",
      clone: false
    });
    marqueeAnimation({
      selector: ".marquee-backward",
      direction: "down",
      clone: true
    });
  };
  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/aae--vertical-marquee.default", AAEVerticalMarquee);
  });
})(jQuery);