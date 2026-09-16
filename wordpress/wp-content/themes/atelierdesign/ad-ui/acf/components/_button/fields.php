<?php

/**
 * ACF Fields for Button
 */

global $adwp, $adui_tokens;

// Collect color variant names, and style names
$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$styleNames = array_keys($adui_tokens['responsiveSizing'][$responsiveSizingModes[0]]['button'] ?? []);
$colorNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['button'] ?? []);

// Build an array of choices for the color and style fields
$colorChoices = [];
$styleChoices = [];
foreach ($colorNames as $colorName) {
  $colorChoices[$colorName] = toTitleCase($colorName);
}
foreach ($styleNames as $styleName) {
  $styleChoices[$styleName] = toTitleCase($styleName);
}

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="16" viewBox="0 0 20 20" width="16"><path d="M17.09,18.5l-3.47-3.47L12.5,18L10,10l8,2.5l-2.97,1.11l3.47,3.47L17.09,18.5z M10,3.5c-3.58,0-6.5,2.92-6.5,6.5 s2.92,6.5,6.5,6.5c0.15,0,0.3-0.01,0.45-0.02l0.46,1.46C10.61,17.98,10.31,18,10,18c-4.42,0-8-3.58-8-8s3.58-8,8-8l0,0 c4.42,0,8,3.58,8,8c0,0.31-0.02,0.61-0.05,0.91l-1.46-0.46c0.01-0.15,0.02-0.3,0.02-0.45C16.5,6.42,13.58,3.5,10,3.5 M10,6.5 c-1.93,0-3.5,1.57-3.5,3.5c0,1.76,1.31,3.23,3.01,3.47L10,15c0,0-0.01,0-0.01,0C7.23,15,5,12.76,5,10c0-2.76,2.24-5,5-5l0,0 c2.76,0,5,2.23,5,4.99c0,0,0,0.01,0,0.01l-1.53-0.49C13.23,7.81,11.76,6.5,10,6.5" fill="currentColor" /></svg>';

$buttonSettingsFields = [
  [
    'key' => 'field-button-setting-isFullWidth',
    'label' => 'Full width wrapper',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
  [
    'key' => 'field-button-setting-alignment',
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
  ],
  [
    'key' => 'field-button-color',
    'label' => 'Color',
    'name' => 'color',
    'type' => 'radio',
    'required' => 1,
    'choices' => $colorChoices,
    'layout' => 'horizontal',
    'return_format' => 'value',
  ],
  [
    'key' => 'field-button-style',
    'label' => 'Style',
    'name' => 'style',
    'type' => 'radio',
    'required' => 1,
    'choices' => $styleChoices,
    'layout' => 'horizontal',
    'return_format' => 'value',
  ],
];

// From nested version of the layout, remove the wrapper width settings
$buttonNestedSettingsFields = $buttonSettingsFields;
unset($buttonNestedSettingsFields[0]);

// Extra deep nested settings, where we remove the alignment as well
$buttonDeepNestedSettingsFields = $buttonNestedSettingsFields;
unset($buttonDeepNestedSettingsFields[1]);

add_action(
  'acf/include_fields',
  static function () use ($buttonSettingsFields, $buttonNestedSettingsFields, $buttonDeepNestedSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-button-settings',
      'title' => 'Button Settings',
      'fields' => $buttonSettingsFields,
    ]);

    acf_add_local_field_group([
      'key' => 'field-group-button-nested-settings',
      'title' => 'Button Nested Settings',
      'fields' => $buttonNestedSettingsFields,
    ]);

    acf_add_local_field_group([
      'key' => 'field-group-button-extra-deep-nested-settings',
      'title' => 'Button Extra Deep Nested Settings',
      'fields' => $buttonDeepNestedSettingsFields,
    ]);
  },
  15
);

$buttonFields = [
  [
    'key' => 'field-button-content',
    'label' => 'Select a link & enter button text',
    'name' => '_button_link',
    'type' => 'link',
    'required' => 1,
  ],
];

$buttonLayout = [
  'key' => 'layout-button',
  'label' => $icon . ' Button',
  'name' => '_button',
  'display' => 'block',
  'sub_fields' => $buttonFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-button-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutButton', $buttonLayout, 15);

// Nested layout variant (no full-width setting)
$nestedButtonLayout = [
  'key' => 'layout-nestedButton',
  'label' => $icon . ' Button',
  'name' => '_button',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $buttonFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-button-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedButton', $nestedButtonLayout, 15);

// Deep layout variant (also no full-width setting)
$deepButtonLayout = [
  'key' => 'layout-deepButton',
  'label' => $icon . ' Button',
  'name' => '_button',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $buttonFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-button-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_deep_layout('layoutDeepButton', $deepButtonLayout, 15);

// Extra deep nested settings, where we remove the alignment as well
$extraDeepButtonLayout = [
  'key' => 'layout-extraDeepButton',
  'label' => $icon . ' Button',
  'name' => '_button',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('extra-deep-', $buttonFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-button-extra-deep-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->register_layout('button', $extraDeepButtonLayout);
