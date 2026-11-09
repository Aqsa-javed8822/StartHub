($ => {
  window.addEventListener("elementor/frontend/init", () => {
    //Animation Elements
    if ("object" === typeof gsap) {
      const Modules = elementorModules.frontend.handlers.Base;
      const wcf_popup = Modules.extend({
        bindEvents: function bindEvents() {
          this.run();
        },
        run: function run() {
          if (this.getElementSettings("wcf_enable_popup")) {
            const container = this.$element[0].closest("[data-elementor-id]");
            const container_id = container.getAttribute("data-elementor-id");
            const loginUser = this.getElementSettings("wcf_enable_login_user");
            const after_xtime = this.getElementSettings("wcf_load_after_xtime") || 0;
            const up_to_xtime = this.getElementSettings("wcf_show_up_to_xtime") || -1;
            const _x_pageviews = this.getElementSettings("wcf_load_after_x_pageviews") || 0;
            const x_devices = this.getElementSettings("wcf_show_x_devices") || [];
            const session_key = "aae_addon_popup_" + this.getID();
            const session_page_key = "aae_addon_page_" + this.getID();
            const limitcross = parseInt(localStorage.getItem(session_key)) || 1;

            //open the popup
            if (this.getElementSettings("popup_condition") === "pageloaded") {
              let checkCon = () => {
                if (this.isEdit && this.getElementSettings("wcf_enable_popup_editor") != "yes") {
                  return;
                }
                if (up_to_xtime > 0) {
                  if (up_to_xtime == limitcross) {
                    return;
                  }
                }
                if (Boolean(WCF_ADDONS_JS?.isLoggedIn)) {
                  if (loginUser === "yes") {
                    return true;
                  } else {
                    return false;
                  }
                }
                if (x_devices.length) {
                  const currentDevice = window.elementorFrontend.getCurrentDeviceMode();
                  if (!x_devices.includes(currentDevice)) {
                    return;
                  }
                }
                if (_x_pageviews) {
                  const pageviewcross = parseInt(localStorage.getItem(session_page_key)) || 0;
                  localStorage.setItem(session_page_key, pageviewcross + 1);
                  if (_x_pageviews > pageviewcross) {
                    return;
                  }
                }
                localStorage.setItem(session_key, limitcross + 1);
                return true;
              };
              if (checkCon()) {
                setTimeout(() => {
                  this.ajax_call(container_id);
                }, after_xtime);
              }
            } else {
              this.$element.on("click", e => {
                e.preventDefault();
                if (this.isEdit && !this.getElementSettings("wcf_enable_popup_editor")) {
                  return;
                }
                this.ajax_call(container_id);
              });
            }
          }
        },
        ajax_call: function ajax_call(container_id) {
          // Store animation globally with unique namespace
          if (!window.WCF_Popup) {
            window.WCF_Popup = {
              animation: null,
              isAnimating: false
            };
          }

          // Prevent opening if already animating
          if (window.WCF_Popup.isAnimating) {
            return;
          }
          $.ajax({
            url: WCF_ADDONS_JS.ajaxUrl,
            data: {
              action: "wcf_load_popup_content",
              element_id: this.getID(),
              post_id: container_id || WCF_ADDONS_JS.post_id,
              nonce: WCF_ADDONS_JS._wpnonce
            },
            dataType: "json",
            type: "POST",
            success: function (response) {
              if (!jQuery("#wcf-aae-global--popup-js").find(".aae-popup-content-container").length) {
                jQuery(`body > #wcf-aae-global--popup-js`).find(".aae-popup-content-container").html(`<div class="aae-popup-content"></div>`);
              }
              jQuery("#wcf-aae-global--popup-js").find(".aae-popup-content-container").html(`${response.data.html}`);

              // Kill existing animation if any
              if (window.WCF_Popup.animation) {
                window.WCF_Popup.animation.kill();
                window.WCF_Popup.animation = null;
              }

              // Set animating flag
              window.WCF_Popup.isAnimating = true;

              // Create opening animation
              window.WCF_Popup.animation = gsap.timeline({
                defaults: {
                  ease: "power2.inOut"
                },
                onComplete: function () {
                  window.WCF_Popup.isAnimating = false;
                }
              }).to(`#wcf-aae-global--popup-js`, {
                scaleY: 0.01,
                x: 1,
                opacity: 1,
                visibility: "visible",
                duration: 0.4
              }).to(`#wcf-aae-global--popup-js`, {
                scaleY: 1,
                duration: 0.6
              }).to(`#wcf-aae-global--popup-js .wcf--popup-video`, {
                scaleY: 1,
                opacity: 1,
                visibility: "visible",
                duration: 0.6
              }, "-=0.4");
            }
          });

          // Setup close handler only once
          if (!window.WCF_Popup.closeHandlerAttached) {
            window.WCF_Popup.closeHandlerAttached = true;
            jQuery(document).on("click", "#wcf-aae-global--popup-js .wcf--popup-close", function (e) {
              e.preventDefault();
              e.stopPropagation();

              // Prevent closing if already animating
              if (window.WCF_Popup.isAnimating) {
                return;
              }

              // Check if animation exists and popup is visible
              if (window.WCF_Popup.animation) {
                window.WCF_Popup.isAnimating = true;

                // Create closing animation
                const closeTimeline = gsap.timeline({
                  onComplete: function () {
                    // Clean up after close
                    jQuery("#wcf-aae-global--popup-js").css({
                      visibility: "hidden",
                      opacity: 0,
                      transform: ""
                    });
                    jQuery("#wcf-aae-global--popup-js .wcf--popup-video").css({
                      visibility: "hidden",
                      opacity: 0,
                      transform: ""
                    });
                    window.WCF_Popup.animation = null;
                    window.WCF_Popup.isAnimating = false;
                  }
                });

                // Animate closing in reverse order
                closeTimeline.to("#wcf-aae-global--popup-js .wcf--popup-video", {
                  scaleY: 0.01,
                  opacity: 0,
                  duration: 0.4,
                  ease: "power2.inOut"
                }).to("#wcf-aae-global--popup-js", {
                  scaleY: 0.01,
                  duration: 0.5,
                  ease: "power2.inOut"
                }, "-=0.2").to("#wcf-aae-global--popup-js", {
                  opacity: 0,
                  duration: 0.3,
                  ease: "power2.inOut"
                }, "-=0.3");
              }
            });
          }
        }
      });
      elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
        elementorFrontend.elementsHandler.addHandler(wcf_popup, {
          $element: $scope
        });
      });
    } // end gsap
  });
})(jQuery);