<?php

/**
 * ACF Fields for Columns
 */

global $adwp, $adui_tokens;

// Collect color variant names, and style names
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

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="16" viewBox="0 0 20 20" width="16"><path d="M13,15h4V5h-4V15z M8,5v10h4V5H8z M7,15V5H3v10H7z" fill="currentColor"/></svg>';

$columnsSettingsFields = [
  [
    'key' => 'field-columns-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
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
    'key' => 'field-columns-width-v1',
    'label' => 'Width of columns',
    'name' => 'width',
    'type' => 'select',
    'required' => 1,
    'choices' => [
      '1/2' => '1/2, 1/2',
      '1/3' => '1/3, 1/3, 1/3',
      '1/3-2/3' => '1/3, 2/3 *',
    ],
    'default_value' => '1/2',
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-columns-setting-isFullWidth',
          'operator' => '==',
          'value' => 0,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-columns-width-v2',
    'label' => 'Width of columns',
    'name' => 'width',
    'type' => 'select',
    'required' => 1,
    'choices' => [
      '1/2' => '1/2, 1/2',
      '1/3' => '1/3, 1/3, 1/3',
      '1/3-2/3' => '1/3, 2/3 *',
      '1/4' => '1/4, 1/4, 1/4, 1/4',
    ],
    'default_value' => '1/2',
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-columns-setting-isFullWidth',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-columns-setting-inverted-thirds',
    'label' => 'Invert 1/3 and 2/3',
    'instructions' => 'If enabled, 2/3 column is first and 1/3 column is second',
    'name' => 'invertedThirds',
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
          'field' => 'field-columns-width-v1',
          'operator' => '==',
          'value' => '1/3-2/3',
        ],
      ],
      [
        [
          'field' => 'field-columns-width-v2',
          'operator' => '==',
          'value' => '1/3-2/3',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-columns-setting-matrix-layout',
    'label' => 'Crossed Layout',
    'instructions' => 'If enabled, the layout inverts on every row: so 1/3, 2/3, 2/3, 1/3...; or 2/3, 1/3, 1/3, 2/3...',
    'name' => 'matrixLayout',
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
          'field' => 'field-columns-width-v1',
          'operator' => '==',
          'value' => '1/3-2/3',
        ],
      ],
      [
        [
          'field' => 'field-columns-width-v2',
          'operator' => '==',
          'value' => '1/3-2/3',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-columns-setting-horizontal-alignment',
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
    'key' => 'field-columns-setting-vertical-alignment',
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
  ],
  [
    'key' => 'field-columns-setting-gap-x',
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
    'key' => 'field-columns-setting-gap-y',
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
  //   'key' => 'field-columns-setting-override-mobile-gap-x',
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
  //   'key' => 'field-columns-setting-override-mobile-gap-spacer',
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
    'key' => 'field-columns-setting-override-mobile-gap-y',
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
  //   'key' => 'field-columns-setting-mobile-gap-x',
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
  //         'field' => 'field-columns-setting-override-mobile-gap-x',
  //         'operator' => '==',
  //         'value' => 1,
  //       ],
  //     ],
  //   ],
  // ],
  // [
  //   'key' => 'field-columns-setting-mobile-gap-x-spacer',
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
  //         'field' => 'field-columns-setting-override-mobile-gap-y',
  //         'operator' => '==',
  //         'value' => 1,
  //       ],
  //     ],
  //   ],
  // ],
  [
    'key' => 'field-columns-setting-mobile-gap-y',
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
          'field' => 'field-columns-setting-override-mobile-gap-y',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-columns-setting-mobile-gap-y-spacer',
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
          'field' => 'field-columns-setting-override-mobile-gap-y',
          'operator' => '!=',
          'value' => 1,
        ],
        [
          'field' => 'field-columns-setting-override-mobile-gap-x',
          'operator' => '==',
          'value' => 1,
        ]
      ],
    ],
  ],
  [
    'key' => 'field-columns-setting-divide-x',
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
    'key' => 'field-columns-setting-divide-y',
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
    'key' => 'field-columns-setting-full-width-divide-y-spacer',
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
          'field' => 'field-columns-setting-divide-y',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-columns-setting-full-width-divide-y',
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
          'field' => 'field-columns-setting-divide-y',
          'operator' => '==',
          'value' => 1,
        ],
      ],
    ],
  ],
];

add_action(
  'acf/include_fields',
  static function () use ($columnsSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-columns-settings',
      'title' => 'Columns settings',
      'fields' => $columnsSettingsFields,
    ]);
  },
  60
);

$columnsFields = [
  [
    'key' => 'field-columns-flexible',
    'label' => '',
    'name' => '_columns',
    'type' => 'flexible_content',
    'acfe_flexible_async' => [
      0 => 'layout',
    ],
    'acfe_flexible_add_actions' => [
      0 => 'toggle',
      1 => 'copy',
    ],
    'layouts' => [
      'layoutColumn' => $columnLayout,
    ],
    'min' => 2,
    'button_label' => 'Add column',
    'acfe_flexible_layouts_settings' => 1,
  ],
];

$columnsLayout = [
  'key' => 'layout-columns',
  'label' => $icon . ' Columns',
  'name' => '_columns',
  'display' => 'block',
  'sub_fields' => $columnsFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-columns-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutColumns', $columnsLayout, 60);

$adwp->register_layout('columns', $columnsLayout);
