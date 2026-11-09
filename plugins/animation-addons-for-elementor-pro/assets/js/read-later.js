(function ($) {
  const PostsReadLater = function ($scope, $) {
    // Load saved posts
    const later_posts = $scope.find('#aae--read-later-list')[0];
    if (later_posts) {
      loadReadLaterPosts(later_posts);
    }

    // Toggle open/close icons and list
    const dd_items_list = $scope.find('.dropdown #aae--read-later-list');
    const toggle_btn = $scope.find('.aae--read-later-toggle');
    toggle_btn.on('click', function () {
      const $this = $(this);
      $this.find('.open-icon').toggle();
      $this.find('.close-icon').toggle();
      dd_items_list.slideToggle();
    });
  };

  // Load saved posts
  const loadReadLaterPosts = async later_posts => {
    const saved = JSON.parse(localStorage.getItem("readLater") || "[]");
    if (!saved.length) {
      later_posts.innerHTML = "<p>No saved posts.</p>";
      return;
    }
    const response = await fetch(`${aae_post_later_path.home_url}/wp-json/wp/v2/posts?include=${saved.join(',')}&_embed`);
    const posts = await response.json();
    const html = posts.map(post => {
      const title = post.title.rendered;
      const link = post.link;
      const date = new Date(post.date).toLocaleDateString();
      const thumbnail = post._embedded?.['wp:featuredmedia']?.[0]?.source_url || '';
      return `
                <div class="aae-post-item">
                  ${thumbnail ? `<div class="thumb"><img src="${thumbnail}" alt="${title}" /></div>` : ''}
                  <div class="content">
                    <h3 class="title"><a href="${link}">${title}</a></h3>
                    <div class="date">${date}</div>
                  </div>
                </div>
              `;
    }).join('');
    later_posts.innerHTML = html;
  };

  // Register with Elementor
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/aae--posts-read-later.default', PostsReadLater);
  });
})(jQuery);