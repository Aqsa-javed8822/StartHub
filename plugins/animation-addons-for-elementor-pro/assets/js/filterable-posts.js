(function ($) {
  const AAE_Filter_Posts = function ($scope, $) {
    const $buttons = $scope.find(".aae--filterable-posts .posts-filter li");
    const $items = $scope.find(".aae--filterable-posts .wcf-post");

    // Handle filter button click
    $buttons.on("click", function () {
      const filter = $(this).data("filter");
      $(this).addClass('mixitup-control-active').siblings().removeClass('mixitup-control-active');
      portfolioFilterItems(filter);
    });
    function portfolioFilterItems(filter) {
      const state = Flip.getState($items.toArray());
      $items.each(function () {
        const $item = $(this);
        if (filter === "all" || $item.hasClass(filter)) {
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
    // Handle item click (show post content in popup)
    const $popup_enabled = $scope.find('.popup-enabled');
    if ($popup_enabled.length > 0) {
      $items.each(function () {
        $(this).off('click').on('click', function (e) {
          e.preventDefault();

          // Add the "popup-opened" class to the container
          $scope.find('.aae--filterable-posts').addClass('popup-opened');
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

  // Elementor Frontend Hook
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/wcf--posts-filter.default', AAE_Filter_Posts);
  });
})(jQuery);