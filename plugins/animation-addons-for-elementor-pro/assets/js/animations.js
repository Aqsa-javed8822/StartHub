($ => {
  window.addEventListener("elementor/frontend/init", () => {
    if ("object" === typeof gsap) {
      let trigger_selector_store = [];
      if ("object" === typeof ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);
      }
      const Modules = elementorModules.frontend.handlers.Base;
      const Animation = Modules.extend({
        bindEvents: function bindEvents() {
          this.run();
        },
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
        run: function run() {
          if ("none" === this.getResponsiveSetting("wcf-animation") && this.isEdit) {
            this.apply_trigger({
              trigger_type: "on_scroll",
              trigger_selector: trigger_selector_store[this.getID()],
              aae_method: "from",
              finalConfig: {}
            });
          } else {
            this.fade_animation();
            this.custom_animation();
          }
          //widget animation
          if ("widget" === this.getElementType()) {
            //text animation
            if (!this.isEdit) {
              document.fonts.ready.then(() => {
                this.text_animation();
              });
            } else {
              this.text_animation();
            }
            //image animation
            this.image_animation();
          }
          // Button Move Animation
          this.button_move_animation();
        },
        responsive_match: function responsive_match($element, callback) {
          const device = elementorFrontend.getCurrentDeviceMode();
          if ($element.length) {
            const hasOffClass = Array.from($element[0].classList).some(cls => cls.startsWith('aae-off-'));
            if (hasOffClass) {
              const deviceClass = 'aae-off-' + device;
              if ($element[0].classList.contains(deviceClass)) {
                return true;
              }
              if (typeof callback === "function") {
                callback();
                return true;
              }
            }
            return false;
          }
          return false;
        },
        text_animation: function text_animation() {
          if (this.isEdit && !this.getElementSettings("wcf_text_animation_editor")) {
            return;
          }
          if (this.getResponsiveSetting("wcf_text_animation") === "none") return;
          const trigger_type = this.getResponsiveSetting("aae_text_trigger");
          const trigger_selector = this.getResponsiveSetting("aae_trigger_text_selector");
          const text_wrapper = this.getResponsiveSetting("aae_anim_txt_wrapper");
          const text_start_trigger = this.getResponsiveSetting("aae_anim_txt_s_t");
          const text_end_trigger = this.getResponsiveSetting("aae_anim_txt_e_t");
          const text_start = this.getResponsiveSetting("aae_anim_txt_s");
          const text_start_cus = this.getResponsiveSetting("aae_anim_txt_s_cus");
          const text_end = this.getResponsiveSetting("aae_anim_txt_e");
          const text_end_cus = this.getResponsiveSetting("aae_anim_txt_e_cus");
          let text_marker = this.getElementSettings("aae_anim_txt_markers");
          text_marker = text_marker == 'true' ? true : false;

          //charter and word animation
          if ("char" === this.getResponsiveSetting("wcf_text_animation") || "word" === this.getResponsiveSetting("wcf_text_animation")) {
            const duration_value = this.getResponsiveSetting("text_duration");
            const stagger_value = this.getResponsiveSetting("text_stagger");
            const translateX_value = this.getResponsiveSetting("text_translate_x");
            const translateY_value = this.getResponsiveSetting("text_translate_y");
            const data_delay = this.getResponsiveSetting("text_delay");
            let aae_text_scrub = this.getResponsiveSetting("spin_text_scrub");
            let element = null;
            let length = 0;
            if (this.findElement(".elementor-widget-container").length) {
              length = this.findElement(".elementor-widget-container").children().length;
              element = $(this.findElement(".elementor-widget-container").children()[length - 1]);
            } else {
              if (this.isEdit) {
                length = this.$element.children().length;
                element = $(this.$element.children()[length - 2]);
              } else {
                element = this.$element;
              }
            }
            if ("number" === aae_text_scrub) {
              aae_text_scrub = this.getElementSettings("wcf_pin_scrub_number");
            } else {
              aae_text_scrub = aae_text_scrub == "yes" ? true : false;
            }
            let config = {
              duration: duration_value,
              autoAlpha: 0,
              delay: data_delay,
              stagger: stagger_value
            };
            if (translateX_value) {
              config.x = translateX_value;
            }
            if (translateY_value) {
              config.y = translateY_value;
            }
            document.fonts.ready.then(() => {
              if (element[0]) {
                let split_word = new SplitText(element[0], {
                  type: "chars, words"
                });
                let split = split_word.chars;
                if ("word" === this.getResponsiveSetting("wcf_text_animation")) {
                  split = split_word.words;
                }
                this.apply_trigger({
                  trigger_type,
                  trigger_selector,
                  aae_method: "from",
                  finalConfig: config,
                  element: split,
                  isSplit: true,
                  wrapper_type: text_wrapper,
                  start_trigger: text_start_trigger,
                  end_trigger: text_end_trigger,
                  start: text_start,
                  start_cus: text_start_cus,
                  end: text_end,
                  end_cus: text_end_cus,
                  scrub: aae_text_scrub,
                  markers: text_marker
                });
                return () => {
                  config = {};
                  split.revert();
                };
              }
            });
          }

          //text_move_animation
          if ("text_move" === this.getResponsiveSetting("wcf_text_animation")) {
            const duration_value = this.getResponsiveSetting("text_duration") || 1;
            const data_delay = this.getResponsiveSetting("text_delay") || 0.15;
            const stagger_value = this.getResponsiveSetting("text_stagger") || 0.02;
            const rotation_di = this.getResponsiveSetting("text_rotation_di") || 'x';
            const rotation_value = this.getResponsiveSetting("text_rotation") || '-80';
            let aae_text_scrub = this.getResponsiveSetting("spin_text_scrub");
            const transformOrigin = this.getResponsiveSetting("text_transform_origin");
            let element = null;
            let length = 0;
            if (this.findElement(".elementor-widget-container").length) {
              length = this.findElement(".elementor-widget-container").children().length;
              element = $(this.findElement(".elementor-widget-container").children()[length - 1]);
            } else {
              if (this.isEdit) {
                length = this.$element.children().length;
                element = $(this.$element.children()[length - 2]);
              } else {
                element = this.$element;
              }
            }
            if (element.hasClass("wcf--text") && element.children().length) {
              element = element.children();
            }
            if ("number" === aae_text_scrub) {
              aae_text_scrub = this.getResponsiveSetting("wcf_pin_scrub_number");
            } else {
              aae_text_scrub = aae_text_scrub == "yes" ? true : false;
            }
            let config = {
              duration: duration_value,
              delay: data_delay,
              opacity: 0,
              force3D: true,
              transformOrigin: transformOrigin,
              stagger: stagger_value
            };
            if ("x" === rotation_di) {
              config.rotationX = rotation_value;
            }
            if ("y" === rotation_di) {
              config.rotationY = rotation_value;
            }
            if (element) {
              const split = new SplitText(element, {
                type: "lines"
              });
              gsap.set(element, {
                perspective: 400
              });
              this.apply_trigger({
                trigger_type,
                trigger_selector,
                aae_method: "from",
                finalConfig: config,
                element: split.lines,
                isSplit: true,
                wrapper_type: text_wrapper,
                start_trigger: text_start_trigger,
                end_trigger: text_end_trigger,
                start: text_start,
                start_cus: text_start_cus,
                end: text_end,
                end_cus: text_end_cus,
                scrub: aae_text_scrub,
                markers: text_marker
              });
              return () => {
                split.revert();
              };
            }
          }

          //text-reveal-animation
          if ("text_reveal" === this.getResponsiveSetting("wcf_text_animation")) {
            const duration_value = this.getResponsiveSetting("text_duration") || 1;
            const stagger_value = this.getResponsiveSetting("text_stagger") || 0.02;
            const data_delay = this.getResponsiveSetting("text_delay") || 0.15;
            let aae_text_scrub = this.getResponsiveSetting("spin_text_scrub");
            let element = null;
            let length = 0;
            if (this.findElement(".elementor-widget-container").length) {
              length = this.findElement(".elementor-widget-container").children().length;
              element = $(this.findElement(".elementor-widget-container").children()[length - 1]);
            } else {
              if (this.isEdit) {
                length = this.$element.children().length;
                element = $(this.$element.children()[length - 2]);
              } else {
                element = this.$element;
              }
            }
            if ("number" === aae_text_scrub) {
              aae_text_scrub = this.getResponsiveSetting("wcf_pin_scrub_number");
            } else {
              aae_text_scrub = aae_text_scrub == "yes" ? true : false;
            }
            const config = {
              duration: duration_value,
              delay: data_delay,
              ease: "circ.out",
              y: 80,
              stagger: stagger_value,
              opacity: 0
            };
            if (element) {
              let split = new SplitText(element, {
                type: "lines,words,chars",
                linesClass: "anim-reveal-line"
              });
              this.apply_trigger({
                trigger_type,
                trigger_selector,
                aae_method: "from",
                finalConfig: config,
                element: split.chars,
                isSplit: true,
                wrapper_type: text_wrapper,
                start_trigger: text_start_trigger,
                end_trigger: text_end_trigger,
                start: text_start,
                start_cus: text_start_cus,
                end: text_end,
                end_cus: text_end_cus,
                scrub: aae_text_scrub,
                markers: text_marker
              });
              return () => {
                split.revert();
              };
            }
          }

          // Text Invert With Scroll
          if ("text_invert" === this.getResponsiveSetting("wcf_text_animation")) {
            const aae_anim_invert_start = this.getResponsiveSetting('aae_anim_invert_s') || 'top 85%';
            const aae_anim_invert_end = this.getResponsiveSetting('aae_anim_invert_e') || 'bottom center';
            const RGBToHSL = (r, g, b) => {
              r /= 255;
              g /= 255;
              b /= 255;
              const l = Math.max(r, g, b);
              const s = l - Math.min(r, g, b);
              const h = s ? l === r ? (g - b) / s : l === g ? 2 + (b - r) / s : 4 + (r - g) / s : 0;
              return [60 * h < 0 ? 60 * h + 360 : 60 * h, 100 * (s ? l <= 0.5 ? s / (2 * l - s) : s / (2 - (2 * l - s)) : 0), 100 * (2 * l - s) / 2];
            };
            let element = null;
            let length = 0;
            if (this.findElement(".elementor-widget-container").length) {
              length = this.findElement(".elementor-widget-container").children().length;
              element = $(this.findElement(".elementor-widget-container").children()[length - 1]);
            } else {
              if (this.isEdit) {
                length = this.$element.children().length;
                element = $(this.$element.children()[length - 2]);
              } else {
                element = this.$element.children(); // fixing issue when Optimized Markup option active 
              }
            }
            let color = element.css("color");
            color = color.toString();
            color = color.match(/(\d+)/g);
            color = RGBToHSL(color[0], color[1], color[2]);
            color = `${color[0].toFixed(1)}, ${color[1].toFixed(1)}%, ${color[2].toFixed(1)}%`;
            element.css("--text-color", color);
            if (element) {
              const split = new SplitText(element, {
                type: "lines",
                linesClass: "invert-line"
              });
              split.lines.forEach(target => {
                gsap.to(target, {
                  backgroundPositionX: 0,
                  ease: "none",
                  scrollTrigger: {
                    trigger: target,
                    scrub: 1,
                    start: aae_anim_invert_start,
                    end: aae_anim_invert_end
                  }
                });
              });
            }
          }

          // Spin Text

          if ("text_spin" === this.getResponsiveSetting("wcf_text_animation")) {
            if (this.responsive_match(this.$element)) return;
            let lastEl = null;
            let container = null;
            let length = 0;
            if (this.findElement(".elementor-widget-container").length) {
              length = this.findElement(".elementor-widget-container").children().length;
              container = this.findElement(".elementor-widget-container");
              lastEl = container.children().last();
            } else {
              if (this.isEdit) {
                length = this.$element.children().length;
                container = $(this.$element.children()[length - 2]);
                lastEl = container;
              } else {
                container = lastEl = this.$element;
              }
            }
            const clone = lastEl[0].cloneNode(true);
            jQuery(clone).addClass("duplicate-text");
            lastEl.css({
              perspective: "600px",
              "white-space": "nowrap"
            });
            jQuery(clone).css({
              perspective: "600px",
              "white-space": "nowrap"
            });
            lastEl.after(clone);
            gsap.set(clone, {
              yPercent: -100
            });
            const originalSplit = lastEl[0] ? new SplitText(lastEl[0], {
              type: "chars"
            }) : null;
            const cloneSplit = clone ? new SplitText(clone, {
              type: "chars"
            }) : null;
            gsap.set(cloneSplit.chars, {
              opacity: 0
            });
            const delay = this.getResponsiveSetting("text_delay") || 0;
            const duration = 0.4;
            const stagger = {
              each: 0.03,
              ease: "power1",
              from: "start"
            };
            const height = lastEl[0].offsetHeight;
            const origin = `50% 50% -${height / 2}`;
            const createTimeline = () => {
              const tl = gsap.timeline();
              tl.set(cloneSplit.chars, {
                rotationX: -90,
                transformOrigin: origin
              });
              tl.to(originalSplit.chars, {
                delay,
                duration,
                rotationX: 90,
                transformOrigin: origin,
                opacity: 0,
                stagger,
                ease: "power2.in"
              }, 0);
              tl.to(cloneSplit.chars, {
                duration: 0.001,
                delay,
                opacity: 1,
                stagger
              }, 0.001);
              tl.to(cloneSplit.chars, {
                duration,
                delay,
                rotationX: 0,
                stagger
              }, 0);
              return tl;
            };
            const runAnimation = () => {
              createTimeline();
            };
            const debounce = (func, wait = 300) => {
              let timeout;
              return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
              };
            };
            if ((trigger_type === "click" || trigger_type === "mouseover") && trigger_selector && document.querySelector(trigger_selector)) {
              const target = document.querySelector(trigger_selector);
              const newTarget = target.cloneNode(true);
              target.parentNode.replaceChild(newTarget, target);
              const debouncedAnimation = debounce(runAnimation, 500);
              newTarget.addEventListener(trigger_type, debouncedAnimation);
            } else if (trigger_type === "on_scroll" || trigger_type === "play_with_scroll") {
              const tl = createTimeline();
              ScrollTrigger.create({
                animation: tl,
                trigger: container ? container[0] : this.$element,
                start: this.getElementSettings("spin_text_start") || "top 85%",
                end: this.getElementSettings("spin_text_end") || "top 30%",
                scrub: trigger_type === "play_with_scroll",
                toggleActions: this.getElementSettings("spin_text_toggle_action") || "play none none none",
                invalidateOnRefresh: true
              });
            } else {
              // "on_load" or no trigger_type provided
              runAnimation();
            }
            return () => {
              originalSplit.revert();
              cloneSplit.revert();
            };
          }

          // Text Scale Animation
          if ("text_scale" === this.getResponsiveSetting("wcf_text_animation")) {
            const data_delay = this.getResponsiveSetting("text_delay") || 0.15;
            const duration_value = this.getResponsiveSetting("text_duration") || 1;
            const stagger_value = this.getResponsiveSetting("text_stagger") || 0.02;
            const text_scale_num = this.getResponsiveSetting("text_scale_num") || 1.5;
            let wcf_pin_scrub = this.getResponsiveSetting("spin_text_scrub");
            let scale_text_ease = this.getResponsiveSetting("scale_text_ease");
            const text_scale_break = this.getResponsiveSetting("text_scale_break") || 'lines';
            let container = null;
            let lastElement = null;
            let length = 0;
            if (this.findElement(".elementor-widget-container").length) {
              container = this.findElement(".elementor-widget-container");
              lastElement = container.find(":not(span.highlight)").last()[0];
            } else {
              if (this.isEdit) {
                length = this.$element.children().length;
                lastElement = $(this.$element.children()[length - 2]);
              } else {
                lastElement = this.$element;
              }
            }
            if ("number" === wcf_pin_scrub) {
              wcf_pin_scrub = this.getResponsiveSetting("wcf_pin_scrub_number");
            } else {
              wcf_pin_scrub = wcf_pin_scrub == "yes" ? true : false;
            }

            // Split the last element
            if (lastElement) {
              const split = new SplitText(lastElement, {
                type: "lines words chars",
                linesClass: "text-scale-anim"
              });

              // Now get the words to animate
              const text_scale_anim = split[text_scale_break];
              if (!text_scale_anim.length) {
                return;
              }
              const scale_config = {
                duration: duration_value,
                autoAlpha: 0,
                scale: text_scale_num,
                stagger: stagger_value,
                transformOrigin: "50% 0%",
                ease: scale_text_ease,
                delay: data_delay
              };
              this.apply_trigger({
                trigger_type,
                trigger_selector,
                aae_method: "from",
                finalConfig: scale_config,
                element: text_scale_anim,
                isSplit: true,
                wrapper_type: text_wrapper,
                start_trigger: text_start_trigger,
                end_trigger: text_end_trigger,
                start: text_start,
                start_cus: text_start_cus,
                end: text_end,
                end_cus: text_end_cus,
                scrub: wcf_pin_scrub,
                markers: text_marker
              });
              return () => {
                split.revert();
              };
            }
          }
        },
        image_animation: function image_animation() {
          if (this.isEdit && !this.getElementSettings("wcf_img_animation_editor")) {
            return;
          }
          if ("none" === this.getResponsiveSetting("wcf-image-animation")) {
            return;
          }
          if ("reveal" === this.getResponsiveSetting("wcf-image-animation")) {
            let wrap = this.findElement("img").parent();
            const element = this.$element;
            this.findElement("img").parent().parent().css("overflow", "hidden");
            wrap.css({
              overflow: "hidden",
              display: "block",
              visibility: "hidden",
              transition: "none"
            });
            let start = this.getResponsiveSetting("wcf-animation-start");
            if ("custom" === this.getResponsiveSetting("wcf-animation-start")) {
              start = this.getResponsiveSetting("wcf_animation_custom_start");
            }
            let start_from = this.getResponsiveSetting("aae_a_start_from");
            let ease = this.getResponsiveSetting("image-ease");
            let image_hover_effect = false;
            let image_hover_class = ["effect-zoom-in", "effect-zoom-out", "left-move", "right-move"];
            let image_hover_effect_class = "";
            $.each(image_hover_class, function (index, value) {
              if (element.hasClass(`wcf--image-${value}`)) {
                image_hover_effect = true;
                image_hover_effect_class = `wcf--image-${value}`;
                element.removeClass(image_hover_effect_class);
              }
            });
            wrap.each(function () {
              let image = jQuery(this).find("img");
              let tl = gsap.timeline({
                scrollTrigger: {
                  trigger: jQuery(this),
                  start: start
                }
              });
              let contentAnim = {
                ease: ease,
                onComplete
              };
              let imageAnim = {
                scale: 1.3,
                delay: -1.5,
                ease: ease
              };
              switch (start_from) {
                case "left":
                  contentAnim.xPercent = 100;
                  imageAnim.xPercent = -100;
                  break;
                case "right":
                  contentAnim.xPercent = -100;
                  imageAnim.xPercent = 100;
                  break;
                case "top":
                  contentAnim.yPercent = 100;
                  imageAnim.yPercent = -100;
                  break;
                case "bottom":
                  contentAnim.yPercent = -100;
                  imageAnim.yPercent = 100;
                  break;
              }
              function onComplete() {
                if (image_hover_effect) {
                  element.addClass(image_hover_effect_class);
                  image_hover_effect = false;
                }
              }
              tl.set(jQuery(this), {
                autoAlpha: 1
              });
              tl.from(jQuery(this), 1.5, contentAnim);
              tl.from(image, 1.5, imageAnim);
            });
          }
          if ("scale" === this.getResponsiveSetting("wcf-image-animation")) {
            let image = this.findElement("img");
            let start = this.getResponsiveSetting("wcf-animation-start");
            if ("custom" === this.getResponsiveSetting("wcf-animation-start")) {
              start = this.getResponsiveSetting("wcf_animation_custom_start");
            }
            gsap.set(image, {
              scale: this.getResponsiveSetting("wcf-scale-start") || 0.5
            });
            gsap.to(image, {
              scrollTrigger: {
                trigger: this.$element,
                start: start,
                scrub: true
              },
              scale: this.getResponsiveSetting("wcf-scale-end") || 1,
              ease: this.getResponsiveSetting("image-ease")
            });
            image.parent().css("overflow", "hidden");
          }
          if ("stretch" === this.getResponsiveSetting("wcf-image-animation")) {
            let image = this.findElement("img");
            let wrap = this.findElement("img").parent();
            wrap.css("padding-bottom", "395px");
            let imageStretch = gsap.timeline({
              scrollTrigger: {
                trigger: wrap,
                start: "top top",
                pin: true,
                scrub: 1,
                pinSpacing: false,
                end: "bottom bottom+=100"
              }
            });
            imageStretch.to(image, {
              width: "100%",
              borderRadius: "0px"
            });
            wrap.css("transition", "none");
          }
        },
        fade_animation: function fade_animation() {
          if (this.getResponsiveSetting("wcf-animation") === "none" || this.getResponsiveSetting("wcf-animation") === "custom") return;
          if (this.isEdit && !this.getElementSettings("wcf_enable_animation_editor")) {
            return;
          }
          const fade_direction = this.getResponsiveSetting("fade-from") || "bottom";
          const trigger_type = this.getResponsiveSetting("aae_trigger") || "on_scroll";
          const trigger_selector = this.getResponsiveSetting("aae_trigger_selector");
          if (trigger_selector) {
            trigger_selector_store[this.getID()] = trigger_selector;
          }
          const wrapper = this.getResponsiveSetting("aae_anim_wrapper");
          const start_trigger = this.getResponsiveSetting("aae_anim_s_t");
          const end_trigger = this.getResponsiveSetting("aae_anim_e_t");
          const start = this.getResponsiveSetting("aae_anim_s");
          const start_cus = this.getResponsiveSetting("aae_anim_s_cus");
          const end = this.getResponsiveSetting("aae_anim_e");
          const end_cus = this.getResponsiveSetting("aae_anim_e_cus");
          let markers = this.getResponsiveSetting("aae_anim_markers");
          markers = markers == 'true' ? true : false;
          const aae_method = this.getResponsiveSetting("aae_method") || "from";
          const duration_value = this.getResponsiveSetting("data-duration");
          const fade_offset = this.getResponsiveSetting("fade-offset");
          const delay_value = this.getResponsiveSetting("delay");
          const ease_value = this.getResponsiveSetting("ease");
          let config = {
            opacity: 0,
            ease: ease_value,
            //ease: "steps(12)",
            duration: duration_value,
            //  duration: 0.1,
            delay: delay_value
            // delay: 0.1,
          };
          if (this.getElementType() === "container") {
            this.$element.addClass("aae-disable-transition");
          }
          this.$element.css("transition", "none");
          if ("fade" === this.getResponsiveSetting("wcf-animation")) {
            if ("top" === fade_direction) {
              config.y = -fade_offset;
            }
            if ("bottom" === fade_direction) {
              config.y = fade_offset;
            }
            if ("left" === fade_direction) {
              config.x = -fade_offset;
            }
            if ("right" === fade_direction) {
              config.x = fade_offset;
            }
            if ("scale" === fade_direction) {
              config.scale = this.getResponsiveSetting("wcf-a-scale");
            }
            if (this.getResponsiveSetting("wcf-animation") !== this.getElementSettings("wcf-animation")) {
              if (!fade_offset && !config?.x && !config?.y) {
                config.y = 50;
              }
            }
          }
          // 3D Move Animation         

          if ("move" === this.getResponsiveSetting("wcf-animation")) {
            const rotation_di = this.getResponsiveSetting("wcf_a_rotation_di");
            const transformOrigin = this.getResponsiveSetting("wcf_a_transform_origin");
            const rotation = this.getResponsiveSetting("wcf_a_rotation");
            config.force3D = true;
            config.transformOrigin = transformOrigin;
            if ("x" === rotation_di) {
              config.rotationX = rotation;
            }
            if ("y" === rotation_di) {
              config.rotationY = rotation;
            }
            if (this.getResponsiveSetting("wcf-animation") !== this.getElementSettings("wcf-animation")) {
              if (!config?.rotation_di) {
                config.rotationX = -80;
              }
              if (!config?.transformOrigin) {
                config.transformOrigin = 'top center -50';
              }
            }
            gsap.set(this.$element.parent(), {
              perspective: 400
            });
          }
          this.apply_trigger({
            trigger_type,
            trigger_selector,
            aae_method,
            finalConfig: config,
            element: this.$element,
            wrapper_type: wrapper,
            start_trigger,
            end_trigger,
            start,
            start_cus,
            end,
            end_cus,
            markers
          });
        },
        custom_animation: function custom_animation() {
          if (this.getResponsiveSetting("wcf-animation") !== "custom") {
            return;
          }
          if (this.isEdit && "none" == this.getResponsiveSetting("wcf-animation")) {
            gsap.killTweensOf(this.$element); // Kills all animations affecting .box
            gsap.set(this.$element, {
              clearProps: "all"
            }); // reset inline styles
            if (document.querySelector(trigger_selector)) {
              const oldElement = document.querySelector(trigger_selector);
              const newElement = oldElement.cloneNode(true);
              oldElement.parentNode.replaceChild(newElement, oldElement);
            }
          }
          if ("custom" !== this.getResponsiveSetting("wcf-animation")) {
            return;
          }
          if (this.isEdit && !this.getResponsiveSetting("wcf_enable_animation_editor")) {
            return;
          }
          const ease_value = this.getResponsiveSetting("ease");
          const trigger_type = this.getResponsiveSetting("aae_trigger");
          const trigger_selector = this.getResponsiveSetting("aae_trigger_selector");
          if (trigger_selector) {
            trigger_selector_store[this.getID()] = trigger_selector;
          }
          const wrapper = this.getResponsiveSetting("aae_anim_wrapper");
          const start_trigger = this.getResponsiveSetting("aae_anim_s_t");
          const end_trigger = this.getResponsiveSetting("aae_anim_e_t");
          const start = this.getResponsiveSetting("aae_anim_s");
          const start_cus = this.getResponsiveSetting("aae_anim_s_cus");
          const end = this.getResponsiveSetting("aae_anim_e");
          const end_cus = this.getResponsiveSetting("aae_anim_e_cus");
          let markers = this.getResponsiveSetting("aae_anim_markers");
          markers = markers == 'true' ? true : false;
          const aae_method = this.getResponsiveSetting("aae_method") || "from";
          const custom_props = this.getResponsiveSetting("aae_ani_custom_props");
          let config = {
            ease: ease_value
          };
          if (this.getElementType() === "container") {
            this.$element.addClass("aae-disable-transition");
          }
          this.$element.css("transition", "none");
          const refineprops = custom_props.reduce((out, {
            property,
            value
          }) => ({
            ...out,
            [property]: this.perse_value(value)
          }), {});
          const finalConfig = {
            ...refineprops,
            ...config
          };
          this.apply_trigger({
            trigger_type,
            trigger_selector,
            aae_method,
            finalConfig,
            wrapper_type: wrapper,
            start_trigger,
            end_trigger,
            start,
            start_cus,
            end,
            end_cus,
            markers
          });
        },
        generate_animation: function generate_animation({
          aae_method,
          finalConfig,
          isKillAnim = true,
          element,
          isSplit
        }) {
          const target = element || this.$element;
          let tween = null;
          const runTween = () => {
            tween = gsap[aae_method](target, {
              ...finalConfig,
              onComplete: () => {
                if (!isKillAnim) return;
                tween.kill();
                if (!isSplit) {
                  gsap.set(target, {
                    clearProps: "all"
                  });
                }
                tween = null;
              }
            });
          };
          runTween();
        },
        apply_trigger: function apply_trigger({
          trigger_type,
          trigger_selector,
          aae_method,
          finalConfig,
          element,
          isSplit = false,
          wrapper_type,
          start_trigger,
          end_trigger,
          start,
          start_cus,
          end,
          end_cus,
          scrub,
          markers
        }) {
          const target = element || this.$element;
          gsap.killTweensOf(target);
          // if (!isSplit) gsap.set(target, { clearProps: "all" });

          if (!isSplit && (trigger_type == "mouseover" || trigger_type === "click")) gsap.set(target, {
            clearProps: "all"
          });
          const debounce = (func, wait = 300) => {
            let timeout;
            return function (...args) {
              clearTimeout(timeout);
              timeout = setTimeout(() => func.apply(this, args), wait);
            };
          };
          const runAnimation = () => {
            if (trigger_type === "on_scroll" || trigger_type === "play_with_scroll") {
              const scrollTriggerConfig = {
                trigger: target[0],
                start: "top 85%",
                scrub: trigger_type === "play_with_scroll"
              };
              if (wrapper_type === "custom") {
                if (start_trigger) scrollTriggerConfig.trigger = start_trigger;
                if (end_trigger) scrollTriggerConfig.endTrigger = end_trigger;
                if (markers) scrollTriggerConfig.markers = markers;
                if (scrub) scrollTriggerConfig.scrub = scrub;
                if (start) {
                  if (start === "custom") {
                    scrollTriggerConfig.start = start_cus;
                  } else {
                    scrollTriggerConfig.start = start;
                  }
                }
                if (end) {
                  if (end === "custom") {
                    scrollTriggerConfig.end = end_cus;
                  } else {
                    scrollTriggerConfig.end = end;
                  }
                }
              }
              let section = this.$element;

              // Convert to DOM element if it's a selector string
              if (typeof section === "string") {
                section = document.querySelector(section);
              }
              if (section?.length) {
                const dataId = section[0].getAttribute("data-id");
                if (dataId) scrollTriggerConfig.id = dataId;
              } else {
                const dataId = section.getAttribute("data-id");
                if (dataId) scrollTriggerConfig.id = dataId;
              }
              if (window.gsap && window.ScrollTrigger) {
                const existing = ScrollTrigger.getById(scrollTriggerConfig.id);
                if (existing) existing.kill();
              }
              finalConfig = {
                ...finalConfig,
                scrollTrigger: scrollTriggerConfig
              };
            }
            this.generate_animation({
              aae_method,
              finalConfig,
              isKillAnim: trigger_type !== "play_with_scroll" && trigger_type !== "on_scroll",
              element,
              isSplit
            });
          };

          // Check if it's a hover or click trigger
          if ((trigger_type === "mouseover" || trigger_type === "click") && trigger_selector && document.querySelector(trigger_selector)) {
            const oldEl = document.querySelector(trigger_selector);
            const newEl = oldEl.cloneNode(true);
            oldEl.parentNode.replaceChild(newEl, oldEl);
            const debouncedHandler = debounce(runAnimation, 500);
            newEl.addEventListener(trigger_type, debouncedHandler);
          } else {
            runAnimation();
          }
        },
        perse_value: function parse_value(value) {
          if (typeof value !== "string") return value;
          const lower = value.toLowerCase().trim();

          // Boolean detection
          if (lower === "true") return true;
          if (lower === "false") return false;

          // Pure number (integer or float)
          if (/^-?\d+(\.\d+)?$/.test(value)) {
            return parseFloat(value);
          }

          // All other values: return as string
          return value;
        },
        button_move_animation: function button_move_animation() {
          if (this.getResponsiveSetting("wcf_text_animation") === "none") return;
          const btnWrap = this.findElement(".btn-wrapper");
          const btnCircle = this.findElement(".btn-item");
          if (btnWrap.length) {
            btnWrap.mousemove(function (e) {
              callParallax(e);
            });
            function callParallax(e) {
              parallaxIt(e, btnCircle, 80);
            }
            function parallaxIt(e, target, movement) {
              const relX = e.pageX - btnWrap.offset().left;
              const relY = e.pageY - btnWrap.offset().top;
              gsap.to(target, 0.5, {
                x: (relX - btnWrap.width() / 2) / btnWrap.width() * movement,
                y: (relY - btnWrap.height() / 2) / btnWrap.height() * movement,
                ease: Power2.easeOut
              });
            }
            btnWrap.mouseleave(function (e) {
              gsap.to(btnCircle, 0.5, {
                x: 0,
                y: 0,
                ease: Power2.easeOut
              });
            });
          }

          // Button Hover Animation
          const btn_hover_all = this.findElement(".btn-hover-bgchange");
          if (btn_hover_all.length) {
            const newSpan = document.createElement("span");
            btn_hover_all.append(newSpan);
            btn_hover_all.on("mouseenter", function (e) {
              var x = e.pageX - jQuery(this).offset().left;
              var y = e.pageY - jQuery(this).offset().top;
              jQuery(this).find("span").css({
                top: y,
                left: x
              });
            });
            btn_hover_all.on("mouseout", function (e) {
              var x = e.pageX - jQuery(this).offset().left;
              var y = e.pageY - jQuery(this).offset().top;
              jQuery(this).find("span").css({
                top: y,
                left: x
              });
            });
          }
        }
      });
      elementorFrontend.hooks.addAction("frontend/element_ready/widget", function ($scope) {
        elementorFrontend.elementsHandler.addHandler(Animation, {
          $element: $scope
        });
      });
      elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
        elementorFrontend.elementsHandler.addHandler(Animation, {
          $element: $scope
        });
      });
    }
  });
})(jQuery);