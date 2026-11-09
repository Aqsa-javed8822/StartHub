($ => {
  window.addEventListener("elementor/frontend/init", () => {
    const Modules = elementorModules.frontend.handlers.Base;
    // Posts Pro

    const PostPro = Modules.extend({
      loadMore: null,
      loadMoreSpin: null,
      anchor: null,
      isLoading: false,
      elementId: null,
      currentPage: 0,
      maxPage: -1,
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
        if (this.isEdit) {
          return;
        }
        this.run();
      },
      run: function run() {
        this.loadMore = this.findElement(".wcf-post-load-more");
        this.loadMoreSpin = this.findElement(".load-more-spinner");
        this.anchor = this.findElement(".load-more-anchor");
        this.elementId = this.getID();
        this.currentPage = this.anchor.data("page");
        this.maxPage = this.anchor.data("max-page");
        let paginationType = this.loadMore.data("type");
        if (this.currentPage == this.maxPage) {
          this.loadMore.css("display", "none");
        }
        if ("load_on_click" === paginationType) {
          this.loadMore.on("click", e => {
            e.preventDefault();
            if (this.currentPage < this.maxPage) {
              ScrollTrigger.refresh();
              this.handlePostsQuery();
            }
          });
        }
        if ("infinite_scroll" === paginationType) {
          // Track URLs per element ID
          const infinityScrollURLs = {};
          if (!infinityScrollURLs[this.elementId]) {
            infinityScrollURLs[this.elementId] = new Set(); // use Set to auto-handle duplicates
          }
          const options = {
            rootMargin: "-30%",
            threshold: 1.0
          };
          const observer = new IntersectionObserver(entries => {
            for (const entry of entries) {
              if (entry.isIntersecting) {
                // Prevent duplicate fetch for same page
                if (infinityScrollURLs[this.elementId].has(this.currentPage)) {
                  console.log(`Skipping duplicate page ${this.currentPage} for element ${this.elementId}`);
                  return;
                }
                infinityScrollURLs[this.elementId].add(this.currentPage);
                console.log(`Loading page ${this.currentPage} for element ${this.elementId}`);
                if (this.currentPage < this.maxPage) {
                  this.handlePostsQuery().then(() => {
                    console.log(`Loaded pages:`, Array.from(infinityScrollURLs[this.elementId]));
                  }).catch(err => console.error(err));
                }
              }
            }
          }, options);
          observer.observe(this.anchor[0]);
        }
      },
      handlePostsQuery: function () {
        this.handleUiBeforeLoading();
        if (this.isLoading) {
          this.loadMoreSpin.css("opacity", 1);
          this.$element.find(".load-more-text").css("opacity", 0);
        }
        this.currentPage++;
        if (this.currentPage > this.maxPage) {
          return;
        }
        const nextPageUrl = this.anchor.attr("data-next-page");
        return fetch(nextPageUrl).then(response => response.text()).then(html => {
          // Convert the HTML string into a document object
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, "text/html");
          this.handleSuccessFetch(doc);
        });
      },
      handleSuccessFetch: function (result) {
        this.handleUiAfterLoading();
        const postsElements = result.querySelectorAll(`[data-id="${this.elementId}"] .wcf-posts > article`);
        $(postsElements).addClass("wcf-hide");
        const nextPageUrl = result.querySelector(`[data-id="${this.elementId}"] .load-more-anchor`).getAttribute("data-next-page");
        postsElements.forEach(element => this.findElement(".wcf-posts").append(element));
        this.anchor.attr("data-page", this.currentPage);
        this.anchor.attr("data-next-page", nextPageUrl);

        /// loading
        setTimeout(() => {
          if (!this.isLoading) {
            this.loadMoreSpin.css("opacity", 0);
            this.$element.find(".load-more-text").css("opacity", 1);
          }
          if (this.currentPage === this.maxPage) {
            ScrollTrigger.refresh();
            this.loadMore.remove();
          }
          $(postsElements).removeClass("wcf-hide");
        }, 300);
      },
      handleUiBeforeLoading: function () {
        this.isLoading = true;
      },
      handleUiAfterLoading: function () {
        this.isLoading = false;
      }
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--posts-pro.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(PostPro, {
        $element: $scope
      });
    });

    // Timeline Posts
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--posts-timeline.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(PostPro, {
        $element: $scope
      });
    });

    // Filterable Posts
    elementorFrontend.hooks.addAction("frontend/element_ready/wcf--posts-filter.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(PostPro, {
        $element: $scope
      });
    });

    // Live Events
    elementorFrontend.hooks.addAction("frontend/element_ready/aae--live-events.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(PostPro, {
        $element: $scope
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/aae--loop-grid.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(PostPro, {
        $element: $scope
      });
    });
  });
})(jQuery);