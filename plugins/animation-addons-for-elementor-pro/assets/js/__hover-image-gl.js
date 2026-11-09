($ => {
  const Modules = elementorModules.frontend.handlers.Base;
  //image hover effect
  const hover_image = Modules.extend({
    bindEvents: function bindEvents() {
      this.run();
    },
    run: function run() {
      if (this.getElementSettings("wcf_enable_hover_image")) {
        if (this.isEdit && !this.getElementSettings("wcf_enable_hover_image_editor")) {
          return;
        }
        const element = $(this.$element);
        if (0 === element.find(".wcf-image-hover").length) {
          element.append('<div class="wcf-image-hover"></div>');
        }
        setTimeout(() => {
          const image = $(element.find(".wcf-image-hover"));
          $(element).mouseenter(function (e) {
            gsap.to(image, {
              delay: 0,
              duration: 0,
              autoAlpha: 1
            });
          });
          $(element).mouseleave(function (e) {
            gsap.to(image, {
              delay: 0,
              duration: 0,
              autoAlpha: 0
            });
          });
          $(element).mousemove(function (e) {
            const contentBox = element[0].getBoundingClientRect();
            const dx = e.clientX - contentBox.x;
            const dy = e.clientY - contentBox.y;
            gsap.set(image, {
              delay: 0,
              duration: 0,
              x: dx,
              y: dy
            });
          });
        }, 100);
      }
    }
  });
  window.addEventListener("elementor/frontend/init", () => {
    elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
      elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
        elementorFrontend.elementsHandler.addHandler(hover_image, {
          $element: $scope
        });
      });
    });
  });
})(jQuery);