<?php

/**
 * ACF Fields for CTA CARDS
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg"><path d="M16 3a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-5-1v12H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm1 0h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-2z"></path></svg>';


global $adwp, $adui_tokens;

// Collect color system mode names
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$layoutNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['layout'] ?? []);

// Build a palette mapping hex => "{mode}/main" and a sensible default
$colors = [];
foreach ($colorSystemModes as $mode) {
  foreach ($layoutNames as $layout) {
    $color = getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layout, $mode);
    $colors[$color] = $mode . '/' . $layout;
  }
}

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

$settingsFields = [
  [
    'key' => 'field-columns-third-setting-vertical-alignment',
    'label' => 'Vertical alignment [Y axis]',
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
  ],
  [
    'key' => 'field-columns-third-setting-gap-x',
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
    'key' => 'field-columns-third-setting-reversed',
    'label' => 'Reversed',
    'name' => 'reversed',
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
    'key' => 'field-columns-third-setting-divide-x',
    'label' => 'Horizontal Divider [X axis]',
    'name' => 'divideX',
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
    'key' => 'field-columns-third-setting-tab-column_1-3',
    'label' => 'Column 1/3',
    'type' => 'tab',
    'placement' => 'top',
  ],
  [
    'key' => 'field-columns-third-setting-sticky',
    'label' => 'Sticky',
    'name' => 'isSticky',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,

  ],
  [
    'key' => 'field-columns-third-setting-column_1-3-gap-y',
    'label' => 'Vertical Gap [Y axis]',
    'name' => 'gapY_1-3',
    'type' => 'range',
    'required' => 1,
    'min' => 0,
    'max' => $maxGapIndex,
    'step' => 1,
    'default_value' => $defaultGapYIndex,

  ],
  [
    'key' => 'field-columns-third-setting-tab-column_2-3',
    'label' => 'Column 2/3',
    'type' => 'tab',
    'placement' => 'top',
  ],
  [
    'key' => 'field-columns-third-setting-column_2-3-gap-y',
    'label' => 'Vertical Gap [Y axis]',
    'name' => 'gapY_2-3',
    'type' => 'range',
    'required' => 1,
    'min' => 0,
    'max' => $maxGapIndex,
    'step' => 1,
    'default_value' => $defaultGapYIndex,

  ],
  [
    'key' => 'field-columns-third-setting-column_2-3-gap-x',
    'label' => 'Horizontal Gap [X axis]',
    'name' => 'gapX_2-3',
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
    'key' => 'field-columns-third-setting-column_2-3-horizontal-alignment',
    'label' => 'Horizontal alignment [X axis]',
    'name' => 'alignX_2-3',
    'aria-label' => '',
    'type' => 'acfe_image_selector',
    'instructions' => '',
    'required' => 1,
    'choices' => [
      'start' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-justify-start.svg'),
      'center' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-justify-center.svg'),
      'end' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-justify-end.svg'),
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
    'key' => 'field-columns-third-setting-column_2-3-divide-x',
    'label' => 'Horizontal Divider [X axis]',
    'name' => 'divideX_2-3',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
    'wrapper' => [
      'width' => '50%',
    ],
  ],

];

acf_add_local_field_group([
  'key' => 'field-group-columns-third-settings',
  'title' => 'Columns third settings',
  'fields' => $settingsFields,
]);

$sliderColumnsThirdFields = [
  [
    'key' => 'field-columns-third-color',
    'label' => '',
    'name' => 'section_color',
    'type' => 'color_picker',
    'required' => 1,
    'default_value' => getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layoutNames[0], $colorSystemModes[0]),
    'enable_opacity' => 0,
    'return_format' => 'label',
    'display' => 'palette',
    'color_picker' => 0,
    'allow_null' => 0,
    'theme_colors' => 0,
    'colors' => $colors,
    'button_label' => 'Select Color',
    'absolute' => false,
    'input' => false,
  ],
  [
    'key' => 'field-columns-third-columns',
    'label' => '',
    'name' => 'columns',
    'type' => 'group',
    'sub_fields' => [
      [
        'key' => 'field-columns-third-columns_tabs-1-3',
        'label' => 'Column 1/3',
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field-columns-third-columns_1_3',
        'label' => '',
        'name' => 'column_1_3',
        'type' => 'flexible_content',
        'layouts' => [
          ...$adwp->get_layout('badge'),
          ...$adwp->get_layout('wysiwyg'),
          ...$adwp->get_layout('image'),
          ...$adwp->get_layout('video'),
          ...$adwp->get_layout('button'),
          ...$adwp->get_layout('separator'),
          ...$adwp->get_layout('keyNumbers'),
          ...$adwp->get_layout('quote'),
          ...$adwp->get_layout('icon'),
          ...$adwp->get_layout('logos'),
          ...$adwp->get_layout('accordion'),

        ],
        'acfe_flexible_add_actions' => [
          0 => 'toggle',
          1 => 'copy',
        ],
        'button_label' => 'Add item',
        'acfe_flexible_layouts_settings' => 1,
        
      ],
    
      [
        'key' => 'field-columns-third-columns_tabs-2-3',
        'label' => 'Column 2/3',
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field-columns-third-columns_2_3',
        'label' => '',
        'name' => 'column_2_3',
        'type' => 'flexible_content',
        'acfe_flexible_async' => [
          0 => 'layout',
        ],
        'acfe_flexible_add_actions' => [
          0 => 'toggle',
          1 => 'copy',
        ],
        'layouts' => [
          'layoutColumnsThirdColumn' => $columnsThirdColumnLayout,
        ],
        'button_label' => 'Add column',
        'acfe_flexible_layouts_settings' => 1,
      ],
    ],
  ],
  

];

$sliderColumnsThirdGroup = [
  'key' => 'field-group-colums-third',
  'title' => 'Columns one third / two third',
  'fields' => $sliderColumnsThirdFields,
];

acf_add_local_field_group($sliderColumnsThirdGroup);

$columnsThirdLayout = [
  'key' => 'layout-columns-third',
  'label' => $icon . ' One third / Two third',
  'name' => 'columns-third',
  'display' => 'block',
  'sub_fields' => [
    [
      
      'key' => 'layout-columns-third-clone',
      'label' => '',
      'name' => '',
      'type' => 'clone',
      'clone' => [
        'field-group-colums-third',
      ]
    ]
    // 
  ],
  'acfe_flexible_settings' => [
    0 => 'field-group-columns-third-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];
$adwp->add_block_layout('layoutColumnsThird', $columnsThirdLayout, 99);