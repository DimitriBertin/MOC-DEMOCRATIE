<?php

/**
 * ACF Fields for Feature Column
 */


$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="16" viewBox="0 0 20 20" width="16"><path d="M2,6.5h3v7H2V6.5z M15,6.5v7h3v-7H15z M6,5h8v10H6V5z"/></svg>';

global $adwp, $adui_tokens;

// Collect color system mode names

// Build a palette mapping hex => "{mode}/main" and a sensible default

$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$gapNames = array_keys($adui_tokens['responsiveSizing'][$responsiveSizingModes[0]]['gap'] ?? []);

// Create dynamic mapping from numeric slider values to gap names based on token order
$gapNamesArray = array_values($gapNames); // Convert to numeric-indexed array
$maxGapIndex = count($gapNamesArray) - 1;

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


$columnsThirdColumnSettingsFields = [
  [
    'key' => 'field-columnsThirdColumn-setting-size',
    'label' => 'Size',
    'name' => 'size',
    'type' => 'radio',
    'layout' => 'horizontal',
    'choices' => [
      '1' => 'Full',
      '1/3' => '1/3',
      '1/2' => '1/2',
      '2/3' => '2/3',
    ],
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-columnsThirdColumn-setting-align',
    'label' => 'Self alignment [Y axis]',
    'name' => 'align',
    'aria-label' => '',
    'type' => 'acfe_image_selector',
    'instructions' => '',
    'required' => 1,
    'choices' => [
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
    'conditional_logic' => [
      [
        [
          'field' => 'field-columnsThirdColumn-setting-size',
          'operator' => '!=',
          'value' => "1",
        ],
      ],
    ],
  ],
  [
    'key' => 'field-columnsThirdColumn-setting-gap-y',
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
  ]
];


acf_add_local_field_group([
  'key' => 'field-group-columnsThirdColumn-settings',
  'title' => 'Columns Third Column settings',
  'fields' => $columnsThirdColumnSettingsFields,
]);


$columnsThirdColumnFields = [
  [
    'key' =>  'field-columns-third-2_3-content',
    'name' => '_columnsThirdColumn_content',
    'type' => 'flexible_content',
    'acfe_flexible_async' => [
      0 => 'layout',
    ],
    'acfe_flexible_add_actions' => [
      0 => 'toggle',
      1 => 'copy',
    ],
    'layouts' => [
      ...$adwp->get_layout('badge'),
      ...$adwp->get_layout('wysiwyg'),
      ...$adwp->get_layout('image'),
      ...$adwp->get_layout('video'),
      ...$adwp->get_layout('separator'),
      ...$adwp->get_layout('button'),
      ...$adwp->get_layout('keyNumbers'),
      ...$adwp->get_layout('quote'),
      ...$adwp->get_layout('icon'),
      ...$adwp->get_layout('logos'),
      ...$adwp->get_layout('accordion'),
    ],
    'min' => 1,
    'max' => '',
    'button_label' => 'Add item',
    'acfe_flexible_layouts_settings' => 1,
  ],
];

$columnsThirdColumnLayout = [
  'key' => 'layout-columnsThirdColumn',
  'label' => $icon . ' Layout',
  'name' => '_columns-thirdColumn',
  'display' => 'block',
  'sub_fields' => $columnsThirdColumnFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-columnsThirdColumn-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];
