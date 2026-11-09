/**
 * WCF Addons Editor Core
 * @version 1.0.0
 */

/* global jQuery, WCF_Addons_Editor*/

/**
 * Regenerate ALL `id` fields in an Elementor JSON (recursively).
 * - Works for trees that use `elements` and/or `content`
 * - Returns a NEW object (original is not mutated)
 * - Guarantees globally unique short hex IDs (7 chars by default)
 *
 * @param {Object|String} input - Elementor JSON object or JSON string
 * @param {number} [len=7] - id length (6–8 looks Elementor-ish)
 * @returns {Object} new JSON with regenerated ids
 */
function regenerateElementorIds(input, len = 7) {
  const data = typeof input === 'string' ? JSON.parse(input) : input;
  const root = typeof structuredClone === 'function' ? structuredClone(data) : JSON.parse(JSON.stringify(data));
  const seen = new Set();
  function genId() {
    let out = '';
    if (typeof crypto !== 'undefined' && crypto.getRandomValues) {
      const bytes = new Uint8Array(len);
      crypto.getRandomValues(bytes);
      for (let i = 0; i < len; i++) out += (bytes[i] & 0x0f).toString(16);
    } else {
      while (out.length < len) out += Math.floor(Math.random() * 16).toString(16);
    }
    return out;
  }
  function uniqueId() {
    let id;
    do {
      id = genId();
    } while (seen.has(id));
    seen.add(id);
    return id;
  }
  function walk(node) {
    if (!node || typeof node !== 'object') return;

    // If this node has an id, replace it with a new unique one
    if (Object.prototype.hasOwnProperty.call(node, 'id')) {
      node.id = uniqueId();
    }

    // Recurse into children (Elementor uses either `elements` or `content`)
    const kids = node.elements || node.content;
    if (Array.isArray(kids)) {
      for (const child of kids) walk(child);
    }
  }
  walk(root);
  return root;
}
(function ($, window, document, config) {
  elementor.channels.editor.on("wcf:editor:play_animation", sectionName => {
    sectionName.$el.parent().find(".elementor-control-wcf_enable_animation_editor .elementor-switch-input").trigger("change");
    sectionName.$el.parent().find(".elementor-control-wcf_img_animation_editor .elementor-switch-input").trigger("change");
    sectionName.$el.parent().find(".elementor-control-wcf_text_animation_editor .elementor-switch-input").trigger("change");
  });

  // dynamic tags

  $(document).on("change", '[data-setting="taxonomy_type"]', function () {
    const taxonomy = $(this).val();
    const $categorySelect = $('[data-setting="category_id"]');
    if (!taxonomy) return;

    // Clear the category dropdown before loading
    $categorySelect.empty().append('<option value="">Loading...</option>');
    $.ajax({
      url: config.ajaxUrl,
      method: "POST",
      data: {
        action: "wcf_get_terms_by_taxonomy",
        nonce: config._wpnonce,
        taxonomy: taxonomy
      },
      success: function (response) {
        $categorySelect.empty();
        if (response.success && response.data.length > 0) {
          response.data.forEach(function (term) {
            $categorySelect.append($("<option></option>").attr("value", term.id).text(term.name));
          });
        } else {
          $categorySelect.append('<option value="">No terms available</option>');
        }
      },
      error: function () {
        $categorySelect.empty().append('<option value="">Error loading terms</option>');
      }
    });
  });
  function getFingerprintId() {
    const url = "https://animation-addons.com/wp-json/live/v1/fingerprint/";
    return $.get(url).done(function (data) {
      localStorage.setItem("aae_machine_id", data.ip);
    }).fail(function (jqXHR, textStatus, errorThrown) {
      console.info("Failed to fetch live copy ID:", textStatus, errorThrown);
    });
  }

  // Function to request widget data
  function requestWidgetData(position) {
    const machineId = localStorage.getItem("aae_machine_id");
    const livePasteUrl = `https://animation-addons.com/wp-json/live/v1/copy-paste?machine_id=${machineId}&type=paste`;
    try {
      $.get({
        url: livePasteUrl,
        crossDomain: true
      }).done(function (data) {
        let options = {
          at: position
        };
        //var selectedElements = elementor.selection.getElements();
        if (data.content && data.content.content) {
          $e.run("document/elements/import", {
            model: window.elementor.elementsModel,
            data: data.content,
            options: options
          });
          elementor.notifications.showToast({
            message: elementor.translate("Live Content Pasted!")
          });
          elementor.notifications.showToast({
            message: elementor.translate("Must be enable required widgets from dashboard settings > Animation Addons.")
          });
        } else {
          elementor.notifications.showToast({
            message: elementor.translate("Live Content not found!")
          });
        }
      }).fail(function (jqXHR, textStatus, errorThrown) {
        elementor.notifications.showToast({
          message: elementor.translate("Only same browser tab works. App uses localStorage to sync content.")
        });
      });
    } catch (error) {
      elementor.notifications.showToast({
        message: elementor.translate("Only same browser tab work, App using browser for live copy ")
      });
    }
  }
  getFingerprintId();
  window.addEventListener("elementor/init", () => {
    getFingerprintId();
    const elTypes = ["widget", "column", "section", "container"];
    elTypes.forEach(elType => {
      elementor.hooks.addFilter(`elements/${elType}/contextMenuGroups`, (groups, view) => {
        const newAction = {
          name: "aae-addon-live-paste",
          icon: "wcf-logo eicon-link aae-icon-pro",
          title: "Paste from animation-addons",
          isEnabled: () => {
            const $currentElement = view.$el; // jQuery element
            const currentDOMElement = $currentElement[0]; // Raw DOM element
            const classesToCheck = ["e-parent", "e-empty"]; // Replace with the classes you want to check
            const hasAllClasses = classesToCheck.every(cls => currentDOMElement.classList.contains(cls));
            if (hasAllClasses) {
              return true;
            }
            return false;
          },
          callback: () => {
            const model = view.getEditModel(); // Current element model
            const $currentElement = view.$el; // jQuery element
            const currentDOMElement = $currentElement[0]; // Raw DOM element
            const classesToCheck = ["e-parent", "e-empty"]; // Replace with the classes you want to check
            const hasAllClasses = classesToCheck.every(cls => currentDOMElement.classList.contains(cls));
            if (hasAllClasses) {
              const siblings = model.collection;
              const index = siblings.indexOf(model);
              requestWidgetData(index, currentDOMElement);
            }
          }
          // shortcut: '^+B', // Custom property for shortcut
        };
        groups.forEach(group => {
          if ("general" === group.name) {
            group.actions.push(newAction);
          }
        });
        return groups;
      });
    });
  });

  // End Live Copy paste
  $(document).on("change", ".elementor-control-aae_text_trigger select", function () {
    const $scrubControl = jQuery(".elementor-control-spin_text_scrub input");
    // For Elementor switchers, values are 'yes' or ''
    $scrubControl.prop("checked", false).trigger("change");
  });
  // Checkbox field , responsive
  jQuery(document).on("change", `
    .elementor-control-aae_hide_widescreen input,
    .elementor-control-aae_hide_desktop input,
    .elementor-control-aae_hide_laptop input,
    .elementor-control-aae_hide_tablet_extra input,
    .elementor-control-aae_hide_tablet input,
    .elementor-control-aae_hide_mobile_extra input,
    .elementor-control-aae_hide_mobile input
    `, function () {
    const $BreakControl = jQuery(".elementor-control-fade_animation_breakpoint select");
    if ($BreakControl.length) {
      // reset to first option or empty value
      $BreakControl.val("").trigger("change");
    }
  });
  // sticky
  jQuery(document).on("change", `
    .elementor-control-aae_sticky_hide_widescreen input,
    .elementor-control-aae_sticky_hide_desktop input,
    .elementor-control-aae_sticky_hide_laptop input,
    .elementor-control-aae_sticky_hide_tablet input,
    .elementor-control-aae_sticky_hide_tablet input,
    .elementor-control-aae_sticky_hide_mobile_extra input,
    .elementor-control-aae_sticky_hide_mobile input
    `, function () {
    const $stickyControl = jQuery(".elementor-control-wcf_pin_breakpoint select");
    if ($stickyControl.length) {
      // reset to first option or empty value
      $stickyControl.val("").trigger("change");
    }
  });
  // Pin
  jQuery(document).on("change", `
    .elementor-control-wcf_pin_end_trigger_type select,
    .elementor-control-wcf_pin_end_trigger_type_desktop select,
    .elementor-control-wcf_pin_end_trigger_type_laptop select,
    .elementor-control-wcf_pin_end_trigger_type_tablet select,
    .elementor-control-wcf_pin_end_trigger_type_tablet_extra select,
    .elementor-control-wcf_pin_end_trigger_type_mobile_extra select,
    .elementor-control-wcf_pin_end_trigger_type_mobile select
    `, function (e) {
    var currentDeviceMode = elementor.channels.deviceMode.request('currentMode');
    let $endTr = null;
    if (currentDeviceMode === 'desktop' || currentDeviceMode == undefined) {
      $endTr = jQuery(`.elementor-control-wcf_pin_end_trigger input`);
    } else {
      $endTr = jQuery(`.elementor-control-wcf_pin_end_trigger_${currentDeviceMode} input`);
    }
    if ($endTr.length) {
      // reset to existting
      $endTr.val('').trigger("focus");
      $endTr.val('').trigger("change");
    }
  });

  // text animation
  jQuery(document).on("change", `
    .elementor-control-aae_text_hide_widescreen input,
    .elementor-control-aae_text_hide_desktop input,
    .elementor-control-aae_text_hide_laptop input,
    .elementor-control-aae_text_hide_tablet input,
    .elementor-control-aae_text_hide_tablet input,
    .elementor-control-aae_text_hide_mobile_extra input,
    .elementor-control-aae_text_hide_mobile input
    `, function () {
    const $txtControl = jQuery(".elementor-control-text_animation_breakpoint select");
    if ($txtControl.length) {
      // reset to first option or empty value
      $txtControl.val("").trigger("change");
    }
  });
  // horizontal
  jQuery(document).on("change", `
    .elementor-control-aae_hor_hide_widescreen input,
    .elementor-control-aae_hor_hide_desktop input,
    .elementor-control-aae_hor_hide_laptop input,
    .elementor-control-aae_hor_hide_tablet input,
    .elementor-control-aae_hor_hide_tablet input,
    .elementor-control-aae_hor_hide_mobile_extra input,
    .elementor-control-aae_hor_hide_mobile input
    `, function () {
    const $horControl = jQuery(".elementor-control-horizontal_scroll_breakpoint select");
    if ($horControl.length) {
      // reset to first option or empty value
      $horControl.val("").trigger("change");
    }
  });
  elementor.channels.editor.on('aaeelementorThemeBuilder:ApplyPreview', function () {
    $e.run('document/save/auto', {
      force: true,
      onSuccess: () => {
        elementor.dynamicTags.cleanCache();
        const isInitialDocument = elementor.config.initial_document.id === elementor.documents.getCurrentId();
        if (isInitialDocument) {
          // Page templates (e.g. single) with header/footer requires a full reload in order
          // to change the main query also for them.
          elementor.reloadPreview();
        } else {
          $e.internal('editor/documents/attach-preview');
        }
      }
    });
  });

  // End dynamic tags

  $(document).on('change', '.elementor-control-aae_loop_page_source select', function () {
    const postType = $(this).val();
    const document = elementor.documents.getCurrent();
    if (!document || !postType) {
      return;
    }

    // 🔍 Find the "Source Item" control select
    const $postSelect = $('.elementor-control-aae_loop_page_post select');
    if (!$postSelect.length) {
      return;
    }

    // 1️⃣ Destroy existing Select2
    if ($postSelect.data('select2')) {
      $postSelect.select2('destroy');
    }

    // 2️⃣ Clear previous value
    $postSelect.val(null);

    // 3️⃣ Re-init Select2 with UPDATED AJAX config
    $postSelect.select2({
      allowClear: true,
      placeholder: 'Select a post',
      ajax: {
        url: ajaxurl,
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            action: 'aae_get_posts_by_type',
            q: params.term || '',
            aae_loop_page_source: postType // ✅ UPDATED HERE
          };
        },
        processResults: function (response) {
          const option = new Option('Selected', config.aae_loop_post, true, true);
          $postSelect.append(option).trigger('change');
          return response;
        }
      }
    });

    // 4️⃣ Force Elementor model update
    const panelView = elementor.getPanelView();
    const pageView = panelView?.getCurrentPageView();
    if (pageView) {
      const model = pageView.collection.find(model => model.get('name') === 'aae_loop_page_post');
      if (model) {
        model.set('value', '');
      }
    }
  });
})(jQuery, window, document, WCF_Addons_Editor);
document.addEventListener("DOMContentLoaded", function () {
  // ✅ Inject CSS only once
  if (!document.getElementById("aae-loading-style")) {
    const style = document.createElement("style");
    style.id = "aae-loading-style";
    style.textContent = `
          #aae-loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(217, 217, 217, 0.5);
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            display: none;
          }
          .aae-loading-inner {
            background-color: #fff;                  
            border-radius: 16px;
            padding: 30px;
          }
          .aae-loading-title {
            font-size: 24px;
            font-weight: 500;
            color: rgba(234, 179, 8, 1);
            padding-bottom: 14px;
          }
          .aae-loading-desc{
            margin-bottom: 30px;
          }
          .aae-template-name{
            margin-top:30px;
            font-size: 18px;
            font-weight:500;
          }                  
          .aae-status {
            margin-top: 14px;
            font: 600 14px/1.3 system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color: #1f2937;
            text-align: center;
            background: #ffffffd9;
            border: 1px solid #e5e7eb;
            padding: 6px 12px; border-radius: 6px;
            min-width: 160px;
            margin-bottom: 20px;
          }
          .aae-error-message {
            display: none;
            color: #b91c1c;
            font-size: 22px;
            font-weight: 600;
            background: #fff3f3;
            border: 1px solid #fca5a5;
            padding: 12px 18px;
            border-radius: 6px;
            text-align: center;
            max-width: 580px;
            margin-top: 15px;
            margin-bottom: 20px;
          }
          @keyframes spin {
            to { transform: rotate(360deg); }
          }
      `;
    document.head.appendChild(style);
  }
  async function aae_requestWidgetData(position, status) {
    // ✅ Inject overlay HTML if not exists
    if (!document.getElementById("aae-loading-overlay")) {
      const overlay = document.createElement("div");
      overlay.id = "aae-loading-overlay";
      overlay.innerHTML = `           
            <div class="aae-loading-inner">
                <h3 class="aae-loading-title">Creating your design ...</h3>
                <p class="aae-loading-desc">Please wait, your page is being created it. it will take few minute. Do not reload.</p>
                <img src="https://crowdytheme.com/assets/wp-content/uploads/2025/09/loading-bg.webp"/>
                <h3 class="aae-template-name"><span></span> </h3>
                <div class="aae-error-message"></div>
            </div>
        `;
      document.body.appendChild(overlay);
    }
    const overlay = document.getElementById("aae-loading-overlay");
    const errorBox = overlay.querySelector(".aae-error-message");
    const title = overlay.querySelector(".aae-template-name span");
    // Reset state to loading  
    errorBox.style.display = "none";
    overlay.style.display = "flex";
    const Preloader = function (msg) {
      setTimeout(() => {
        overlay.style.display = "none";
        overlay.remove(); // remove completely, optional
      }, 1300);
      errorBox.style.display = "block";
      errorBox.textContent = msg;
      title.textContent = '';
      elementor.notifications.showToast({
        message: elementor.translate(msg)
      });
    };
    const machineId = localStorage.getItem("aae_machine_id");
    const livePasteUrl = `https://animation-addons.com/wp-json/live/v1/copy-paste?machine_id=${machineId}&type=paste`;
    try {
      const data = await jQuery.ajax({
        url: livePasteUrl,
        crossDomain: true
      });
      const options = {
        at: position
      };
      if (data.content && data.content.content) {
        if (status === 'download') {
          const cancel = elementorCommon.ajax.addRequest("get_wcf_template_data", {
            data: {
              edit_mode: !0,
              display: !0,
              template_id: 2,
              json_data: data.content
            },
            success: function (e) {
              $e.run("document/elements/import", {
                model: window.elementor.elementsModel,
                data: e,
                options: options
              });
              Preloader('Content pasted');
            }
          }).fail(function () {
            Preloader('Content not found');
          });
        } else {
          const newJson = regenerateElementorIds(data.content);
          $e.run("document/elements/import", {
            model: window.elementor.elementsModel,
            data: newJson,
            options
          });
          Preloader('Content pasted');
        }
      } else {
        Preloader('❌ Element missing or third party widget conflict.');
      }
    } catch (error) {
      Preloader('❌ Only same browser tab works. App uses localStorage to sync content..');
    } finally {
      jQuery(".elementor-context-menu").hide();
    }
  }
  async function handlePasteAndCleanup(poindex, foundelement) {
    aaeConfirmDialog("Download the attached file from the remote source", "Download with Attachment").then(choice => {
      if (choice) {
        aae_requestWidgetData(poindex, 'download');
        // run your download function
      } else {
        aae_requestWidgetData(poindex, 'no');
      }
    });
    if (foundelement) {
      foundelement.remove();
    }
  }
  const observer = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
      mutation.addedNodes.forEach(node => {
        if (node.nodeType === 1 && node.classList.contains("dialog-simple-widget") && node.classList.contains("elementor-context-menu")) {
          // Check if Delete All Content item exists
          const deleteItem = node.querySelector(".elementor-context-menu-list__item-delete_all_content");
          if (!deleteItem) return;

          // Prevent adding multiple times
          if (node.querySelector(".elementor-context-menu-list__item-paste_animation_addons")) return;

          // Create new item
          const customItem = document.createElement("div");
          customItem.className = "elementor-context-menu-list__item elementor-context-menu-list__item-paste_animation_addons";
          customItem.setAttribute("role", "menuitem");
          customItem.setAttribute("tabindex", "0");

          // Add content inside item
          customItem.innerHTML = `
            <div class="elementor-context-menu-list__item__icon"><i class="eicon-import-export"></i></div>
            <div class="elementor-context-menu-list__item__title">Paste from animation-addons</div>
          `;

          // Add click functionality
          customItem.addEventListener("click", function () {
            let poindex = -1;
            let foundelement = null;
            elementor.$previewContents.find(".elementor-section-wrap > *").each(function (index) {
              if (jQuery(this).hasClass("elementor-add-section-inline")) {
                poindex = index;
                foundelement = jQuery(this);
              }
            });
            handlePasteAndCleanup(poindex, foundelement);
          });

          // Append after Delete All Content
          deleteItem.parentNode.insertBefore(customItem, deleteItem.nextSibling);
        }
      });
    });
  });
  observer.observe(document.body, {
    childList: true,
    subtree: true
  });
});
function aaeConfirmDialog(message = "Do you want to continue?", title = "Confirm") {
  return new Promise(resolve => {
    // inject CSS once
    if (!document.getElementById("aae-confirm-style")) {
      const style = document.createElement("style");
      style.id = "aae-confirm-style";
      style.textContent = `
        .aae-confirm-thumb {
          background: rgba(255, 248, 232, 1);
          border: 1px solid rgba(255, 245, 223, 1);
          border-radius: 75px;
          height: 80px;
          width: 80px;
          display: flex;
          align-items: center;
          justify-content: center;
          margin: auto;
          margin-bottom: 24px;
        }
        #aae-confirm-backdrop {
          position: fixed; inset: 0;
          background: rgba(0,0,0,.45);
          display: flex; align-items: center; justify-content: center;
          z-index: 999999;
        }
        .aae-confirm {
          width: min(92vw, 306px);
          background: #fff; border-radius: 12px;
          padding: 20px 18px;
          box-shadow: 0 10px 30px rgba(0,0,0,.15);
          font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
          text-align: center;
        }
        .aae-confirm h3 { margin: 0 0 12px; font-size: 18px; color: #111827; }
        .aae-confirm p  { margin: 0 0 18px; font-size: 14px; color: #374151; }
        .aae-actions { display: flex; gap: 10px; justify-content: center; }
        .aae-btn {
          border-radius: 6px; padding: 8px 14px;
          font-weight: 600; cursor: pointer;
          border: 1px solid rgba(225, 228, 234, 1);
          flex: 1;
          transition: 0.3s ease-in-out;
        }
        .aae-btn:hover{
          background: #ef6d22; color: #fff;
          border: 1px solid transparent;
        }
        .aae-btn:focus  { outline: 2px solid #ef6d22aa; outline-offset: 2px; }
      `;
      document.head.appendChild(style);
    }

    // build modal
    const back = document.createElement("div");
    back.id = "aae-confirm-backdrop";
    back.innerHTML = `
      <div class="aae-confirm" role="dialog" aria-modal="true">
        <div class="aae-confirm-thumb">
          <img src="https://crowdytheme.com/assets/wp-content/uploads/2025/09/folder_icon.svg" />
        </div>
        <h3>${title}</h3>
        <p>${message}</p>
        <div class="aae-actions">
          <button class="aae-btn aae-btn-cancel" type="button">No</button>
          <button class="aae-btn aae-btn-ok" type="button">Yes</button>
        </div>
      </div>
    `;
    document.body.appendChild(back);

    // handlers
    const btnOk = back.querySelector(".aae-btn-ok");
    const btnCancel = back.querySelector(".aae-btn-cancel");
    function cleanup(value) {
      back.remove();
      resolve(value);
    }
    btnOk.addEventListener("click", () => cleanup(true));
    btnCancel.addEventListener("click", () => cleanup(false));
    back.addEventListener("click", e => {
      if (e.target === back) cleanup(false); // click outside = cancel
    });
    document.addEventListener("keydown", function esc(e) {
      if (e.key === "Escape") {
        document.removeEventListener("keydown", esc);
        cleanup(false);
      }
    });
  });
}