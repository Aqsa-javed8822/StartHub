(function ($) {
  $(document).on('click', '#wcf-addon-pro-expire-notice .notice-dismiss', function (e) {
    $.ajax({
      type: 'POST',
      url: wcf_addons_pro_admin.ajax_url,
      data: {
        action: "wcf_animation_addon_pro_tr_dismiss_notice",
        nonce: wcf_addons_pro_admin.nonce
      },
      cache: false,
      success: function (response) {}
    });
  });
})(jQuery);
document.addEventListener('DOMContentLoaded', function () {
  /**
   * Hide links for a specific plugin row when a certain text appears.
   *
   * @param {string} pluginSlug    - value of data-plugin attribute
   * @param {string} textMatch     - substring to look for
   * @param {string} linkSelector  - CSS selector for links to hide
   */

  function hidePluginLinks(pluginSlug, textMatch, linkSelector) {
    const els = document.querySelectorAll(`tr[data-plugin="${pluginSlug}"] .update-message em`);
    els.forEach(function (em) {
      if (em.textContent.includes(textMatch)) {
        const p = em.closest('tr');
        if (p) {
          p.querySelectorAll(linkSelector).forEach(function (up) {
            up.remove();
          });
        }
      }
    });
  }
  hidePluginLinks('animation-addons-for-elementor-pro/animation-addons-for-elementor-pro.php', 'Automatic update is unavailable for this plugin.', '.plugin-update');

  // Fetch the license key via admin-ajax and return the JSON payload
  const getVarSettings = async () => {
    if (!AAE_ADMIN?.aae_pro_ping || !AAE_ADMIN?.ajaxUrl) return null;
    try {
      const res = await fetch(AAE_ADMIN.ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
          Accept: "application/json"
        },
        credentials: "same-origin",
        body: new URLSearchParams({
          action: "aae_get_dynamic_settings",
          setting_name: "wcf_addon_sl_license_key",
          nonce: AAE_ADMIN.nonce
        })
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const json = await res.json();
      return json; // adjust below if your PHP returns { success, data: { key, value } }
    } catch (err) {
      return null;
    }
  };
  const varify = async lic => {
    const body_args = {
      action: 'wcf_addon_pro_sl_activate',
      wcf_addon_sl_license_key: lic,
      email: "",
      nonce: AAE_ADMIN.nonce
    };
    const response = await fetch(AAE_ADMIN.ajaxUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        Accept: "application/json"
      },
      body: new URLSearchParams(body_args)
    });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    const json = await response.json();
    return json;
  };

  // Call it correctly (use .then(...) or an async IIFE)
  if (typeof AAE_ADMIN === "object" && AAE_ADMIN?.ajaxUrl) {
    (async () => {
      const resp = await getVarSettings();
      if (resp?.settings) {
        await varify(resp.settings);
      }
    })();
  }
});