(function ($) {
  /**
   * @param $scope The Widget wrapper element as a jQuery element
   * @param $ The jQuery alias
   */
  var Wcf_Animated_Offcanvas = function ($scope, $) {
    if (!window.__wcfOffcanvasHandlersBound__) {
      /* -----------------------------
       Dropdown toggle
      ------------------------------ */
      $(document).on("click", ".nav-direction-icon", function (e) {
        e.preventDefault();
        if ($(this).parent("a").next(".dp-menu").length) {
          var submenu = $(this)[0];
          var dpmenu = $(this).parent("a").next(".dp-menu");
          const expanded = submenu.getAttribute("aria-expanded") === "true";
          submenu.setAttribute("aria-expanded", !expanded);
          if (expanded) {
            this.setAttribute("data-icon", "+");
            dpmenu.slideUp();
          } else {
            dpmenu.slideDown();
            this.setAttribute("data-icon", "-");
          }
        }
      });

      /* -----------------------------
       OPEN OFFCANVAS
      ------------------------------ */
      $(document).on("click", ".cwt--animated-offcanvas", function (e) {
        e.preventDefault();
        var ocId = $(this).attr("data-oc-id");
        var $area = ocId ? $(".cwt--offcanvas-area[data-oc-id='" + ocId + "']") : $(".cwt--offcanvas-area");
        var $left = $area.find(".cwt--offcanvas-left");
        var $right = $area.find(".cwt--offcanvas-right");

        /* ✅ ENABLE POINTER EVENTS ON OPEN */
        $area.css("pointer-events", "auto");
        $left.css("pointer-events", "auto");
        $right.css("pointer-events", "auto");
        if (typeof gsap === "object") {
          if (typeof wcf_smoother !== "undefined") {
            wcf_smoother.paused(true);
          }
          var canvas2 = gsap.timeline();
          canvas2.to($area.get(0), {
            duration: 0.5,
            opacity: 1,
            visibility: "visible",
            zIndex: 9999
          });
          canvas2.to($left.get(0), {
            duration: 0.6,
            top: 0,
            opacity: 1,
            visibility: "visible"
          }, "-=0.5");
          canvas2.to($right.get(0), {
            duration: 0.6,
            bottom: 0,
            opacity: 1,
            visibility: "visible"
          }, "-=0.6");
        } else {
          $area.css({
            opacity: 1,
            visibility: "visible",
            transition: "all 0.5s",
            zIndex: 9999
          });
          $left.css({
            opacity: 1,
            top: 0,
            visibility: "visible",
            transition: "all 0.5s"
          });
          $right.css({
            opacity: 1,
            bottom: 0,
            visibility: "visible",
            transition: "all 0.5s"
          });
        }
      });

      /* -----------------------------
       CLOSE OFFCANVAS
      ------------------------------ */
      $(document).on("click", ".offcanvas--close--button-js", function () {
        var $area = $(this).closest(".cwt--offcanvas-area");
        var $left = $area.find(".cwt--offcanvas-left");
        var $right = $area.find(".cwt--offcanvas-right");
        if (typeof gsap === "object") {
          if (typeof wcf_smoother !== "undefined") {
            wcf_smoother.paused(false);
          }

          /* ❌ DISABLE POINTER EVENTS ON CLOSE */
          $area.css("pointer-events", "none");
          $left.css("pointer-events", "none");
          $right.css("pointer-events", "none");
          var canvas2 = gsap.timeline();
          canvas2.to($right.get(0), {
            duration: 0.6,
            bottom: "-50%",
            opacity: 0
          });
          canvas2.to($left.get(0), {
            duration: 0.6,
            top: "-50%",
            opacity: 0
          }, "-=0.6");
          canvas2.to($area.get(0), {
            duration: 0.8,
            opacity: 0,
            visibility: "hidden",
            zIndex: -1,
            pointerEvents: "none"
          });
          $(".cwt--animated-offcanvas").css({
            cursor: "wait",
            pointerEvents: "none"
          });
          setTimeout(function () {
            $(".cwt--animated-offcanvas").removeAttr("style");
          }, 1000);
        } else {
          $area.css({
            opacity: 0,
            visibility: "hidden",
            transition: "all 0.5s",
            zIndex: -1,
            pointerEvents: "none"
          });
        }
      });
      window.__wcfOffcanvasHandlersBound__ = true;
    }

    /* -----------------------------
       Elementor DOM transfer
    ------------------------------ */
    const offcanvasEl = $scope[0].querySelector(".cwt-element-transfer-to-body");
    if (offcanvasEl) {
      var ocIdClass = Array.prototype.slice.call(offcanvasEl.classList).find(function (c) {
        return c.indexOf("oc-") === 0;
      });
      if (ocIdClass) {
        $scope.find(".cwt--animated-offcanvas").attr("data-oc-id", ocIdClass);
      }
      const clone = offcanvasEl.cloneNode(true);
      if (ocIdClass) {
        clone.setAttribute("data-oc-id", ocIdClass);
      }
      document.body.appendChild(clone);
      offcanvasEl.remove();
      document.dispatchEvent(new CustomEvent("cwtOffcanvasAppended", {
        detail: {
          element: clone
        }
      }));
    }
  };

  /* -----------------------------
     Elementor init
  ------------------------------ */
  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--animated-offcanvas.default", Wcf_Animated_Offcanvas);
  });
})(jQuery);