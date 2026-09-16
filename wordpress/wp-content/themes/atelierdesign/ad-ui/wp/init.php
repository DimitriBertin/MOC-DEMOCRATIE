<?php

/**
 * A modern WordPress theme development workflow for AD UI (coded by Gyozo G)
 */

class ADWP
{
  /**
   * Public variables
   * @var array $js_modules — Array of js modules to be loaded
   * @var array $config — Configuration array
   */

  /**
   * Utility array for storing the JS modules to be loaded
   * @var array $js_modules Array of js modules to be loaded
   */
  public $js_modules = [];

  /**
   * Configuration array
   * @var array $config Configuration array
   */
  public $config = [
    'packageFolder' => '../',
    'packageUrl' => '../',
    'customFlexibleComponentPath' => '../',
    'disableEnqueueStyles' => false,
  ];

  /**
   * Layout arrays
   */
  public $blockLayouts;
  public $inlineLayouts;
  public $nestedLayouts;
  public $deepLayouts;

  /**
   * Registry for individually named layouts
   * @var array
   */
  public $registeredLayouts = [];

  /**
   * Constructor function
   * Enqueues the app.js and app.css files
   */
  function __construct($config = [])
  {
    // Merge config with default config
    $this->config = array_merge($this->config, $config);

    // Get WP theme version defined in default style.css
    $theme = wp_get_theme();
    $theme_version = $theme->get('Version');

    // Define script and style paths
    $script_path = $this->config['packageUrl'] . '/core/dist/app.js';
    $style_path = $this->config['packageUrl'] . '/core/dist/app.css';

    // Enqueue scripts and styles
    add_action('wp_enqueue_scripts', function () use ($script_path, $style_path, $theme_version) {
      // Enqueue application script at the bottom of the page
      wp_enqueue_script('ad-ui-app', $script_path, array(), $theme_version, true);

      // Enqueue application stylesheet
      if (!$this->config['disableEnqueueStyles']) {
        wp_enqueue_style('ad-ui-app', $style_path, array(), $theme_version, 'all');
      }
    }, PHP_INT_MAX);

    // Add support for editor styles, and include the generated editor-style.css file
    add_theme_support('editor-styles');
    add_editor_style($this->config['packageUrl'] . '/core/dist/editor-style.css');
  }

  /**
   * Store the block layouts in the $blockLayouts array
   */
  function add_block_layout($key, $data, $order = 99)
  {
    $this->blockLayouts[$key] = $data;
    $this->blockLayouts[$key]['order'] = $order;
  }

  /**
   * Store the inline layouts in the $inlineLayouts array
   */
  function add_inline_layout($key, $data, $order = 99)
  {
    $this->inlineLayouts[$key] = $data;
    $this->inlineLayouts[$key]['order'] = $order;
  }

  /**
   * Store the nested layouts in the $nestedLayouts array
   */
  function add_nested_layout($key, $data, $order = 99)
  {
    $this->nestedLayouts[$key] = $data;
    $this->nestedLayouts[$key]['order'] = $order;
  }

  /**
   * Store the deep layouts in the $deepLayouts array
   */
  function add_deep_layout($key, $data, $order = 99)
  {
    $this->deepLayouts[$key] = $data;
    $this->deepLayouts[$key]['order'] = $order;
  }

  /**
   * Return the block layouts
   * @return array $blockLayouts
   */
  function get_block_layouts()
  {
    // sort the block layouts by order
    usort($this->blockLayouts, function ($a, $b) {
      return $a['order'] <=> $b['order'];
    });

    // return the block layouts without the 'order' column
    return array_map(function ($item) {
      unset($item['order']);
      return $item;
    }, $this->blockLayouts);
  }

  /**
   * Return the inline layouts
   * @return array $inlineLayouts
   */
  function get_inline_layouts()
  {
    // sort the inline layouts by order
    usort($this->inlineLayouts, function ($a, $b) {
      return $a['order'] <=> $b['order'];
    });

    // return the inline layouts without the 'order' column
    return array_map(function ($item) {
      unset($item['order']);
      return $item;
    }, $this->inlineLayouts);
  }

  /**
   * Return the nested layouts
   * @return array $nestedLayouts
   */
  function get_nested_layouts()
  {
    // sort the nested layouts by order
    usort($this->nestedLayouts, function ($a, $b) {
      return $a['order'] <=> $b['order'];
    });

    // return the nested layouts without the 'order' column
    return array_map(function ($item) {
      unset($item['order']);
      return $item;
    }, $this->nestedLayouts);
  }

  /**
   * Return the deep layouts
   * @return array $deepLayouts
   */
  function get_deep_layouts()
  {
    // sort the deep layouts by order
    usort($this->deepLayouts, function ($a, $b) {
      return $a['order'] <=> $b['order'];
    });

    // return the deep layouts without the 'order' column
    return array_map(function ($item) {
      unset($item['order']);
      return $item;
    }, $this->deepLayouts);
  }

  /**
   * Register a single layout by name for later retrieval
   * @param string $name†
   * @param array $data
   * @return void
   */
  function register_layout($name, $data)
  {
    $this->registeredLayouts[$name] = $data;
  }

  /**
   * Retrieve a previously registered layout by name
   * Returns a single-element list (or empty list) to support array unpacking
   * in ACF flexible content definitions.
   * @param string $name
   * @return array
   */
  function get_layout($name)
  {
    if (isset($this->registeredLayouts[$name])) {
      return [$this->registeredLayouts[$name]];
    }
    return [];
  }

  /**
   * 
   */
  function head()
  {
    // Run native WP function to get header
    wp_head();

    // Void
    return;
  }

