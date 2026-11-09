(() => {
  //table of content
  const Modules = elementorModules.frontend.handlers.Base;

  //advanced tooltip
  const Advanced_tooltip = Modules.extend({
    onInit: function onInit() {
      if ("enable" !== this.getElementSettings("wcf_advanced_tooltip_enable")) {
        return;
      }
      this.$element.append("<span class='wcf-advanced-tooltip animated'></span>");
      this.run();
    },
    run: function run() {
      let trigger = this.getElementSettings("wcf_advanced_tooltip_trigger"),
        content = this.getElementSettings("wcf_advanced_tooltip_content"),
        animation = this.getElementSettings("wcf_advanced_tooltip_animation"),
        duration = this.getElementSettings("wcf_advanced_tooltip_duration") || 500,
        showArrow = this.getElementSettings("wcf_advanced_tooltip_arrow") || false,
        tooltip = this.$element[0].querySelector(".wcf-advanced-tooltip");
      const parsed = new DOMParser().parseFromString(content, "text/html").body.childNodes;
      tooltip.innerHTML = "";
      tooltip.append(...parsed);
      tooltip.style['animation-duration'] = duration + "ms";
      if (!showArrow) {
        tooltip.classList.add("no-arrow");
      }
      if (trigger === "click") {
        this.$element[0].addEventListener("click", () => {
          if (tooltip.classList.contains("show")) {
            tooltip.classList.remove("show", animation);
          } else {
            tooltip.classList.add("show", animation);
          }
        });
      } else if (trigger === "hover") {
        this.$element[0].addEventListener("mouseenter", () => {
          tooltip.classList.add("show", animation);
        });
        this.$element[0].addEventListener("mouseleave", () => {
          tooltip.classList.remove("show", animation);
        });
      }
    }
  });
  window.addEventListener("elementor/frontend/init", () => {
    elementorFrontend.hooks.addAction("frontend/element_ready/widget", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(Advanced_tooltip, {
        $element: $scope
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(Advanced_tooltip, {
        $element: $scope
      });
    });
  });
})();