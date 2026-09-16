<?php

/**
 * Parent → child conditional logic helpers for Flexible Content layouts.
 *
 * Allows child fields to react to parent layout field values (including ACFE layout settings).
 */

if (!function_exists('ad_acf_prepare_parent_conditions_field')) {
  /**
   * Attach data attributes when a field declares ad_parent_conditional_logic.
   *
   * @param array $field
   * @return array
   */
  function ad_acf_prepare_parent_conditions_field($field)
  {
    if (!isset($field['ad_parent_conditional_logic'])) {
      return $field;
    }

    $conditions = $field['ad_parent_conditional_logic'];

    if (!$conditions) {
      return $field;
    }

    if (!isset($field['wrapper']) || !is_array($field['wrapper'])) {
      $field['wrapper'] = [];
    }

    $field['wrapper']['data-ad-parent-conditions'] = wp_json_encode($conditions);
    $field['wrapper']['data-ad-parent-conditions-present'] = '1';

    return $field;
  }

  add_filter('acf/prepare_field', 'ad_acf_prepare_parent_conditions_field', 20);
}

if (!function_exists('ad_acf_print_parent_conditions_script')) {
  /**
   * Outputs the admin footer script that powers the parent-conditions behaviour.
   *
   * @return void
   */
  function ad_acf_print_parent_conditions_script()
  {
    static $printed = false;

    if ($printed) {
      return;
    }

    $printed = true;

?>
    <script>
      (function($, acf) {
        if (typeof acf === 'undefined') {
          return;
        }

        var ATTR = 'data-ad-parent-conditions';
        var FLAG = 'data-ad-parent-conditions-initialised';
        var NAMESPACE = '.adParentConditions';
        var PROP_NAME = 'ad_parent_conditional_logic';

        function toArray(value) {
          if (Array.isArray(value)) {
            return value;
          }
          if (typeof value === 'undefined' || value === null) {
            return [];
          }
          return [value];
        }

        function normaliseValue(value) {
          if (Array.isArray(value)) {
            return value.map(normaliseValue);
          }

          if (value === null || typeof value === 'undefined') {
            return '';
          }

          if (typeof value === 'boolean') {
            return value ? '1' : '0';
          }

          return value;
        }

        function normaliseGroups(raw) {
          if (!raw) {
            return [];
          }

          if (!Array.isArray(raw)) {
            raw = [raw];
          }

          return raw
            .map(function(group) {
              if (!Array.isArray(group)) {
                group = [group];
              }
              return group
                .map(function(rule) {
                  return rule && rule.field ? rule : null;
                })
                .filter(Boolean);
            })
            .filter(function(group) {
              return group.length > 0;
            });
        }

        function collectFieldKeys(groups) {
          var seen = {};
          var keys = [];

          groups.forEach(function(group) {
            group.forEach(function(rule) {
              var key = rule.field;
              if (!key || seen[key]) {
                return;
              }
              seen[key] = true;
              keys.push(key);
            });
          });

          return keys;
        }

        function compareValues(actual, operator, expected) {
          operator = (operator || '==').toString().toLowerCase();
          actual = normaliseValue(actual);
          expected = normaliseValue(expected);

          var actualArray = Array.isArray(actual) ? actual : null;
          var expectedArray = Array.isArray(expected) ? expected : null;

          function asNumber(value) {
            if (Array.isArray(value)) {
              value = value[0];
            }
            var number = parseFloat(value);
            return Number.isNaN(number) ? null : number;
          }

          switch (operator) {
            case '==':
            case '===':
              if (actualArray) {
                return actualArray.indexOf(expectedArray ? expectedArray[0] : expected) !== -1;
              }
              return actual == expected;

            case '!=':
            case '!==':
              if (actualArray) {
                return actualArray.indexOf(expectedArray ? expectedArray[0] : expected) === -1;
              }
              return actual != expected;

            case '>':
            case '>=':
            case '<':
            case '<=': {
              var actualNum = asNumber(actual);
              var expectedNum = asNumber(expected);

              if (actualNum === null || expectedNum === null) {
                return false;
              }

              if (operator === '>') return actualNum > expectedNum;
              if (operator === '>=') return actualNum >= expectedNum;
              if (operator === '<') return actualNum < expectedNum;
              return actualNum <= expectedNum;
            }

            case 'in':
            case 'anyin':
              if (!expectedArray) {
                expectedArray = toArray(expected);
              }
              if (actualArray) {
                return actualArray.some(function(item) {
                  return expectedArray.indexOf(item) !== -1;
                });
              }
              return expectedArray.indexOf(actual) !== -1;

            case 'not_in':
            case 'notin':
              if (!expectedArray) {
                expectedArray = toArray(expected);
              }
              if (actualArray) {
                return actualArray.every(function(item) {
                  return expectedArray.indexOf(item) === -1;
                });
              }
              return expectedArray.indexOf(actual) === -1;

            case 'contains':
              if (actualArray) {
                return actualArray.indexOf(expected) !== -1;
              }
              return actual && actual.toString().indexOf(expected) !== -1;

            case 'not_contains':
              if (actualArray) {
                return actualArray.indexOf(expected) === -1;
              }
              return !actual || actual.toString().indexOf(expected) === -1;

            case 'empty':
              if (actualArray) {
                return actualArray.length === 0;
              }
              return actual === '' || actual === null || typeof actual === 'undefined';

            case 'not_empty':
              if (actualArray) {
                return actualArray.length > 0;
              }
              return actual !== '' && actual !== null && typeof actual !== 'undefined';

            case 'truthy':
              if (actualArray) {
                return actualArray.length > 0;
              }
              return !!actual && actual !== '0' && actual !== 'false';

            case 'falsy':
              if (actualArray) {
                return actualArray.length === 0;
              }
              return !actual || actual === '0' || actual === 'false';

            default:
              return actual == expected;
          }
        }

        function evaluateGroups(groups, valueResolver) {
          if (!groups.length) {
            return true;
          }

          for (var i = 0; i < groups.length; i++) {
            var rules = groups[i];
            var groupPass = true;

            for (var j = 0; j < rules.length; j++) {
              var rule = rules[j];
              var value = valueResolver(rule.field);

              if (!compareValues(value, rule.operator, rule.value)) {
                groupPass = false;
                break;
              }
            }

            if (groupPass) {
              return true;
            }
          }

          return false;
        }

        function findParentLayout($field) {
          return $field.closest('.layout[data-layout]');
        }

        function buildLayoutChain($layout) {
          var chain = [];
          var visited = [];
          var current = $layout;

          while (current && current.length) {
            var element = current[0];
            if (!element || visited.indexOf(element) !== -1) {
              break;
            }

            chain.push(current);
            visited.push(element);

            var $flex = current.closest('.acf-field-flexible-content');

            if (!$flex.length) {
              break;
            }

            current = $flex.closest('.layout[data-layout]');
          }

          return chain;
        }

        function findFieldInLayout($layout, key) {
          if (!$layout || !$layout.length) {
            return null;
          }

          var selector = '[data-key="' + key + '"]';
          var layoutId = $layout.data('id');
          var $fieldsContainer = $layout.children('.acf-fields');

          if (!$fieldsContainer.length) {
            $fieldsContainer = $layout;
          }

          var $candidates = $fieldsContainer.find(selector);

          if (!$candidates.length) {
            // Look inside any ACFE modal currently open for this layout.
            if (layoutId) {
              $candidates = $('.acfe-modal.-open').find(selector + '[data-acfe-flexible-modal-layout="' + layoutId + '"]');
            }
          }

          if (!$candidates.length) {
            return null;
          }

          // Filter by input name matching prefix to isolate the correct layout instance.
          var prefix = layoutId + ']';
          var $candidate = $();

          $candidates.each(function() {
            var $node = $(this);
            var $input = $node.find('input[name], select[name], textarea[name]').first();

            if (!$input.length) {
              return;
            }

            var name = $input.attr('name');

            if (!name) {
              return;
            }

            if (name.indexOf(layoutId) !== -1) {
              $candidate = $node;
              return false;
            }
          });

          if (!$candidate.length) {
            $candidate = $candidates.first();
          }

          return $candidate.length ? acf.getField($candidate) : null;
        }

        function findParentFieldInstance(layoutChain, key) {
          for (var i = 0; i < layoutChain.length; i++) {
            var field = findFieldInLayout(layoutChain[i], key);
            if (field) {
              return field;
            }
          }

          return null;
        }

        function initField($field) {
          var childField = acf.getField($field);

          if (!childField) {
            return;
          }

          if (childField._adParentConditionsInitialised) {
            if (childField._adParentConditionsCleanup) {
              childField._adParentConditionsCleanup();
            }
            return;
          }

          var rawConditions = $field.attr(ATTR);

          if (!rawConditions) {
            rawConditions = childField[PROP_NAME];
            if (!rawConditions) {
              return;
            }
          }

          var parsed;

          if (typeof rawConditions === 'string') {
            try {
              parsed = JSON.parse(rawConditions);
            } catch (error) {
              return;
            }
          } else if (Array.isArray(rawConditions)) {
            parsed = rawConditions;
          } else {
            return;
          }

          var groups = normaliseGroups(parsed);

          if (!groups.length) {
            return;
          }

          var $layout = findParentLayout($field);

          if (!$layout.length) {
            return;
          }

          var layoutChain = buildLayoutChain($layout);

          if (!layoutChain.length) {
            return;
          }

          var parentKeys = collectFieldKeys(groups);

          if (!parentKeys.length) {
            return;
          }

          var parentFields = {};
          var watchers = [];

          function cleanup() {
            if (cleanup.done) {
              return;
            }

            cleanup.done = true;

            watchers.forEach(function(entry) {
              if (entry.field) {
                entry.field.off('change', entry.handler);
                entry.field.off('input', entry.handler);
              }
              if (entry.$inputs && entry.$inputs.length) {
                entry.$inputs.off(NAMESPACE);
              }
            });

            childField._adParentConditionsInitialised = false;
            childField._adParentConditionsCleanup = null;
            $field.removeAttr(FLAG);
          }

          var missingKey = false;

          parentKeys.forEach(function(key) {
            if (parentFields[key]) {
              return;
            }

            var parentField = findParentFieldInstance(layoutChain, key);

            if (!parentField) {
              missingKey = true;
              return;
            }

            parentFields[key] = parentField;

            var handler = function() {
              updateVisibility();
            };

            parentField.on('change', handler);
            parentField.on('input', handler);

            var $inputs = parentField.$input || parentField.$el.find('input, select, textarea');

            if ($inputs && $inputs.length) {
              $inputs.on('input' + NAMESPACE + ' change' + NAMESPACE, handler);
            }

            parentField.on('remove', cleanup);

            watchers.push({
              field: parentField,
              handler: handler,
              $inputs: $inputs
            });
          });

          if (missingKey || !Object.keys(parentFields).length) {
            return;
          }

          childField.on('remove', cleanup);

          function resolveValue(key) {
            var field = parentFields[key];
            if (!field) {
              return null;
            }
            return field.val();
          }

          function updateVisibility() {
            var shouldDisplay = evaluateGroups(groups, resolveValue);

            if (shouldDisplay) {
              childField.show();
            } else {
              childField.hide();
            }
          }

          childField._adParentConditionsInitialised = true;
          childField._adParentConditionsCleanup = cleanup;
          $field.attr(FLAG, '1');

          updateVisibility();
        }

        function initialiseWithin($context) {
          var $root = $context && $context.$el ? $context.$el : $context;

          if (!$root || !$root.length) {
            $root = $(document);
          }

          var selector = '[' + ATTR + ']';
          var $targets = $root.find(selector);

          if ($root.is && $root.is(selector)) {
            $targets = $targets.add($root);
          }

          $targets.each(function() {
            initField($(this));
          });
        }

        acf.addAction('ready', initialiseWithin);
        acf.addAction('append', initialiseWithin);
      })(jQuery, window.acf);
    </script>
<?php
  }

  add_action('acf/input/admin_footer', 'ad_acf_print_parent_conditions_script', 50);
}
