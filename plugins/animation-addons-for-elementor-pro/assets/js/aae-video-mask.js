($ => {
  window.addEventListener("elementor/frontend/init", () => {
    //Animation Elements
    if ("object" === typeof gsap) {
      const video_mask = function (scope) {
        if (!scope.length) return;
        scope = scope[0];
        const videoBtns = scope.querySelectorAll(".video--btn");
        videoBtns.forEach(btn => {
          btn.addEventListener("click", () => {
            // Toggle mask-open class
            scope.classList.toggle("mask-open");
            // Toggle open and close titles
            const openTitle = scope.querySelector(".open-title");
            const closeTitle = scope.querySelector(".close-title");
            if (openTitle) openTitle.classList.toggle("hidden");
            if (closeTitle) closeTitle.classList.toggle("hidden");
            const widget_id = scope.dataset.id;
            const closestContent = scope.closest(".wcf-video-mask-content");
            if (closestContent && widget_id) {
              closestContent.classList.toggle(`wcf-video-mask-content-${widget_id}`);
            }
            // Handle video play/pause
            const videos = scope.querySelectorAll("video");
            videos.forEach(video => {
              if (video.autoplay) return;
              if (video.paused) {
                video.play();
              } else {
                video.pause();
              }
            });
          });
        });
      };
      elementorFrontend.hooks.addAction(`frontend/element_ready/wcf--video-mask.default`, video_mask);
    } // end gsap
  });
})(jQuery);