<?php

if (! defined('ABSPATH')) {
  exit;
}

if (! defined('ACFMS_VERSION')) {
  define('ACFMS_VERSION', '0.3.3-beta');
}

if (! defined('ACFMS_DIR')) {
  define('ACFMS_DIR', trailingslashit(__DIR__));
}

if (! defined('ACFMS_URL')) {
  $dir_path = ACFMS_DIR;
  $stylesheet_dir = trailingslashit(get_stylesheet_directory());
  $stylesheet_uri = trailingslashit(get_stylesheet_directory_uri());
  if (strpos($dir_path, $stylesheet_dir) === 0) {
    $relative = ltrim(str_replace($stylesheet_dir, '', $dir_path), '/');
    define('ACFMS_URL', $stylesheet_uri . $relative);
  } else {
    define('ACFMS_URL', $stylesheet_uri);
  }
}

require_once ACFMS_DIR . 'fields/class-acf-field-material-symbol.php';

function acfms_register_field($version = 0)
{
  $field = new ACFMS_Field_Material_Symbol();
  $field->initialize();
}

add_action('acf/include_field_types', 'acfms_register_field');
add_action('acf/register_fields', 'acfms_register_field');
