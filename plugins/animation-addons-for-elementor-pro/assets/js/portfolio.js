/* global WCF_ADDONS_JS */
(function ($) {
  /**
   * @param $scope The Widget wrapper element as a jQuery element
   * @param $ The jQuery alias
   */
  const WcfPosts = function ($scope, $) {
    const $buttons = $scope.find(".wcf__portfolio .filter button");
    const $items = $scope.find(".enable-filter .wcf-post");
    const $items_ajax = $scope.find(".enable-aae-ajax-filter .wcf-post");
    $buttons.on("click", function () {
      const filter = $(this).data("filter");
      $(this).addClass('mixitup-control-active').siblings().removeClass('mixitup-control-active');
      if ($items_ajax.length) {
        const tax = $(this).data("tax");
        const term = $(this).data("term");
        portfolioFilterItemsAjax(filter, tax, term);
      } else {
        portfolioFilterItems(filter);
      }
    });
    const handleAjaxPagination = function ($scope) {
      let pagination = $('.pf-pagination.aae-ajax-pagination .page-numbers', $scope);
      if (pagination.length) {
        pagination.on('click', function (e) {
          e.preventDefault();
          const page = getPageNumber($(this).attr('href'));
          let tax = $('.aae-more-anchor-ajax-filter', $scope).attr('data-tax') || 0;
          let term = $('.aae-more-anchor-ajax-filter', $scope).attr('data-term') || 'all';
          handlePostsQueryAjax(tax, term, page);
        });
      }
    };
    handleAjaxPagination($scope);
    function portfolioFilterItems(filter) {
      const state = Flip.getState($items.toArray());
      $items.each(function () {
        const $item = $(this);
        if (filter === "all" || $item.hasClass(filter)) {
          $item.show();
        } else {
          $item.hide();
        }
      });
      Flip.from(state, {
        duration: 0.5,
        ease: "power1.inOut",
        stagger: 0.1
      });
    }
    /* ajax filter */
    function portfolioFilterItemsAjax(filter, tax, term) {
      handlePostsQueryAjax(tax, term);
      $('.aae-more-anchor-ajax-filter', $scope).attr('data-tax', tax);
      $('.aae-more-anchor-ajax-filter', $scope).attr('data-term', term);
    }

    //load more
    const loadMore = $('.pf-load-more a', $scope);
    const elementId = $('.load-more-anchor, .aae-more-anchor-ajax-filter', $scope).data('e-id');
    const pageType = $('.aae-more-anchor-ajax-filter', $scope).data('page-type');
    let isLoading = false;
    let currentPage = $('.load-more-anchor', $scope).data('page');
    let maxPage = $('.load-more-anchor', $scope).data('max-page');
    loadMore.on('click', function (e) {
      e.preventDefault();
      if (currentPage < maxPage) {
        handlePostsQuery();
      }
    });
    const handlePostsQuery = function () {
      handleUiBeforeLoading();
      if (isLoading) {
        $('.wcf__btn', $scope).addClass('loading');
      }
      currentPage++;
      const nextPageUrl = $('.load-more-anchor', $scope).attr('data-next-page');
      return fetch(nextPageUrl).then(response => response.text()).then(html => {
        // Convert the HTML string into a document object
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        handleSuccessFetch(doc);
      });
    };
    // Ajax filter
    const handlePostsQueryAjax = function (tax, term, page = 1) {
      handleUiBeforeLoading();
      if (isLoading) {
        $('.wcf__btn', $scope).addClass('loading');
        $('.wrapper', $scope).addClass('loading');
      }
      currentPage = 1;
      const baseurl = $('.aae-more-anchor-ajax-filter', $scope).attr('data-next-page');
      // add url query params
      const url = new URL(baseurl);
      url.searchParams.set('tax', tax);
      url.searchParams.set('term', term);
      url.searchParams.set('cpaged', page);
      url.searchParams.set('aae-ajax-filter', 1);
      const nextPageUrl = url.toString();
      return fetch(nextPageUrl).then(response => response.text()).then(html => {
        // Convert the HTML string into a document object
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        $(`[data-id="${elementId}"] .wcf-posts`).html('');
        handleSuccessFetchAjax(doc);
      });
    };
    function getPageNumber(raw) {
      const str = String(raw);
      // 1) Try path: /page/3/ or /page/3
      const m = str.split('?')[0].match(/\/page\/(\d+)(?:\/|$)/i);
      if (m) return parseInt(m[1], 10);

      // 2) Fallback to query params (?paged=3, ?page=3, ?cpaged=3)
      try {
        const u = new URL(str.replace(/&amp;/g, '&')); // normalize HTML-encoded &
        for (const key of ['paged', 'page', 'cpaged']) {
          const v = u.searchParams.get(key);
          if (v && /^\d+$/.test(v)) return parseInt(v, 10);
        }
      } catch (_) {}
      return null;
    }
    const handleSuccessFetchAjax = function (result) {
      handleUiAfterLoading();
      let MaxPage = 1;
      const postsElements = result.querySelectorAll(`[data-id="${elementId}"] .wcf-posts > article`);
      MaxPage = result.querySelector(`[data-id="${elementId}"] .aae-more-anchor-ajax-filter`) ? parseInt(result.querySelector(`[data-id="${elementId}"] .aae-more-anchor-ajax-filter`).getAttribute('data-maxpage')) : 1;
      let _currentPage = 1;
      $('.aae-more-anchor-ajax-filter', $scope).attr('data-page', _currentPage);
      $('.wrapper', $scope).removeClass('loading');
      if (pageType === 'load_more') {
        _currentPage = result.querySelector(`[data-id="${elementId}"] .aae-more-anchor-ajax-filter`).getAttribute('data-page');
        $('.aae-more-anchor-ajax-filter', $scope).attr('data-maxpage', MaxPage);
        const nextPageUrl = result.querySelector(`[data-id="${elementId}"] .load-more-anchor`) ? result.querySelector(`[data-id="${elementId}"] .load-more-anchor`).getAttribute('data-next-page') : false;
        // postsElements.forEach(element => {                    
        //      $(`[data-id="${elementId}"] .wcf-posts`).append(element);
        // });
        const appended = [];
        postsElements.forEach(el => {
          el.style.opacity = '0';
          el.style.transform = 'translateY(12px)';
          $(`[data-id="${elementId}"] .wcf-posts`).append(el);
          appended.push(el);
        });
        if (window.gsap && appended.length) {
          gsap.fromTo(appended, {
            opacity: 0,
            y: 20,
            skewY: 3
          }, {
            opacity: 1,
            y: 0,
            skewY: 0,
            duration: 0.8,
            ease: "expo.out",
            stagger: {
              each: 0.16
            },
            // slowest
            clearProps: "all"
          });
        }
        if (nextPageUrl) {
          $('.load-more-anchor', $scope).attr('data-page', currentPage);
          $('.load-more-anchor', $scope).attr('data-next-page', nextPageUrl);
        }
        $('.load-more-anchor', $scope).attr('data-max-page', MaxPage);
        if (!isLoading) {
          $('.wcf__btn', $scope).removeClass('loading');
        }
        if (currentPage === MaxPage || currentPage >= MaxPage) {
          loadMore.hide();
        } else {
          loadMore.show();
        }
      } else if (pageType === 'numbers_and_prev_next') {
        if (result.querySelector(`[data-id="${elementId}"] .pf-pagination`)) {
          $('.pf-pagination', $scope).html(result.querySelector(`[data-id="${elementId}"] .pf-pagination`).innerHTML);
          handleAjaxPagination($scope);
        } else {
          $('.pf-pagination', $scope).html('');
        }
        postsElements.forEach(element => $(`[data-id="${elementId}"] .wcf-posts`).append(element));
      }
    };
    const handleSuccessFetch = function (result) {
      handleUiAfterLoading();
      const postsElements = result.querySelectorAll(`[data-id="${elementId}"] .wcf-posts > article`);
      const nextPageUrl = result.querySelector(`[data-id="${elementId}"] .load-more-anchor`).getAttribute('data-next-page');
      const _currentPage = result.querySelector(`[data-id="${elementId}"] .aae-more-anchor-ajax-filter`).getAttribute('data-page');
      let _maxPage = result.querySelector(`[data-id="${elementId}"] .aae-more-anchor-ajax-filter`).getAttribute('data-maxpage');
      if (result.querySelector(`[data-id="${elementId}"] .aae-more-anchor-ajax-filter`)) {
        _maxPage = result.querySelector(`[data-id="${elementId}"] .aae-more-anchor-ajax-filter`).getAttribute('data-maxpage');
        //if(parseInt(_maxPage)){
        maxPage = parseInt(_maxPage);
        //}
      }
      // postsElements.forEach(element => $(`[data-id="${elementId}"] .wcf-posts`).append(element));
      const appended = [];
      postsElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(12px)';
        $(`[data-id="${elementId}"] .wcf-posts`).append(el);
        appended.push(el);
      });
      if (window.gsap && appended.length) {
        gsap.fromTo(appended, {
          opacity: 0,
          y: 20,
          skewY: 3
        }, {
          opacity: 1,
          y: 0,
          skewY: 0,
          duration: 0.8,
          ease: "expo.out",
          stagger: {
            each: 0.10
          },
          // slowest
          clearProps: "all"
        });
      }
      $('.load-more-anchor', $scope).attr('data-page', currentPage);
      $('.load-more-anchor', $scope).attr('data-next-page', nextPageUrl);
      $('.aae-more-anchor-ajax-filter', $scope).attr('data-page', _currentPage);
      if (!isLoading) {
        $('.wcf__btn', $scope).removeClass('loading');
      }
      // equal or greater than maxPage
      if (currentPage >= maxPage || currentPage === maxPage) {
        loadMore.hide();
      } else {
        loadMore.show();
      }
    };
    const handleUiBeforeLoading = function () {
      isLoading = true;
    };
    const handleUiAfterLoading = function () {
      isLoading = false;
    };
  };

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/wcf--portfolio.default', WcfPosts);
  });
})(jQuery);