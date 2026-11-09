(function ($) {
  /**
   * Advanced Button Widget Handler
   *
   * @param $scope The Widget wrapper element as a jQuery element
   * @param $ The jQuery alias
   */
  const AdvButton = function ($scope, $) {
    const $btn = $scope.find('.aae-adv-btn--hover-ripple');

    if ($btn.length) {
      $btn.on('click', function (e) {
        const $this = $(this);
        const offset = $this.offset();
        const x = e.pageX - offset.left;
        const y = e.pageY - offset.top;

        const $ripple = $('<span class="aae-adv-btn__ripple"></span>');
        const size = Math.max($this.outerWidth(), $this.outerHeight());

        $ripple.css({
          width: size,
          height: size,
          top: y - size / 2,
          left: x - size / 2,
        });

        $this.append($ripple);

        setTimeout(function () {
          $ripple.remove();
        }, 600);
      });
    }
  };

  // Register with Elementor frontend
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction(
      'frontend/element_ready/aae--adv-button.default',
      AdvButton
    );
  });
})(jQuery);
