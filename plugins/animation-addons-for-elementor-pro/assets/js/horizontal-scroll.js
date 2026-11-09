($ => {
  const Modules = elementorModules.frontend.handlers.Base;
  //horizontal scroll
  const horizontal_scroll = Modules.extend({
    getResponsiveSetting(controlName) {
      const settings = this.getElementSettings();
      const currentDevice = elementorFrontend.getCurrentDeviceMode();
      const devices = ['mobile', 'mobile_extra', 'tablet', 'tablet_extra', 'laptop', 'desktop', 'widescreen'];
      const currentIndex = devices.indexOf(currentDevice);
      const start = currentIndex >= 0 ? currentIndex : devices.indexOf('desktop');
      for (let i = start; i < devices.length; i++) {
        const value = elementorFrontend.utils.controls.getResponsiveControlValue(settings, controlName, '', devices[i]);
        // check for valid slider object or simple value
        if (value && typeof value === 'object') {
          if (value.size !== undefined && value.size !== '' && !isNaN(value.size)) {
            return value;
          }
        } else if (value !== undefined && value !== null && value !== '') {
          return value;
        }
      }

      // Loop downward for missing upper levels (e.g., widescreen → desktop → tablet)
      for (let i = start - 1; i >= 0; i--) {
        const value = elementorFrontend.utils.controls.getResponsiveControlValue(settings, controlName, '', devices[i]);
        if (value && typeof value === 'object') {
          if (value.size !== undefined && value.size !== '' && !isNaN(value.size)) {
            return value;
          }
        } else if (value !== undefined && value !== null && value !== '') {
          return value;
        }
      }

      // Final fallback to base control
      return this.getElementSettings(controlName);
    },
    bindEvents: function bindEvents() {
      this.run();
    },
    run: function run() {
      if (this.isEdit) {
        return;
      }
      if ("yes" !== this.getResponsiveSetting("wcf_enable_horizontal_scroll")) {
        return;
      }
      let sections = this.$element.children();
      let element = this.$element;
      let end = this.getResponsiveSetting("horizontal_scroll_end");
      end = end["size"];
      if (end == "") {
        end = element[0].offsetWidth;
      }
      let width = this.getResponsiveSetting("horizontal_scroll_width");
      width = width["size"] + width["unit"];
      if (this.$element.hasClass("e-con-boxed")) {
        gsap.set(element[0], {
          width: "100%"
        });
        element = this.$element.children();
        sections = this.$element.children(".e-con-inner").children();
      }
      if (!sections.length) {
        return;
      }
      element.addClass("wcf-horizontal-scroll");
      const run = () => {
        element.css({
          width: width,
          "max-width": width,
          transition: "none",
          height: "100%",
          display: "flex",
          "flex-wrap": "nowrap",
          "flex-direction": "row"
        });
        sections.css({
          transition: "none",
          height: "100%"
        });
        gsap.to(sections, {
          xPercent: -100 * (sections.length - 1),
          ease: "none",
          scrollTrigger: {
            trigger: element,
            pin: true,
            scrub: 1,
            end: "+=" + end,
            invalidateOnRefresh: true
          }
        });
        return () => {
          // custom cleanup code here (runs when it STOPS matching)
          element.css({
            width: "var(--width)",
            "max-width": "min(100%,var(--width))",
            height: "auto"
          });
        };
      };
      run();
    }
  });
  window.addEventListener("elementor/frontend/init", () => {
    elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(horizontal_scroll, {
        $element: $scope
      });
    });
  });
})(jQuery);