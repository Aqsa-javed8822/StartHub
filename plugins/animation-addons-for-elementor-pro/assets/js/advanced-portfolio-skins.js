($ => {
  window.addEventListener("elementor/frontend/init", () => {
    const elementorBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;
    if ("object" === typeof gsap) {
      let gsap_mm = gsap.matchMedia();
      //advance portfolio
      const advance_portfolio = function ($scope) {
        const animationSettings = $(".wcf--advance-portfolio", $scope).data("animation-settings");
        if ("yes" === animationSettings["enable"]) {
          if ("skin-portfolio-five" === animationSettings["skin"]) {
            animate_portfolio_content_five($scope);
          }
          if ($scope.hasClass("elementor-element-edit-mode") && "" === animationSettings["enable_editor"]) {
            return;
          }
          if (animationSettings["breakpoint"]) {
            const breakpoint = elementorBreakpoints[animationSettings["breakpoint"]].value;
            gsap_mm.add(`(${"min-width: " + breakpoint + "px"})`, () => {
              if ("skin-portfolio-three" === animationSettings["skin"]) {
                animate_portfolio_content_three($scope, animationSettings);
              }
              if ("skin-portfolio-four" === animationSettings["skin"]) {
                animate_portfolio_content_four($scope, animationSettings);
              }
            });
          } else {
            if ("skin-portfolio-three" === animationSettings["skin"]) {
              animate_portfolio_content_three($scope, animationSettings);
            }
            if ("skin-portfolio-four" === animationSettings["skin"]) {
              animate_portfolio_content_four($scope, animationSettings);
            }
          }
        }
        if ("skin-portfolio-eight" === animationSettings["skin"]) {
          animate_portfolio_content_eight($scope, animationSettings);
        }
      };
      const animate_portfolio_content_three = function ($scope, animationSettings) {
        const section_title = $(".section-title", $scope);

        //add the pre styles
        $(".item", $scope).css({
          scale: 0.5,
          opacity: 0,
          "-webkit-transform": "perspective(4000px) rotateX(90deg)",
          transform: "perspective(4000px) rotateX(90deg)"
        });
        let portfolioline = gsap.timeline({
          scrollTrigger: {
            trigger: $scope,
            start: animationSettings["pin_area_start"],
            pin: section_title,
            end: animationSettings["pin_area_end"],
            markers: false,
            pinSpacing: false,
            scrub: 1
          }
        });
        portfolioline.to(section_title, {
          scale: 3,
          duration: 1
        });
        portfolioline.to(section_title, {
          scale: 1,
          duration: 1
        }, "+=2");
        $(".item", $scope).each((index, portfolio) => {
          gsap.set(portfolio, {
            opacity: 0.7
          });
          let t1 = gsap.timeline();
          t1.set(portfolio, {
            position: "relative"
          });
          t1.to(portfolio, {
            scrollTrigger: {
              trigger: portfolio,
              scrub: 2,
              duration: 1.5,
              start: "top bottom+=100",
              end: "bottom center",
              markers: false
            },
            scale: 1,
            opacity: 1,
            rotateX: 0
          });
        });
      };
      const animate_portfolio_content_four = function ($scope, animationSettings = []) {
        let skewSetter = gsap.quickTo($(".wcf--advance-portfolio.skin-portfolio-four img"), "skewY"),
          clamp = gsap.utils.clamp(-15, 15);
        ScrollSmoother.create({
          smooth: 1.35,
          smoothTouch: false,
          normalizeScroll: false,
          ignoreMobileResize: true,
          onUpdate: self => skewSetter(clamp(self.getVelocity() / -80)),
          onStop: () => skewSetter(0)
        });
      };
      const animate_portfolio_content_five = function ($scope) {
        $(".item", $scope).wcf_tilt();
      };
      const animate_portfolio_content_eight = function ($scope, animationSettings) {
        let slider = $(".slider_items", $scope);
        if (slider) {
          document.querySelector(".slider_items").style.display = "none";
          let cols = 1;
          if ($(window).width() > 767) {
            cols = 3;
          }
          const main = document.getElementById("main-" + animationSettings["skin"]);
          let parts = [];
          var slide_item = $(".slide_item", $scope);
          let current = 0;
          let playing = false;
          for (let col = 0; col < cols; col++) {
            let part = document.createElement("div");
            part.className = "part";
            let el = document.createElement("a");
            el.className = "section";
            el.href = $(slide_item[current]).find("a").attr("href");
            el.innerHTML = slide_item[current].innerHTML;
            part.style.setProperty("--x", -100 * col + "%");
            part.style.setProperty("--image-width", $(main).width() + "px");
            part.appendChild(el);
            main.appendChild(part);
            parts.push(part);
          }

          // Rollover UP & Down Mouse Wheel Navigation
          let animOptions = {
            duration: 2.3,
            ease: Power4.easeInOut
          };
          function go(dir) {
            if (!playing) {
              playing = true;
              if (current + dir < 0) current = slide_item.length - 1;else if (current + dir >= slide_item.length) current = 0;else current += dir;
              function up(part, next) {
                part.appendChild(next);
                gsap.to(part, {
                  ...animOptions,
                  y: -window.innerHeight
                }).then(function () {
                  part.children[0].remove();
                  gsap.to(part, {
                    duration: 0,
                    y: 0
                  });
                });
              }
              function down(part, next) {
                part.prepend(next);
                gsap.to(part, {
                  duration: 0,
                  y: -window.innerHeight
                });
                gsap.to(part, {
                  ...animOptions,
                  y: 0
                }).then(function () {
                  part.children[1].remove();
                  playing = false;
                });
              }
              for (let p in parts) {
                let part = parts[p];
                let next = document.createElement("a");
                next.href = $(slide_item[current]).find("a").attr("href");
                next.className = "section";
                next.innerHTML = slide_item[current].innerHTML;
                if ((p - Math.max(0, dir)) % 2) {
                  down(part, next);
                } else {
                  up(part, next);
                }
              }
            }
          }

          //Mouse Wheel Scroll Transition
          let scrollTimeout;
          function wheel(e) {
            clearTimeout(scrollTimeout);
            setTimeout(function () {
              if (e.deltaY < -40) {
                go(-1);
              } else if (e.deltaY >= 40) {
                go(1);
              }
            });
          }
          window.addEventListener("mousewheel", wheel, false);
          window.addEventListener("wheel", wheel, false);
          let alls = document.querySelectorAll(`#main-${animationSettings["skin"]} .part`);
          alls[0].classList.add("showed");
        }
      };
      const advance_portfolio_skin = elementorFrontend.hooks.applyFilters("wcf/widgets/a-portfolio", ["skin-portfolio-three", "skin-portfolio-four", "skin-portfolio-five", "skin-portfolio-eight"]);
      for (const $skin of advance_portfolio_skin) {
        elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--a-portfolio.${$skin}`, advance_portfolio);
      }
      const advance_portfolio_nine = function ($scope) {
        let items = $(".item", $scope);
        let total = items.length;
        if (total < 10) {
          total = "0" + total;
        }
        $(".total", $scope).html(total);
        gsap.timeline({
          scrollTrigger: {
            trigger: $scope,
            start: "top top",
            end: "bottom bottom",
            pin: $(".widget_header", $scope),
            pinSpacing: false,
            scrub: true,
            markers: false
          }
        });
        items.each((i, item) => {
          ScrollTrigger.create({
            trigger: item,
            start: "top center",
            end: "bottom center",
            markers: false,
            onToggle: self => {
              if (self.isActive) {
                $(".current", $scope).html(i + 1);
              }
            }
          });
        });
      };
      elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--a-portfolio.skin-portfolio-nine`, advance_portfolio_nine);
    }

    //slider
    elementorFrontend.hooks.addFilter("wcf/widgets/slider", function (el) {
      const new_slider = {
        "a-portfolio": ["skin-portfolio-one", "skin-portfolio-two", "skin-portfolio-six", "skin-portfolio-seven"]
      };
      return Object.assign({}, el, new_slider);
    });
  });
})(jQuery);