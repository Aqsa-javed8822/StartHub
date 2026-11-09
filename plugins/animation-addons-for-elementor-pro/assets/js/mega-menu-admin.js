;
(function ($) {
  "use strict";

  let WCFMegaMenuAdmin = {
    instance: [],
    menuId: 0,
    depth: 0,
    init: function () {
      this.menuButton();
      $(document).on('click.WCFMegaMenuAdmin', '.wcf-mega-menu-settings-save', this.saveMenuOpt).on('click.WCFMegaMenuAdmin', '.wcf-mega-menu-trigger', this.openPopup).on('click.WCFMegaMenuAdmin', '.wcf-mega-menu-popup-close', this.closePopup).on('click.WCFMegaMenuAdmin', '.wcf-mega-menu-popup-close-btn', this.closePopup).on('click.WCFMegaMenuAdmin', '.wcf-mega-menu-submit-btn', this.saveMenuData);
    },
    saveMenuOpt: function () {
      let spinner = $(this).parent().find('.spinner');
      spinner.addClass('loading');
      WCFMegaMenuAdmin.save_menu_options($(this));
    },
    save_menu_options: function (that) {
      let parent = that.parents("#wcf_mega_menu_meta_box"),
        settings = {
          'enable_menu': parent.find("#wcf-megamenu-menu-metabox-input-is-enabled").is(':checked') === true ? 'on' : 'off'
        };
      $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
          action: "wcf_mega_menu_ajax_requests",
          sub_action: "save_menu_options",
          settings: settings,
          menu_id: $("#wcf-megamenu-metabox-input-menu-id").val(),
          nonce: WCFMEGAMENU.nonce
        },
        cache: false,
        success: function (response) {
          that.parent().find('.spinner').removeClass('loading');
        }
      });
    },
    menuButton: function () {
      let button = wp.template('wcf-mega-menu-button');
      let enableMegaMenu = $('#wcf-megamenu-menu-metabox-input-is-enabled');
      $('#menu-to-edit .menu-item').each(function () {
        let $this = $(this),
          depth = WCFMegaMenuAdmin.getItemDepth($this),
          id = WCFMegaMenuAdmin.getItemId($this);
        $this.find('.item-title').append(button({
          id: id,
          depth: depth,
          label: 'WCF Mega Menu'
        }));
      });
      if (true !== enableMegaMenu.is(':checked')) {
        $('.wcf-mega-menu-trigger').hide();
      } else {
        $('.wcf-mega-menu-trigger').show();
      }
      enableMegaMenu.on('change', function () {
        if (true !== enableMegaMenu.is(':checked')) {
          $('.wcf-mega-menu-trigger').hide();
        } else {
          $('.wcf-mega-menu-trigger').show();
        }
      });
    },
    getItemId: function ($item) {
      var id = $item.attr('id');
      return id.replace('menu-item-', '');
    },
    getItemDepth: function ($item) {
      var depthClass = $item.attr('class').match(/menu-item-depth-\d/);
      if (!depthClass[0]) {
        return 0;
      }
      return depthClass[0].replace('menu-item-depth-', '');
    },
    openPopup: function () {
      let $this = $(this),
        id = $this.data('item-id'),
        depth = $this.data('item-depth'),
        popupid = '#wcf-mega-popup-' + id,
        content = null,
        wrapper = wp.template('wcf-mega-menu-popup');
      $.ajax({
        url: ajaxurl,
        type: 'GET',
        dataType: 'JSON',
        data: {
          action: "wcf_mega_menu_ajax_requests",
          sub_action: "get_menu_options",
          menu_item_id: id,
          nonce: WCFMEGAMENU.nonce
        },
        cache: false,
        beforeSend: function () {},
        success: function (response) {
          content = wrapper({
            id: id,
            depth: depth,
            content: response.data.content,
            templatelist: WCFMEGAMENU.templates
          });
          $('body').append(content);
          let savebtn = $(popupid).find('.wcf-mega-menu-submit-btn');
          WCFMegaMenuAdmin.init_tab('.mega-menu-popup-tab-menu');
          $(popupid + ' form.wcf-mega-menu-data').on('keyup', '.mega-menu-popup-input', function () {
            savebtn.removeClass('disabled').attr('disabled', false).text(WCFMEGAMENU.button.text);
          });
          $(popupid + ' form.wcf-mega-menu-data').on('change', '.mega-menu-popup-input', function () {
            savebtn.removeClass('disabled').attr('disabled', false).text(WCFMEGAMENU.button.text);
          });
          $('[data-checked="true"]').prop('checked', true);

          //custom width selection
          let width_checkbox = $(popupid + ' .width_lists input');
          let custom_width_row = $(popupid + ' .custom-width-row');
          width_checkbox.each(function () {
            let status = $(this).prop("checked");
            let value = $(this).val();
            if (status && 'custom_width' === value) {
              custom_width_row.show();
            } else {
              custom_width_row.hide();
            }
          });
          width_checkbox.on('change', function () {
            let value = $(this).val();
            if ('custom_width' !== value) {
              custom_width_row.hide();
            } else {
              custom_width_row.show();
            }
          });
        },
        complete: function (data) {
          $('.wcf-mega-menu-popup').addClass('open-popup');
        }
      });
    },
    closePopup: function (e) {
      e.preventDefault();
      $(this).closest('.wcf-mega-menu-popup').removeClass('open-popup');
      $(this).closest('.wcf-mega-menu-popup').remove();
    },
    saveMenuData: function () {
      let $this = $(this),
        id = $this.data('id');
      let $menu_form = $('#wcf-mega-menu-form-' + id),
        $savebtn = $menu_form.find('.wcf-mega-menu-submit-btn');
      $menu_form.on('submit', function (event) {
        event.preventDefault();
        $.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
            action: "wcf_mega_menu_ajax_requests",
            sub_action: "save_menu_settings",
            settings: $menu_form.serialize(),
            menu_item_id: id,
            nonce: WCFMEGAMENU.nonce
          },
          cache: false,
          beforeSend: function () {
            $savebtn.text(WCFMEGAMENU.button.lodingtext).addClass('updating-message');
          },
          success: function (response) {
            $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(WCFMEGAMENU.button.successtext);
          },
          complete: function (data) {
            $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(WCFMEGAMENU.button.successtext);
          }
        });
      });
    },
    init_tab: function (menu) {
      $(menu).on('click', 'a', function (e) {
        e.preventDefault();
        let $this = $(this),
          $target = $this.data('target'),
          $tabPane = $this.closest(menu).siblings('.mega-menu-popup-tab-content').find('.mega-menu-popup-tab-pane[data-id=' + $target + ']');
        $this.addClass('active').closest('li').siblings().find('a').removeClass('active');
        $tabPane.addClass('active').siblings().removeClass('active');
      });
    }
  };
  WCFMegaMenuAdmin.init();
})(jQuery);