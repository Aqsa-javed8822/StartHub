(function () {
  /**
   * GSAP DrawSVG Handler
   *
   * - Hooks into Elementor’s frontend.
   * - Finds any `.gsap-draw-svg` container.
   * - Reads `data-…` attrs from its dataset.
   * - Animates all <path> & <circle> children via GSAP + DrawSVGPlugin (+ ScrollTrigger).
   */
  function WgsapDrawSvg(scopeEl) {
    const container = scopeEl.querySelector(".aae-gsap-draw-svg");
    if (!container) {
      return;
    }
    const Linkable = scopeEl.dataset;
    let ScopeSettings = null;
    if (Linkable?.settings && Linkable?.settings != "") {
      ScopeSettings = JSON.parse(Linkable.settings);
    }
    const ds = container.dataset;
    const method = ds.method || "from";
    const from = ds.from || "0%";
    let to = ds.to || "100%";
    const duration = parseFloat(ds.duration) || 1;
    const delay = parseFloat(ds.delay) || 0;
    const repeat = parseInt(ds.repeat, 10) || 0;
    const repeatDelay = parseFloat(ds.repeatdelay) || 0;
    const ease = ds.ease || "sine.inOut";
    const yoyo = ds.yoyo === "yes" ? true : false;
    const dTimeline = ds.timeline === "yes" ? true : false;
    const scrollTriggerFlag = ds.scroll_trigger === "yes" ? true : false;
    const start = ds.scrolltriggerstart || "top 75%";
    const end = ds.scrolltriggerend || "bottom 0%";
    const scrub = ds.scrub === "number" ? ds.scrub_number : ds.scrub === "true" ? true : false;
    const selector = `      
        path, circle, rect, line, polyline, polygon, ellipse, textPath
      `;
    const elems = container.querySelectorAll(selector.trim());
    if (elems.length === 0) {
      console.warn("DrawSVG: no SVG elements found for", selector);
      return;
    }
    function splitPaths(paths) {
      let toSplit = gsap.utils.toArray(paths);
      let newPaths = [];
      toSplit.forEach(element => {
        const tag = element.tagName.toLowerCase();
        if (tag === "circle" || tag === "rect" || tag === "ellipse" || tag === "line" || tag === "textpath") {
          newPaths.push(element);
          return;
        }
        if (tag === "path" || tag === "polyline" || tag === "polygon") {
          const rawPath = MotionPathPlugin.getRawPath(element);
          const parent = element.parentNode;
          const attributes = Array.from(element.attributes);
          if (!rawPath || rawPath.length === 0) return;
          rawPath.forEach(segment => {
            const newPath = document.createElementNS("http://www.w3.org/2000/svg", "path");
            attributes.forEach(attr => newPath.setAttribute(attr.name, attr.value));
            newPath.setAttribute("d", `M${segment[0]},${segment[1]}C${segment.slice(2).join(",")}${segment.closed ? "z" : ""}`);
            parent.insertBefore(newPath, element);
            newPaths.push(newPath);
          });
          parent.removeChild(element);
        }
      });
      return newPaths;
    }
    gsap.registerPlugin(ScrollTrigger, DrawSVGPlugin, MotionPathPlugin);
    let paths = splitPaths(elems);
    const totalLength = paths.reduce((sum, path) => sum + path.getTotalLength(), 0);
    let runSvg = function () {
      if (ScopeSettings?.aae_svg_linkable && ScopeSettings.aae_svg_linkable === "yes" && ScopeSettings?.aae_website_link?.url && ScopeSettings.aae_website_link.url != "") {
        container.setAttributeNS("http://www.w3.org/1999/xlink", "xlink:href", ScopeSettings.aae_website_link.url);
        if (ScopeSettings.aae_website_link?.is_external) {
          container.setAttribute("target", "_blank");
        }
        if (ScopeSettings.aae_website_link.nofollow) {
          container.setAttribute("rel", "nofollow");
        }
        container.style.cursor = "pointer";
        container.addEventListener("click", () => {
          if (ScopeSettings.aae_website_link?.is_external) {
            window.open(ScopeSettings.aae_website_link.url, "_blank");
          } else {
            window.open(ScopeSettings.aae_website_link.url, "_self");
          }
        });
      }
      if (dTimeline) {
        let tl = gsap.timeline({
          repeat,
          yoyo,
          delay: scrollTriggerFlag ? 0 : delay,
          repeatDelay,
          defaults: {
            ease
          },
          scrollTrigger: scrollTriggerFlag ? {
            trigger: container,
            start,
            end,
            scrub
          } : null
        });
        paths.forEach((elem, index) => {
          const pathLength = elem.getTotalLength();
          const pathDuration = duration * (pathLength / totalLength);
          const position = scrollTriggerFlag ? 0 : index * 0.1;
          const animationProps = {
            drawSVG: method === "to" ? to : from,
            duration: pathDuration
          };
          if (method === "from") {
            tl.from(elem, animationProps, position);
          } else if (method === "to") {
            tl.to(elem, animationProps, position);
          } else if (method === "fromTo") {
            tl.fromTo(elem, {
              drawSVG: from
            }, {
              ...animationProps,
              drawSVG: to
            }, position);
          }
        });
      } else {
        // Individual animations when not using timeline
        paths.forEach((elem, index) => {
          const pathLength = elem.getTotalLength();
          const pathDuration = duration * (pathLength / totalLength);
          const fixedAnimProps = {
            drawSVG: method === "to" ? to : from,
            duration: pathDuration,
            delay: scrollTriggerFlag ? 0 : delay,
            repeat: scrub ? 0 : repeat,
            yoyo: scrub ? false : yoyo,
            repeatDelay: scrub ? 0 : repeatDelay,
            ease,
            scrollTrigger: scrollTriggerFlag ? {
              trigger: container,
              start,
              end,
              scrub
            } : null
          };
          if (method === "from") {
            gsap.from(elem, fixedAnimProps);
          } else if (method === "to") {
            gsap.to(elem, fixedAnimProps);
          } else if (method === "fromTo") {
            gsap.fromTo(elem, {
              drawSVG: from
            }, {
              ...fixedAnimProps,
              drawSVG: to
            });
          }
        });
      }
    };
    try {
      runSvg();
    } catch (e) {
      console.error("GSAP DrawSVG Error:", e);
    }
  }

  // 6️⃣ Hook into Elementor’s frontend init
  window.addEventListener("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf-gsap-drawsvg.default", function ($scope) {
      // $scope is a jQuery object—grab its first DOM node
      WgsapDrawSvg($scope[0]);
    });
  });
})();