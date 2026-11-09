/* global WCF_ADDONS_JS */
(function ($) {
  /**
   * @param $scope The Widget wrapper element as a jQuery element
   * @param $ The jQuery alias
   */
  const HeloTestimonial = function ($scope, $) {
    const slider = $($('.helo_testimonial_slider', $scope)[0]);
    const sliderSettings = $($('.helo_testimonial_wrapper', $scope)[0]).data('settings') || {};
    sliderSettings.handleElementorBreakpoints = true;
    new elementorFrontend.utils.swiper(slider, sliderSettings).then(newSwiperInstance => newSwiperInstance);
  };

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/helo--testimonial.default', HeloTestimonial);
  });
})(jQuery);