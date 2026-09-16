<?php

/**
 * ACF Fields for Image
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" fill="currentColor" /></svg>';

$imageSettingsFields = [
  [
    'key' => 'field-image-width',
    'label' => 'Width',
    'name' => 'width',
    'type' => 'range',
    'required' => 1,
    'default_value' => 100,
    'min' => 1,
    'max' => 100,
    'step' => 1,
  ],
  [
    'key' => 'field-image-width-isFullWidth',
    'label' => 'Full Width',
    'instructions' => 'Note: only works if width is set to 100%',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'conditional_logic' => [
      [
        [
          'field' => 'field-image-width',
          'operator' => '==',
          'value' => 100,
        ],
      ],
    ],
  ],
  [
    'key' => 'field-image-alignment',
    'label' => 'Alignment',
    'instructions' => 'Note: only works if width is lower than 100%',
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
    'conditional_logic' => [
      [
        [
          'field' => 'field-image-width',
          'operator' => '<',
          'value' => 100,
        ],
      ]
    ]
  ],
  [
    'key' => 'field-image-aspect',
    'label' => 'Aspect Ratio',
    'name' => 'aspect',
    'type' => 'select',
    'choices' => [
      'auto' => 'Auto',
      '21/9' => 'cinema - 21/9',
      'md:21/9' => 'cinema - 21/9 (only on desktop)',
      '16/9' => 'video - 16/9',
      '3/2' => 'landscape photo - 3/2',
      '4/3'  => 'landscape photo - 4/3',
      // '5/4' - => '5',
      // '2/1' => '2/1',
      '1/1' => 'square - 1/1',
      '4/5' => 'instagram - 4/5',
      '3/4' => 'portrait photo - 3/4',
      // '2/3' => '2/3',
      // '1/2' => '1/2',
    ],
    'default_value' => 'auto',
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  // [
  //   'key' => 'field-image-fit',
  //   'label' => 'Fit',
  //   'name' => 'fit',
  //   'type' => 'true_false',
  //   'default_value' => 1,
  //   'ui' => 1,
  //   'ui_on_text' => 'Cover',
  //   'ui_off_text' => 'Contain',
  //   'conditional_logic' => [
  //     [
  //       [
  //         'field' => 'field-image-aspect',
  //         'operator' => '!=',
  //         'value' => 'auto',
  //       ],
  //     ],
  //   ],
  // ],
  [
    'key' => 'field-image-fit',
    'label' => 'Fit',
    'name' => 'fit',
    'type' => 'acfe_hidden',
    'default_value' => 1,
  ],
  [
    'key' => 'field-image-parallax',
    'label' => 'Movement',
    'name' => 'parallax',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Parallax',
    'ui_off_text' => 'Static',
    'wrapper' => [
      'width' => '50%',
    ],
  ],
];

// From nested version of the layout, remove the wrapper width settings
$imageNestedSettingsFields = $imageSettingsFields;
unset($imageNestedSettingsFields[1]);

add_action(
  'acf/include_fields',
  static function () use ($imageSettingsFields, $imageNestedSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-image-settings',
      'title' => 'Image settings',
      'fields' => $imageSettingsFields,
    ]);

    acf_add_local_field_group([
      'key' => 'field-group-image-nested-settings',
      'title' => 'Image Nested Settings',
      'fields' => $imageNestedSettingsFields,
    ]);
  },
  25
);


$imageFields = [
  [
    'key' => 'field-image-image',
    'label' => '',
    'name' => '_image_image',
    'type' => 'image',
    'preview_size' => 'thumbnail',
    'required' => 1,
  ],
];

$imageLayout = [
  'key' => 'layout-image',
  'label' => $icon . ' Image',
  'name' => '_image',
  'display' => 'block',
  'sub_fields' => $imageFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-image-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutImage', $imageLayout, 25);

// Nested layout variant (no full-width setting)
$nestedImageLayout = [
  'key' => 'layout-nestedImage',
  'label' => $icon . ' Image',
  'name' => '_image',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $imageFields, true),
  'acfe_flexible_settings' => [
    0 => 'field-group-image-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedImage', $nestedImageLayout, 25);

// Deep layout variant (also no full-width setting)
$deepImageLayout = [
  'key' => 'layout-deepImage',
  'label' => $icon . ' Image',
  'name' => '_image',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $imageFields, true),
  'acfe_flexible_settings' => [
    0 => 'field-group-image-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_deep_layout('layoutDeepImage', $deepImageLayout, 25);

$adwp->register_layout('image', $deepImageLayout);
