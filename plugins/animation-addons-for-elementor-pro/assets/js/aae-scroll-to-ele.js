document.addEventListener("DOMContentLoaded", function () {
  if (window.ScrollToPlugin) {
    gsap.registerPlugin(ScrollToPlugin);
  }
  if (typeof gsap !== "object" || typeof ScrollToPlugin === "undefined") {
    console.warn("GSAP or ScrollToPlugin not loaded.");
    return;
  }
  function aaeGetSmoother() {
    if (window.wcf_smoother) return window.wcf_smoother;
    if (typeof ScrollSmoother !== "undefined" && ScrollSmoother.get) {
      return ScrollSmoother.get() || null;
    }
    return null;
  }
  function aaeScrollToTarget(targetEl, duration, ease) {
    const smoother = aaeGetSmoother();
    if (smoother) {
      gsap.to(smoother, {
        scrollTop: smoother.offset(targetEl, "top 70px"),
        duration: duration,
        ease: ease
      });
    } else {
      gsap.to(window, {
        duration: duration,
        scrollTo: {
          y: targetEl,
          offsetY: 70
        },
        ease: ease
      });
    }
  }

  // Event delegation — works for dynamically rendered dropdown items (mega menu, AJAX menus, etc.)
  document.addEventListener("click", function (e) {
    const el = e.target.closest('.aae-scroll-to, a[href*="#"]');
    if (!el) return;
    let href = el.getAttribute("href");
    if (!href || href === "#") return;
    let targetHash = "";
    try {
      const hrefURL = new URL(href, window.location.origin);
      targetHash = hrefURL.hash;
    } catch (err) {
      console.warn("Invalid href:", href);
      return;
    }
    if (!targetHash) return;
    const targetEl = document.querySelector(targetHash);
    if (targetEl) {
      e.preventDefault();
      const duration = el.dataset?.duration || 1;
      const ease = el.dataset?.ease || "power2.out";
      const currentUrl = window.location.href.split("#")[0];
      const newUrl = `${currentUrl}${targetHash}`;
      history.pushState(null, "", newUrl);
      aaeScrollToTarget(targetEl, duration, ease);

      // close any open dropdown/submenu after click
      const openParent = el.closest(".menu-item-has-children.open, .menu-item-has-children.active, .sub-menu");
      if (openParent) {
        openParent.classList.remove("open", "active");
      }
    } else if (el.classList.contains("aae-scroll-to")) {
      // cross-page smooth-scroll trick (only for explicit aae-scroll-to wrappers)
      e.preventDefault();
      window.location = href.replace(/\/#/, "/#!");
    }
    // else: target not on this page and not an aae-scroll-to wrapper — let browser navigate natively
  });

  // loaded
  if (!document.querySelector(".wcf-nav-menu-container")) {
    let hash = window.location.hash;
    if (hash) {
      hash = hash.replace(/\#!/, "#");
      const targetEl = document.querySelector(hash);
      if (targetEl) {
        // defer so ScrollSmoother has time to initialize
        gsap.delayedCall(0.1, function () {
          const smoother = aaeGetSmoother();
          if (smoother) {
            gsap.to(smoother, {
              scrollTop: smoother.offset(targetEl, "top 70px"),
              duration: 1,
              ease: "power2.out",
              onComplete: function () {
                const newUrl = window.location.href.replace("/#!", "/#");
                history.pushState(null, null, newUrl);
              }
            });
          } else {
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
        });
      }
    }
  }
});