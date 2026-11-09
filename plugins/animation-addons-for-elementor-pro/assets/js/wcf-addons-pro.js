/* global WCF_ADDONS_JS */
(function ($) {
  let adminbar_height = document.querySelector("#wpadminbar") ? document.querySelector("#wpadminbar").clientHeight : 0;
  //tilt
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

  // Make sure you run this code under Elementor.
  $(window).on("elementor/frontend/init", function () {
    const elementorBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;
    const Modules = elementorModules.frontend.handlers.Base;

    //gsap related
    if ("object" === typeof gsap) {
      let gsap_mm = gsap.matchMedia();

      //horizontal scroll
      const horizontal_scroll = Modules.extend({
        getResponsiveSetting(controlName) {
          const currentDevice = elementorFrontend.getCurrentDeviceMode();
          return elementorFrontend.utils.controls.getResponsiveControlValue(this.getElementSettings(), controlName, '', currentDevice);
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
      for (const $skin of ["skin-portfolio-one", "skin-portfolio-two", "skin-portfolio-three", "skin-portfolio-four", "skin-portfolio-five", "skin-portfolio-six", "skin-portfolio-seven", "skin-portfolio-eight", "skin-portfolio-nine"]) {
        elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--a-portfolio.${$skin}`, function ($scope) {
          elementorFrontend.elementsHandler.addHandler(cursor_hover_effect, {
            $element: $scope
          });
        });
      }
      elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
        elementorFrontend.elementsHandler.addHandler(hover_image, {
          $element: $scope
        });
        elementorFrontend.elementsHandler.addHandler(cursor_hover_effect, {
          $element: $scope
        });
        elementorFrontend.elementsHandler.addHandler(horizontal_scroll, {
          $element: $scope
        });
      });

      //scroll elements
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
      elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--scroll-elements.default`, scroll_elements);

      //wcf cursor
      const cursor = function () {
        const cursor_enable = elementorFrontend.getKitSettings("wcf_enable_cursor");
        if (!cursor_enable) {
          return;
        }
        const cursor = $(".wcf-cursor");
        const cursor_follower = $(".wcf-cursor-follower");
        const breakpoint = elementorBreakpoints[elementorFrontend.getKitSettings("wcf_cursor_breakpoint")].value;
        if ($(window).width() < breakpoint) {
          cursor.hide();
          cursor_follower.hide();
          return;
        }
        cursor.css("display", "flex");
        cursor_follower.show();
        gsap.set(cursor, {
          xPercent: -50,
          yPercent: -50,
          scale: 0
        });
        gsap.set(cursor_follower, {
          xPercent: -50,
          yPercent: -50,
          scale: 0
        });
        const setCursorX = gsap.quickTo(cursor, "x", {
          duration: 0.6,
          ease: "power4.out"
        });
        const setCursorFollowerX = gsap.quickTo(cursor_follower, "x", {
          duration: 0.6,
          ease: "power4.out"
        });
        const setCursorY = gsap.quickTo(cursor, "y", {
          duration: 0.6,
          ease: "power4.out"
        });
        const setCursorFollowerY = gsap.quickTo(cursor_follower, "y", {
          duration: 0.6,
          ease: "power4.out"
        });
        const tl = gsap.timeline({
          paused: true
        });
        tl.to(cursor, {
          scale: 1,
          opacity: 1,
          duration: 0.5,
          ease: "power4.out"
        });
        tl.to(cursor_follower, {
          scale: 1,
          opacity: 1,
          duration: 0.5,
          ease: "power4.out"
        });
        $(document).mousemove(function (e) {
          tl.play();
          setCursorX(e.clientX);
          setCursorY(e.clientY);
          setCursorFollowerX(e.clientX);
          setCursorFollowerY(e.clientY);
        });
      };
      cursor();

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
        const smoother = ScrollSmoother.create({
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

    // Wrapper Link
    const wrapper_link = function ($scope) {
      const attr = $scope.data("wcf-wrapper-link");
      if (undefined === attr) {
        return;
      }
      $scope.on("click", function () {
        let anchor = document.createElement("a");
        $(anchor).attr(attr);
        anchor.click();
      });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/container", wrapper_link);

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

    //table of content
    const table_of_content = Modules.extend({
      getDefaultSettings: function getDefaultSettings() {
        const elementSettings = this.getElementSettings(),
          listWrapperTag = "numbers" === elementSettings.marker_view ? "ol" : "ul";
        return {
          selectors: {
            widgetContainer: ".elementor-widget-container",
            postContentContainer: '.elementor:not([data-elementor-type="header"]):not([data-elementor-type="footer"]):not([data-elementor-type="popup"])',
            expandButton: ".toc__toggle-button--expand",
            collapseButton: ".toc__toggle-button--collapse",
            body: ".toc__body",
            headerTitle: ".toc__header-title"
          },
          classes: {
            anchor: "elementor-menu-anchor",
            listWrapper: "toc__list-wrapper",
            listItem: "toc__list-item",
            listTextWrapper: "toc__list-item-text-wrapper",
            firstLevelListItem: "toc__top-level",
            listItemText: "toc__list-item-text",
            activeItem: "elementor-item-active",
            headingAnchor: "toc__heading-anchor",
            collapsed: "toc--collapsed"
          },
          listWrapperTag
        };
      },
      getDefaultElements: function getDefaultElements() {
        const settings = this.getSettings();
        return {
          $pageContainer: this.getContainer(),
          $widgetContainer: this.$element.find(settings.selectors.widgetContainer),
          $expandButton: this.$element.find(settings.selectors.expandButton),
          $collapseButton: this.$element.find(settings.selectors.collapseButton),
          $tocBody: this.$element.find(settings.selectors.body),
          $listItems: this.$element.find("." + settings.classes.listItem)
        };
      },
      getContainer: function getContainer() {
        const elementSettings = this.getElementSettings();

        // If there is a custom container defined by the user, use it as the headings-scan container
        if (elementSettings.container) {
          return jQuery(elementSettings.container);
        }

        // Get the document wrapper element in which the TOC is located
        const $documentWrapper = this.$element.parents(".elementor");

        // If the TOC container is a popup, only scan the popup for headings
        if ("popup" === $documentWrapper.attr("data-elementor-type")) {
          return $documentWrapper;
        }

        // If the TOC container is anything other than a popup, scan only the post/page content for headings
        const settings = this.getSettings();
        return jQuery(settings.selectors.postContentContainer);
      },
      getHeadings: function () {
        // Get all headings from document by user-selected tags
        const elementSettings = this.getElementSettings(),
          tags = elementSettings.headings_by_tags.join(","),
          selectors = this.getSettings("selectors"),
          excludedSelectors = elementSettings.exclude_headings_by_selector;
        return this.elements.$pageContainer.find(tags).not(selectors.headerTitle).filter((index, heading) => {
          ScrollTrigger.create({
            trigger: heading,
            start: "top center",
            end: "bottom center",
            onEnter: () => this.setActiveLink(heading.previousSibling.id),
            onLeaveBack: () => this.setActiveLink(heading.previousSibling.id)
          });
          return !jQuery(heading).closest(excludedSelectors).length; // Handle excluded selectors if there are any
        });
      },
      setActiveLink: function (id) {
        for (const element of this.headingsData) {
          let link = document.querySelector(`[href="#${element.anchorLink}"]`);
          link.classList.toggle("elementor-item-active", link.getAttribute("href") === `#${id}`);
        }
      },
      handleNoHeadingsFound: function () {
        const noHeadingsText = "No headings were found on this page.";
        return this.elements.$tocBody.html(noHeadingsText);
      },
      getHeadingAnchorLink: function (index, classes) {
        const headingID = this.elements.$headings[index].id,
          wrapperID = this.elements.$headings[index].closest(".elementor-widget").id;
        let anchorLink = "";
        if (headingID) {
          anchorLink = headingID;
        } else if (wrapperID) {
          // If the heading itself has an ID, we don't want to overwrite it
          anchorLink = wrapperID;
        }

        // If there is no existing ID, use the heading text to create a semantic ID
        if (headingID || wrapperID) {
          jQuery(this.elements.$headings[index]).data("hasOwnID", true);
        } else {
          anchorLink = `${classes.headingAnchor}-${index}`;
        }
        return anchorLink;
      },
      setHeadingsData: function () {
        this.headingsData = [];
        const classes = this.getSettings("classes");

        // Create an array for simplifying TOC list creation
        this.elements.$headings.each((index, element) => {
          const anchorLink = this.getHeadingAnchorLink(index, classes);
          this.headingsData.push({
            tag: +element.nodeName.slice(1),
            text: element.textContent,
            anchorLink
          });
        });
      },
      addAnchorsBeforeHeadings: function () {
        const classes = this.getSettings("classes");

        // Add an anchor element right before each TOC heading to create anchors for TOC links
        this.elements.$headings.before(index => {
          // Check if the heading element itself has an ID, or if it is a widget which includes a main heading element, whether the widget wrapper has an ID
          if (jQuery(this.elements.$headings[index]).data("hasOwnID")) {
            return;
          }
          return `<span id="${classes.headingAnchor}-${index}" class="${classes.anchor} "></span>`;
        });
      },
      followAnchors: function () {
        this.$listItemTexts = this.$element.find(".toc__list-item-text");
        gsap.registerPlugin(ScrollToPlugin);
        this.$listItemTexts.toArray().forEach(link => {
          link.addEventListener("click", e => {
            e.preventDefault();
            const targetId = link.getAttribute("href");
            gsap.to(window, {
              duration: 0.6,
              scrollTo: targetId,
              ease: "power2.inOut"
            });
          });
        });
      },
      populateTOC: function () {
        this.listItemPointer = 0;
        const elementSettings = this.getElementSettings();
        if (elementSettings.hierarchical_view) {
          this.createNestedList();
        } else {
          this.createFlatList();
        }
        if (!elementorFrontend.isEditMode()) {
          this.followAnchors();
        }
      },
      createNestedList: function () {
        this.headingsData.forEach((heading, index) => {
          heading.level = 0;
          for (let i = index - 1; i >= 0; i--) {
            const currentOrderedItem = this.headingsData[i];
            if (currentOrderedItem.tag <= heading.tag) {
              heading.level = currentOrderedItem.level;
              if (currentOrderedItem.tag < heading.tag) {
                heading.level++;
              }
              break;
            }
          }
        });
        this.elements.$tocBody.html(this.getNestedLevel(0));
      },
      createFlatList: function () {
        this.elements.$tocBody.html(this.getNestedLevel());
      },
      getNestedLevel: function (level) {
        const settings = this.getSettings(),
          elementSettings = this.getElementSettings(),
          icon = this.getElementSettings("icon");
        let renderedIcon;
        if (icon) {
          // We generate the icon markup in PHP and make it available via get_frontend_settings(). As a result, the
          // rendered icon is not available in the editor, so in the editor we use the regular <i> tag.
          if (elementorFrontend.config.experimentalFeatures.e_font_icon_svg && !elementorFrontend.isEditMode()) {
            renderedIcon = icon.rendered_tag;
          } else {
            renderedIcon = `<i class="${icon.value}"></i>`;
          }
        }

        // Open new list/nested list
        let html = `<${settings.listWrapperTag} class="${settings.classes.listWrapper}">`;

        // For each list item, build its markup.
        while (this.listItemPointer < this.headingsData.length) {
          const currentItem = this.headingsData[this.listItemPointer];
          let listItemTextClasses = settings.classes.listItemText;
          if (0 === currentItem.level) {
            // If the current list item is a top level item, give it the first level class
            listItemTextClasses += " " + settings.classes.firstLevelListItem;
          }
          if (level > currentItem.level) {
            break;
          }
          if (level === currentItem.level) {
            html += `<li class="${settings.classes.listItem}">`;
            html += `<div class="${settings.classes.listTextWrapper}">`;
            let liContent = `<a href="#${currentItem.anchorLink}" class="${listItemTextClasses}">${currentItem.text}</a>`;

            // If list type is bullets, add the bullet icon as an <i> tag
            if ("bullets" === elementSettings.marker_view && icon) {
              liContent = `${renderedIcon}${liContent}`;
            }
            html += liContent;
            html += "</div>";
            this.listItemPointer++;
            const nextItem = this.headingsData[this.listItemPointer];
            if (nextItem && level < nextItem.level) {
              // If a new nested list has to be created under the current item,
              // this entire method is called recursively (outside the while loop, a list wrapper is created)
              html += this.getNestedLevel(nextItem.level);
            }
            html += "</li>";
          }
        }
        html += `</${settings.listWrapperTag}>`;
        return html;
      },
      run: function run() {
        this.elements.$headings = this.getHeadings();
        if (!this.elements.$headings.length) {
          return this.handleNoHeadingsFound();
        }
        this.setHeadingsData();
        if (!elementorFrontend.isEditMode()) {
          this.addAnchorsBeforeHeadings();
        }
        this.populateTOC();
        if (this.getElementSettings("minimize_box")) {
          this.collapseBodyListener();
        }
      },
      bindEvents: function bindEvents() {
        this.viewportItems = [];
        this.run();
        const elementSettings = this.getElementSettings();
        if (elementSettings.minimize_box) {
          this.elements.$expandButton.on("click", () => this.expandBox()).on("keyup", event => this.triggerClickOnEnterSpace(event));
          this.elements.$collapseButton.on("click", () => this.collapseBox()).on("keyup", event => this.triggerClickOnEnterSpace(event));
        }
        if (elementSettings.collapse_subitems) {
          this.elements.$listItems.on("hover", event => jQuery(event.target).slideToggle());
        }
      },
      expandBox: function () {
        let changeFocus = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
        const boxHeight = this.getCurrentDeviceSetting("min_height");
        this.$element.removeClass(this.getSettings("classes.collapsed"));
        this.elements.$tocBody.attr("aria-expanded", "true").slideDown();

        // Return container to the full height in case a min-height is defined by the user
        this.elements.$widgetContainer.css("min-height", boxHeight.size + boxHeight.unit);
        if (changeFocus) {
          this.elements.$collapseButton.trigger("focus");
        }
      },
      collapseBox: function () {
        let changeFocus = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
        this.$element.addClass(this.getSettings("classes.collapsed"));
        this.elements.$tocBody.attr("aria-expanded", "false").slideUp();

        // Close container in case a min-height is defined by the user
        this.elements.$widgetContainer.css("min-height", "0px");
        if (changeFocus) {
          this.elements.$expandButton.trigger("focus");
        }
      },
      triggerClickOnEnterSpace: function (event) {
        const ENTER_KEY = 13,
          SPACE_KEY = 32;
        if (ENTER_KEY === event.keyCode || SPACE_KEY === event.keyCode) {
          event.currentTarget.click();
          event.stopPropagation();
        }
      },
      collapseBodyListener: function () {
        const activeBreakpoints = elementorFrontend.breakpoints.getActiveBreakpointsList({
          withDesktop: true
        });
        const minimizedOn = this.getElementSettings("minimized_on"),
          currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
          isCollapsed = this.$element.hasClass(this.getSettings("classes.collapsed"));

        // If minimizedOn value is set to desktop, it applies for widescreen as well.
        if ("desktop" === minimizedOn || activeBreakpoints.indexOf(minimizedOn) >= activeBreakpoints.indexOf(currentDeviceMode)) {
          if (!isCollapsed) {
            this.collapseBox(false);
          }
        } else if (isCollapsed) {
          this.expandBox(false);
        }
      }
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--table-of-contents.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(table_of_content, {
        $element: $scope
      });
    });

    //image accordion
    // const image_accordion = Modules.extend({
    //   run: function run() {
    //     let expand = this.getElementSettings("expand_style");
    //     let accordionItems = this.findElement(".accordion-item");

    //     accordionItems.each((index, item) => {
    //       if ("click" === expand) {
    //         item.addEventListener("click", () => {
    //           this.openAccordion(index, item, accordionItems);
    //         });
    //       } else {
    //         //hover
    //         $(item).mouseenter(() => {
    //           this.openAccordion(index, item, accordionItems);
    //         });

    //         $(item).mouseleave(() => {
    //           item.classList.remove("accordion-hover-active");
    //         });
    //       }
    //     });
    //   },
    //   bindEvents: function bindEvents() {
    //     this.run();
    //   },

    //   openAccordion: function (index, item, accordionItems) {
    //     accordionItems.each((i, single) => {
    //       if (single === item) {
    //         single.classList.add("accordion-hover-active");
    //       } else {
    //         single.classList.remove("accordion-hover-active");
    //       }
    //     });
    //   },
    // });
    // elementorFrontend.hooks.addAction(
    //   "frontend/element_ready/wcf--imag-accordion.default",
    //   function ($scope) {
    //     elementorFrontend.elementsHandler.addHandler(image_accordion, {
    //       $element: $scope,
    //     });
    //   }
    // );

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
          tooltip = this.$element.find(".wcf-advanced-tooltip");
        tooltip.html($.parseHTML(content));
        tooltip.css("animation-duration", duration + "ms");
        if (!showArrow) {
          tooltip.addClass("no-arrow");
        }
        if (trigger === "click") {
          this.$element.on("click", function () {
            if (tooltip.hasClass("show")) {
              tooltip.removeClass("show");
              tooltip.removeClass(animation);
            } else {
              tooltip.addClass("show");
              tooltip.addClass(animation);
            }
          });
        } else if (trigger === "hover") {
          this.$element.on("mouseenter", function () {
            tooltip.addClass("show");
            tooltip.addClass(animation);
          });
          this.$element.on("mouseleave", function () {
            tooltip.removeClass("show");
            tooltip.removeClass(animation);
          });
        }
      }
    });
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

    // Posts Pro
    const PostPro = Modules.extend({
      loadMore: null,
      loadMoreSpin: null,
      anchor: null,
      isLoading: false,
      elementId: null,
      currentPage: 0,
      maxPage: -1,
      bindEvents: function bindEvents() {
        if ("yes" === this.getElementSettings("show_title_highlight")) {
          const length = this.getElementSettings("highlight_title_length");
          const allTitle = this.findElement(".wcf-post-title");
          allTitle.each((index, title) => {
            let current_title = $(title).children("a");
            let current_content = $(title).children("a").text().trim().split(" ");
            const highlight_text = current_content.slice(0, length);
            const normal_text = current_content.slice(length);
            let result = `<span class="highlight">${highlight_text.join(" ")}</span> ${normal_text.join(" ")}`;
            current_title.html(result);
          });
        }
        if (this.isEdit) {
          return;
        }
        this.run();
      },
      run: function run() {
        this.loadMore = this.findElement(".wcf-post-load-more");
        this.loadMoreSpin = this.findElement(".load-more-spinner");
        this.anchor = this.findElement(".load-more-anchor");
        this.elementId = this.getID();
        this.currentPage = this.anchor.data("page");
        this.maxPage = this.anchor.data("max-page");
        let paginationType = this.loadMore.data("type");
        if (this.currentPage == this.maxPage) {
          this.loadMore.css("display", "none");
        }
        if ("load_on_click" === paginationType) {
          this.loadMore.on("click", e => {
            e.preventDefault();
            if (this.currentPage < this.maxPage) {
              ScrollTrigger.refresh();
              this.handlePostsQuery();
            }
          });
        }
        if ("infinite_scroll" === paginationType) {
          const options = {
            rootMargin: "-30%",
            threshold: 1.0
          };
          const observer = new IntersectionObserver(entries => {
            for (const entry of entries) {
              if (entry.isIntersecting) {
                if (this.currentPage < this.maxPage) {
                  this.handlePostsQuery();
                }
              }
            }
          }, options);
          observer.observe(this.anchor[0]);
        }
      },
      handlePostsQuery: function () {
        this.handleUiBeforeLoading();
        if (this.isLoading) {
          this.loadMoreSpin.css("opacity", 1);
          this.$element.find(".load-more-text").css("opacity", 0);
        }
        this.currentPage++;
        const nextPageUrl = this.anchor.attr("data-next-page");
        return fetch(nextPageUrl).then(response => response.text()).then(html => {
          // Convert the HTML string into a document object
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, "text/html");
          this.handleSuccessFetch(doc);
        });
      },
      handleSuccessFetch: function (result) {
        this.handleUiAfterLoading();
        const postsElements = result.querySelectorAll(`[data-id="${this.elementId}"] .wcf-posts > article`);
        $(postsElements).addClass("wcf-hide");
        const nextPageUrl = result.querySelector(`[data-id="${this.elementId}"] .load-more-anchor`).getAttribute("data-next-page");
        postsElements.forEach(element => this.findElement(".wcf-posts").append(element));
        this.anchor.attr("data-page", this.currentPage);
        this.anchor.attr("data-next-page", nextPageUrl);

        /// loading
        setTimeout(() => {
          if (!this.isLoading) {
            this.loadMoreSpin.css("opacity", 0);
            this.$element.find(".load-more-text").css("opacity", 1);
          }
          if (this.currentPage === this.maxPage) {
            ScrollTrigger.refresh();
            this.loadMore.remove();
          }
          $(postsElements).removeClass("wcf-hide");
        }, 300);
      },
      handleUiBeforeLoading: function () {
        this.isLoading = true;
      },
      handleUiAfterLoading: function () {
        this.isLoading = false;
      }
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--posts-pro.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(PostPro, {
        $element: $scope
      });
    });

    // Timeline Posts
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--posts-timeline.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(PostPro, {
        $element: $scope
      });
    });

    // Filterable Posts
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--posts-filter.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(PostPro, {
        $element: $scope
      });
    });

    // Live Events
    elementorFrontend.hooks.addAction("frontend/element_ready/aae--live-events.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(PostPro, {
        $element: $scope
      });
    });

    //Feature Post
    const FeaturePost = Modules.extend({
      bindEvents: function bindEvents() {
        if ("yes" === this.getElementSettings("show_title_highlight")) {
          const length = this.getElementSettings("highlight_title_length");
          const allTitle = this.findElement(".wcf-post-title");
          allTitle.each((index, title) => {
            let current_title = $(title).children("a");
            let current_content = $(title).children("a").text().trim().split(" ");
            const highlight_text = current_content.slice(0, length);
            const normal_text = current_content.slice(length);
            let result = `<span class="highlight">${highlight_text.join(" ")}</span> ${normal_text.join(" ")}`;
            current_title.html(result);
          });
        }
        this.banner();
        if (this.isEdit) {
          return;
        }
        this.run();
      },
      run: function run() {},
      banner: function () {
        let banner = this.findElement(".post-banner");
        let posts = this.findElement(".tabs-wrap .thumb").clone();
        banner.html(posts);
        this.tab();
      },
      tab: function () {
        let posts = this.findElement(".tabs-wrap .wcf-post-title");
        let banner = this.findElement(".post-banner");
        let thumb = banner.find(".thumb");
        this.findElement(".tabs-wrap .wcf-post:first").addClass("active");
        this.findElement(".post-banner .thumb:first").addClass("active");

        //On Click Event
        posts.click(function (e) {
          e.preventDefault();
          let currentPost = $(this).parent(".wcf-post");
          if (currentPost.hasClass("active")) {
            return;
          }
          const active = currentPost.attr("data-id");
          posts.parent(".wcf-post").removeClass("active");
          currentPost.addClass("active");
          thumb.removeClass("active");
          banner.find(`[data-target='${active}']`).addClass("active");
          return false;
        });
        $(document).on("click", ".wcf-post-popup", function (e) {
          e.preventDefault();
          let $_url = $(this).attr("data-src");
          $(`.wcf--popup-video-wrapper`).find(".aae-popup-content-container").html("");
          if ($(this).hasClass("audio")) {
            $(".wcf--popup-video-wrapper").find(".aae-popup-content-container").html(`<div class="audio wcf-audio-wrapper-clean">
                        <audio controls>
                            <source src="${$_url}" type="audio/mpeg">
                        </audio>
                    </div>`);
          }
          if ($(this).hasClass("video")) {
            $(".wcf--popup-video-wrapper").find(".aae-popup-content-container").html(`<iframe  src="${$_url}" ></iframe>`);
          }
          window.VideoAnimation = gsap.timeline({
            defaults: {
              ease: "power2.inOut"
            }
          }).to(`body > .wcf--popup-video-wrapper`, {
            scaleY: 0.01,
            x: 1,
            opacity: 1,
            visibility: "visible",
            duration: 0.4
          }).to(`body > .wcf--popup-video-wrapper`, {
            scaleY: 1,
            duration: 0.6
          }).to(`body > .wcf--popup-video-wrapper .wcf--popup-video`, {
            scaleY: 1,
            opacity: 1,
            visibility: "visible",
            duration: 0.6
          }, "-=0.4");
        });
      }
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--feature-posts.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(FeaturePost, {
        $element: $scope
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--posts-pro.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(FeaturePost, {
        $element: $scope
      });
    });
  });

  //need to run all the page not depending on elementorFrontend
  //scroll to Top
  const scrollToTop = function () {
    const cursor = $(".wcf-scroll-to-top");
    if (cursor.length) {
      if (cursor.hasClass("scroll-to-circle")) {
        let progressPath = document.querySelector(".wcf-scroll-to-top .progress-circle path");
        let pathLength = progressPath.getTotalLength();
        progressPath.style.transition = progressPath.style.WebkitTransition = "none";
        progressPath.style.strokeDasharray = pathLength + " " + pathLength;
        progressPath.style.strokeDashoffset = pathLength;
        progressPath.getBoundingClientRect();
        progressPath.style.transition = progressPath.style.WebkitTransition = "stroke-dashoffset 10ms linear";
        let updateProgress = function () {
          let scroll = $(window).scrollTop();
          let height = $(document).height() - $(window).height();
          let progress = pathLength - scroll * pathLength / height;
          progressPath.style.strokeDashoffset = progress;
        };
        updateProgress();
        $(window).scroll(updateProgress);
      }
      $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
          cursor.addClass("show-scroll-to-top");
        } else {
          cursor.removeClass("show-scroll-to-top");
        }
      });
      cursor.on("click", function () {
        window.scrollTo({
          top: 0,
          behavior: "smooth"
        });
      });
    }
  };
  scrollToTop();

  //preloader
  const preloader = function () {
    const preloader = $(".wcf-preloader");
    if (!preloader.length) {
      return;
    }
    $(document).ready(function () {
      setTimeout(() => {
        $("body.wcf-preloader-active").removeClass("wcf-preloader-active");
        preloader.remove();
      }, 500);
    });
  };
  preloader();

  //scroll indicator
  const scrollIndicator = function () {
    if (!document.querySelector(".wcf-scroll-indicator")) {
      return;
    }
    const handleScrollIndicator = () => {
      const scrollIndicator = document.querySelector(".wcf-scroll-indicator .indicator-bar");
      const maxHeight = document.body.scrollHeight - window.innerHeight;
      const widthPercentage = window.scrollY / maxHeight * 100;
      scrollIndicator.style.width = `${widthPercentage}%`;
    };
    window.addEventListener("scroll", handleScrollIndicator);
    if (document.querySelector(".scroll-indicator-top")) {
      document.querySelector(".scroll-indicator-top").style.top = adminbar_height + "px";
    }
  };
  scrollIndicator();

  //Sticky Header
  if ("function" === typeof ScrollSmoother && "object" === typeof gsap && window.wcf_header_settings) {
    let settings = wcf_header_settings;
    let header = $(`.elementor-${settings.id}`);
    ScrollTrigger.create({
      trigger: "body",
      pin: header,
      pinSpacing: false,
      start: "top top",
      end: "bottom bottom"
    });
  }

  // Read Later Posts
  document.addEventListener("DOMContentLoaded", function () {
    const read_later_btns = document.querySelectorAll(".aae-post-read-later");
    if (read_later_btns.length) {
      function getReadLaterSavedPosts() {
        try {
          const saved = JSON.parse(localStorage.getItem("readLater"));
          return Array.isArray(saved) ? saved : [];
        } catch (e) {
          console.error("Error parsing saved posts:", e);
          return [];
        }
      }
      function saveReadLaterPosts(posts) {
        localStorage.setItem("readLater", JSON.stringify(posts));
      }
      read_later_btns.forEach(btn => {
        const postId = btn.dataset.postId;
        let saved = getReadLaterSavedPosts();

        // Initial state
        if (saved.includes(postId)) {
          btn.textContent = "Saved";
        }
        btn.addEventListener("click", () => {
          saved = getReadLaterSavedPosts();
          if (!saved.includes(postId)) {
            saved.push(postId);
            saveReadLaterPosts(saved);
            btn.textContent = "Saved";
          } else {
            saved = saved.filter(id => id !== postId);
            saveReadLaterPosts(saved);
            btn.textContent = "Read Later";
          }
        });
      });
    }
  });
  const saved_read_later_ids = localStorage.getItem("readLater") || "[]";
  document.cookie = "readLater=" + saved_read_later_ids + "; path=/";
  document.addEventListener("DOMContentLoaded", function () {
    if (typeof gsap !== "object" || typeof ScrollToPlugin === "undefined") {
      console.warn("GSAP or ScrollToPlugin not loaded.");
      return;
    }
    document.querySelectorAll(".aae-scroll-to, .elementor-element a:not(nav a):not(ul a):not(li a)").forEach(function (el) {
      let href = el.getAttribute("href");
      if (!href) return;
      el.addEventListener("click", function (e) {
        let targetHash = "";
        let hrefURL = null;
        try {
          hrefURL = new URL(href, window.location.origin);
          targetHash = hrefURL.hash; // e.g. "#form"
        } catch (err) {
          console.warn("Invalid href:", href);
          return;
        }
        let duration = el.dataset?.duration || 1;
        let ease = el.dataset?.ease || "power2.out";
        if (targetHash) {
          e.preventDefault();
          const targetEl = document.querySelector(targetHash);
          if (targetEl) {
            const currentUrl = window.location.href.split("#")[0]; // remove existing hash
            const newUrl = `${currentUrl}${targetHash}`;
            history.pushState(null, "", newUrl);
            gsap.to(window, {
              duration: duration,
              scrollTo: {
                y: targetEl,
                offsetY: 70
              },
              ease: ease
            });
          } else {
            href = href.replace(/\/#/, "/#!");
            window.location = href;
          }
        }
      });
    });

    // loaded
    if (!document.querySelector(".wcf-nav-menu-container")) {
      let hash = window.location.hash;
      if (hash) {
        hash = hash.replace(/\#!/, "#");
        const targetEl = document.querySelector(hash);
        if (targetEl) {
          gsap.to(window, {
            duration: 1,
            scrollTo: {
              y: targetEl,
              offsetY: 70
            },
            ease: "power2.out",
            onComplete: function () {
              const newUrl = window.location.href.replace("/#!", "/#");
              history.pushState(null, null, newUrl);
            }
          });
        }
      }
    }
  });

  //Sticky Header
  // if (
  //   "function" === typeof ScrollSmoother &&
  //   "object" === typeof gsap &&
  //   window.wcf_header_settings
  // ) {
  //   let settings = wcf_header_settings;
  //   let header = $(`.elementor-${settings.id}`);
  //   ScrollTrigger.create({
  //     trigger: "body",
  //     pin: header,
  //     pinSpacing: false,
  //     start: "top top",
  //     end: "bottom bottom",
  //   });
  // }
})(jQuery);