(function ($) {
  const TiktokFeed = function ($scope, $) {
    let videos = $('.aae--tiktok-feed.style-2 .tiktok-video');
    let popup_videos = $('.aae--tiktok-feed.style-3 .tiktok-video');
    if (videos.length) {
      videos.each(function () {
        const $video = $(this);
        const thumb = $video.data('thumb');
        $video.html('<img src="' + thumb + '" class="tiktok-thumb"> <button class="play-icon"></button>');
      });
      videos.on('click', function () {
        const $clicked = $(this);
        videos.each(function () {
          const $v = $(this);
          const id = $v.data('video-id');
          const thumb = $v.data('thumb');
          if ($v.is($clicked)) {
            $v.html('<iframe src="https://www.tiktok.com/player/v1/' + id + '?autoplay=0&mute=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>');
          } else {
            $v.html('<img src="' + thumb + '" class="tiktok-thumb"><button class="play-icon"></button>');
          }
        });
      });
    }
    if (popup_videos.length) {
      const modalBody = $('.wcf--popup-video-wrapper .aae-popup-content-container');
      let currentIndex = 0;

      // Open Modal
      popup_videos.on('click', function () {
        currentIndex = popup_videos.index(this);
        openModalWithVideo(currentIndex, true); // true = full modal content build
      });
      function openModalWithVideo(index, full = false) {
        const $video = popup_videos.eq(index);
        const videoId = $video.data('video-id');

        // User Info
        let user_info = $('.aae--tiktok-feed.style-3');
        const user_name = user_info.attr('data-name');
        const user_img = user_info.attr('data-avatar');
        const user_bio = user_info.attr('data-bio');
        const followers = user_info.attr('data-followers');
        const following = user_info.attr('data-following');
        const likes = user_info.attr('data-likes');
        const total_videos = user_info.attr('data-videos');
        const iframe = `
                    <iframe src="https://www.tiktok.com/player/v1/${videoId}?autoplay=0&loop=1&mute=0"
                        allow="autoplay; encrypted-media"
                        allowfullscreen></iframe>
                        <div class="user-info-wrapper">
                            <div class="toggle"> > </div>
                            <div class="user-info">
                                <div class="name-wrap">
                                    <div class="image">
                                        <img src="${user_img}" alt="${user_name}">
                                    </div>
                                    <div class="name">${user_name}</div>
                                </div>
                                <p class="bio">${user_bio}</p>
                                <div class="stat followers">Total Followers: ${followers}</div>
                                <div class="stat following">Total Following: ${following}</div>
                                <div class="stat likes">Total Likes: ${likes}</div>
                                <div class="stat videos">Total Videos: ${total_videos}</div>
                            </div>
                        </div>
                `;
        if (full) {
          const nav = `
                        <div class="wcf--tiktok-video-nav">
                            <button id="tt-prev-video">⟵ Prev</button>
                            <button id="tt-next-video">Next ⟶</button>
                        </div>
                    `;
          modalBody.html(`<div class="wcf--tiktok-video-wrapper ttw-${$scope.data('id')}">${iframe}</div>` + nav);

          // Only bind events once
          modalBody.off('click', '#tt-prev-video');
          modalBody.on('click', '#tt-prev-video', function () {
            currentIndex = (currentIndex - 1 + popup_videos.length) % popup_videos.length;
            openModalWithVideo(currentIndex);
          });
          modalBody.off('click', '#tt-next-video');
          modalBody.on('click', '#tt-next-video', function () {
            currentIndex = (currentIndex + 1) % popup_videos.length;
            openModalWithVideo(currentIndex);
          });

          // GSAP popup animation
          window.VideoAnimation = gsap.timeline({
            defaults: {
              ease: "power2.inOut"
            }
          }).to('body > .wcf--popup-video-wrapper', {
            scaleY: 0.01,
            x: 1,
            opacity: 1,
            visibility: 'visible',
            duration: 0.4
          }).to('body > .wcf--popup-video-wrapper', {
            scaleY: 1,
            duration: 0.6
          }).to('body > .wcf--popup-video-wrapper .wcf--popup-video', {
            scaleY: 1,
            opacity: 1,
            visibility: 'visible',
            duration: 0.6
          }, "-=0.4");
        } else {
          // Only update iframe part, keep nav untouched
          modalBody.find('.wcf--tiktok-video-wrapper').html(iframe);
        }
        $('.toggle').on('click', function () {
          var $toggle = $(this);
          var $userInfo = $('.wcf--tiktok-video-wrapper .user-info');
          $userInfo.toggle();
          if ($toggle.text().trim() === '>') {
            $toggle.text('<');
          } else {
            $toggle.text('>');
          }
        });
      }
    }
  };

  // Elementor Frontend Ready
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/aae--tiktok-feed.default', TiktokFeed);
  });
})(jQuery);