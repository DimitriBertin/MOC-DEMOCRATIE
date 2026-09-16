<?php

/**
 * ACF Fields for Feature
 */

global $adwp, $adui_tokens;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16" fill="currentColor"><path d="M40-200v-560h560v560H40Zm640-320v-240h240v240H680Zm80-80h80v-80h-80v80ZM120-280h400v-400H120v400Zm40-80h320L375-500l-75 100-55-73-85 113Zm520 160v-240h240v240H680Zm80-80h80v-80h-80v80Zm-640 0v-400 400Zm640-320v-80 80Zm0 320v-80 80Z"/></svg>';

global $adui_tokens;

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

// Collect color variant names, and style names
$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$gapNames = array_keys($adui_tokens['responsiveSizing'][$responsiveSizingModes[0]]['gap'] ?? []);

// Create dynamic mapping from numeric slider values to gap names based on token order
$gapNamesArray = array_values($gapNames); // Convert to numeric-indexed array
$maxGapIndex = count($gapNamesArray) - 1;

// Find default indices for 'md' and 'md' if they exist
$defaultGapXIndex = array_search('md', $gapNamesArray);
$defaultGapYIndex = array_search('md', $gapNamesArray);
// Fallback to middle values if md/md don't exist
if ($defaultGapXIndex === false) $defaultGapXIndex = min(2, $maxGapIndex);
if ($defaultGapYIndex === false) $defaultGapYIndex = min(4, $maxGapIndex);

// Build an array of choices for the color and style fields (keeping for compatibility)
$gapChoices = [];
foreach ($gapNames as $gapName) {
  $gapChoices[$gapName] = $gapName;
}

