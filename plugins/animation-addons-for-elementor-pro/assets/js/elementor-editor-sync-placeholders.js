(function ($, elementor) {
  'use strict';

  var CONTROL_NAMES = ['wcf_text_animation', 'aae_text_trigger', 'aae_trigger_text_selector', 'aae_anim_txt_wrapper', 'aae_anim_txt_s_t', 'aae_anim_txt_e_t', 'aae_anim_txt_s', 'aae_anim_txt_e', 'aae_anim_txt_e_cus', 'aae_anim_txt_markers', 'text_delay', 'text_duration', 'text_stagger', 'text_translate_x', 'text_translate_y', 'text_rotation_di', 'text_rotation', 'text_transform_origin', 'spin_text_start', 'spin_text_end', 'spin_text_scrub', 'spin_text_toggle_action', 'scale_text_ease', 'text_scale_num', 'text_scale_break', 'wcf-image-animation', 'aae_a_start_from', 'wcf-scale-start', 'wcf-scale-end', 'wcf-animation-start', 'wcf-animation', 'wcf_animation_custom_start', 'image-ease', 'aae_method', 'aae_trigger', 'aae_trigger_selector', 'aae_anim_wrapper', 'aae_anim_s_t', 'aae_anim_e_t', 'aae_anim_s', 'aae_anim_s_cus', 'aae_anim_e', 'aae_anim_e_cus', 'aae_anim_markers', 'delay', 'fade-from', 'data-duration', 'ease', 'fade-offset', 'wcf-a-scale', 'wcf_a_rotation_di', 'wcf_a_rotation', 'wcf_a_transform_origin', 'wcf_enable_pin_area', 'wcf_custom_pin_area', 'wcf_pin_area_trigger', 'wcf_pin_end_trigger', 'wcf_pin_status', 'wcf_pin_custom', 'wcf_pin_spacing', 'wcf_pin_spacing_custom', 'wcf_pin_type', 'wcf_pin_scrub', 'wcf_pin_scrub_number', 'wcf_pin_area_start', 'wcf_pin_area_start_custom', 'wcf_pin_area_end', 'wcf_pin_area_end_custom', 'wcf_enable_horizontal_scroll', 'horizontal_scroll_end', 'horizontal_scroll_width', 'aae_enable_header_sticky_area', 'aae_header_sticky_end_trigger', 'aae_header_sticky_start_position', 'aae_header_sticky_z_index', 'aae_header_up_scroll_sticky', 'aae_header_sticky_ease', 'aae_header_sticky_duration'];
  function findValueRecursive(controlsList, device, visited = new Set()) {
    const item = controlsList.find(it => it.device === device);
    if (!item) return '';

    // cycle protection
    if (visited.has(item.key)) return '';
    visited.add(item.key);

    // if current has value
    if (item.value !== undefined && item.value !== null && item.value !== '') {
      return item.value;
    }

    // fallback to parent
    if (item.parent) {
      const parentItem = controlsList.find(it => it.key === item.parent);
      if (parentItem) {
        return findValueRecursive(controlsList, parentItem.device, visited);
      }
    }
    return '';
  }

  // Update placeholders for controls in the currently open panel (panel argument is PanelView)
  function updatePlaceholdersForPanel(panel, model, device) {
    try {
      var settings = model && model.get ? model.get('settings') : null;
      if (!settings) {
        return;
      }
      CONTROL_NAMES.forEach(function (controlName) {
        let controlsList = [];
        var $control = panel && panel.$el ? panel.$el.find('.elementor-control-' + controlName + '_' + device) : $('.elementor-control-' + controlName + '_' + device);
        if (!$control.length) {
          return;
        }
        var $input = $control.find('input,.elementor-slider-input input[type="number"], input[type="text"], input[type="number"], input[type="url"], textarea, select').last();
        if ($input.length) {
          if (!$input.val()) {
            let $control_laptop = $('.elementor-control-' + controlName + '_' + 'laptop');
            let $control_desktop = panel && panel.$el ? panel.$el.find('.elementor-control-' + controlName) : $('.elementor-control-' + controlName);
            let $control_tablet_extra = panel && panel.$el ? panel.$el.find('.elementor-control-' + controlName + '_' + 'tablet_extra') : $('.elementor-control-' + controlName + '_' + 'tablet_extra');
            let $control_tablet = panel && panel.$el ? panel.$el.find('.elementor-control-' + controlName + '_' + 'tablet') : $('.elementor-control-' + controlName + '_' + 'tablet');
            let $control_mobile_extra = panel && panel.$el ? panel.$el.find('.elementor-control-' + controlName + '_' + 'mobile_extra') : $('.elementor-control-' + controlName + '_' + 'mobile_extra');
            let $control_mobile = panel && panel.$el ? panel.$el.find('.elementor-control-' + controlName + '_' + 'mobile') : $('.elementor-control-' + controlName + '_' + 'mobile');
            let $control_widescreen = panel && panel.$el ? panel.$el.find('.elementor-control-' + controlName + '_' + 'widescreen') : $('.elementor-control-' + controlName + '_' + 'widescreen');
            controlsList = [{
              device: 'widescreen',
              'value': $control_widescreen.find('input, input[type="text"], input[type="number"], input[type="url"], textarea, select').val(),
              key: controlName + '_widescreen',
              parent: null
            }, {
              device: 'desktop',
              'value': $control_desktop.find('input, .elementor-slider-input input[type="number"], input[type="text"], input[type="number"], input[type="url"], textarea, select').last().val(),
              key: controlName,
              parent: controlName + '_widescreen'
            }, {
              device: 'laptop',
              'value': $control_laptop.find('input, .elementor-slider-input input[type="number"] ,input[type="text"], input[type="number"], input[type="url"], textarea, select').last().val(),
              key: controlName + '_laptop',
              parent: controlName
            }, {
              device: 'tablet_extra',
              'value': $control_tablet_extra.find('input,.elementor-slider-input input[type="number"] ,input[type="text"], input[type="number"], input[type="url"], textarea, select').last().val(),
              key: controlName + '_tablet_extra',
              parent: controlName + '_laptop'
            }, {
              device: 'tablet',
              'value': $control_tablet.find('input,.elementor-slider-input input[type="number"] ,input[type="text"], input[type="number"], input[type="url"], textarea, select').last().val(),
              key: controlName + '_tablet',
              parent: controlName + '_tablet_extra'
            }, {
              device: 'mobile_extra',
              'value': $control_mobile_extra.find('input,.elementor-slider-input input[type="number"] ,input[type="text"], input[type="number"], input[type="url"], textarea, select').last().val(),
              key: controlName + '_mobile_extra',
              parent: controlName + '_tablet'
            }, {
              device: 'mobile',
              'value': $control_mobile.find('input,.elementor-slider-input input[type="number"] ,input[type="text"], input[type="number"], input[type="url"], textarea, select').last().val(),
              key: controlName + '_mobile',
              parent: controlName + '_mobile_extra'
            }];
            const currentCR = findValueRecursive(controlsList, device);
            if (currentCR) {
              if ($control.hasClass('elementor-control-type-slider')) {
                $input.attr('placeholder', currentCR);
              } else {
                $input.val(currentCR);
              }
            }
            if (device === 'widescreen') {
              const cselectEle = controlsList.find(it => it.device === 'desktop');
              if (cselectEle && cselectEle.value) {
                $input.val(cselectEle.value);
              }
            }
          }
        }
      });
    } catch (e) {
      // non-fatal
      console.info('updatePlaceholdersForPanel error', e);
    }
  }

  /* Helper to run the main function (scoped to a specific panel/model) */
  function run_element_set(panel, model, view) {
    var currentDevice = elementor.channels.deviceMode.request('currentMode') || 'desktop';
    updatePlaceholdersForPanel(panel, model, currentDevice);
    var $element = panel.$el.find('.elementor-control-_section_wcf_text_animation, .elementor-control-_section_wcf_image_animation, .elementor-control-_section_wcf_animation, .elementor-control-_section_wcf_horizontal_scroll_area, .elementor-control-_section_pin-area');
    if ($element.length) {
      $element[0].addEventListener('click', function () {
        currentDevice = elementor.channels.deviceMode.request('currentMode') || 'desktop';
        updatePlaceholdersForPanel(panel, model, currentDevice);
      });
    }
    panel.$el.find('.elementor-tab-control-advanced').on('click', function () {
      $element = panel.$el.find('.elementor-control-_section_wcf_animation');
      if ($element.length) {
        $element[0].addEventListener('click', function () {
          currentDevice = elementor.channels.deviceMode.request('currentMode') || 'desktop';
          console.log(currentDevice);
          updatePlaceholdersForPanel(panel, model, currentDevice);
        });
      }
    });

    // device change handler (scoped to this panel/model)
    var onDeviceModeChange = function () {
      var mode = elementor.channels.deviceMode.request('currentMode') || 'desktop';
      updatePlaceholdersForPanel(panel, model, mode);
    };

    // listen while panel open
    elementor.listenTo(elementor.channels.deviceMode, 'change', onDeviceModeChange);
    var cleanup = function () {
      try {
        elementor.stopListening(elementor.channels.deviceMode, 'change', onDeviceModeChange);
        // remove attributes we set (optional)
        if (panel && panel.$el) {
          CONTROL_NAMES.forEach(function (controlName) {
            var $control = panel.$el.find('.elementor-control-' + controlName);
            if ($control.length) {
              $control.find('.elementor-control-input-wrapper, .elementor-control-field').removeAttr('data-inherited-placeholder').removeAttr('title');
              $control.find('input, textarea, select').removeAttr('placeholder'); // beware: if you want to keep original placeholders remove this line
            }
          });
        }
      } catch (e) {
        // ignore
      }
    };

    // Panel view may emit 'hidden' or similar – support common hooks:
    if (panel && panel.on) {
      panel.once('hidden', cleanup);
      panel.once('panel:close', cleanup);
    } else {
      // fallback: remove listeners after a short timeout (defensive)
      setTimeout(cleanup, 60 * 60 * 1000);
    }
  }
  elementor.hooks.addAction('panel/open_editor/widget', function (panel, model, view) {
    run_element_set(panel, model, view);
    const allowed_sections = ['_section_wcf_text_animation'];
    // Define handler
    function sectionActivatedHandler(sectionName, editor) {
      if (allowed_sections.includes(sectionName)) {
        run_element_set(panel, model, view);
      }
    }
    // Attach handler
    elementor.channels.editor.on('section:activated', sectionActivatedHandler);
    // When the panel closes, remove the handler
    panel.$el.on('panel:close', function () {
      elementor.channels.editor.off('section:activated', sectionActivatedHandler);
    });
  });
  elementor.hooks.addAction('panel/open_editor/container', function (panel, model) {
    run_element_set(panel, model, null);
    // Define handler
    const allowed_sections = ['_section_wcf_animation', '_section_wcf_horizontal_scroll_area', '_section_pin-area'];
    function sectionActivatedHandler(sectionName, editor) {
      if (allowed_sections.includes(sectionName)) {
        run_element_set(panel, model, null);
      }
    }
    // Attach handler
    elementor.channels.editor.on('section:activated', sectionActivatedHandler);
    // When the panel closes, remove the handler
    panel.$el.on('panel:close', function () {
      elementor.channels.editor.off('section:activated', sectionActivatedHandler);
    });
  });
})(jQuery, elementor);