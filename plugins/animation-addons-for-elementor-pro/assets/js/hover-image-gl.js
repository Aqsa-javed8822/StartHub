(() => {
  class HoverImageHandler extends elementorModules.frontend.handlers.Base {
    // Called when the handler initializes
    onInit() {
      if (super.onInit) super.onInit();
      this.run();
    }
    run() {
      // Always clean up old event listeners first to prevent duplicates
      if (this._cleanup) this._cleanup();

      // // Check settings
      if (this.getElementSettings('wcf_enable_hover_image') !== 'yes') {
        return;
      }
      if (this.isEdit && this.getElementSettings('wcf_enable_hover_image_editor') !== 'yes') {
        return;
      }
      const el = this.$element && this.$element[0] || this.$element;
      if (!el || el.nodeType !== 1) return;
      // Ensure container is positioned to allow absolute children
      const computedPos = window.getComputedStyle(el).position;
      if (computedPos === 'static') {
        el.style.position = 'relative';
      }

      // Create hover element if missing
      let hover = el.querySelector('.wcf-image-hover');
      if (!hover) {
        hover = document.createElement('div');
        hover.className = 'wcf-image-hover';
        // Inline styles minimal — feel free to move to CSS
        hover.style.position = 'absolute';
        hover.style.pointerEvents = 'none';
        hover.style.transform = 'translate(-50%, -50%)';
        hover.style.willChange = 'transform, opacity';
        el.appendChild(hover);
      }

      // Event handlers using native DOM APIs
      const onEnter = () => {
        if (window.gsap) {
          gsap.to(hover, {
            autoAlpha: 1,
            duration: 0
          });
        } else {
          hover.style.opacity = '1';
        }
      };
      const onLeave = () => {
        if (window.gsap) {
          gsap.to(hover, {
            autoAlpha: 0,
            duration: 0
          });
        } else {
          hover.style.opacity = '0';
        }
      };
      const onMove = e => {
        const rect = el.getBoundingClientRect();
        const dx = e.clientX - rect.left;
        const dy = e.clientY - rect.top;
        if (window.gsap) {
          gsap.set(hover, {
            x: dx,
            y: dy,
            duration: 0
          });
        } else {
          hover.style.transform = `translate(${dx}px, ${dy}px) translate(-50%, -50%)`;
        }
      };

      // Attach listeners and keep references for cleanup
      el.addEventListener('mouseenter', onEnter);
      el.addEventListener('mouseleave', onLeave);
      el.addEventListener('mousemove', onMove);
      this._cleanup = () => {
        el.removeEventListener('mouseenter', onEnter);
        el.removeEventListener('mouseleave', onLeave);
        el.removeEventListener('mousemove', onMove);
      };
    }

    // React to editor setting changes
    onElementChange(propertyName) {
      if (propertyName === 'wcf_enable_hover_image' || propertyName === 'wcf_enable_hover_image_editor') {
        if (this._cleanup) this._cleanup();
        const el = this.$element && this.$element[0] || this.$element;
        let hover = el.querySelector('.wcf-image-hover');
        if (hover) {
          hover.remove();
        }
        if (this.getElementSettings('wcf_enable_hover_image') === 'yes') {
          if (this.getElementSettings('wcf_enable_hover_image_editor') === 'yes') {
            this.run();
          }
        }
      }
    }

    // Clean up when element destroyed/unmounted
    onDestroy() {
      if (this._cleanup) this._cleanup();
    }
  }

  // Registration helper: register for the 'container' scope (replace 'container' with widget name if needed)
  function registerHandler() {
    elementorFrontend.hooks.addAction('frontend/element_ready/container', $scope => {
      elementorFrontend.elementsHandler.addHandler(HoverImageHandler, {
        $element: $scope
      });
    });
  }
  window.addEventListener('elementor/frontend/init', registerHandler);
})();