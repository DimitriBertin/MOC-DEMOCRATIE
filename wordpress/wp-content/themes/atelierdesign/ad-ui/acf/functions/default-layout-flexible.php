<?php

/**
 * Ensure `_layout` flexible field starts with two `_wrapper` layouts by default.
 */

add_filter('acf/prepare_field/key=field-layout-flexible', function ($field) {
  if (!is_admin()) {
    return $field;
  }

  $defaultWrapper = [
    'acf_fc_layout' => '_wrapper',
    '_wrapper_content' => [],
  ];

  if (!is_array($field['value']) || empty($field['value'])) {
    $field['value'] = [
      $defaultWrapper,
      // $defaultWrapper,
    ];
  }

  return $field;
});

/**
 * Ensure `_nestedLayout` flexible field starts with two `_nestedWrapper` layouts by default.
 */

add_filter('acf/prepare_field/key=field-nestedLayout-flexible', function ($field) {
  if (!is_admin()) {
    return $field;
  }

  $defaultWrapper = [
    'acf_fc_layout' => '_nestedWrapper',
    '_nestedWrapper_content' => [],
  ];

  if (!is_array($field['value']) || empty($field['value'])) {
    $field['value'] = [
      $defaultWrapper,
      // $defaultWrapper,
    ];
  }

  return $field;
});