$featureSettingsFields = [
  [
    'key' => 'field-feature-setting-contained',
    'label' => 'Style',
    'name' => 'contained',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Contained',
    'ui_off_text' => 'Full Width',
  ],
  [
    'key' => 'field-feature-setting-image-options-clone',
    'label' => 'Image Settings',
    'name' => 'image',
    'type' => 'clone',
    'clone' => [
      1 => 'field-image-aspect',
      3 => 'field-image-parallax',
    ],
    'display' => 'normal',
    'layout' => 'block',
    'conditional_logic' => [
      [
        [
          'field' => 'field-feature-setting-contained',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
  // [
  //   'key' => 'field-feature-width-v1',
  //   'label' => 'Width of layout',
  //   'name' => 'width',
  //   'type' => 'select',
  //   'required' => 1,
  //   'choices' => [
  //     '1/2' => '1/2, 1/2',
  //     '1/3' => '1/3, 1/3, 1/3',
  //     '1/3-2/3' => '1/3, 2/3 *',
  //   ],
  //   'default_value' => '1/2',
  //   'wrapper' => [
  //     'width' => '50%',
  //   ],
  // ],
  // [
  //   'key' => 'field-feature-width-v2',
  //   'label' => 'Width of columns',
  //   'name' => 'width',
  //   'type' => 'select',
  //   'required' => 1,
  //   'choices' => [
  //     '1/2' => '1/2, 1/2',
  //     '1/3' => '1/3, 1/3, 1/3',
  //     '1/3-2/3' => '1/3, 2/3 *',
  //     '1/4' => '1/4, 1/4, 1/4, 1/4',
  //   ],
  //   'default_value' => '1/2',
  //   'wrapper' => [
  //     'width' => '50%',
  //   ],
  // ],
  // [
  //   'key' => 'field-feature-setting-inverted-thirds',
  //   'label' => 'Invert 1/3 and 2/3',
  //   'instructions' => 'If enabled, 2/3 column is first and 1/3 column is second',
  //   'name' => 'invertedThirds',
  //   'type' => 'true_false',
  //   'ui' => 1,
  //   'ui_on_text' => 'Yes',
  //   'ui_off_text' => 'No',
  //   'default_value' => 0,
  //   'wrapper' => [
  //     'width' => '50%',
  //   ],
  //   'conditional_logic' => [
  //     [
  //       [
  //         'field' => 'field-feature-width-v1',
  //         'operator' => '==',
  //         'value' => '1/3-2/3',
  //       ],
  //     ],
  //     [
  //       [
  //         'field' => 'field-feature-width-v2',
  //         'operator' => '==',
  //         'value' => '1/3-2/3',
  //       ],
  //     ],
  //   ],
  // ],
  // [
  //   'key' => 'field-feature-setting-matrix-layout',
  //   'label' => 'Crossed Layout',
  //   'instructions' => 'If enabled, the layout inverts on every row: so 1/3, 2/3, 2/3, 1/3...; or 2/3, 1/3, 1/3, 2/3...',
  //   'name' => 'matrixLayout',
  //   'type' => 'true_false',
  //   'ui' => 1,
  //   'ui_on_text' => 'Yes',
  //   'ui_off_text' => 'No',
  //   'default_value' => 0,
  //   'wrapper' => [
  //     'width' => '50%',
  //   ],
  //   'conditional_logic' => [
  //     [
  //       [
  //         'field' => 'field-feature-width-v1',
  //         'operator' => '==',
  //         'value' => '1/3-2/3',
  //       ],
  //     ],
  //     [
  //       [
  //         'field' => 'field-feature-width-v2',
  //         'operator' => '==',
  //         'value' => '1/3-2/3',
  //       ],
  //     ],
  //   ],
  // ],
  [
    'key' => 'field-feature-setting-horizontal-alignment',
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
      // 'between' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-justify-between.svg'),
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
    'key' => 'field-feature-setting-vertical-alignment',
    'label' => 'Vertical alignment [Y axis]',
    'name' => 'align',
    'aria-label' => '',
    'type' => 'acfe_image_selector',
    'instructions' => '',
    'required' => 1,
    'choices' => [
      // 'stretch' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-stretch.svg'),
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
          'field' => 'field-feature-setting-contained',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-feature-setting-vertical-alignment-spacer',
    'label' => '',
    'name' => 'verticalAlignmentSpacer',
    'type' => 'message',
    'message' => '&nbsp;',
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-feature-setting-contained',
          'operator' => '!=',
          'value' => 1,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-feature-setting-gap-x',
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
    'key' => 'field-feature-setting-gap-y',
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
  // [
  //   'key' => 'field-feature-setting-override-mobile-gap-x',
  //   'label' => 'Different gap on mobile [X axis]',
  //   'name' => 'overrideMobileGapX',
  //   'type' => 'true_false',
  //   'ui' => 1,
  //   'ui_off_text' => 'Same',
  //   'ui_on_text' => 'Different',
  //   'default_value' => 0,
  //   'wrapper' => [
  //     'width' => '50%',
  //   ],
  // ],
  // [
  //   'key' => 'field-feature-setting-override-mobile-gap-spacer',
  //   'label' => '',
  //   'name' => 'overrideMobileGapYSpacer',
  //   'type' => 'message',
  //   'message' => '&nbsp;',
  //   'default_value' => 0,
  //   'wrapper' => [
  //     'width' => '50%',
  //   ],
  // ],
  [
    'key' => 'field-feature-setting-override-mobile-gap-y',
    'label' => 'Different gap on mobile [Y axis]',
    'name' => 'overrideMobileGapY',
    'type' => 'true_false',
    'ui' => 1,
    'ui_off_text' => 'Same',
    'ui_on_text' => 'Different',
    'default_value' => 0,
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  // [
  //   'key' => 'field-feature-setting-mobile-gap-x',
  //   'label' => 'Horizontal Gap on mobile [X axis]',
  //   'name' => 'mobileGapX',
  //   'type' => 'range',
  //   'required' => 1,
  //   'min' => 0,
  //   'max' => $maxGapIndex,
  //   'step' => 1,
  //   'default_value' => $defaultGapXIndex,
  //   'wrapper' => [
  //     'width' => '50%',
  //   ],
  //   'conditional_logic' => [
  //     [
  //       [
  //         'field' => 'field-feature-setting-override-mobile-gap-x',
  //         'operator' => '==',
  //         'value' => 1,
  //       ],
  //     ],
  //   ],
  // ],
  // [
  //   'key' => 'field-feature-setting-mobile-gap-x-spacer',
  //   'label' => '',
  //   'name' => 'mobileGapXSpacer',
  //   'type' => 'message',
  //   'message' => '&nbsp;',
  //   'wrapper' => [
  //     'width' => '50%',
  //   ],
  //   'conditional_logic' => [
  //     [
  //       [
  //         'field' => 'field-feature-setting-override-mobile-gap-y',
  //         'operator' => '==',
  //         'value' => 1,
  //       ],
  //     ],
  //   ],
  // ],
  [
    'key' => 'field-feature-setting-mobile-gap-y',
    'label' => 'Vertical Gap on mobile [Y axis]',
    'name' => 'mobileGapY',
    'type' => 'range',
    'required' => 1,
    'min' => 0,
    'max' => $maxGapIndex,
    'step' => 1,
    'default_value' => $defaultGapYIndex,
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-feature-setting-override-mobile-gap-y',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-feature-setting-mobile-gap-y-spacer',
    'label' => '',
    'name' => 'mobileGapYSpacer',
    'type' => 'message',
    'message' => '&nbsp;',
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-feature-setting-override-mobile-gap-y',
          'operator' => '!=',
          'value' => 1,
        ],
        [
          'field' => 'field-feature-setting-override-mobile-gap-x',
          'operator' => '==',
          'value' => 1,
        ]
      ],
    ],
  ],
  [
    'key' => 'field-feature-setting-divide-x',
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
    'key' => 'field-feature-setting-divide-y',
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
    'key' => 'field-feature-setting-full-width-divide-y-spacer',
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
          'field' => 'field-feature-setting-divide-y',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-feature-setting-full-width-divide-y',
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
          'field' => 'field-feature-setting-divide-y',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
];

add_action(
  'acf/include_fields',
  static function () use ($featureSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-feature-settings',
      'title' => 'Feature Settings',
      'fields' => $featureSettingsFields,
    ]);
  },
  20
);

$featureFields = [
  [
    'key' => 'field-feature-color',
    'label' => 'Color',
    'name' => 'feature_color',
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
    'key' => 'field-feature-image',
    'label' => 'Image',
    'name' => 'feature_image',
    'type' => 'image',
    'required' => 1,
    'return_format' => 'array',
    'preview_size' => 'thumbnail',
    'library' => 'all',
    'wrapper' => [
      'width' => '50',
    ],
  ],
  [
    'key' => 'field-feature-reversed',
    'label' => 'Image on Left/Right',
    'name' => 'feature_reversed',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Right',
    'ui_off_text' => 'Left',
    'wrapper' => [
      'width' => '50',
    ],
  ],
  [
    'key' => 'field-feature-flexible',
    'label' => '',
    'name' => 'feature',
    'type' => 'flexible_content',
    'acfe_flexible_async' => [
      0 => 'layout',
    ],
    'acfe_flexible_add_actions' => [
      0 => 'toggle',
      1 => 'copy',
    ],
    'layouts' => [
      'layoutFeatureColumn' => $featureColumnLayout,
    ],
    'min' => '',
    'button_label' => 'Add column',
    'acfe_flexible_layouts_settings' => 1,
  ],
];

$featureLayout = [
  'key' => 'layout-feature',
  'label' => $icon . ' Section with Side-Image',
  'name' => 'feature',
  'display' => 'block',
  'sub_fields' => $featureFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-feature-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_block_layout('layoutFeature', $featureLayout, 20);

$adwp->register_layout('feature', $featureLayout);
