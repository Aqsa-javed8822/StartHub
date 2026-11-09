($ => {
  const scroll_elements = function ($scope) {
    const links = $(".scroll-title", $scope);
    const images = $(".image-wrap img", $scope);
    const sections = $(".single-content", $scope);
    const data_navigation = $(".wcf--scroll-elements", $scope).data("navigation");
    const data_image = $(".wcf--scroll-elements", $scope).data("image");
    if ("yes" === data_navigation) {
      gsap.timeline({
        scrollTrigger: {
          trigger: $scope,
          start: "top top",
          end: "bottom bottom",
          pin: $(".scroll-nav-bar", $scope),
          pinSpacing: false,
          scrub: true,
          markers: false
        }
      });
      links.each((index, item) => {
        $(item).on("click", function (e) {
          gsap.to(window, {
            duration: 1,
            scrollTo: {
              y: sections[index],
              autoKill: true,
              ease: "power2"
            }
          });
          links.removeClass("active");
          $(this).addClass("active");
        });
      });
    }
    if ("yes" === data_image) {
      gsap.timeline({
        scrollTrigger: {
          trigger: $scope,
          pin: $(".scroll-images", $scope),
          pinSpacing: false,
          start: "top top",
          end: "bottom bottom",
          markers: false
        }
      });
    }
    if ("yes" === data_navigation || "yes" === data_image) {
      sections.each((i, section) => {
        ScrollTrigger.create({
          trigger: section,
          start: "top center",
          end: "bottom center",
          markers: false,
          onToggle: self => {
            if (self.isActive) {
              if ("yes" === data_navigation) {
                gsap.to(links[i], {
                  scale: 1,
                  //1.3
                  onStart: function () {
                    $(this._targets).addClass("active");
                  }
                });
              }
              if ("yes" === data_image) {
                gsap.to(images[i], {
                  opacity: 1,
                  duration: 1,
                  scale: 1
                });
              }
            } else {
              if ("yes" === data_navigation) {
                gsap.to(links[i], {
                  scale: 1,
                  onStart: function () {
                    $(this._targets).removeClass("active");
                  }
                });
              }
              if ("yes" === data_image) {
                gsap.to(images[i], {
                  opacity: 0,
                  duration: 1,
                  scale: 1.2
                });
              }
            }
          }
        });
      });
    }
  };
  window.addEventListener("elementor/frontend/init", () => {
    elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--scroll-elements.default`, scroll_elements);
  });
})(jQuery);