<?php
function ad_get_default_typography_config(array $include = [])
{
  $groups = [
    'heading' => [
      [
        'key' => 'heading-2xl',
        'text' => 'Extra Extra Large',
        'format' => ['block' => 'h2', 'classes' => 'heading-2xl'],
      ],
      [
        'key' => 'heading-xl',
        'text' => 'Extra Large',
        'format' => ['block' => 'h3', 'classes' => 'heading-xl'],
      ],
      [
        'key' => 'heading-lg',
        'text' => 'Large',
        'format' => ['block' => 'h4', 'classes' => 'heading-lg'],
      ],
      [
        'key' => 'heading-md',
        'text' => 'Medium',
        'format' => ['block' => 'h5', 'classes' => 'heading-md'],
      ],
      [
        'key' => 'heading-sm',
        'text' => 'Small',
        'format' => ['block' => 'h6', 'classes' => 'heading-sm'],
      ],
    ],
    'paragraph' => [
      [
        'key' => 'paragraph-xl',
        'text' => 'Extra Large',
        'format' => ['block' => 'p', 'classes' => 'paragraph-xl'],
      ],
      [
        'key' => 'paragraph-lg',
        'text' => 'Large',
        'format' => ['block' => 'p', 'classes' => 'paragraph-lg'],
      ],
      [
        'key' => 'paragraph-md',
        'text' => 'Medium',
        'format' => ['block' => 'p', 'classes' => 'paragraph-md'],
      ],
      [
        'key' => 'paragraph-sm',
        'text' => 'Small',
        'format' => ['block' => 'p', 'classes' => 'paragraph-sm'],
      ],
    ],
    'label' => [
      [
        'key' => 'label',
        'text' => 'Label',
        'format' => ['block' => 'p', 'classes' => 'badge-normal'],
      ],
    ],
  ];

  $normalisedInclude = array_filter(array_map('strtolower', $include));
  $normalisedInclude = array_combine($normalisedInclude, $normalisedInclude);
  $shouldFilter = !empty($normalisedInclude);

  $result = [];

  foreach ($groups as $groupKey => $items) {
    $groupItems = [];

    foreach ($items as $item) {
      if ($shouldFilter && !isset($normalisedInclude[strtolower($item['key'])])) {
        continue;
      }

      $groupItems[] = $item;
    }

    if (!empty($groupItems)) {
      if ($groupKey === 'label') {
        $result[] = $groupItems[0];
      } else {
        $labels = [
          'heading' => 'Heading',
          'paragraph' => 'Paragraph',
        ];

        $result[] = [
          'text' => isset($labels[$groupKey]) ? $labels[$groupKey] : ucfirst($groupKey),
          'items' => $groupItems,
        ];
      }
    }
  }

  return $result;
}

function typography_selector_get_default_config()
{
  $config = ad_get_default_typography_config();
  return apply_filters('typography_selector/default_config', $config);
}

function typography_selector_plugin($plugin_array)
{
  $plugin_array['typography_selector'] =
    get_template_directory_uri() . '/ad-ui/acf/functions/tinymce-typography-plugin/plugin.js';
  return $plugin_array;
}

function typography_selector_buttons($buttons)
{
  array_unshift($buttons, 'typography-selector');
  return $buttons;
}

function typography_selector_normalize_config($config)
{
  if (is_string($config)) {
    $decoded = json_decode($config, true);
    if (json_last_error() === JSON_ERROR_NONE) {
      $config = $decoded;
    }
  }

  if (!is_array($config) || empty($config)) {
    return null;
  }

  return $config;
}

function typography_selector_prepare_acf_wysiwyg_field($field)
{
  if (!isset($field['ad_typography_config'])) {
    return $field;
  }

  $config = typography_selector_normalize_config($field['ad_typography_config']);

  if ($config === null) {
    $config = typography_selector_get_default_config();
  }

  if (!isset($field['wrapper']) || !is_array($field['wrapper'])) {
    $field['wrapper'] = [];
  }

  $field['wrapper']['data-ad-typography-config'] = wp_json_encode($config);
  $field['wrapper']['data-ad-typography-config-present'] = '1';

  return $field;
}

add_filter('acf/prepare_field/type=wysiwyg', 'typography_selector_prepare_acf_wysiwyg_field', 20);

function typography_selector_print_acf_bridge_script()
{
  static $printed = false;

  if ($printed) {
    return;
  }

  $printed = true;
  $default_config = typography_selector_get_default_config();
?>
  <script>
    (function($, window) {
      window.typographySelectorDefaultConfig = <?php echo wp_json_encode($default_config); ?>;

      if (typeof acf === 'undefined') {
        return;
      }

      acf.addFilter('wysiwyg_tinymce_settings', function(mceInit, id, field) {
        if (!field || !field.$el) {
          return mceInit;
        }

        var $wrapper = field.$el;

        if (!$wrapper.attr('data-ad-typography-config-present')) {
          return mceInit;
        }

        var config = $wrapper.data('ad-typography-config');

        if (!config) {
          var raw = $wrapper.attr('data-ad-typography-config');
          if (raw) {
            try {
              config = JSON.parse(raw);
            } catch (error) {
              config = null;
            }
          }
        }

        if (!config || !Array.isArray(config) || !config.length) {
          config = window.typographySelectorDefaultConfig;
        }

        try {
          mceInit.typography_selector_config = JSON.parse(JSON.stringify(config));
        } catch (error) {
          mceInit.typography_selector_config = config;
        }

        return mceInit;
      });
    })(jQuery, window);
  </script>
<?php
}

add_action('acf/input/admin_footer', 'typography_selector_print_acf_bridge_script', 5);

function typography_selector_mce_settings($settings)
{
  $filter = 'typography_selector/config';

  $config = null;

  if (isset($settings['typography_selector_config'])) {
    $config = typography_selector_normalize_config($settings['typography_selector_config']);
  }

  $config = apply_filters($filter, $config, $settings);

  if ($config === null) {
    $config = typography_selector_get_default_config();
  }

  $settings['typography_selector_config'] = wp_json_encode($config);

  return $settings;
}

function typography_selector_init()
{
  if (!function_exists('get_current_screen')) {
    return;
  }

  $screen = get_current_screen();

  if (!ad_custom_tinymce_is_supported_screen($screen)) {
    return;
  }

  add_filter('mce_external_plugins', 'typography_selector_plugin');
  add_filter('mce_buttons', 'typography_selector_buttons');
  add_filter('tiny_mce_before_init', 'typography_selector_mce_settings', 20, 1);
}

add_action('current_screen', 'typography_selector_init');
