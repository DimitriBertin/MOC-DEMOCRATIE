<?php

/**
 * ACF Fields for Key Numbers
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path d="M10 8H8v4H4v2h4v4h2v-4h4v-2h-4V8zm4.5-1.92V7.9l2.5-.5V18h2V5l-4.5 1.08z" fill="currentColor" /></svg>';

$keyNumbersSettingsFields = [
  [
    'key' => 'field-keyNumbers-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
  [
    'key' => 'field-keyNumbers-alignment',
    'label' => 'Alignment',
    'name' => 'alignment',
    'aria-label' => '',
    'type' => 'acfe_image_selector',
    'instructions' => '',
    'required' => 1,
    'choices' => [
      'left' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/icon-align-left.svg'),
      'center' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/icon-align-center.svg'),
      'right' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/icon-align-right.svg'),
    ],
    'default_value' => 'center',
    'image_size' => 'thumbnail',
    'width' => '32',
    'height' => '32',
    'border' => 1,
    'return_format' => 'value',
    'allow_null' => 0,
    'multiple' => 0,
    'layout' => 'horizontal',
  ],
];

// From nested version of the layout, remove the wrapper width settings
$keyNumbersNestedSettingsFields = $keyNumbersSettingsFields;
unset($keyNumbersNestedSettingsFields[0]);

add_action(
  'acf/include_fields',
  static function () use ($keyNumbersSettingsFields, $keyNumbersNestedSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-keyNumbers-settings',
      'title' => 'Key Numbers Settings',
      'fields' => $keyNumbersSettingsFields,
    ]);

    acf_add_local_field_group([
      'key' => 'field-group-keyNumbers-nested-settings',
      'title' => 'Key Numbers Nested Settings',
      'fields' => $keyNumbersNestedSettingsFields,
    ]);
  },
  10
);

$keyNumbersFields = [
  [
    'key' => 'field-keyNumbers-prefix',
    'label' => 'Top label',
    'name' => '_keyNumbers_prefix',
    'type' => 'text',
    'wrapper' => [
      'width' => '33%',
    ],
  ],
  [
    'key' => 'field-keyNumbers-number',
    'label' => 'Number',
    'name' => '_keyNumbers_number',
    'type' => 'text',
    'required' => 1,
    'wrapper' => [
      'width' => '33%',
    ],
  ],
  [
    'key' => 'field-keyNumbers-suffix',
    'label' => 'Bottom label',
    'name' => '_keyNumbers_suffix',
    'type' => 'text',
    'wrapper' => [
      'width' => '33%',
    ],
  ]
];

$keyNumbersLayout = [
  'key' => 'layout-keyNumbers',
  'label' => $icon . ' Key Numbers',
  'name' => '_keyNumbers',
  'display' => 'block',
  'sub_fields' => $keyNumbersFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-keyNumbers-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutKeyNumbers', $keyNumbersLayout, 10);

// Nested layout variant (no full-width setting)
$nestedKeyNumbersLayout = [
  'key' => 'layout-nestedKeyNumbers',
  'label' => $icon . ' Key Numbers',
  'name' => '_keyNumbers',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $keyNumbersFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-keyNumbers-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedKeyNumbers', $nestedKeyNumbersLayout, 10);

// Deep layout variant (also no full-width setting)
$deepKeyNumbersLayout = [
  'key' => 'layout-deepKeyNumbers',
  'label' => $icon . ' Key Numbers',
  'name' => '_keyNumbers',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $keyNumbersFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-keyNumbers-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_deep_layout('layoutDeepKeyNumbers', $deepKeyNumbersLayout, 10);

$adwp->register_layout('keyNumbers', $deepKeyNumbersLayout);
