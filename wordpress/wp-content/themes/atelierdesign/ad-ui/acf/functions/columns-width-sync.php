<?php

/**
 * Keep `_columns` width select inputs in sync across conditional variants.
 */

add_action('acf/input/admin_footer', function () {
?>
  <script>
    (function(window, document, acf) {
      if (!document) {
        return;
      }

      var lock = false;
      var SELECTOR = '[data-key="field-columns-width-v1"] select, [data-key="field-columns-width-v2"] select';
      var SEGMENT_V1 = 'field-columns-width-v1';
      var SEGMENT_V2 = 'field-columns-width-v2';
      var SEGMENT_TOGGLE = 'field-columns-setting-isFullWidth';

      function optionExists(select, value) {
        if (!select) {
          return false;
        }

        if (value === '') {
          return true;
        }

        for (var i = 0; i < select.options.length; i++) {
          if (select.options[i].value === value) {
            return true;
          }
        }

        return false;
      }

      function replaceSegment(name, segment, replacement) {
        if (!name || name.indexOf(segment) === -1) {
          return null;
        }

        return name.replace(segment, replacement);
      }

      function getPairName(name) {
        if (!name) {
          return null;
        }

        if (name.indexOf(SEGMENT_V1) !== -1) {
          return replaceSegment(name, SEGMENT_V1, SEGMENT_V2);
        }

        if (name.indexOf(SEGMENT_V2) !== -1) {
          return replaceSegment(name, SEGMENT_V2, SEGMENT_V1);
        }

        return null;
      }

      function findPairSelect(select) {
        var pairName = getPairName(select && select.name);

        if (!pairName) {
          return null;
        }

        return document.querySelector('select[name="' + pairName.replace(/"/g, '\\"') + '"]');
      }

      function findToggleName(name) {
        if (!name) {
          return null;
        }

        if (name.indexOf(SEGMENT_V1) !== -1) {
          return replaceSegment(name, SEGMENT_V1, SEGMENT_TOGGLE);
        }

        if (name.indexOf(SEGMENT_V2) !== -1) {
          return replaceSegment(name, SEGMENT_V2, SEGMENT_TOGGLE);
        }

        return null;
      }

      function findToggle(select) {
        var toggleName = findToggleName(select && select.name);

        if (!toggleName) {
          return null;
        }

        return document.querySelector('input[type="checkbox"][name="' + toggleName.replace(/"/g, '\\"') + '"]');
      }

      function syncValues(source, target) {
        if (!target || source === target) {
          return;
        }

        var value = source.value;

        if (!optionExists(target, value)) {
          return;
        }

        lock = true;
        target.value = value;
        target.dispatchEvent(new Event('input', {
          bubbles: true
        }));
        target.dispatchEvent(new Event('change', {
          bubbles: true
        }));
        lock = false;
      }

      function handleChange(event) {
        if (lock) {
          return;
        }

        var select = event.currentTarget;
        var pair = findPairSelect(select);
        var toggle = findToggle(select);

        if (pair && (!toggle || toggle.checked || optionExists(pair, select.value))) {
          syncValues(select, pair);
        }
      }

      function initialiseSelect(select) {
        if (!select || select.dataset.columnsWidthSyncInitialised) {
          return;
        }

        select.dataset.columnsWidthSyncInitialised = '1';
        select.addEventListener('change', handleChange);

        var pair = findPairSelect(select);
        var toggle = findToggle(select);

        if (pair && !pair.dataset.columnsWidthSyncInitialised) {
          initialiseSelect(pair);
        }

        if (toggle && !toggle.dataset.columnsWidthSyncToggleInitialised) {
          initialiseToggle(toggle);
        }
      }

      function handleToggle(toggle) {
        var toggleName = toggle.name;
        var v1Name = replaceSegment(toggleName, SEGMENT_TOGGLE, SEGMENT_V1);
        var v2Name = replaceSegment(toggleName, SEGMENT_TOGGLE, SEGMENT_V2);

        var v1 = v1Name ? document.querySelector('select[name="' + v1Name.replace(/"/g, '\\"') + '"]') : null;
        var v2 = v2Name ? document.querySelector('select[name="' + v2Name.replace(/"/g, '\\"') + '"]') : null;

        var isFullWidth = !!toggle.checked;

        if (v1) {
          v1.disabled = isFullWidth;
        }

        if (v2) {
          v2.disabled = !isFullWidth;
        }

        if (!isFullWidth && v1 && v2 && optionExists(v1, v2.value)) {
          syncValues(v2, v1);
        }
      }

      function initialiseToggle(toggle) {
        if (!toggle || toggle.dataset.columnsWidthSyncToggleInitialised) {
          return;
        }

        toggle.dataset.columnsWidthSyncToggleInitialised = '1';

        handleToggle(toggle);

        toggle.addEventListener('change', function() {
          handleToggle(toggle);
        });
      }

      function initialiseWithin(root) {
        if (!root) {
          root = document;
        }

        var context = root;

        if (root instanceof window.jQuery) {
          context = root.length ? root[0] : null;
        }

        if (!context || !context.querySelectorAll) {
          return;
        }

        var selects = context.querySelectorAll(SELECTOR);

        for (var i = 0; i < selects.length; i++) {
          initialiseSelect(selects[i]);
        }

        var toggles = context.querySelectorAll('input[type="checkbox"][name*="' + SEGMENT_TOGGLE + '"]');

        for (var j = 0; j < toggles.length; j++) {
          initialiseToggle(toggles[j]);
        }
      }

      if (acf && acf.addAction) {
        acf.addAction('ready', function($el) {
          initialiseWithin($el || document);
        });

        acf.addAction('append', function($el) {
          initialiseWithin($el || document);
        });
      }

      if (document.readyState === 'complete' || document.readyState === 'interactive') {
        initialiseWithin(document);
      } else {
        document.addEventListener('DOMContentLoaded', function() {
          initialiseWithin(document);
        });
      }
    })(window, document, window.acf);
  </script>
<?php
});
