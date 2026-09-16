(function ($) {
  var NO_RESULTS_MESSAGE = "No icons found.";
  var LOADING_MESSAGE = "Loading ...";
  var SEARCH_DEBOUNCE = 600;

  function escapeSelector(value) {
    if (window.CSS && typeof window.CSS.escape === "function") {
      return window.CSS.escape(value);
    }
    return String(value).replace(/[\0-\x1F\x7F"'\\]/g, "\\$&");
  }
  function familyClass(family) {
    switch (family) {
      case "rounded":
        return "material-symbols-rounded";
      case "sharp":
        return "material-symbols-sharp";
      case "outlined":
      default:
        return "material-symbols-outlined";
    }
  }

  function parseFamilies(value) {
    if (Array.isArray(value)) {
      return value;
    }

    if (typeof value === "string") {
      try {
        var parsed = JSON.parse(value);
        return Array.isArray(parsed) ? parsed : [];
      } catch (error) {
        return [];
      }
    }

    return [];
  }

  function readableLabel(value) {
    if (!value) {
      return "";
    }

    return value.replace(/_/g, " ");
  }

  function updatePreview($field, value, families) {
    var $acfms = $field.find(".acfms-field");
    var $preview = $acfms.find(".acfms-preview");
    var storedFamilies = parseFamilies($acfms.data("families"));
    var allowedFamilies =
      families && families.length ? families : storedFamilies;
    if (!allowedFamilies.length) allowedFamilies = ["outlined"];
    var family = $acfms.attr("data-family") || allowedFamilies[0];
    var weight = $acfms.attr("data-weight") || "400";
    var grad = $acfms.attr("data-grad") || "0";
    var opsz = $acfms.attr("data-opsz") || "24";
    var fill = $acfms.attr("data-fill") || "0";
    var iconClass = familyClass(family);
    var fontStyle =
      "font-variation-settings: 'FILL' " +
      fill +
      ", 'wght' " +
      weight +
      ", 'GRAD' " +
      grad +
      ", 'opsz' " +
      opsz +
      ";";

    if (!value) {
      $preview.removeClass("has-value");
      $preview.html(
        '<span class="acfms-preview__empty-icon ' +
          iconClass +
          '" aria-hidden="true" style="' +
          fontStyle +
          '">hide_image</span>' +
          '<span class="acfms-preview__empty-text">No icon selected</span>'
      );
      return;
    }
    var safeText = $("<div>").text(value).html();
    var label = $("<div>").text(readableLabel(value)).html();
    $preview.addClass("has-value");
    $preview.html(
      '<span class="acfms-preview__icon ' +
        iconClass +
        '" aria-hidden="true" style="' +
        fontStyle +
        '">' +
        safeText +
        "</span>" +
        '<span class="acfms-preview__label">' +
        label +
        "</span>"
    );
  }

  function updateRadioIcons($acfms) {
    var family = $acfms.attr("data-family") || "outlined";
    var weight = $acfms.attr("data-weight") || "400";
    var grad = $acfms.attr("data-grad") || "0";
    var opsz = $acfms.attr("data-opsz") || "24";
    var fill = $acfms.attr("data-fill") || "0";
    $acfms.find(".acfms-radio .acfms-option__icon").each(function () {
      this.className = "acfms-option__icon " + familyClass(family);
      this.style.fontVariationSettings =
        "'FILL' " +
        fill +
        ", 'wght' " +
        weight +
        ", 'GRAD' " +
        grad +
        ", 'opsz' " +
        opsz;
    });
  }

  function ensureFeedbackElements($acfms, $radioGroup) {
    var $noResults = $acfms.find(".acfms-no-results");
    if (!$noResults.length) {
      $noResults = $(
        '<div class="acfms-no-results" aria-live="polite"></div>'
      ).hide();
      $radioGroup.after($noResults);
    }

    var $loading = $acfms.find(".acfms-loading");
    if (!$loading.length) {
      $loading = $(
        '<div class="acfms-loading" aria-live="polite"></div>'
      ).hide();
      $radioGroup.after($loading);
    }

    return { $noResults: $noResults, $loading: $loading };
  }

  function fetchAndRenderIcons($field, search) {
    var term = (search || "").trim();
    var $acfms = $field.find(".acfms-field");
    var $radioGroup = $acfms.find(".acfms-radio-group");
    var feedback = ensureFeedbackElements($acfms, $radioGroup);
    var $noResults = feedback.$noResults;
    var $loading = feedback.$loading;
    var families = parseFamilies($acfms.data("families"));
    var family = $acfms.attr("data-family") || "outlined";
    var weight = $acfms.attr("data-weight") || "400";
    var grad = $acfms.attr("data-grad") || "0";
    var opsz = $acfms.attr("data-opsz") || "24";
    var fill = $acfms.attr("data-fill") || "0";
    var inputName = $radioGroup.attr("data-input-name");
    var currentValue = $radioGroup.find("input[type=radio]:checked").val();
    var requestId = ($acfms.data("acfms-request-id") || 0) + 1;
    $acfms.data("acfms-request-id", requestId);

    if (!term.length) {
      // Clear search results, keep only None option and currently selected value
      $radioGroup
        .find(".acfms-radio")
        .not(".acfms-radio--none")
        .not(":has(input:checked)")
        .remove();
      $radioGroup.hide();
      $noResults.hide();
      $loading.hide();
      return;
    }

    // Show loading state
    $radioGroup.hide();
    $noResults.hide();
    $loading.text(LOADING_MESSAGE).show();

    // Make AJAX call
    $.ajax({
      url: ACFMS.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "acfms/icons/query",
        s: term,
        families: families,
        nonce: ACFMS.nonce,
        _ajax_nonce: ACFMS.nonce,
      },
      success: function (response) {
        if (requestId !== $acfms.data("acfms-request-id")) {
          return;
        }

        if (!response) {
          $radioGroup.hide();
          $noResults.text("Error loading icons.").show();
          $loading.hide();
          return;
        }

        var results = [];

        if (Array.isArray(response.results)) {
          results = response.results;
        } else if (response.data && Array.isArray(response.data.results)) {
          results = response.data.results;
        } else if (
          response.data &&
          response.data.results &&
          Array.isArray(response.data.results.results)
        ) {
          results = response.data.results.results;
        }

        var iconClass = familyClass(family);
        var iconStyle =
          "font-variation-settings: 'FILL' " +
          fill +
          ", 'wght' " +
          weight +
          ", 'GRAD' " +
          grad +
          ", 'opsz' " +
          opsz +
          ";";

        var $selected = $radioGroup
          .find('input[type="radio"]:checked')
          .closest(".acfms-radio");
        if ($selected.length) {
          $selected.detach();
        }

        // Clear old results but keep None option
        $radioGroup.find(".acfms-radio").not(".acfms-radio--none").remove();

        if (results.length === 0) {
          $radioGroup.hide();
          $noResults.text(NO_RESULTS_MESSAGE).show();
          $loading.hide();
          return;
        }

        // Check if selected icon is in the results
        var selectedInResults = false;
        if ($selected && $selected.length && currentValue) {
          for (var i = 0; i < results.length; i++) {
            if (results[i] && results[i].name === currentValue) {
              selectedInResults = true;
              break;
            }
          }
        }

        // Render results
        results.forEach(function (icon) {
          var iconName = icon && icon.name ? String(icon.name) : "";

          // Skip if this icon is already in the DOM (e.g., the selected value)
          var isAlreadyRendered = false;
          if (iconName) {
            try {
              isAlreadyRendered =
                $radioGroup.find(
                  'input[value="' + escapeSelector(iconName) + '"]'
                ).length > 0;
            } catch (err) {
              isAlreadyRendered = false;
            }
          }

          if (isAlreadyRendered) {
            return;
          }

          if (iconName === currentValue) {
            return;
          }

          var isChecked = currentValue === iconName;
          var familiesAttr = JSON.stringify(icon.families || []);
          var categoriesAttr = Array.isArray(icon.categories)
            ? icon.categories.join(",")
            : "";
          var tagsAttr = Array.isArray(icon.tags) ? icon.tags.join(",") : "";

          var $label = $("<label>")
            .addClass("acfms-radio")
            .attr("data-name", iconName)
            .attr("data-categories", categoriesAttr)
            .attr("data-tags", tagsAttr);

          var $input = $("<input>")
            .attr("type", "radio")
            .attr("name", inputName)
            .attr("value", iconName)
            .attr("data-families", familiesAttr);

          if (isChecked) {
            $input.prop("checked", true);
          }

          var $iconSpan = $("<span>")
            .addClass("acfms-option__icon")
            .addClass(iconClass)
            .attr("style", iconStyle)
            .text(iconName);

          var $labelSpan = $("<span>")
            .addClass("acfms-option__label")
            .text(iconName);

          $label.append($input, $iconSpan, $labelSpan);
          $radioGroup.append($label);
        });

        // Only append selected if it's in the results
        if ($selected && $selected.length && selectedInResults) {
          $radioGroup.append($selected);
        }

        $radioGroup.show();
        $noResults.hide();
        $loading.hide();
        updateRadioIcons($acfms);
      },
      error: function () {
        if (requestId !== $acfms.data("acfms-request-id")) {
          return;
        }
        $noResults.text("Error loading icons.").show();
        $loading.hide();
        $radioGroup.hide();
      },
    });
  }

  function debounce(fn, delay) {
    var timer = null;
    return function () {
      var context = this,
        args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        fn.apply(context, args);
      }, delay);
    };
  }

  function initField(fieldElem) {
    var $field = $(fieldElem);
    var $acfms = $field.find(".acfms-field");
    var $radioGroup = $acfms.find(".acfms-radio-group");
    var $radios = $radioGroup.find("input[type=radio]");
    var $search = $acfms.find(".acfms-search");
    var storedFamilies = parseFamilies($acfms.data("families"));
    if (!$radioGroup.length) return;

    // Fix input names if they contain acfcloneindex placeholder
    // This happens when ACF clones the field for flexible content/repeater
    var inputName = $radioGroup.attr("data-input-name");
    if (inputName && inputName.indexOf("acfcloneindex") !== -1) {
      // Use the hidden tracker input to get the correct name (ACF updates this when cloning)
      var $tracker = $acfms.find(".acfms-field-name-tracker");
      if ($tracker.length && $tracker.attr("name")) {
        var correctName = $tracker.attr("name");
        // Update the data attribute so AJAX-generated radios use the correct name
        $radioGroup.attr("data-input-name", correctName);

        // Also update any existing radio inputs
        $radioGroup.find('input[type="radio"]').each(function () {
          $(this).attr("name", correctName);
        });
      }
    }

    // Use event delegation for all radios (existing and future)
    $radioGroup.on("change", "input[type=radio]", function () {
      var $selected = $(this);
      var value = $selected.val();
      var families = parseFamilies($selected.data("families"));
      updatePreview($field, value, families.length ? families : storedFamilies);
    });

    // Initial preview and icon style
    updatePreview($field, $radios.filter(":checked").val(), storedFamilies);
    updateRadioIcons($acfms);

    var debouncedFetch = debounce(function () {
      fetchAndRenderIcons($field, $search.val());
    }, SEARCH_DEBOUNCE);

    $search.on("input", function () {
      var term = ($search.val() || "").trim();
      var feedback = ensureFeedbackElements($acfms, $radioGroup);
      if (term.length) {
        // Show loading immediately
        $radioGroup.hide();
        feedback.$noResults.hide();
        feedback.$loading.text(LOADING_MESSAGE).show();
      } else {
        // Clear loading when search is empty
        feedback.$loading.hide();
      }
      // Debounced fetch
      debouncedFetch();
    });

    // Close icon list when clicking outside
    var closeOnClickOutside = function (e) {
      // Check if click is outside the search wrapper and radio group
      var $target = $(e.target);
      var isOutside =
        !$target.closest(".acfms-search-wrapper").length &&
        !$target.closest(".acfms-radio-group").length &&
        !$target.closest(".acfms-no-results").length &&
        !$target.closest(".acfms-loading").length;

      if (isOutside && $search.val().trim().length > 0) {
        // Clear search and hide the list
        $search.val("");
        var feedback = ensureFeedbackElements($acfms, $radioGroup);
        $radioGroup
          .find(".acfms-radio")
          .not(".acfms-radio--none")
          .not(":has(input:checked)")
          .remove();
        $radioGroup.hide();
        feedback.$noResults.hide();
        feedback.$loading.hide();
      }
    };

    // Attach click handler to document
    $(document).on("click.acfms-" + $field.data("key"), closeOnClickOutside);

    // Clean up handler when field is removed (for repeaters/flexible content)
    $field.on("remove", function () {
      $(document).off("click.acfms-" + $field.data("key"));
    });

    // Initialize with empty search - hides icons
    fetchAndRenderIcons($field, $search.val());
  }

  function observeFields() {
    // Initialize any .acfms-field not yet initialized
    function initAll() {
      $(".acfms-field").each(function () {
        var $acfms = $(this);
        if ($acfms.data("acfms-initialized")) return;
        $acfms.data("acfms-initialized", true);
        // Pass the closest .acf-field as the $field context
        var $field = $acfms.closest(".acf-field");
        initField($field);
      });
    }
    // Initial run
    initAll();
    // Observe DOM for new .acfms-field
    var observer = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        mutation.addedNodes.forEach(function (node) {
          if (node.nodeType === 1) {
            if ($(node).hasClass("acfms-field")) {
              var $acfms = $(node);
              if (!$acfms.data("acfms-initialized")) {
                $acfms.data("acfms-initialized", true);
                var $field = $acfms.closest(".acf-field");
                initField($field);
              }
            } else {
              // Check descendants
              $(node)
                .find(".acfms-field")
                .each(function () {
                  var $acfms = $(this);
                  if (!$acfms.data("acfms-initialized")) {
                    $acfms.data("acfms-initialized", true);
                    var $field = $acfms.closest(".acf-field");
                    initField($field);
                  }
                });
            }
          }
        });
      });
    });
    observer.observe(document.body, { childList: true, subtree: true });
  }

  // Start observing on script load
  $(observeFields);

  acf.addFilter("select2_args", function (args, $select) {
    if (!$select.hasClass("acfms-select")) {
      return args;
    }

    var $field = $select.closest(".acfms-field");
    var $acfms = $field.find(".acfms-field");
    var families = parseFamilies($acfms.data("families"));

    args.minimumInputLength = 0;

    if (args.ajax && args.ajax.data) {
      var originalData = args.ajax.data;
      args.ajax.data = function (params) {
        var data = originalData(params) || {};
        data.families = families;
        return data;
      };
    }

    var iconTemplate = function (item) {
      if (!item || !item.id) {
        return item && item.text ? item.text : "";
      }

      var itemFamilies =
        item.families && item.families.length ? item.families : families;
      var iconClass = familyClass(itemFamilies[0] || "outlined");
      var value = $("<div>")
        .text(item.text || item.id)
        .html();

      return (
        '<span class="acfms-option"><span class="acfms-option__icon ' +
        iconClass +
        '">' +
        value +
        '</span><span class="acfms-option__label">' +
        value +
        "</span></span>"
      );
    };

    args.escapeMarkup = function (markup) {
      return markup;
    };
    args.templateResult = iconTemplate;
    args.templateSelection = iconTemplate;

    return args;
  });
})(jQuery);