  /**
   * Provides a simple mechanism for loading components as reusable sections of code in the theme.
   * Includes files from /src/components/[slug]/markup.php and /src/components/[slug]/script.module.js
   * If the defined $slug does not exist, the function will void.
   * If the script.module.js file exists, it will be added to the $js_modules array.
   * 
   * @param string $slug The slug name for the generic template. (eg. "header" for /src/components/header/markup.php)
   * @param array $args Optional. Additional arguments passed to the template. Default empty array.
   * @param array $customModules Optional. Additional JS modules to be loaded. Default empty array.
   * @return void|false Void on success, false if the template does not exist.
   */
  function get_template_part($slug, $args = [], $customModules = [])
  {
    // Turn slug into path
    $markup_file_path = $this->config['packageFolder'] . '/acf/components/' . $slug . '/markup.php';
    $custom_markup_file_path = $this->config['customFlexibleComponentPath'] . '/' . $slug . '/markup.php';
    // If the custom markup file path exists, use it instead of the default markup file path
    if (file_exists($custom_markup_file_path)) {
      $markup_file_path = $custom_markup_file_path;
    }

    // If file exists
    if (file_exists($markup_file_path)) {

      // If script.module.js exists, add it to the js_modules array
      if (file_exists($this->config['packageFolder'] . '/acf/components/' . $slug . '/script.module.js')) {
        $this->js_modules[] = $slug;
        $this->js_modules = array_unique($this->js_modules);
      }

      // If custom modules are defined, add them to the js_modules array
      if (count($customModules) > 0) {
        array_push($this->js_modules, ...$customModules);
        $this->js_modules = array_unique($this->js_modules);
        echo "<script> console.log(" . json_encode($this->js_modules) . "); </script>";
      }

      // run native WP function to get template part
      if (strpos($markup_file_path, get_template_directory()) !== false) {
        $path = str_replace(get_template_directory(), '', $markup_file_path);
        $path = substr($path, 0, -4);
        get_template_part($path, $slug, $args);
      } else {
        echo "Cannot locate template part: " . $markup_file_path;
      }

      return;
    } else {
      return;
    }
  }

  /**
   * Renders the native WordPress footer (wp_footer) but also prints the js_modules array as a global variable.
   * JavaScript modules are automatically collected based on the usage of the get_template_part function on the page.
   * @return void;
   */
  function footer()
  {
    // Print the js_modules array as a global variable
    echo "<script> let adwpScriptModules = [";
    if (count($this->js_modules) > 0) {
      echo '\'' . implode("', '", $this->js_modules) . '\'';
    }
    echo "]; </script>";

    // Run native WP function to get footer
    wp_footer();

    // Void
    return;
  }

  /**
   * Deep-merge two arrays, replacing scalar values and list arrays,
   * but recursively merging associative arrays by keys.
   *
   * @param array $base      The base array to merge into
   * @param array $override  The overriding values
   * @return array           The merged result
   */
  private function deep_merge_replace(array $base, array $override): array
  {
    foreach ($override as $key => $overrideValue) {
      if (array_key_exists($key, $base)) {
        if (
          is_array($base[$key]) && is_array($overrideValue)
          && $this->is_assoc_array($base[$key]) && $this->is_assoc_array($overrideValue)
        ) {
          $base[$key] = $this->deep_merge_replace($base[$key], $overrideValue);
        } else {
          $base[$key] = $overrideValue;
        }
      } else {
        $base[$key] = $overrideValue;
      }
    }
    return $base;
  }

  /**
   * Determine if an array is associative (has non-sequential keys).
   *
   * @param array $arr
   * @return bool
   */
  private function is_assoc_array(array $arr): bool
  {
    if ($arr === []) {
      return false;
    }
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  /**
   * Renders ACF flexible content fields by processing each block and loading the appropriate template parts.
   * Removes ACF flexible layout prefixes from field keys and adds parent context.
   * 
   * @param array $flexible_layout The ACF flexible content field data
   * @param array $additional_args Optional. Additional context passed to template parts (e.g., parentColor). Default empty array.
   * @return void
   */
  function render_flexible_layout($flexible_layout, $additional_args = [])
  {
    if (!$flexible_layout || !is_array($flexible_layout)) {
      return;
    }

    foreach ($flexible_layout as $block) {
      // Force remove 'acf_fc_layout' value + '_' from keys in $block array to be able to use it as flexible layout name
      // Example: hero_enabled -> enabled
      $block = array_combine(
        array_map(function ($key) use ($block) {
          return str_replace($block['acf_fc_layout'] . '_', '', $key);
        }, array_keys($block)),
        array_values($block)
      );

      // Merge any parent arguments into the block data (deep merge)
      $block = $this->deep_merge_replace($block, $additional_args);

      // Load the template part for this block
      $this->get_template_part($block['acf_fc_layout'], $block);
    }
  }
}


/**
 * ACF Utility functions
 */

function prefix_fields_keys($keyPrefix, $data, $conditionalLogic = false)
{
  return array_map(function ($item) use ($keyPrefix, $conditionalLogic) {
    $item['key'] = $keyPrefix . $item['key'];
    if ($conditionalLogic && isset($item['conditional_logic'])) {
      $logics = [];
      if (isset($item['conditional_logic']) && is_array($item['conditional_logic'])) {
        foreach ($item['conditional_logic'] as $logic) {
          $conditions = [];
          foreach ($logic as $condition) {
            $conditions[] = [
              'field' => $keyPrefix . $condition['field'],
              'operator' => $condition['operator'],
              'value' => $condition['value'],
            ];
          }
          $logics[] = $conditions;
        }
      }
      $item['conditional_logic'] = $logics;
    }
    return $item;
  }, $data);
}

require_once 'misc.php';
