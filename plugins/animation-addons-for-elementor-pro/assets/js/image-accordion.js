(function ($) {
  //image accordion
  // Make sure you run this code under Elementor.
  $(window).on("elementor/frontend/init", function () {
    const Modules = elementorModules.frontend.handlers.Base;
    const image_accordion = Modules.extend({
      run: function run() {
        let expand = this.getElementSettings("expand_style");
        let accordionItems = this.findElement(".accordion-item");
        accordionItems.each((index, item) => {
          if ("click" === expand) {
            item.addEventListener("click", () => {
              this.openAccordion(index, item, accordionItems);
            });
          } else {
            //hover
            $(item).mouseenter(() => {
              this.openAccordion(index, item, accordionItems);
            });
            $(item).mouseleave(() => {
              item.classList.remove("accordion-hover-active");
            });
          }
        });
      },
      bindEvents: function bindEvents() {
        this.run();
      },
      openAccordion: function (index, item, accordionItems) {
        accordionItems.each((i, single) => {
          if (single === item) {
            single.classList.add("accordion-hover-active");
          } else {
            single.classList.remove("accordion-hover-active");
          }
        });
      }
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--imag-accordion.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(image_accordion, {
        $element: $scope
      });
    });
  });
})(jQuery);