($ => {
  window.addEventListener("elementor/frontend/init", () => {
    if ("object" !== typeof gsap) return;

    // --- Animation variation presets (content-level only) ---
    // Each has contentFrom (initial state) and contentTo (final state).
    // Wrapper reveal stays as original for stability.
    const VARIATIONS = {
      'scale-reveal': {
        from: {
          scaleX: 1,
          scaleY: 0,
          opacity: 0
        },
        to: {
          scaleX: 1,
          scaleY: 1,
          opacity: 1,
          visibility: 'visible'
        },
        ease: 'power2.inOut'
      },
      'zoom-in': {
        from: {
          scale: 0.3,
          opacity: 0,
          transformOrigin: '50% 50%'
        },
        to: {
          scale: 1,
          opacity: 1,
          visibility: 'visible'
        },
        ease: 'back.out(1.6)'
      },
      'fade-slide-up': {
        from: {
          scale: 1,
          y: 120,
          opacity: 0
        },
        to: {
          scale: 1,
          y: 0,
          opacity: 1,
          visibility: 'visible'
        },
        ease: 'power3.out'
      },
      'flip-3d': {
        from: {
          scale: 1,
          rotationY: 90,
          opacity: 0,
          transformPerspective: 1000
        },
        to: {
          scale: 1,
          rotationY: 0,
          opacity: 1,
          visibility: 'visible'
        },
        ease: 'power2.out'
      },
      'curtain-split': {
        from: {
          scaleY: 1,
          scaleX: 0,
          transformOrigin: '50% 50%'
        },
        to: {
          scaleY: 1,
          scaleX: 1,
          opacity: 1,
          visibility: 'visible'
        },
        ease: 'power4.inOut'
      },
      'blur-in': {
        from: {
          opacity: 0,
          filter: 'blur(30px)',
          scale: 1.1
        },
        to: {
          opacity: 1,
          filter: 'blur(0px)',
          scale: 1,
          visibility: 'visible'
        },
        ease: 'power2.out'
      },
      'rotate-in': {
        from: {
          rotation: -180,
          scale: 0.3,
          opacity: 0,
          transformOrigin: '50% 50%'
        },
        to: {
          rotation: 0,
          scale: 1,
          opacity: 1,
          visibility: 'visible'
        },
        ease: 'back.out(1.4)'
      },
      'elastic-bounce': {
        from: {
          scale: 0,
          opacity: 0,
          transformOrigin: '50% 50%'
        },
        to: {
          scale: 1,
          opacity: 1,
          visibility: 'visible'
        },
        ease: 'elastic.out(1, 0.6)'
      }
    };
    const safeJson = str => {
      if (!str) return {};
      try {
        return JSON.parse(str) || {};
      } catch (e) {
        return {};
      }
    };
    const applyStyles = (el, map) => {
      if (!el || !map) return;
      Object.entries(map).forEach(([k, v]) => {
        if (v) el.style[k] = v;
      });
    };

    // --- Per-widget customizations applied to the shared global popup ---
    const applyWidgetCustomizations = (btn, popupWrapper) => {
      const content = popupWrapper.querySelector('.wcf--popup-video');
      const closeBtn = popupWrapper.querySelector('.wcf--popup-close');
      if (!content) return;
      const closeStyle = safeJson(btn.getAttribute('data-close-style'));
      const closeIconHtml = btn.getAttribute('data-close-icon');
      const overlayColor = btn.getAttribute('data-overlay-color');
      const popupWid = btn.getAttribute('data-popup-wid');

      // Tag wrapper with this widget's id so scoped <style> rules apply
      if (popupWid) popupWrapper.dataset.wid = popupWid;

      // Overlay color on wrapper
      if (overlayColor) popupWrapper.style.backgroundColor = overlayColor;

      // Close icon markup override
      if (closeBtn && closeIconHtml) closeBtn.innerHTML = closeIconHtml;

      // Close button styling
      if (closeBtn) {
        applyStyles(closeBtn, {
          color: closeStyle.color || '',
          fill: closeStyle.color || '',
          backgroundColor: closeStyle.bg || '',
          borderColor: closeStyle.border || ''
        });
        // Stash for hover revert
        closeBtn.dataset.origColor = closeStyle.color || '';
        closeBtn.dataset.origBg = closeStyle.bg || '';
        closeBtn.dataset.origBorder = closeStyle.border || '';
        closeBtn.dataset.hoverColor = closeStyle.colorHover || '';
        closeBtn.dataset.hoverBg = closeStyle.bgHover || '';
        closeBtn.dataset.hoverBorder = closeStyle.borderHover || '';
        bindCloseHover(closeBtn);
      }
    };
    const bindCloseHover = closeBtn => {
      if (closeBtn.dataset.aaeHoverBound) return;
      closeBtn.dataset.aaeHoverBound = '1';
      closeBtn.addEventListener('mouseenter', () => {
        const {
          hoverColor,
          hoverBg,
          hoverBorder
        } = closeBtn.dataset;
        if (hoverColor) {
          closeBtn.style.color = hoverColor;
          closeBtn.style.fill = hoverColor;
        }
        if (hoverBg) closeBtn.style.backgroundColor = hoverBg;
        if (hoverBorder) closeBtn.style.borderColor = hoverBorder;
      });
      closeBtn.addEventListener('mouseleave', () => {
        const {
          origColor,
          origBg,
          origBorder
        } = closeBtn.dataset;
        closeBtn.style.color = origColor || '';
        closeBtn.style.fill = origColor || '';
        closeBtn.style.backgroundColor = origBg || '';
        closeBtn.style.borderColor = origBorder || '';
      });
    };

    // --- Original video_popup open flow, with variation support injected ---
    const video_popup = function (currentEle) {
      if (!currentEle.length) return;
      currentEle = currentEle[0];
      let popup_content = document.querySelector(".wcf--popup-video-wrapper");
      if (popup_content && popup_content.parentNode.tagName.toLowerCase() !== "body") {
        if (!document.querySelector("body > .wcf--popup-video-wrapper")) {
          document.body.appendChild(popup_content);
        }
      }
      const innerPopup = currentEle.querySelector(".wcf--popup-video-wrapper");
      if (innerPopup) innerPopup.remove();
      const open_popup = currentEle.querySelectorAll(".wcf-popup-btn");
      open_popup.forEach(btn => {
        btn.addEventListener("click", () => {
          const url = btn.getAttribute("data-src");
          const popupWrapper = document.querySelector("body > .wcf--popup-video-wrapper");
          if (!popupWrapper) return;
          const container = popupWrapper.querySelector(".aae-popup-content-container");
          container.innerHTML = "";

          // Apply per-widget customizations BEFORE animating
          applyWidgetCustomizations(btn, popupWrapper);

          // Inject iframe (original behavior)
          if (!popupWrapper.querySelector("iframe")) {
            container.innerHTML = `<iframe src="${url}" ></iframe>`;
          }
          const variationKey = btn.getAttribute('data-popup-animation') || 'scale-reveal';
          const duration = parseFloat(btn.getAttribute('data-popup-duration')) || 0.8;
          const variation = VARIATIONS[variationKey] || VARIATIONS['scale-reveal'];

          // Neutralize any lingering transform on the content box so variations that don't
          // explicitly set scale don't inherit scaleY:0 from the base CSS.
          gsap.set(".wcf--popup-video-wrapper .wcf--popup-video", {
            clearProps: "transform,filter"
          });
          if (variationKey === 'scale-reveal') {
            // Original 3-step scaleY reveal (default only)
            window.VideoAnimation = gsap.timeline({
              defaults: {
                ease: "power2.inOut"
              }
            }).to(".wcf--popup-video-wrapper", {
              scaleY: 0.01,
              x: 1,
              opacity: 1,
              visibility: "visible",
              duration: 0.4
            }).to(".wcf--popup-video-wrapper", {
              scaleY: 1,
              duration: 0.6
            }).fromTo(".wcf--popup-video-wrapper .wcf--popup-video", variation.from, Object.assign({}, variation.to, {
              duration: duration,
              ease: variation.ease
            }), "-=0.4");
          } else {
            // Overlay + content animate together as one motion
            window.VideoAnimation = gsap.timeline().set(".wcf--popup-video-wrapper", {
              scaleY: 1,
              x: 0
            }).to(".wcf--popup-video-wrapper", {
              opacity: 1,
              visibility: "visible",
              duration: duration,
              ease: variation.ease
            }, 0).fromTo(".wcf--popup-video-wrapper .wcf--popup-video", variation.from, Object.assign({}, variation.to, {
              duration: duration,
              ease: variation.ease
            }), 0);
          }
        });
      });
    };

    // Video Box (unchanged)
    const video_box = function ($currentEle) {
      const video_box_video = jQuery(".thumb video", $currentEle);
      if (video_box_video.length) {
        jQuery(".wcf--video-box", $currentEle).hover(function () {
          video_box_video.get(0).play();
        }, function () {
          video_box_video.get(0).pause();
          video_box_video.get(0).currentTime = 0;
        });
      }
    };
    const video_widgets = ["video-box", "video-box-slider"];
    for (const widget of video_widgets) {
      elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--${widget}.default`, video_box);
    }
    elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--video-popup.default`, video_popup);
    elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--video-box.default`, video_popup);
    elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--video-box-slider.default`, video_popup);
  });
})(jQuery);