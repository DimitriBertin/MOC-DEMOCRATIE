<?php
if (!function_exists('ad_custom_tinymce_is_supported_screen')) {
  function ad_custom_tinymce_is_supported_screen($screen)
  {
    if (!$screen) {
      return false;
    }

    $base = isset($screen->base) ? $screen->base : '';
    $id = isset($screen->id) ? $screen->id : '';

    $isSupported = $base === 'post';

    if (!$isSupported) {
      if ($base && strpos($base, 'acf-options') !== false) {
        $isSupported = true;
      } elseif ($id && strpos($id, 'acf-options') !== false) {
        $isSupported = true;
      }
    }

    /**
     * Allow third parties to extend the list of screens where the custom TinyMCE
     * plugins are enabled.
     *
     * @param bool   $isSupported Whether the current screen should receive the plugins.
     * @param object $screen      The current WP_Screen instance.
     */
    return (bool) apply_filters('ad/custom_tinymce/is_supported_screen', $isSupported, $screen);
  }
}

function balance_text_plugin($plugin_array)
{
  $plugin_array['balance_text'] =
    get_template_directory_uri() . '/ad-ui/acf/functions/tinymce-balance-plugin/plugin.js';
  return $plugin_array;
}
function balance_text_buttons($buttons)
{
  // Find position of alignment buttons
  $pos = array_search('aligncenter', $buttons);

  // If found, insert our button after it
  if ($pos !== false) {
    array_splice($buttons, $pos + 1, 0, 'balance-text');
  } else {
    // Fallback: add it after basic formatting
    $basic_formatting = array('bold', 'italic', 'underline');
    foreach ($basic_formatting as $format) {
      $format_pos = array_search($format, $buttons);
      if ($format_pos !== false) {
        array_splice($buttons, $format_pos + 1, 0, 'balance-text');
        return $buttons;
      }
    }

    // Last resort: add to beginning
    array_unshift($buttons, 'balance-text');
  }

  return $buttons;
}
function balance_text_init()
{
  if (!function_exists('get_current_screen')) {
    return;
  }

  $screen = get_current_screen();

  if (!ad_custom_tinymce_is_supported_screen($screen)) {
    return;
  }

  add_filter('mce_external_plugins', 'balance_text_plugin');
  add_filter('mce_buttons', 'balance_text_buttons');
}
add_action('current_screen', 'balance_text_init');
