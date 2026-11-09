(function ($) {
  elementor.hooks.addFilter('editor/controls/select2/data', function (data, control) {
    if (control.model.get('name') !== 'aae_loop_page_post') {
      return data;
    }
    const document = elementor.documents.getCurrent();
    const settings = document.settings.toJSON();
    data.aae_loop_page_source = settings.aae_loop_page_source || 'post';
    return data;
  });
})(jQuery);