/* global WCF_ADDONS_JS */
(function ($) {
  /**
   * @param $scope The Widget wrapper element as a jQuery element
   * @param $ The jQuery alias
   */
  const AdvanceSlider = function ($scope, $) {
    const slider = $($('.wcf__slider', $scope)[0]);
    const sliderSettings = $($('.slider-wrapper', $scope)[0]).data('settings') || {};
    sliderSettings.handleElementorBreakpoints = true;
    if (sliderSettings.hasOwnProperty('pagination')) {
      sliderSettings.pagination.renderCustom = function (swiper, current, total) {
        let width = 100 / total * current;
        return "0" + current + ' <span class="paginate-fill" style="' + '--width:' + width + '%"></span> ' + 0 + total;
      };
    }
    const sliderGallery = $($('.wcf-post-gallery-active', $scope)[0]);
    const thumbSlider = sliderGallery;
    const thumbSettings = {
      loop: false,
      spaceBetween: 0,
      slidesPerView: 1,
      freeMode: true,
      watchSlidesProgress: true
    };
    if (sliderGallery.length) {
      new elementorFrontend.utils.swiper(thumbSlider, thumbSettings).then(newSwiperInstance => newSwiperInstance).then(thumbSliderInstance => {
        sliderSettings.slideToClickedSlide = true;
        new elementorFrontend.utils.swiper(slider, sliderSettings).then(newSwiperInstance => newSwiperInstance).then(newSwiperInstance => {
          newSwiperInstance.controller.control = thumbSliderInstance;
          thumbSliderInstance.controller.control = newSwiperInstance;
        });
      });
    } else {
      if (sliderSettings?.effect == 'panorama') {
        sliderSettings.modules = [EffectPanorama];
      }
      new elementorFrontend.utils.swiper(slider, sliderSettings).then(newSwiperInstance => newSwiperInstance);
    }

    // Handle item click (show post content in popup)
    const $popup_enabled = $scope.find('.popup-enabled');
    const $items = $scope.find(".wcf--posts-slider .slide_item");
    if ($popup_enabled.length > 0) {
      $items.each(function () {
        $(this).off('click').on('click', function (e) {
          e.preventDefault();

          // Add the "popup-opened" class to the container
          $scope.find('.wcf--posts-slider').addClass('popup-opened');
          const $popup_id = $scope.attr('data-id');
          const $pageElement = $('[class*="page-id-"]');
          if ($pageElement.length) {
            // Get all class names
            const classList = $pageElement.attr('class').split(/\s+/);

            // Find the class that starts with "page-id-"
            const pageIdClass = classList.find(cls => cls.startsWith('page-id-'));
            if (pageIdClass) {
              // Extract the numeric part of page-id
              const numericPageId = pageIdClass.split('page-id-')[1];

              // Update the content to include the dynamic page ID class
              let content = `
                            <div class="aae-post-popup-content">${$(this).html()}</div>
                            `;

              // Add post content to the popup
              let $popupWrapper = $('.wcf--popup-video-wrapper');
              $popupWrapper.addClass('elementor elementor-' + numericPageId);
              $popupWrapper.find('.wcf--popup-video').addClass('post-details-popup elementor-element elementor-element-' + $popup_id);
              $popupWrapper.find('.aae-popup-content-container').html(content);

              // Popup animation
              window.VideoAnimation = gsap.timeline({
                defaults: {
                  ease: "power2.inOut"
                }
              }).to(`body > .wcf--popup-video-wrapper`, {
                scaleY: 0.01,
                x: 1,
                opacity: 1,
                visibility: 'visible',
                duration: 0.4
              }).to(`body > .wcf--popup-video-wrapper`, {
                scaleY: 1,
                duration: 0.6
              }).to(`body > .wcf--popup-video-wrapper .wcf--popup-video`, {
                scaleY: 1,
                opacity: 1,
                visibility: 'visible',
                duration: 0.6
              }, "-=0.4");
            }
          }
        });
      });
    }
  };

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/wcf--posts-slider.default', AdvanceSlider);
  });
})(jQuery);