(function () {
  const VideoStory = function (scope) {
    const root = scope[0];
    const videos = root.querySelectorAll('.aae--video-story .thumb video');
    if (!videos.length) return;
    videos.forEach(video => {
      const article = video.closest('article.aae--post');
      if (!article) return;
      let clicked = false;

      // Show duration when metadata loaded
      video.addEventListener('loadedmetadata', () => {
        const duration = video.duration;
        const minutes = Math.floor(duration / 60);
        const seconds = Math.floor(duration % 60);
        const formatted = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
        const durationEl = article.querySelector('.duration');
        if (durationEl) durationEl.textContent = formatted;
      });

      // Hover on <article>
      article.addEventListener('mouseenter', () => {
        if (!clicked) video.play();
      });
      article.addEventListener('mouseleave', () => {
        if (!clicked) {
          video.pause();
          video.currentTime = 0;
        }
      });

      // Click play / activate
      article.addEventListener('click', () => {
        const allVideos = root.querySelectorAll('.aae--video-story .thumb video');
        allVideos.forEach(other => {
          if (other !== video) {
            other.pause();
            other.currentTime = 0;
            other.muted = true;
            other.removeAttribute('controls');
          }
        });
        if (!clicked) {
          video.currentTime = 0;
          video.play();
        }
        video.muted = false;
        video.setAttribute('controls', 'controls');
        clicked = true;

        // Active state
        root.querySelectorAll('.aae--post').forEach(el => el.classList.remove('active'));
        article.classList.add('active');
      });
    });
  };

  // Elementor frontend init
  window.addEventListener('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/aae--video-story.default', VideoStory);
  });
})();