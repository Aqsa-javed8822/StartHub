($ => {
  window.addEventListener("elementor/frontend/init", () => {
    const Modules = elementorModules.frontend.handlers.Base;
    //Feature Post
    const FeaturePost = Modules.extend({
      bindEvents: function bindEvents() {
        if ("yes" === this.getElementSettings("show_title_highlight")) {
          const length = this.getElementSettings("highlight_title_length");
          const allTitle = this.findElement(".wcf-post-title");
          allTitle.each((index, title) => {
            let current_title = $(title).children("a");
            let current_content = $(title).children("a").text().trim().split(" ");
            const highlight_text = current_content.slice(0, length);
            const normal_text = current_content.slice(length);
            let result = `<span class="highlight">${highlight_text.join(" ")}</span> ${normal_text.join(" ")}`;
            current_title.html(result);
          });
        }
        this.banner();
        if (this.isEdit) {
          return;
        }
        this.run();
      },
      run: function run() {},
      banner: function () {
        let banner = this.findElement(".post-banner");
        let posts = this.findElement(".tabs-wrap .thumb").clone();
        banner.html(posts);
        this.tab();
      },
      tab: function () {
        let posts = this.findElement(".tabs-wrap .wcf-post-title");
        let banner = this.findElement(".post-banner");
        let thumb = banner.find(".thumb");
        this.findElement(".tabs-wrap .wcf-post:first").addClass("active");
        this.findElement(".post-banner .thumb:first").addClass("active");

        //On Click Event
        posts.click(function (e) {
          e.preventDefault();
          let currentPost = $(this).parent(".wcf-post");
          if (currentPost.hasClass("active")) {
            return;
          }
          const active = currentPost.attr("data-id");
          posts.parent(".wcf-post").removeClass("active");
          currentPost.addClass("active");
          thumb.removeClass("active");
          banner.find(`[data-target='${active}']`).addClass("active");
          return false;
        });
        $(document).on("click", ".wcf-post-popup", function (e) {
          e.preventDefault();
          let $_url = $(this).attr("data-src");
          $(`.wcf--popup-video-wrapper`).find(".aae-popup-content-container").html("");
          if ($(this).hasClass("audio")) {
            $(".wcf--popup-video-wrapper").find(".aae-popup-content-container").html(`<div class="audio wcf-audio-wrapper-clean">
                            <audio controls>
                                <source src="${$_url}" type="audio/mpeg">
                            </audio>
                        </div>`);
          }
          if ($(this).hasClass("video")) {
            $(".wcf--popup-video-wrapper").find(".aae-popup-content-container").html(`<iframe  src="${$_url}" ></iframe>`);
          }
          window.VideoAnimation = gsap.timeline({
            defaults: {
              ease: "power2.inOut"
            }
          }).to(`body > .wcf--popup-video-wrapper`, {
            scaleY: 0.01,
            x: 1,
            opacity: 1,
            visibility: "visible",
            duration: 0.4
          }).to(`body > .wcf--popup-video-wrapper`, {
            scaleY: 1,
            duration: 0.6
          }).to(`body > .wcf--popup-video-wrapper .wcf--popup-video`, {
            scaleY: 1,
            opacity: 1,
            visibility: "visible",
            duration: 0.6
          }, "-=0.4");
        });
      }
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--feature-posts.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(FeaturePost, {
        $element: $scope
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--posts-pro.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(FeaturePost, {
        $element: $scope
      });
    });
  });
})(jQuery);