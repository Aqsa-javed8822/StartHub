($ => {
  const Modules = elementorModules.frontend.handlers.Base;
  //mouse hover effect
  const mouse_move_effect = Modules.extend({
    bindEvents: function bindEvents() {
      this.run();
    },
    run: function run() {
      let move_area = this.$element;
      if (this.isEdit) {
        if (this.getElementSettings("wcf_enable_mouse_movee_editor") != "yes") {
          move_area.off("mousemove", this.move_effect);
          return;
        }
      }
      if (!this.getElementSettings("wcf_enable_mouse_move_effect")) {
        return;
      }
      if ("custom" === this.getElementSettings("wcf_mouse_move_area_trigger")) {
        move_area = $(this.getElementSettings("wcf_custom_mouse_move_area"));
      }
      move_area.on("mousemove", this.move_effect);
    },
    getTypedValue: function (value) {
      if (!isNaN(Number(value)) && value !== true && value !== false) {
        return Number(value);
      } else {
        if (value == "true") {
          return true;
        } else if (value == "false") {
          return false;
        } else {
          return value;
        }
      }
    },
    move_effect: function (e) {
      const moveX = this.getElementSettings("wcf_mouse_move_x");
      const moveY = this.getElementSettings("wcf_mouse_move_y");
      const duration = this.getElementSettings("wcf_mouse_move_duration");
      const customConfig = this.get_custom_config();

      // Get window width and height
      const windowWidth = window.innerWidth;
      const windowHeight = window.innerHeight;

      // Calculate the percentage of the cursor's position relative to the screen
      const xPosPercent = e.clientX / windowWidth - 0.5;
      const yPosPercent = e.clientY / windowHeight - 0.5;
      const defaults = {
        x: xPosPercent * moveX,
        y: yPosPercent * moveY,
        ease: "power3.out",
        duration: duration
      };
      const config = Object.assign({}, customConfig, defaults);

      // GSAP animation to move the image
      gsap.to(this.$element, config);
    },
    get_custom_config: function () {
      const custom = this.getElementSettings("wcf_mouse_move_custom");
      let data = {};
      if (!custom) {
        return data;
      }
      if (custom.length) {
        const properties = custom.split(",");
        properties.map(el => {
          if (0 === el.replace(/\s/g, "").length) {
            return;
          }
          let property = el.split(":").filter(e => 0 !== e.replace(/\s/g, "").length);
          if (2 !== property.length) {
            return;
          }

          // First item of the array
          let f = property[0].replace(/\s/g, "");

          // Last item of the array
          let l = property[property.length - 1].replace(/\s/g, "");
          data[f] = this.getTypedValue(l);
        });
      }
      return data;
    }
  });
  window.addEventListener("elementor/frontend/init", () => {
    elementorFrontend.hooks.addAction("frontend/element_ready/widget", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(mouse_move_effect, {
        $element: $scope
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(mouse_move_effect, {
        $element: $scope
      });
    });
  });
})(jQuery);