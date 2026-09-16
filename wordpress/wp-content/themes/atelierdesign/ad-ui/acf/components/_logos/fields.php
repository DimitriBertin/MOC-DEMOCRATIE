<?php

/**
 * ACF Fields for Logos
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="16" viewBox="0 0 24 24" width="16"><g><g><rect fill="currentColor" height="8" width="8" x="3" y="3"/><rect fill="currentColor" height="8" width="8" x="3" y="13"/><rect fill="currentColor" height="8" width="8" x="13" y="3"/><rect fill="currentColor" height="8" width="8" x="13" y="13"/></g></g></svg>';

$logosSettingsFields = [
  [
    'key' => 'field-logos-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
  [
    'key' => 'field-logos-setting-alignment',
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
];

// From nested version of the layout, remove the wrapper width settings
$logosNestedSettingsFields = $logosSettingsFields;
unset($logosNestedSettingsFields[0]);

add_action(
  'acf/include_fields',
  static function () use ($logosSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-logos-settings',
      'title' => 'Logos Settings',
      'fields' => $logosSettingsFields,
    ]);
  },
  50
);

add_action(
  'acf/include_fields',
  static function () use ($logosNestedSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-logos-nested-settings',
      'title' => 'Logos Nested Settings',
      'fields' => $logosNestedSettingsFields,
    ]);
  },
  15
);

$logosFields = [
  [
    'key' => 'field-logos-repeater',
    'label' => '',
    'name' => '_logos_logos',
    'type' => 'repeater',
    'layout' => 'table',
    'button_label' => 'Add Logo',
    'wrapper' => [
      'class' => 'logos',
    ],
    'min' => 1,
    'max' => '',
    'sub_fields' => [
      [
        'key' => 'field-logos-image',
        'label' => 'Image',
        'name' => 'image',
        'type' => 'image',
        'required' => true,
        'preview_size' => 'thumbnail',
        'library' => 'all',
        'mime_types' => 'jpg,jpeg,png,svg,webp',
      ],
      [
        'key' => 'field-logos-link',
        'label' => 'Link (optional)',
        'name' => 'link',
        'type' => 'link',
      ],
      [
        'key' => 'field-logos-scale',
        'label' => 'Control scale',
        'name' => 'scale',
        'type' => 'range',
        'required' => 1,
        'conditional_logic' => 0,
        'default_value' => 0,
        'min' => -6,
        'max' => 6,
        'step' => 1,
      ],
    ],
  ]
];

$logosLayout = [
  'key' => 'layout-logos',
  'label' => $icon . ' Logos',
  'name' => '_logos',
  'display' => 'block',
  'sub_fields' => $logosFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-logos-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutLogos', $logosLayout, 50);

// Nested layout variant (no full-width setting)
$nestedLogosLayout = [
  'key' => 'layout-nestedLogos',
  'label' => $icon . ' Logos',
  'name' => '_logos',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $logosFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-logos-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedLogos', $nestedLogosLayout, 15);

$adwp->register_layout('logos', $nestedLogosLayout);
