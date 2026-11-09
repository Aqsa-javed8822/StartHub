($ => {
  window.addEventListener("elementor/frontend/init", () => {
    // Wrapper Link
    const wrapper_link = function ($scope) {
      const attr = $scope.data("wcf-wrapper-link");
      if (undefined === attr || !attr || !attr.href) {
        return;
      }

      // Add pointer cursor to indicate clickability
      $scope.css('cursor', 'pointer');
      $scope.on("click.wcfWrapperLink", function (e) {
        // Don't trigger if clicking on an actual link or button inside
        if ($(e.target).is('a, button') || $(e.target).closest('a, button').length) {
          return;
        }

        // Create and click anchor element
        const anchor = document.createElement("a");
        $(anchor).attr(attr);

        // Append to body temporarily for proper handling
        document.body.appendChild(anchor);
        anchor.click();

        // Clean up
        setTimeout(() => {
          document.body.removeChild(anchor);
        }, 100);
      });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/container", wrapper_link);
  });
})(jQuery);