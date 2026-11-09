(function () {
  function AAEScrollableVideo($scope) {
    if (!$scope) return;
    const wrapper = $scope.querySelector(".aae--s-video-wrapper");
    const video = $scope.querySelector(".aae--scrollable-video");
    const proxy = wrapper?.querySelector(".aae-scroll-height");
    if (!wrapper || !video || !proxy) return;

    // ---- Get responsive values height,pin,video control directly from Elementor proxy ----
    const computedHeight = window.getComputedStyle(proxy).height;
    let pinValue = getComputedStyle(proxy).getPropertyValue("--pin").replace(/"/g, "").trim();
    let video_control = getComputedStyle(proxy).getPropertyValue("--video-control").replace(/"/g, "").trim();
    pinValue === 'true' ? pinValue = true : pinValue === 'false' ? pinValue = false : pinValue = pinValue;
    video_control === 'yes' ? video.setAttribute("controls", "") : video_control === 'no' ? video_control = video.setAttribute("nocontrol", "") : video_control = video_control;

    // Remove unit (px only)
    const scrollLength = parseFloat(computedHeight) || 2000;
    const src = video.currentSrc || video.src;

    // Stop autoplay
    video.pause();
    function once(el, event, fn) {
      const handler = function (e) {
        el.removeEventListener(event, handler);
        fn(e);
      };
      el.addEventListener(event, handler);
    }

    // iOS play/pause init
    once(document.documentElement, "touchstart", () => {
      video.play();
      video.pause();
    });

    // GSAP scroll control
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: wrapper,
        start: "top top",
        end: `+=${scrollLength}`,
        scrub: true,
        pin: pinValue,
        anticipatePin: 1
      }
    });
    once(video, "loadedmetadata", () => {
      tl.fromTo(video, {
        currentTime: 0
      }, {
        currentTime: video.duration || 1
      });
    });

    // Optional fetch optimization
    setTimeout(() => {
      if (!window.fetch) return;
      fetch(src).then(res => res.blob()).then(blob => {
        const blobURL = URL.createObjectURL(blob);
        const currentTime = video.currentTime;
        video.src = blobURL;
        video.currentTime = currentTime + 0.01;
      });
    }, 1000);
  }

  // Elementor hook
  window.addEventListener("elementor/frontend/init", () => {
    elementorFrontend.hooks.addAction("frontend/element_ready/aae--scrollable-video.default", scope => AAEScrollableVideo(scope[0]));
  });
})();