<?php

/**
 * Ensure `_columns` flexible field starts with two `_column` layouts by default.
 */

add_filter('acf/prepare_field/key=field-columns-flexible', function ($field) {
  if (!is_admin()) {
    return $field;
  }

  $defaultColumn = [
    'acf_fc_layout' => '_column',
    '_column_content' => [],
  ];

  if (!is_array($field['value']) || empty($field['value'])) {
    $field['value'] = [
      $defaultColumn,
      // $defaultColumn,
    ];
  }

  return $field;
});

/**
 * Ensure `_nestedColumns` flexible field starts with two `_nestedColumn` layouts by default.
 */

add_filter('acf/prepare_field/key=field-nestedColumns-flexible', function ($field) {
  if (!is_admin()) {
    return $field;
  }

  $defaultColumn = [
    'acf_fc_layout' => '_nestedColumn',
    '_nestedColumn_content' => [],
  ];

  if (!is_array($field['value']) || empty($field['value'])) {
    $field['value'] = [
      $defaultColumn,
      // $defaultColumn,
    ];
  }

  return $field;
});
