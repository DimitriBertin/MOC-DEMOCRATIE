<?php

/**
 * ACF Fields for Nested Advanced Layout
 */

global $adwp, $adui_tokens;

// Collect color variant names, and style names
$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$gapNames = array_keys($adui_tokens['responsiveSizing'][$responsiveSizingModes[0]]['gap'] ?? []);

// Create dynamic mapping from numeric slider values to gap names based on token order
$gapNamesArray = array_values($gapNames); // Convert to numeric-indexed array
$maxGapIndex = count($gapNamesArray) - 1;

// Find default indices for 'sm' and 'lg' if they exist
$defaultGapXIndex = array_search('sm', $gapNamesArray);
$defaultGapYIndex = array_search('lg', $gapNamesArray);
// Fallback to middle values if sm/lg don't exist
if ($defaultGapXIndex === false) $defaultGapXIndex = min(2, $maxGapIndex);
if ($defaultGapYIndex === false) $defaultGapYIndex = min(4, $maxGapIndex);

// Build an array of choices for the color and style fields (keeping for compatibility)
$gapChoices = [];
foreach ($gapNames as $gapName) {
  $gapChoices[$gapName] = $gapName;
}

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M9 11H3V3h6v8Zm4 10H3v-8h10v8Zm8-10H11V3h10v8Zm0 10h-6v-8h6v8Z"/></svg>';

$nestedLayoutSettingsFields = [
  [
    'key' => 'field-nestedLayout-setting-horizontal-alignment',
    'label' => 'Horizontal alignment [X axis]',
    'name' => 'justify',
    'aria-label' => '',
    'type' => 'acfe_image_selector',
    'instructions' => '',
    'required' => 1,
    'choices' => [
      'start' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-justify-start.svg'),
      'center' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-justify-center.svg'),
      'end' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-justify-end.svg'),
      'between' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-justify-between.svg'),
    ],
    'default_value' => 'start',
    'image_size' => 'thumbnail',
    'width' => '32',
    'height' => '32',
    'border' => 1,
    'return_format' => 'value',
    'allow_null' => 0,
    'multiple' => 0,
    'layout' => 'horizontal',
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-nestedLayout-setting-vertical-alignment',
    'label' => 'Vertical alignment [Y axis]',
    'name' => 'align',
    'aria-label' => '',
    'type' => 'acfe_image_selector',
    'instructions' => '',
    'required' => 1,
    'choices' => [
      'stretch' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-stretch.svg'),
      'start' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-start.svg'),
      'center' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-center.svg'),
      'end' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-end.svg'),
    ],
    'default_value' => 'start',
    'image_size' => 'thumbnail',
    'width' => '32',
    'height' => '32',
    'border' => 1,
    'return_format' => 'value',
    'allow_null' => 0,
    'multiple' => 0,
    'layout' => 'horizontal',
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-nestedLayout-setting-gap-x',
    'label' => 'Horizontal Gap [X axis]',
    'name' => 'gapX',
    'type' => 'range',
    'required' => 1,
    'min' => 0,
    'max' => $maxGapIndex,
    'step' => 1,
    'default_value' => $defaultGapXIndex,
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-nestedLayout-setting-gap-y',
    'label' => 'Vertical Gap [Y axis]',
    'name' => 'gapY',
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
  [
    'key' => 'field-nestedLayout-setting-divide-x',
    'label' => 'Horizontal Divider [X axis]',
    'name' => 'divideX',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
    'layout' => 'horizontal',
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-nestedLayout-setting-divide-y',
    'label' => 'Vertical Divider [Y axis]',
    'name' => 'divideY',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-nestedLayout-setting-full-width-divide-y-spacer',
    'label' => '',
    'name' => 'fullWidthDivideYSpacer',
    'type' => 'message',
    'message' => '&nbsp;',
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-nestedLayout-setting-divide-y',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-nestedLayout-setting-full-width-divide-y',
    'label' => 'Full Width Vertical Divider [Y axis]',
    'name' => 'fullWidthDivideY',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-nestedLayout-setting-divide-y',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
];

add_action(
  'acf/include_fields',
  static function () use ($nestedLayoutSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-nestedLayout-settings',
      'title' => 'Nested Advanced Layout settings',
      'fields' => $nestedLayoutSettingsFields,
    ]);
  },
  70
);

$nestedLayoutFields = [
  [
    'key' => 'field-nestedLayout-flexible',
    'label' => '',
    'name' => '_nestedLayout',
    'type' => 'flexible_content',
    'acfe_flexible_async' => [
      0 => 'layout',
    ],
    'acfe_flexible_add_actions' => [
      0 => 'toggle',
      1 => 'copy',
    ],
    'layouts' => [
      'nestedWrapperLayout' => $nestedWrapperLayout,
    ],
    'button_label' => 'Add layout',
    'acfe_flexible_layouts_settings' => 1,
  ],
];

$nestedLayoutLayout = [
  'key' => 'layout-nestedLayout',
  'label' => $icon . ' Advanced Layout',
  'name' => '_nestedLayout',
  'display' => 'block',
  'sub_fields' => $nestedLayoutFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-nestedLayout-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedLayout', $nestedLayoutLayout, 70);
