jQuery(function ($) {
  $('#aae_enable_wpml_rewrite_fix').on('change', function () {
    const isChecked = $(this).is(':checked') ? '1' : '0';
    $.post(aaeWpmlAjax.ajax_url, {
      action: 'aae_addon_toggle_wpml_rewrite',
      nonce: aaeWpmlAjax.nonce,
      enabled: isChecked
    }, function (response) {
      if (response.success) {
        alert(response.data.message); // Or use a toast
      }
    });
  });
});