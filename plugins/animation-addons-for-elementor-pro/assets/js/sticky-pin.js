($ => {
  window.addEventListener("elementor/frontend/init", () => {
    // Pin Element
    const Modules = elementorModules.frontend.handlers.Base;
    const PingArea = Modules.extend({
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
        if (this.isEdit) {
          return;
        }
        if ("yes" == this.getResponsiveSetting("wcf_enable_pin_area")) {
          this.stickyElement();
        }
        if ("yes" == this.getResponsiveSetting("aae_enable_header_sticky_area")) {
          this.headerSticky();
        }
      },
      stickyElement: function stickyElement() {
        let pin_area = this.$element;
        let pin_area_start = this.getResponsiveSetting("wcf_pin_area_start");
        let pin_area_end = this.getResponsiveSetting("wcf_pin_area_end");
        let end_trigger = this.getResponsiveSetting("wcf_pin_end_trigger");
        let end_trigger_type = this.getResponsiveSetting("wcf_pin_end_trigger_type");
        let pin_status = this.getResponsiveSetting("wcf_pin_status");
        let wcf_pin_spacing = this.getResponsiveSetting("wcf_pin_spacing");
        //let wcf_pin_scrub = this.getResponsiveSetting("wcf_pin_scrub");
        let wcf_pin_markers = this.getElementSettings("wcf_pin_markers");
        let wcf_pin_active_cls = String(this.getElementSettings('wcf_pin_active_cls') || '').trim().replace(/^\.+/, '');
        if (pin_area[0].dataset?.backwordPinEndTrigger && pin_area[0].dataset?.backwordPinEndTrigger != '') {
          end_trigger = pin_area[0].dataset?.backwordPinEndTrigger;
        }
        if (pin_area[0].dataset?.EndTriggerType && pin_area[0].dataset?.EndTriggerType != '') {
          end_trigger = pin_area[0].dataset?.backwordPinEndTrigger;
        }

        // data-backword-end-trigger-desktop

        // if ("number" === wcf_pin_scrub) {
        //   wcf_pin_scrub = this.getResponsiveSetting("wcf_pin_scrub_number");
        // } else {
        //   wcf_pin_scrub = wcf_pin_scrub == "true" ? true : false;
        // }

        if ("custom" === wcf_pin_spacing) {
          wcf_pin_spacing = this.getResponsiveSetting("wcf_pin_spacing_custom");
        } else {
          wcf_pin_spacing = wcf_pin_spacing == "true" ? true : false;
        }
        if ("custom" === pin_status) {
          pin_status = this.getResponsiveSetting("wcf_pin_custom");
        } else {
          pin_status = pin_status == "true" ? true : false;
        }
        if ("custom" === pin_area_start) {
          pin_area_start = this.getResponsiveSetting("wcf_pin_area_start_custom");
          if (this.getResponsiveSetting(`wcf_pin_area_start_custom`) != undefined) {
            pin_area_start = this.getResponsiveSetting(`wcf_pin_area_start_custom`);
          }
        }
        if ("custom" === pin_area_end) {
          pin_area_end = this.getResponsiveSetting("wcf_pin_area_end_custom");
          if (this.getResponsiveSetting(`wcf_pin_area_end_custom`) != undefined) {
            pin_area_end = this.getResponsiveSetting(`wcf_pin_area_end_custom`);
          }
        }
        if (this.getResponsiveSetting("wcf_custom_pin_area")) {
          pin_area = this.getResponsiveSetting("wcf_custom_pin_area");
          // if it's already an element, keep it
          if (!(pin_area instanceof HTMLElement)) {
            // if it's a valid selector string → query it
            if (typeof pin_area === "string" && pin_area.trim() !== "") {
              pin_area = document.querySelector(pin_area);
            }
          }
        }
        this.$element.css("transition", "unset");
        gsap.set(this.$element, {
          'transition': "none"
        });
        let prevY = null; // previous translateY
        const EPS = 0.5; // tolerance to ignore sub-pixel noise
        let translating = false; // current state    

        const setTranslating = isOn => {
          if (isOn === translating) return;
          translating = isOn;
          if (pin_area.length) {
            pin_area[0].classList.toggle('aae-is-translating', isOn); // while translateY is changing
            pin_area[0].parentElement.classList.toggle('aae-is-translating', isOn); // while translateY is changing
          }
        };
        let tempConfig = {
          trigger: pin_area,
          endTrigger: end_trigger,
          pin: pin_status,
          pinSpacing: wcf_pin_spacing,
          start: pin_area_start,
          end: pin_area_end,
          markers: wcf_pin_markers == "true" ? true : false,
          onToggle: self => {
            if (self.isActive) {
              self.trigger.classList.add("aae-pro-sticky-active");
              if (wcf_pin_active_cls) {
                self.trigger.classList.add(wcf_pin_active_cls);
              }
            } else {
              self.trigger.classList.remove("aae-pro-sticky-active");
              if (wcf_pin_active_cls) {
                self.trigger.classList.remove(wcf_pin_active_cls);
              }
            }
          },
          onUpdate: () => {
            if (pin_area.length) {
              const y = gsap.getProperty(pin_area[0], 'y') || 0;
              if (prevY === null) prevY = y;

              // If translateY changed more than EPS, mark as translating.
              const changed = Math.abs(y - prevY) > EPS;
              setTranslating(changed);
              prevY = y;

              // Optional: if you want "momentary" class only while changing, remove it on next frame if stable
              if (!changed && translating) {
                requestAnimationFrame(() => {
                  const y2 = gsap.getProperty(pin_area[0], 'y') || 0;
                  setTranslating(Math.abs(y2 - y) > EPS);
                });
              }
            }
          }
        };
        if (typeof end_trigger === 'undefined' || end_trigger === '') {
          delete tempConfig.endTrigger;
        }
        pinInstance = ScrollTrigger.create(tempConfig);
      },
      headerSticky: function headerSticky() {
        let pin_area = this.$element;
        let pin_area_end = this.getResponsiveSetting("aae_header_sticky_end_trigger");
        let start_position = this.getResponsiveSetting("aae_header_sticky_start_position");
        let z_index = this.getResponsiveSetting("aae_header_sticky_z_index");
        let up_scroll_sticky = this.getResponsiveSetting("aae_header_up_scroll_sticky");
        up_scroll_sticky = up_scroll_sticky == 'yes';
        let ease = this.getResponsiveSetting("aae_header_sticky_ease");
        let duration = this.getResponsiveSetting("aae_header_sticky_duration");
        let style_class = this.getElementSettings("aae_pro_header_sticky_style_cls");
        this.$element.css("transition", "unset");
        gsap.set(this.$element, {
          transition: "none"
        });
        const defaultTop = 0;
        const defaultDuration = duration || 0.3;
        const item = pin_area[0];
        const endClass = pin_area_end ?? '.aae_footer_sticky_header_trigger';
        const startPosition = start_position;
        const startPositionPx = this.convertToPixels(startPosition) + 0;
        const itemClone = item.cloneNode(true);
        itemClone.classList.add("aae-pro-header-sticky-active");
        if (style_class && typeof style_class === "string") {
          const cleanClass = style_class.replace(/^[.#]/, "");
          itemClone.classList.add(cleanClass);
        }
        const wrapper = document.createElement("div");
        wrapper.style.position = "relative";
        wrapper.style.width = "100%";
        wrapper.style.zIndex = z_index || 999;
        item.parentNode.insertBefore(wrapper, item);
        wrapper.appendChild(item);
        wrapper.appendChild(itemClone);
        gsap.set(itemClone, {
          position: 'absolute',
          width: "100%",
          top: 0,
          left: 0,
          right: 0,
          zIndex: z_index || 999,
          opacity: 0,
          y: defaultTop,
          transition: "none",
          willChange: "transform, opacity"
        });
        const cleanup = () => {
          ScrollTrigger.getAll().forEach(trigger => {
            if (trigger.vars.trigger === itemClone) {
              trigger.kill();
            }
          });
          gsap.killTweensOf(itemClone);
          if (window.stickyScrollHandler) {
            window.removeEventListener('scroll', window.stickyScrollHandler);
            window.stickyScrollHandler = null;
          }
        };
        cleanup();
        let lastScrollY = window.scrollY;
        let isVisible = false;
        let isInRange = false;
        if (up_scroll_sticky) {
          gsap.timeline({
            scrollTrigger: {
              trigger: wrapper,
              endTrigger: endClass,
              pin: wrapper,
              pinType: "transform",
              anticipatePin: 1,
              start: `top+=${startPositionPx} top`,
              end: "bottom bottom-=600",
              pinSpacing: false,
              invalidateOnRefresh: true,
              onEnter: () => {
                isInRange = true;
                lastScrollY = window.scrollY;
                gsap.to(item, {
                  opacity: 0
                });
              },
              onLeave: () => {
                isInRange = false;
                isVisible = false;
                gsap.killTweensOf(itemClone);
                gsap.to(itemClone, {
                  y: defaultTop,
                  opacity: 0,
                  duration: defaultDuration,
                  ease: ease || "power2.out",
                  overwrite: true
                });
                gsap.to(item, {
                  opacity: 1
                });
              },
              onEnterBack: () => {
                isInRange = true;
                lastScrollY = window.scrollY;
                gsap.to(item, {
                  opacity: 0
                });
              },
              onLeaveBack: () => {
                isInRange = false;
                isVisible = false;
                gsap.killTweensOf(itemClone);
                gsap.to(itemClone, {
                  y: defaultTop,
                  opacity: 0,
                  duration: defaultDuration,
                  ease: ease || "power2.out",
                  overwrite: true
                });
                gsap.to(item, {
                  opacity: 1
                });
              }
            }
          });
          const scrollHandler = () => {
            if (!isInRange) return;
            const currentScrollY = window.scrollY;
            const scrollDiff = currentScrollY - lastScrollY;
            const isScrollingUp = scrollDiff < -5;
            const isScrollingDown = scrollDiff > 5;
            if (isScrollingUp && !isVisible) {
              gsap.killTweensOf(itemClone);
              gsap.to(itemClone, {
                y: startPositionPx,
                opacity: 1,
                duration: defaultDuration,
                ease: ease || "power2.out",
                overwrite: true
              });
              isVisible = true;
            } else if (isScrollingDown && isVisible) {
              gsap.killTweensOf(itemClone);
              gsap.to(itemClone, {
                y: defaultTop,
                opacity: 0,
                duration: defaultDuration,
                ease: ease || "power2.out",
                overwrite: true
              });
              isVisible = false;
            }
            lastScrollY = currentScrollY;
          };
          window.addEventListener('scroll', scrollHandler, {
            passive: true
          });
          window.stickyScrollHandler = scrollHandler;
        } else {
          let lastScrollY = window.scrollY;
          gsap.timeline({
            scrollTrigger: {
              trigger: wrapper,
              endTrigger: endClass,
              pin: wrapper,
              pinType: "transform",
              anticipatePin: 1,
              start: `top+=${startPositionPx} top`,
              end: "bottom bottom-=600",
              pinSpacing: false,
              invalidateOnRefresh: true,
              onEnter: () => {
                gsap.killTweensOf(itemClone);
                gsap.to(itemClone, {
                  y: startPositionPx,
                  opacity: 1,
                  duration: defaultDuration,
                  ease: ease || "power2.out",
                  overwrite: true
                });
                gsap.to(item, {
                  opacity: 0
                });
              },
              onLeave: () => {
                gsap.killTweensOf(itemClone);
                gsap.to(itemClone, {
                  y: defaultTop,
                  opacity: 0,
                  duration: defaultDuration,
                  ease: ease || "power2.out",
                  overwrite: true
                });
                gsap.to(item, {
                  opacity: 1
                });
              },
              onEnterBack: () => {
                gsap.killTweensOf(itemClone);
                gsap.to(itemClone, {
                  y: startPositionPx,
                  opacity: 1,
                  duration: defaultDuration,
                  ease: ease || "power2.out",
                  overwrite: true
                });
                gsap.to(item, {
                  opacity: 0
                });
              },
              onLeaveBack: () => {
                gsap.killTweensOf(itemClone);
                gsap.to(itemClone, {
                  y: defaultTop,
                  opacity: 0,
                  duration: defaultDuration,
                  ease: ease || "power2.out",
                  overwrite: true
                });
                gsap.to(item, {
                  opacity: 1
                });
              }
            }
          });
          const scrollHandler = () => {
            const currentScrollY = window.scrollY;
            const scrollDiff = currentScrollY - lastScrollY;
            const isScrollingDown = scrollDiff > 5;
            if (isScrollingDown) {
              gsap.to(itemClone, {
                y: startPositionPx
              });
            }
            lastScrollY = currentScrollY;
          };
          window.addEventListener('scroll', scrollHandler, {
            passive: true
          });
          window.stickyScrollHandler = scrollHandler;
        }
      },
      convertToPixels: function convertToPixels(value) {
        if (typeof value === "number") return value;
        if (/^\d+(\.\d+)?$/.test(value)) return parseFloat(value);
        const el = document.createElement("div");
        el.style.position = "absolute";
        el.style.visibility = "hidden";
        el.style.height = value;
        document.body.appendChild(el);
        const px = el.offsetHeight;
        document.body.removeChild(el);
        return px;
      }
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(PingArea, {
        $element: $scope
      });
    });
  });
})(jQuery);