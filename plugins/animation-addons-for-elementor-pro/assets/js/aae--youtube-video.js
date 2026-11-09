(function ($) {
  window.addEventListener('elementor/frontend/init', function () {
    const Modules = elementorModules.frontend.handlers.Base;
    const WCFVideoSliderHandler = Modules.extend({
      onInit() {
        this.run();
      },
      onEditSettingsChange(propertyName, value) {},
      run: function run() {
        const thisModule = this; // thisModule.$element
        const wrapper = thisModule.$element[0].querySelector('.aae-addon-yt-grid');
        if (wrapper) {
          const items = thisModule.$element[0].querySelectorAll('.aae-addon-yt-item');
          let autoplay = wrapper.getAttribute('data-autoplay');
          let controls = wrapper.getAttribute('data-controls');
          items.forEach(item => item.addEventListener('click', function () {
            // Stop other inline videos
            thisModule.$element[0].querySelectorAll('.aae-addon-yt-item iframe').forEach(f => {
              const parent = f.parentElement;
              const thumb = parent.getAttribute('data-thumb');
              const title = parent.getAttribute('data-title');
              parent.innerHTML = '<img src="' + thumb + '" alt="' + title + '"><div class="aae-play-button"></div><p>' + title + '</p>';
            });
          }));
          if (thisModule.$element[0].querySelectorAll('.aae-addon-yt-item.no-popup').length) {
            items.forEach(item => item.addEventListener('click', function () {
              const vid = this.getAttribute('data-video-id');
              this.innerHTML = '<iframe src="https://www.youtube.com/embed/' + vid + '?autoplay=' + autoplay + '&controls=' + controls + '" allow="autoplay; encrypted-media" allowfullscreen"></iframe>';
            }));
          }

          // Popup Open
          const popup_items = thisModule.$element[0].querySelectorAll('.aae-addon-yt-item.open-popup');
          let players = [];
          popup_items.forEach(clickedItem => {
            clickedItem.addEventListener('click', function () {
              const clickedID = this.getAttribute('data-video-id');
              const videoIDs = [clickedID, ...Array.from(popup_items).map(item => item.getAttribute('data-video-id')).filter(id => id !== clickedID)];
              let slides = '';
              videoIDs.forEach((id, index) => {
                slides += `
                            <div class="swiper-slide">
                                <div class="yt-player" id="yt-player-${index}" data-video-id="${id}"></div>
                            </div>
                        `;
              });
              const output = `
                            <div class="swiper yt-videos-slider">
                                <div class="swiper-wrapper">
                                    ${slides}
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        `;
              const popupWrapper = document.querySelector('.wcf--popup-video-wrapper .aae-popup-content-container');
              popupWrapper.innerHTML = output;

              // Initialize Swiper
              const swiper = new Swiper(".yt-videos-slider", {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: false,
                navigation: {
                  nextEl: ".swiper-button-next",
                  prevEl: ".swiper-button-prev"
                }
              });

              // YouTube API loading and player setup
              function onYouTubeIframeAPIReadyCustom() {
                players = [];
                let playersReadyCount = 0;
                videoIDs.forEach((id, index) => {
                  const player = new YT.Player(`yt-player-${index}`, {
                    videoId: id,
                    events: {
                      'onReady': event => {
                        players[index] = event.target;
                        if (index !== 0) {
                          event.target.pauseVideo();
                        } else {
                          event.target.playVideo(); // autoplay first video
                        }
                        playersReadyCount++;

                        // Attach Swiper event after all players are ready
                        if (playersReadyCount === videoIDs.length) {
                          swiper.on('slideChange', () => {
                            players.forEach((p, i) => {
                              if (i === swiper.activeIndex) {
                                p.playVideo();
                              } else {
                                p.pauseVideo();
                              }
                            });
                          });
                        }
                      }
                    }
                  });
                });
              }
              if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
                const tag = document.createElement('script');
                tag.src = "https://www.youtube.com/iframe_api";
                document.body.appendChild(tag);
                window.onYouTubeIframeAPIReady = onYouTubeIframeAPIReadyCustom;
              } else {
                onYouTubeIframeAPIReadyCustom();
              }

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
            });
          });
        }
        const playlist_wrapper = thisModule.$element[0].querySelector('.aae--yt-playlist-wrapper');
        if (playlist_wrapper) {
          const items = playlist_wrapper.querySelectorAll('.aae--yt-playlist .item-wrap');
          const player = playlist_wrapper.querySelector('.aae--yt-videos iframe');
          let autoplay = playlist_wrapper.getAttribute('data-autoplay');
          let controls = playlist_wrapper.getAttribute('data-controls');
          items[0].classList.add('active');
          items.forEach(item => {
            item.addEventListener('click', function () {
              items.forEach(el => el.classList.remove('active'));
              this.classList.add('active');
              const videoId = this.getAttribute('data-video-id');
              if (videoId && player) {
                player.src = `https://www.youtube.com/embed/${videoId}?autoplay=${autoplay}&controls=${controls}`;
              }
            });
          });
        }
      }
    });
    const widgets = ['aae-pro-youtube-videos.default'];
    widgets.forEach(widgetName => {
      elementorFrontend.hooks.addAction(`frontend/element_ready/${widgetName}`, $element => {
        elementorFrontend.elementsHandler.addHandler(WCFVideoSliderHandler, {
          $element
        });
      });
    });
  });
})(jQuery);