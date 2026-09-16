<?php

/**
 * ACF Fields for Big Icon
 */

global $adwp, $adui_tokens;

// Collect color variant names, and style names
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$colorNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['icon'] ?? []);

// Build an array of choices for the color and style fields
$colorChoices = [];
foreach ($colorNames as $colorName) {
  $colorChoices[$colorName] = toTitleCase($colorName);
}

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path d="M21 3H3v18h18V3zM5.5 7.5h2v-2H9v2h2V9H9v2H7.5V9h-2V7.5zM19 19H5L19 5v14zm-2-2v-1.5h-5V17h5z" fill="currentColor" /></svg>';

$iconSettingsFields = [
  [
    'key' => 'field-icon-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
];

// // From nested version of the layout, remove the wrapper width settings
// $iconNestedSettingsFields = $iconSettingsFields;
// unset($iconNestedSettingsFields[0]);

add_action(
  'acf/include_fields',
  static function () use ($iconSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-icon-settings',
      'title' => 'Icon settings',
      'fields' => $iconSettingsFields,
    ]);
  },
  30
);

// acf_add_local_field_group([
//   'key' => 'field-group-icon-nested-settings',
//   'title' => 'Icon Nested Settings',
//   'fields' => $iconNestedSettingsFields,
// ]);


$iconFields = [
  [
    'key' => 'field-icon-alignment',
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
    'default_value' => 'left',
    'image_size' => 'thumbnail',
    'width' => '32',
    'height' => '32',
    'border' => 1,
    'return_format' => 'value',
    'allow_null' => 0,
    'multiple' => 0,
    'layout' => 'horizontal',
    'wrapper' => [
      'width' => '33%',
    ],
  ],
  [
    'key' => 'field-icon-color',
    'label' => 'Color',
    'name' => 'color',
    'type' => 'radio',
    'required' => 1,
    'choices' => $colorChoices,
    'layout' => 'horizontal',
    'return_format' => 'value',
    'wrapper' => [
      'width' => '33%',
    ],
  ],
  [
    'key' => 'field-icon-hasRound',
    'label' => 'Has background?',
    'name' => '_icon_hasBackground',
    'type' => 'true_false',
    'message' => '',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'wrapper' => [
      'width' => '33%',
    ],
  ],
  [
    'key' => 'field-icon-icon',
    'label' => 'Select an icon',
    'name' => '_icon_icon',
    'type' => 'material_symbol',
    'required' => 1,
    'allow_null' => 0,
    'return_format' => 'array',
    'families' => [
      'outlined',
      'rounded',
      'sharp',
    ],
    'acf_material_symbol_appearance' => [
      'family' => 'outlined',
      'wght' => 400,
      'grad' => 0,
      'opsz' => 24,
      'fill' => 0,
    ],
  ],
];

$iconLayout = [
  'key' => 'layout-icon',
  'label' => $icon . ' Icon',
  'name' => '_icon',
  'display' => 'block',
  'sub_fields' => $iconFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-icon-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutIcon', $iconLayout, 30);

// Nested layout variant (no full-width setting)
$nestedIconLayout = [
  'key' => 'layout-nestedIcon',
  'label' => $icon . ' Icon',
  'name' => '_icon',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $iconFields),
  // 'acfe_flexible_settings' => [
  //   0 => 'field-group-icon-nested-settings',
  // ],
  // 'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedIcon', $nestedIconLayout, 30);

// Deep layout variant (also no full-width setting)
$deepIconLayout = [
  'key' => 'layout-deepIcon',
  'label' => $icon . ' Icon',
  'name' => '_icon',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $iconFields),
  // 'acfe_flexible_settings' => [
  //   0 => 'field-group-icon-nested-settings',
  // ],
  // 'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedIcon', $nestedIconLayout, 30);

// Extra deep nested settings, where we don't even include settings
$extraDeepIconLayout = [
  'key' => 'layout-extraDeepIcon',
  'label' => $icon . ' Icon',
  'name' => '_icon',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('extra-deep-', $iconFields),
  // 'acfe_flexible_settings' => [
  //   0 => 'field-group-icon-nested-settings',
  // ],
  // 'acfe_flexible_settings_size' => 'medium',
];

$adwp->register_layout('icon', $extraDeepIconLayout);
