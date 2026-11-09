($ => {
  const Modules = elementorModules.frontend.handlers.Base;
  //cursor hover effect
  const cursor_hover_effect = Modules.extend({
    bindEvents: function bindEvents() {
      this.run();
    },
    run: function run() {
      if (this.getElementSettings("wcf_enable_cursor_hover_effect")) {
        const widget_id = this.getID();
        const text = this.getElementSettings("wcf_enable_cursor_hover_effect_text");
        let cursor = $(`.wcf-hover-cursor-effect.active-${widget_id}`);
        if (this.isEdit && !this.getElementSettings("wcf_enable_cursor_hover_effect_editor")) {
          cursor.css({
            display: "none"
          });
          return;
        }
        cursor.css({
          display: "flex"
        });
        if (!$(`.wcf-hover-cursor-effect.active-${widget_id}`).length) {
          $("body").prepend(`<div class="wcf-hover-cursor-effect active-${widget_id}"></div>`);
        }
        cursor = $(`.wcf-hover-cursor-effect.active-${widget_id}`);
        let element = $(this.$element);
        if ("wcf--a-portfolio" === this.getWidgetType()) {
          element = $(this.findElement("article"));
        }
        gsap.set(cursor, {
          xPercent: -50,
          yPercent: -50,
          scale: 0
        });
        const setCursorX = gsap.quickTo(cursor, "x", {
          duration: 0.6,
          ease: "expo"
        });
        const setCursorY = gsap.quickTo(cursor, "y", {
          duration: 0.6,
          ease: "expo"
        });
        const tl = gsap.timeline({
          paused: true
        });
        tl.to(cursor, {
          scale: 1,
          opacity: 1,
          duration: 0.5,
          ease: "expo.inOut"
        });
        $(document).mousemove(function (e) {
          setCursorX(e.clientX);
          setCursorY(e.clientY);
        });
        $(element).mouseenter(e => {
          tl.play();
          cursor.html(text);
        });
        $(element).mouseleave(e => {
          tl.reverse();
        });
      }
    }
  });
  window.addEventListener("elementor/frontend/init", () => {
    for (const $skin of ["skin-portfolio-one", "skin-portfolio-two", "skin-portfolio-three", "skin-portfolio-four", "skin-portfolio-five", "skin-portfolio-six", "skin-portfolio-seven", "skin-portfolio-eight", "skin-portfolio-nine"]) {
      elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--a-portfolio.${$skin}`, function ($scope) {
        elementorFrontend.elementsHandler.addHandler(cursor_hover_effect, {
          $element: $scope
        });
      });
    }

    // end loop
    elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(cursor_hover_effect, {
        $element: $scope
      });
    });
  });
})(jQuery);