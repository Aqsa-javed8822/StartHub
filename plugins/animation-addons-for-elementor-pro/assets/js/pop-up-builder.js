window.onload = function () {
  const allPopups = document.querySelectorAll(".aae-popup-builder");
  function lockScroll() {
    document.documentElement.style.overflow = "hidden";
  }
  function unlockScroll() {
    document.documentElement.style.overflow = "";
  }
  allPopups.forEach(popup => {
    const trigger = popup.dataset.popup_trigger;
    const selector = popup.dataset.popup_selector;
    const delayTime = parseFloat(popup.dataset.delaytime);
    const scrollYPosition = parseFloat(popup.dataset.scrollpostion);
    const effect = popup.dataset.effect;
    const content = popup.querySelector(".popup-content");
    const close = popup.querySelector(".close-button");
    let effectType = AAE_POPUP_BUILDER[effect];
    // check popup already open
    let isOpen = false;
    function openPopup() {
      if (isOpen) return; // guard
      isOpen = true;
      let animConfig = typeof effectType.animateIn === "function" ? effectType.animateIn() : {
        ...effectType.animateIn
      };
      let animIConfig = typeof effectType.init === "function" ? effectType.init() : {
        ...effectType.init
      };
      gsap.set(popup, {
        autoAlpha: 1,
        pointerEvents: "auto"
      });
      gsap.set(content, animIConfig);
      gsap.to(content, animConfig);
      let overlayElement = document.createElement("div");
      overlayElement.classList.add("popup-overlay");
      document.body.appendChild(overlayElement);
      overlayElement.addEventListener("click", function () {
        close.click();
      });
      lockScroll();
    }
    function closePopup(e) {
      if (e) e.preventDefault();
      if (!isOpen) return;
      let outConfig = typeof effectType.animateOut === "function" ? effectType.animateOut() : {
        ...effectType.animateOut
      };
      gsap.to(content, {
        ...outConfig,
        onComplete: () => {
          const ov = document.querySelector(".popup-overlay");
          if (ov) ov.remove();
          gsap.set(popup, {
            autoAlpha: 0,
            pointerEvents: "none"
          });
          let animIConfig = typeof effectType.init === "function" ? effectType.init() : {
            ...effectType.init
          };
          gsap.set(content, animIConfig);
          unlockScroll();
          isOpen = false;
        }
      });
    }

    //  pageloaded
    if (trigger == "pageloaded") {
      setTimeout(openPopup, delayTime);
    }

    // click trigger
    if (trigger == "click" && selector) {
      const opener = document.querySelector(selector);
      if (opener) {
        const onClick = function (e) {
          e.preventDefault();
          setTimeout(openPopup, delayTime);
          opener.removeEventListener("click", onClick); // remove after first fire
        };
        opener.addEventListener("click", onClick);
      }
    }

    // page exit
    if (trigger == "pageexit") {
      const onLeave = function (e) {
        if (e.clientY <= 0) {
          setTimeout(openPopup, delayTime);
          document.removeEventListener("mouseout", onLeave);
        }
      };
      document.addEventListener("mouseout", onLeave);
    }

    // page scroll
    if (trigger == "page_scroll") {
      const onScroll = function () {
        if (window.scrollY > scrollYPosition) {
          openPopup();
          window.removeEventListener("scroll", onScroll);
        }
      };
      window.addEventListener("scroll", onScroll, {
        passive: true
      });
    }

    // page scroll up
    if (trigger == "page_scroll_up") {
      let lastY = window.scrollY;
      const onScrollUp = function () {
        const y = window.scrollY;
        if (y < lastY) {
          openPopup();
          window.removeEventListener("scroll", onScrollUp);
        }
        lastY = y;
      };
      window.addEventListener("scroll", onScrollUp, {
        passive: true
      });
    }

    // user inactivity
    if (trigger == "user_inactivity") {
      let inactivityTime = parseInt(popup.dataset.inactivity, delayTime) || delayTime;
      let timer;
      console.log(delayTime);
      function resetTimer() {
        clearTimeout(timer);
        timer = setTimeout(function () {
          openPopup();
          ["mousemove", "keydown", "mousedown", "touchstart", "scroll"].forEach(evt => {
            window.removeEventListener(evt, resetTimer);
          });
        }, inactivityTime);
      }
      ["mousemove", "keydown", "mousedown", "touchstart", "scroll"].forEach(evt => {
        window.addEventListener(evt, resetTimer);
      });
      resetTimer();
    }

    // close button
    if (close) {
      close.addEventListener("click", closePopup);
    }
  });
};