<?php

/**
 * Ensure `feature` flexible field starts with two `_featureColumn` layouts by default.
 */

add_filter('acf/prepare_field/key=field-feature-flexible', function ($field) {
  if (!is_admin()) {
    return $field;
  }

  $defaultFeatureColumn = [
    'acf_fc_layout' => '_featureColumn',
    '_featureColumn_content' => [],
  ];

  if (!is_array($field['value']) || empty($field['value'])) {
    $field['value'] = [
      $defaultFeatureColumn,
    ];
  }

  return $field;
});
