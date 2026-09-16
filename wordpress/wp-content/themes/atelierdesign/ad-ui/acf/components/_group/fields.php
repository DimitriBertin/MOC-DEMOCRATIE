<?php

/**
 * ACF Fields for Group
 */

global $adwp, $adui_tokens;

$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$defaultSizingMode = $responsiveSizingModes[0] ?? null;
$gapTokens = $defaultSizingMode ? ($adui_tokens['responsiveSizing'][$defaultSizingMode]['gap'] ?? []) : [];
$gapNames = array_keys($gapTokens);
$gapNamesArray = array_values($gapNames);
$maxGapIndex = count($gapNamesArray) - 1;

if ($maxGapIndex < 0) {
  $gapNamesArray = ['none'];
  $maxGapIndex = 0;
}

$defaultGapXIndex = array_search('sm', $gapNamesArray, true);
if ($defaultGapXIndex === false) {
  $defaultGapXIndex = (int)floor($maxGapIndex / 2);
}

$defaultGapYIndex = array_search('sm', $gapNamesArray, true);
if ($defaultGapYIndex === false) {
  $defaultGapYIndex = (int)ceil($maxGapIndex / 2);
}

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960" width="16" height="16"><path d="M640-280q83 0 141.5-58.5T840-480q0-83-58.5-141.5T640-680q-27 0-52.5 7T540-653q29 36 44.5 80t15.5 93q0 49-15.5 93T540-307q22 13 47.5 20t52.5 7Zm-160-80q19-25 29.5-55.5T520-480q0-34-10.5-64.5T480-600q-19 25-29.5 55.5T440-480q0 34 10.5 64.5T480-360Zm-160 80q27 0 52.5-7t47.5-20q-29-36-44.5-80T360-480q0-49 15.5-93t44.5-80q-22-13-47.5-20t-52.5-7q-83 0-141.5 58.5T120-480q0 83 58.5 141.5T320-280Zm0 80q-117 0-198.5-81.5T40-480q0-117 81.5-198.5T320-760q45 0 85.5 13t74.5 37q34-24 74.5-37t85.5-13q117 0 198.5 81.5T920-480q0 117-81.5 198.5T640-200q-45 0-85.5-13T480-250q-34 24-74.5 37T320-200Z"/></svg>';

