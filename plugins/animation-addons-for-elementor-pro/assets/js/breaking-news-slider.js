(function ($) {
  const breaking_news_slider = function ($scope, $) {
    const $marquee = $scope.find('.marquee');
    if ($marquee.length === 0) return; // Ensure there is at least one marquee element

    $marquee.each(function () {
      const $item = $(this);
      const $marqueeInner = $item.find('.marquee__inner');
      const $marqueeContent = $marqueeInner.find('.marquee__content');

      // Get duration from data attribute
      const duration = $item.data('marquee-duration') || 10; // Default 10s if not set

      // Clone the marquee content
      const $marqueeContentClone = $marqueeContent.clone();
      $marqueeInner.append($marqueeContentClone);

      // Set width dynamically to prevent overlapping
      const totalWidth = $marqueeContent.outerWidth() * 2;
      $marqueeInner.css("width", totalWidth);

      // Apply marquee animation using GSAP
      gsap.to($marqueeInner, {
        x: `-${$marqueeContent.outerWidth()}px`,
        repeat: -1,
        duration: duration,
        // Duration from data attribute
        ease: 'linear' // Smooth scrolling effect
      });
    });
  };

  // Run the code when Elementor is ready
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/wcf--breaking-news-slider.default', breaking_news_slider);
  });
})(jQuery);