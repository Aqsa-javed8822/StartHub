/* global WCF_ADDONS_JS */

document.addEventListener("DOMContentLoaded", function () {
  let smooth_value = 1.35;
  let on_mobile = false;
  let mobile_media = "min-width: 768px";
  function getCurrentDevice() {
    if (window.elementorFrontend && typeof elementorFrontend.getCurrentDeviceMode === "function") {
      const elDevice = elementorFrontend.getCurrentDeviceMode();
      if (elDevice === "mobile") return "mobile";
      if (elDevice === "tablet") return "tablet";
      // Elementor treats both laptop & desktop as "desktop"
    }
    let _mobile = WCF_ADDONS_JS?.elementor_breakpoint ? WCF_ADDONS_JS.elementor_breakpoint.mobile : 767;
    let _tablet = WCF_ADDONS_JS?.elementor_breakpoint ? WCF_ADDONS_JS.elementor_breakpoint.tablet : 1024;
    let _laptop = WCF_ADDONS_JS?.elementor_breakpoint ? WCF_ADDONS_JS.elementor_breakpoint.laptop : 1366;
    // 2️⃣ Width-based fallback (frontend & laptop split)
    const width = window.innerWidth;
    if (width <= _mobile) return "mobile";
    if (width <= _tablet) return "tablet";
    if (width <= _laptop) return "laptop"; // 👈 custom laptop range
    return "desktop";
  }
  if (null !== WCF_ADDONS_JS.smoothScroller) {
    if (typeof elementorFrontend != undefined && WCF_ADDONS_JS.smoothScroller?.desktop) {
      let deviceValue = WCF_ADDONS_JS.smoothScroller[getCurrentDevice()];
      if (deviceValue?.enabled && deviceValue?.enabled) {
        smooth_value = deviceValue.smotherLevel;
        on_mobile = 'on';
      } else {
        smooth_value = false;
      }
    } else if (WCF_ADDONS_JS.smoothScroller?.media) {
      // legecy old version  , remove after june 2026
      smooth_value = WCF_ADDONS_JS.smoothScroller.smooth;
      on_mobile = "on" === WCF_ADDONS_JS.smoothScroller.mobile ? true : false;
      mobile_media = WCF_ADDONS_JS.smoothScroller?.media ?? mobile_media;
    }
  }

  // end of settings

  // In editor mode, dashboard toggle is the single source of truth.
  // Override any device-level "enabled:false" so the smoother can run in editor
  // unless the dashboard switch is ON.
  if (WCF_ADDONS_JS?.editor_mode) {
    if (WCF_ADDONS_JS?.page_smoother?.disableInEditor === true) {
      smooth_value = false; // force-disable
    } else {
      smooth_value = 1.35; // force-enable with sane default
      on_mobile = 'on';
    }
  }
  if (WCF_ADDONS_JS?.editor_mode && WCF_ADDONS_JS?.page_smoother?.disableInEditor === true) {
    // Smoother disabled in editor mode by dashboard setting
  } else {
    if ("function" === typeof ScrollSmoother && "object" === typeof gsap) {
      // 1. Define a global aaeinitSmoother
      gsap.registerPlugin(ScrollSmoother);
      window.aaeinitSmoother = function (smoothValue = 1) {
        if (smoothValue == false) {
          return;
        }
        // kill any old instance
        if (window.wcf_smoother) {
          window.wcf_smoother.kill();
        }
        if (WCF_ADDONS_JS?.page_smoother?.disable && WCF_ADDONS_JS.page_smoother.disable == true) {
          return;
        }
        const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        const isTouch = matchMedia('(hover: none), (pointer: coarse)').matches;
        const touchSmooth = Math.min(Number(smooth_value) || 0, 0.8);
        gsap.registerPlugin(ScrollSmoother);
        // create a new one

        window.wcf_smoother = ScrollSmoother.create({
          smooth: smooth_value,
          effects: true,
          smoothTouch: smooth_value,
          normalizeScroll: isTouch,
          ignoreMobileResize: true
        });
      };
      let gsap_mm = gsap.matchMedia();
      if (on_mobile) {
        window.aaeinitSmoother(smooth_value); // pass a new smoothValue if you want
      } else {
        gsap_mm.add(`(${mobile_media})`, () => {
          window.aaeinitSmoother(smooth_value);
        });
      }
    }
  }
  const close_video = function (VideoAnimation) {
    if (VideoAnimation) {
      window.VideoAnimation.timeScale(1.6).reverse();
      document.querySelectorAll(".aae-popup-content-container").forEach(el => {
        el.innerHTML = '';
      });
    }
  };
  document.addEventListener('click', event => {
    if (event.target.closest('.wcf--popup-video-wrapper .wcf--popup-close')) {
      close_video(VideoAnimation);
      window.VideoAnimation = null;
    }
  });

  //wcf cursor
  const cursor = function () {
    const elementorBreakpoints = WCF_ADDONS_JS?.elementor_devices;
    const cursorEnable = WCF_ADDONS_JS?.enable_cursor === "yes";
    if (!cursorEnable) return;
    const cursorEl = document.querySelector(".wcf-cursor");
    const cursorFollower = document.querySelector(".wcf-cursor-follower");
    if (!cursorEl || !cursorFollower) return;
    const breakpoint = elementorBreakpoints[WCF_ADDONS_JS?.cursor_breakpoint || "mobile"].value;
    if (window.innerWidth < breakpoint) {
      cursorEl.style.display = "none";
      cursorFollower.style.display = "none";
      return;
    }

    // Show elements
    cursorEl.style.display = "flex";
    cursorFollower.style.display = "block";

    // Initial GSAP setup
    gsap.set([cursorEl, cursorFollower], {
      xPercent: -50,
      yPercent: -50,
      scale: 0,
      opacity: 0
    });

    // QuickTo for smooth following
    const setCursorX = gsap.quickTo(cursorEl, "x", {
      duration: 0.6,
      ease: "power4.out"
    });
    const setCursorY = gsap.quickTo(cursorEl, "y", {
      duration: 0.6,
      ease: "power4.out"
    });
    const setFollowerX = gsap.quickTo(cursorFollower, "x", {
      duration: 0.6,
      ease: "power4.out"
    });
    const setFollowerY = gsap.quickTo(cursorFollower, "y", {
      duration: 0.6,
      ease: "power4.out"
    });

    // Timeline for fade-in animation
    const tl = gsap.timeline({
      paused: true
    });
    tl.to(cursorEl, {
      scale: 1,
      opacity: 1,
      duration: 0.5,
      ease: "power4.out"
    }).to(cursorFollower, {
      scale: 1,
      opacity: 1,
      duration: 0.5,
      ease: "power4.out"
    });

    // Mouse move event
    document.addEventListener("mousemove", e => {
      tl.play();
      setCursorX(e.clientX);
      setCursorY(e.clientY);
      setFollowerX(e.clientX);
      setFollowerY(e.clientY);
    });
  };
  cursor();
  // Read Later Posts
  const read_later_btns = document.querySelectorAll(".aae-post-read-later");
  if (read_later_btns.length) {
    function getReadLaterSavedPosts() {
      try {
        const saved = JSON.parse(localStorage.getItem("readLater"));
        return Array.isArray(saved) ? saved : [];
      } catch (e) {
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
  const saved_read_later_ids = localStorage.getItem("readLater") || "[]";
  document.cookie = "readLater=" + saved_read_later_ids + "; path=/";
});