$groupSettingsFields = [
  [
    'key' => 'field-group-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
  [
    'key' => 'field-group-setting-direction',
    'label' => 'Direction',
    'name' => 'direction',
    'type' => 'button_group',
    'choices' => [
      'row' => 'Row',
      'column' => 'Column',
      'row-desktop' => 'Desktop: Row',
      // 'row-reverse' => 'Row Reverse',
      // 'column-reverse' => 'Column Reverse',
    ],
    'default_value' => 'row',
    'wrapper' => [
      'width' => '50%',
    ],
    'return_format' => 'value',
  ],
  [
    'key' => 'field-group-setting-wrap',
    'label' => 'Wrap',
    'name' => 'wrap',
    'type' => 'button_group',
    'choices' => [
      'wrap' => 'Wrap',
      'nowrap' => 'No Wrap',
      'nowrap-desktop' => 'Desktop: No Wrap',
      // 'wrap-reverse' => 'Wrap Reverse',
    ],
    'default_value' => 'wrap',
    'wrapper' => [
      'width' => '50%',
    ],
    'return_format' => 'value',
    'conditional_logic' => [
      [
        [
          'field' => 'field-group-setting-direction',
          'operator' => '==',
          'value' => 'row',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-group-setting-justify-row',
    'label' => 'Align X',
    'name' => 'justify',
    'type' => 'acfe_image_selector',
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
    'conditional_logic' => [
      [
        [
          'field' => 'field-group-setting-direction',
          'operator' => '==',
          'value' => 'row',
        ],
      ],
      [
        [
          'field' => 'field-group-setting-direction',
          'operator' => '==',
          'value' => 'row-desktop',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-group-setting-align-items-row',
    'label' => 'Align Y',
    'name' => 'alignItems',
    'type' => 'acfe_image_selector',
    'choices' => [
      // 'stretch' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-stretch.svg'),
      'start' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-start.svg'),
      'center' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-center.svg'),
      'end' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-end.svg'),
    ],
    'default_value' => 'center',
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
          'field' => 'field-group-setting-direction',
          'operator' => '==',
          'value' => 'row',
        ],
      ],
      [
        [
          'field' => 'field-group-setting-direction',
          'operator' => '==',
          'value' => 'row-desktop',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-group-setting-align-items-column',
    'label' => 'Align X',
    'name' => 'alignItems',
    'type' => 'acfe_image_selector',
    'choices' => [
      // 'stretch' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-stretch.svg'),
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
    'conditional_logic' => [
      [
        [
          'field' => 'field-group-setting-direction',
          'operator' => '==',
          'value' => 'column',
        ],
      ],
    ],
  ],
  /* [
    'key' => 'field-group-setting-justify-column',
    'label' => 'Align Y',
    'name' => 'justify',
    'type' => 'acfe_image_selector',
    'choices' => [
      'start' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-start.svg'),
      'center' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-center.svg'),
      'end' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-end.svg'),
      // 'between' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-justify-between.svg'),
    ],
    'default_value' => 'center',
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
          'field' => 'field-group-setting-direction',
          'operator' => '==',
          'value' => 'column',
        ],
      ],
    ],
  ], */
  [
    'key' => 'field-group-setting-gap-x',
    'label' => 'Gap X',
    'name' => 'gapX',
    'type' => 'range',
    'min' => 0,
    'max' => $maxGapIndex,
    'step' => 1,
    'default_value' => $defaultGapXIndex,
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-group-setting-direction',
          'operator' => '==',
          'value' => 'row',
        ],
      ],
      [
        [
          'field' => 'field-group-setting-direction',
          'operator' => '==',
          'value' => 'row-desktop',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-group-setting-gap-y',
    'label' => 'Gap Y',
    'name' => 'gapY',
    'type' => 'range',
    'min' => 0,
    'max' => $maxGapIndex,
    'step' => 1,
    'default_value' => $defaultGapYIndex,
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-group-setting-additional-classes',
    'label' => 'Additional Classes',
    'name' => 'additionalClasses',
    'type' => 'text',
    'instructions' => 'Optional extra classes appended to the wrapper.',
  ],
];

// From nested version of the layout, remove the wrapper width settings
$nestedGroupSettingsFields = $groupSettingsFields;
unset($nestedGroupSettingsFields[0]);

add_action(
  'acf/include_fields',
  static function () use ($groupSettingsFields, $nestedGroupSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-group-settings',
      'title' => 'Group settings',
      'fields' => $groupSettingsFields,
    ]);

    acf_add_local_field_group([
      'key' => 'field-group-group-nested-settings',
      'title' => 'Group Nested Settings',
      'fields' => $nestedGroupSettingsFields,
    ]);
  },
  45
);

$groupFields = [
  [
    'key' => 'field-group-flexible',
    'label' => '',
    'name' => '_group',
    'type' => 'flexible_content',
    'acfe_flexible_async' => [
      0 => 'layout',
    ],
    'acfe_flexible_add_actions' => [
      0 => 'toggle',
      1 => 'copy',
    ],
    'layouts' => [
      'groupLayoutButton' => $extraDeepButtonLayout,
      'groupLayoutBadge' => $extraDeepBadgeLayout,
      'groupLayoutIcon' => $extraDeepIconLayout,
      'groupLayoutWysiwyg' => $deepWysiwygLayout, // deep is good enough here, because wysiwyg doesn't have more settings on deep level
    ],
    'min' => 1,
    'button_label' => 'Add item',
    'acfe_flexible_layouts_settings' => 1,
  ],
];

$groupLayout = [
  'key' => 'layout-group',
  'label' => $icon . ' Group',
  'name' => '_group',
  'display' => 'block',
  'sub_fields' => $groupFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-group-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutGroup', $groupLayout, 45);

// Nested layout variant (no full-width setting)
$nestedGroupLayout = [
  'key' => 'layout-nestedGroup',
  'label' => $icon . ' Group',
  'name' => '_group',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $groupFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-group-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedGroup', $nestedGroupLayout, 45);

// Deep layout variant (also no full-width setting)
$deepGroupLayout = [
  'key' => 'layout-deepGroup',
  'label' => $icon . ' Group',
  'name' => '_group',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $groupFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-group-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_deep_layout('layoutDeepGroup', $deepGroupLayout, 45);

$adwp->register_layout('group', $deepGroupLayout);
