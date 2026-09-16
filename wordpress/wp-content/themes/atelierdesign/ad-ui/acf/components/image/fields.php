<?php

/**
 * ACF Fields for Image Section
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960" width="16" height="16"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z"/></svg>';

$imageSectionSettingsFields = [
  [
    'key' => 'field-image-section-aspect',
    'label' => 'Aspect Ratio',
    'name' => 'aspect',
    'type' => 'select',
    'choices' => [
      'auto' => 'Auto',
      '21/9' => 'cinema - 21/9',
      'md:21/9' => 'cinema - 21/9 (only on desktop)',
      '16/9' => 'video - 16/9',
      '3/2' => 'landscape photo - 3/2',
      // '4/3'  => 'landscape photo - 4/3',
      // '5/4' - => '5',
      // '2/1' => '2/1',
      // '1/1' => 'square - 1/1',
      // '4/5' => 'instagram - 4/5',
      // '3/4' => 'portrait photo - 3/4',
      // '2/3' => '2/3',
      // '1/2' => '1/2',
    ],
    'default_value' => 'md:21/9',
  ],
  [
    'key' => 'field-image-section-parallax',
    'label' => 'Movement',
    'name' => 'parallax',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'ui_on_text' => 'Parallax',
    'ui_off_text' => 'Static',
    'conditional_logic' => [
      [
        [
          'field' => 'field-image-section-aspect',
          'operator' => '!=',
          'value' => 'auto',
        ],
      ],
    ],
  ],
];

add_action(
  'acf/include_fields',
  static function () use ($imageSectionSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-image-section-settings',
      'title' => 'Image Section settings',
      'fields' => $imageSectionSettingsFields,
    ]);
  },
  30
);


$imageSectionFields = [
  [
    'key' => 'field-image-section-image',
    'label' => '',
    'name' => 'image_image',
    'type' => 'image',
    'preview_size' => 'thumbnail',
    'required' => 1,
  ],
];

$imageSectionLayout = [
  'key' => 'layout-image-section',
  'label' => $icon . ' Full Width Image',
  'name' => 'image',
  'display' => 'block',
  'sub_fields' => $imageSectionFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-image-section-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_block_layout('layoutImageSection', $imageSectionLayout, 30);

$adwp->register_layout('image.section', $imageSectionLayout);
