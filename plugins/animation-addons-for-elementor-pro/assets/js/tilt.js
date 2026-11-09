($ => {
  //table of content
  const Modules = elementorModules.frontend.handlers.Base;
  $.fn.wcf_tilt = function (options) {
    this.settings = $.extend({
      maxTilt: 20,
      perspective: 1000,
      // Transform perspective, the lower the more extreme the tilt gets.
      easing: "cubic-bezier(.03,.98,.52,.99)",
      // Easing on enter/exit.
      scale: 1,
      // 2 = 200%, 1.5 = 150%, etc..
      speed: 3000,
      // Speed of the enter/exit transition.
      reset: true // If the tilt effect has to be reset on exit.
    }, options);
    $(this).css({
      transition: `all ${this.settings.speed}ms ${this.settings.easing}`
    });
    $(this).each((index, item) => {
      $(item).mousemove(e => {
        let cx = window.innerWidth / 2;
        let cy = window.innerHeight / 2;
        let dx = e.clientX - cx;
        let dy = e.clientY - cy;
        let tiltx = dy / cy * this.settings.maxTilt;
        let tilty = -(dx / cx) * this.settings.maxTilt;
        $(item).css({
          transform: `perspective(${this.settings.perspective}px) rotateX(${tiltx}deg) rotateY(${tilty}deg) scale3d(${this.settings.scale},${this.settings.scale},${this.settings.scale})`
        });
      });
      if (this.settings.reset) {
        $(item).mouseleave(e => {
          $(item).css({
            transform: ""
          });
        });
      }
    });
  };

  //Tilt Effect
  const Tilt_Effect = Modules.extend({
    run: function run() {
      if ("yes" !== this.getElementSettings("wcf_enable_tilt")) {
        return;
      }
      if (this.isEdit && !this.getElementSettings("wcf_enable_tilt_editor")) {
        return;
      }
      let settings = {};
      let maxTilt = this.getElementSettings("wcf_max_tilt");
      let perspective = this.getElementSettings("wcf_tilt_perspective");
      let scale = this.getElementSettings("wcf_tilt_scale");
      let speed = this.getElementSettings("wcf_tilt_speed");
      if (maxTilt) {
        settings.maxTilt = maxTilt;
      }
      if (maxTilt) {
        settings.perspective = perspective;
      }
      if (maxTilt) {
        settings.scale = scale;
      }
      if (maxTilt) {
        settings.speed = speed;
      }
      this.$element.wcf_tilt(settings);
    },
    bindEvents: function bindEvents() {
      this.run();
    }
  });
  window.addEventListener("elementor/frontend/init", () => {
    elementorFrontend.hooks.addAction("frontend/element_ready/widget", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(Tilt_Effect, {
        $element: $scope
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(Tilt_Effect, {
        $element: $scope
      });
    });
  });
})(jQuery);