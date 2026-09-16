<?php

/**
 * ACF Fields for Badge
 */

global $adwp, $adui_tokens;

// Collect color variant names, and style names
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$styleNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['badge'] ?? []);

// Build an array of choices for the color and style fields
$styleChoices = [];
foreach ($styleNames as $styleName) {
  $styleChoices[$styleName] = toTitleCase($styleName);
}

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path d="M4 18.99h12.04L21 12l-4.97-7H4l5 7-5 6.99z" fill="currentColor" /></svg>';

$badgeSettingsFields = [
  [
    'key' => 'field-badge-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
  [
    'key' => 'field-badge-alignment',
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
$badgeNestedSettingsFields = $badgeSettingsFields;
unset($badgeNestedSettingsFields[0]);

add_action(
  'acf/include_fields',
  static function () use ($badgeSettingsFields, $badgeNestedSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-badge-settings',
      'title' => 'Badge settings',
      'fields' => $badgeSettingsFields,
    ]);

    acf_add_local_field_group([
      'key' => 'field-group-badge-nested-settings',
      'title' => 'Badge Nested Settings',
      'fields' => $badgeNestedSettingsFields,
    ]);
  },
  20
);

$badgeFields = [
  [
    'key' => 'field-badge-text',
    'label' => 'Text',
    'name' => '_badge_text',
    'type' => 'text',
    'required' => 1,
  ],
  [
    'key' => 'field-badge-style',
    'label' => 'Style',
    'name' => '_badge_style',
    'type' => 'radio',
    'required' => 1,
    'choices' => $styleChoices,
    'layout' => 'horizontal',
    'return_format' => 'value',
  ],
];

$badgeLayout = [
  'key' => 'layout-badge',
  'label' => $icon . ' Badge',
  'name' => '_badge',
  'display' => 'block',
  'sub_fields' => $badgeFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-badge-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutBadge', $badgeLayout, 20);

// Nested layout variant (no full-width setting)
$nestedBadgeLayout = [
  'key' => 'layout-nestedBadge',
  'label' => $icon . ' Badge',
  'name' => '_badge',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $badgeFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-badge-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedBadge', $nestedBadgeLayout, 20);

// Deep layout variant (also no full-width setting)
$deepBadgeLayout = [
  'key' => 'layout-deepBadge',
  'label' => $icon . ' Badge',
  'name' => '_badge',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $badgeFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-badge-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_deep_layout('layoutDeepBadge', $deepBadgeLayout, 20);

// Extra deep nested settings, where we don't even include settings
$extraDeepBadgeLayout = [
  'key' => 'layout-extraDeepBadge',
  'label' => $icon . ' Badge',
  'name' => '_badge',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('extra-deep-', $badgeFields),
];

$adwp->register_layout('badge', $extraDeepBadgeLayout);
