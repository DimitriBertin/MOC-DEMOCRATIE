<?php

/**
 * ACF Fields for Separator
 */

global $adwp, $adui_tokens;

// Collect color variant names, and style names
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$colorNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['separator'] ?? []);

// Build an array of choices for the color and style fields
$colorChoices = [];
foreach ($colorNames as $colorName) {
  $colorChoices[$colorName] = toTitleCase($colorName);
}

$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$gapNames = array_keys($adui_tokens['responsiveSizing'][$responsiveSizingModes[0]]['gap'] ?? []);

// Create dynamic mapping from numeric slider values to gap names based on token order
$gapNamesArray = array_values($gapNames); // Convert to numeric-indexed array
$maxGapIndex = count($gapNamesArray) - 1;

// Find default indices for 'sm' and 'lg' if they exist
$defaultGapXIndex = array_search('lg', $gapNamesArray);
$defaultGapYIndex = array_search('xl', $gapNamesArray);
// Fallback to middle values if sm/lg don't exist
if ($defaultGapXIndex === false) $defaultGapXIndex = min(2, $maxGapIndex);
if ($defaultGapYIndex === false) $defaultGapYIndex = min(4, $maxGapIndex);

// Build an array of choices for the color and style fields (keeping for compatibility)
$gapChoices = [];
foreach ($gapNames as $gapName) {
  $gapChoices[$gapName] = $gapName;
}

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path d="M19 13H5v-2h14v2z" fill="currentColor" /></svg>';

$separatorSettingsFields = [
  [
    'key' => 'field-separator-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
  [
    'key' => 'field-separator-color',
    'label' => 'Color',
    'name' => 'color',
    'type' => 'radio',
    'required' => 1,
    'choices' => $colorChoices,
    'layout' => 'horizontal',
    'return_format' => 'value',
  ],
  [
    'key' => 'field-separator-setting-margin-y',
    'label' => 'Vertical Margin [Y axis]',
    'name' => 'marginY',
    'type' => 'range',
    'required' => 1,
    'min' => 0,
    'max' => $maxGapIndex,
    'step' => 1,
    'default_value' => $defaultGapYIndex,
    'wrapper' => [
      'width' => '50%',
    ]
  ],
];

// From nested version of the layout, remove the wrapper width settings
$separatorNestedSettingsFields = $separatorSettingsFields;
unset($separatorNestedSettingsFields[0]);

add_action(
  'acf/include_fields',
  static function () use ($separatorSettingsFields, $separatorNestedSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-separator-settings',
      'title' => 'Separator settings',
      'fields' => $separatorSettingsFields,
    ]);

    acf_add_local_field_group([
      'key' => 'field-group-separator-nested-settings',
      'title' => 'Separator Nested Settings',
      'fields' => $separatorNestedSettingsFields,
    ]);
  },
  45
);


$separatorFields = [];

$separatorLayout = [
  'key' => 'layout-separator',
  'label' => $icon . ' Separator',
  'name' => '_separator',
  'display' => 'block',
  'sub_fields' => $separatorFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-separator-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutSeparator', $separatorLayout, 45);

// Nested layout variant (no full-width setting)
$nestedSeparatorLayout = [
  'key' => 'layout-nestedSeparator',
  'label' => $icon . ' Separator',
  'name' => '_separator',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $separatorFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-separator-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedSeparator', $nestedSeparatorLayout, 45);

// Deep layout variant (also no full-width setting)
$deepSeparatorLayout = [
  'key' => 'layout-deepSeparator',
  'label' => $icon . ' Separator',
  'name' => '_separator',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $separatorFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-separator-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_deep_layout('layoutDeepSeparator', $deepSeparatorLayout, 45);

$adwp->register_layout('separator', $deepSeparatorLayout);
