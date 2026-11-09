(function () {
  window.addEventListener("elementor/frontend/init", function () {
    if (window.ScrollToPlugin) {
      gsap.registerPlugin(ScrollToPlugin);
    }
    const Modules = elementorModules.frontend.handlers.Base;
    const WCFOnePageNavHandler = Modules.extend({
      bindEvents: function bindEvents() {
        this.run();
      },
      run: function run() {
        const thisModule = this;
        const duration = thisModule.getElementSettings("onpsc_duration") || 1;
        const ease_type = thisModule.getElementSettings("ease_type") || "power1.in";
        let links = thisModule.$element[0].querySelectorAll('a[href^="#"]');
        if (thisModule.getWidgetType() === "wcf--animated-offcanvas") {
          links = document.querySelectorAll('.cwt-element-transfer-to-body nav ul a[href*="#"]');
        }
        if (thisModule.getWidgetType() === "wcf--nav-menu") {
          links = thisModule.$element[0].querySelectorAll('ul a[href*="#"]');
        }
        if (thisModule.getWidgetType() === "wcf--one-page-nav" && ScrollTrigger) {
          if (thisModule.isEdit) {
            const mo = new MutationObserver(muts => {
              for (let m of muts) {
                if (m.type === "attributes" && m.attributeName === "class") {
                  ScrollTrigger.refresh();
                  break;
                }
              }
            });
            mo.observe(thisModule.$element[0], {
              attributes: true,
              attributeFilter: ["class"],
              attributeOldValue: true
            });
          }
          const nav = thisModule.$element[0].querySelector(".wcf--onepage-nav");
          const container = thisModule.$element[0].closest(".e-parent") || document.body; // or section
          const x_pos = thisModule.getElementSettings("aae_pos_x") || 0.01;
          const y_pos = thisModule.getElementSettings("aae_pos_y") || 0.01;
          const preset_nav_pos = thisModule.getElementSettings("aae_preset_nav_pos") || "center-right";
          gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

          // pass in the position you want: 'left-center' or 'right-top'
          function placeNav(position) {
            if (WCF_ADDONS_JS?.page_smoother?.disable && WCF_ADDONS_JS.page_smoother.disable == true) {
              return;
            }
            const {
              width: navW,
              height: navH
            } = nav.getBoundingClientRect();
            let x, y;
            if (position === "top-left") {
              // 2
              x = thisModule.getElementSettings("aae_pospre_x") || 0;
              y = thisModule.getElementSettings("aae_pospre_y") || 0; // 10px from container’s top
            } else if (position === "top-center") {
              // 2
              x = (window.innerWidth - navW) / 2;
              y = thisModule.getElementSettings("aae_pospre_y") || 0.1; // 10px from container’s top
            } else if (position === "top-right") {
              //3
              if (thisModule.getElementSettings("aae_pospre_x")) {
                x = window.innerWidth - navW - thisModule.getElementSettings("aae_pospre_x");
              } else {
                x = window.innerWidth - navW - 1;
              }
              y = thisModule.getElementSettings("aae_pospre_y") || 0;
            } else if (position === "bottom-center") {
              // 4
              x = (window.innerWidth - navW) / 2; // center horizontally
              if (thisModule.getElementSettings("aae_pospre_y")) {
                y = window.innerHeight - navH - thisModule.getElementSettings("aae_pospre_y"); // offset up from bottom
              } else {
                y = window.innerHeight - navH - 1; // offset up from bottom
              }
            } else if (position === "bottom-left") {
              // 5
              if (thisModule.getElementSettings("aae_pospre_x")) {
                x = thisModule.getElementSettings("aae_pospre_x"); // 10px from container’s right
              } else {
                x = window.innerWidth * 0; // 10px from container’s right
              }
              if (thisModule.getElementSettings("aae_pospre_y")) {
                y = window.innerHeight - navH - thisModule.getElementSettings("aae_pospre_y");
              } else {
                y = window.innerHeight - navH - 1;
              }
              // 10px from container’s top
            } else if (position === "bottom-right") {
              // 6

              if (thisModule.getElementSettings("aae_pospre_x")) {
                x = window.innerWidth - navW - thisModule.getElementSettings("aae_pospre_x");
              } else {
                x = window.innerWidth - navW - 1;
              }
              if (thisModule.getElementSettings("aae_pospre_y")) {
                y = window.innerHeight - navH - thisModule.getElementSettings("aae_pospre_y");
              } else {
                y = window.innerHeight - navH - 1;
              }
            } else if (position === "center-left") {
              // 6

              if (thisModule.getElementSettings("aae_pospre_x")) {
                const extraX = parseFloat(thisModule.getElementSettings("aae_pospre_x")) || 0;
                const sign = thisModule.getElementSettings("aae_pospre_x_plusminus") === "+" ? 1 : -1;
                const baseX = thisModule.getElementSettings("aae_pospre_x");
                x = baseX + sign * extraX;
              } else {
                x = window.innerWidth * 0;
              }
              if (thisModule.getElementSettings("aae_pospre_y")) {
                const extraY = parseFloat(thisModule.getElementSettings("aae_pospre_y")) || 0;
                const sign = thisModule.getElementSettings("aae_pospre_y_plusminus") === "+" ? 1 : -1;
                const baseY = (window.innerHeight - navH) / 2;
                y = baseY + sign * extraY;
              } else {
                y = (window.innerHeight - navH) / 2;
              }
            } else if (position === "center-right") {
              // 7

              if (thisModule.getElementSettings("aae_pospre_x")) {
                const extraX = parseFloat(thisModule.getElementSettings("aae_pospre_x")) || 0;
                const sign = thisModule.getElementSettings("aae_pospre_x_plusminus") === "+" ? 1 : -1;
                const baseX = window.innerWidth - navW;
                x = baseX + sign * extraX;
              } else {
                x = window.innerWidth - navW - 0;
              }
              if (thisModule.getElementSettings("aae_pospre_y")) {
                const extraY = parseFloat(thisModule.getElementSettings("aae_pospre_y")) || 0;
                const sign = thisModule.getElementSettings("aae_pospre_y_plusminus") === "+" ? 1 : -1;
                const baseY = (window.innerHeight - navH) / 2;
                y = baseY + sign * extraY;
              } else {
                y = (window.innerHeight - navH) / 2; // vertically centered
              }
            } else if (position === "center-center") {
              // 8
              x = (window.innerWidth - navW) / 2; // center horizontally
              y = (window.innerHeight - navH) / 2; // center vertically
            } else if (position === "custom") {
              x = window.innerWidth * x_pos; // 10px from container’s right
              y = window.innerHeight * y_pos;
            }
            gsap.set(nav, {
              x,
              y
            });
            // pin it using transforms
            ScrollTrigger.refresh(); // re‑measure & re‑pin
            if (ScrollTrigger) {
              ScrollTrigger.create({
                trigger: document.body,
                start: "top top",
                end: "bottom bottom",
                pin: nav,
                pinSpacing: false,
                pinType: "transform"
              });
            }
          }
          placeNav(preset_nav_pos);
          window.addEventListener("resize", thisModule.throttle(() => {
            const toKill = ScrollTrigger.getAll().filter(tr => tr.pin === nav);
            // kill each one
            toKill.forEach(tr => tr.kill());
            placeNav(preset_nav_pos);
          }, 1000));
        }
        setTimeout(() => {
          const sections = [];
          if (!ScrollToPlugin) {
            console.info("ScrollToPlugin not found for nav widget");
            return;
          }
          gsap.registerPlugin(ScrollToPlugin);
          if (thisModule.getWidgetType() === "wcf--nav-menu") {
            document.addEventListener('click', function (e) {
              if (e.target.classList.contains('wcf-nav-item')) {
                const hasHash = e.target.href.indexOf('#') !== -1;
                const _link = hasHash ? e.target : null;
                document.querySelectorAll('.wcf__nav-menu').forEach(function (el) {
                  el.classList.remove("wcf-nav-is-toggled");
                });
                document.body.style.overflow = 'auto';
                if (_link) {
                  const _hash = _link.hash;
                  const _targetEl = document.querySelector(_hash);
                  if (_targetEl) {
                    e.preventDefault();
                    thisModule._scrollTo(_targetEl, {
                      duration: duration,
                      ease: ease_type
                    });
                  }
                }
              }
            });
          } else {
            Array.prototype.forEach.call(links, function (link) {
              const targetId = link.getAttribute("href");
              if (targetId.length > 1) {
                const hash = link.hash;
                if (document.querySelector(hash)) {
                  const targetEl = document.querySelector(hash);
                  sections.push({
                    link: link,
                    target: targetEl
                  });
                  link.addEventListener("click", function (e) {
                    e.preventDefault();
                    if (targetEl) {
                      thisModule._scrollTo(targetEl, {
                        duration,
                        ease_type
                      });
                    }
                    const closeButtons = document.querySelectorAll(".offcanvas--close--button-js, .wcf-menu-close");
                    if (closeButtons) {
                      closeButtons.forEach(btn => {
                        btn.click();
                      });
                    }
                    history.pushState(null, "", `${targetId}`);
                  });
                }
              }
            });
          }

          // ScrollSpy logic
          window.addEventListener("scroll", thisModule.throttle(() => {
            let current = null;
            for (let i = 0; i < sections.length; i++) {
              const sectionTop = sections[i].target.offsetTop - 80;
              if (window.scrollY >= sectionTop) {
                current = sections[i];
              }
            }
            Array.prototype.forEach.call(links, function (link) {
              if (thisModule.getWidgetType() === "wcf--one-page-nav") {
                link.closest(".wcf-onepage-nav-item").classList.remove("active");
              }
            });
            if (current) {
              if (thisModule.getWidgetType() === "wcf--one-page-nav") {
                current.link.closest(".wcf-onepage-nav-item").classList.add("active");
              }
            }
            if (current?.target?.id) {
              history.replaceState(null, "", `#${current?.target?.id}`);
            }
          }, 200));

          // On page load: scroll to hash
          let hash = window.location.hash;
          if (hash) {
            hash = hash.replace(/\#!/, "#");
            const ptargetEl = document.querySelector(hash);
            if (ptargetEl) {
              // give the page a moment to render
              thisModule._scrollTo(ptargetEl, {
                duration,
                ease_type
              });
            }
          }
        }, 200);
      },
      _scrollTo: function _scrollTo(targetEl, config) {
        gsap.to(window, {
          duration: config.duration,
          // Smooth scroll duration
          scrollTo: {
            y: targetEl,
            // Target element to scroll to
            offsetY: 5,
            // Offset for sticky header
            autoKill: true,
            // Auto-cancel scroll if user intervenes
            x: 0 // Optional: Lock horizontal scroll
          },
          ease: config.ease_type,
          overwrite: "auto",
          onStart: () => {},
          onUpdate: () => {},
          onComplete: () => {
            const newUrl = window.location.href.replace("/#!", "/#");
            history.pushState(null, null, newUrl);
          }
        });
      },
      throttle: function throttle(fn, wait = 200) {
        let last, timer;
        return function () {
          const now = Date.now();
          if (last && now < last + wait) {
            clearTimeout(timer);
            timer = setTimeout(() => {
              last = now;
              fn();
            }, wait);
          } else {
            last = now;
            fn();
          }
        };
      }
    });
    const widgets = ["wcf--one-page-nav.default", "wcf--animated-offcanvas.default", "wcf--nav-menu.default"];
    widgets.forEach(widgetName => {
      elementorFrontend.hooks.addAction(`frontend/element_ready/${widgetName}`, $element => {
        elementorFrontend.elementsHandler.addHandler(WCFOnePageNavHandler, {
          $element
        });
      });
    });
  });
})();