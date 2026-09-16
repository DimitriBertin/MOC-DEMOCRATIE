<?php

/**
 * ACF Fields for Feature Column
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="16" viewBox="0 0 20 20" width="16"><path d="M2,6.5h3v7H2V6.5z M15,6.5v7h3v-7H15z M6,5h8v10H6V5z"/></svg>';

$featureColumnSettingsFields = [
  [
    'key' => 'field-featureColumn-setting-isRow',
    'label' => 'Size',
    'name' => 'isRow',
    'type' => 'true_false',
    'ui' => 1,
    'ui_off_text' => '1/2',
    'ui_on_text' => '1/1',
    'default_value' => 1,
  ],
  [
    'key' => 'field-featureColumn-setting-align-self',
    'label' => 'Align self',
    'name' => 'alignSelf',
    'aria-label' => '',
    'type' => 'acfe_image_selector',
    'instructions' => '',
    'required' => 1,
    'choices' => [
      // 'stretch' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/flex-align-stretch.svg'),
      'inherit' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/move-up.svg'),
      'start' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/align-vertical-top.svg'),
      'center' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/align-vertical-center.svg'),
      'end' => home_url('/wp-content/themes/atelierdesign/ad-ui/acf/assets/align-vertical-bottom.svg'),
    ],
    'default_value' => 'inherit',
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
          'field' => 'field-featureColumn-setting-isRow',
          'operator' => '!=',
          'value' => '1',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-featureColumn-setting-disable-divider-x',
    'label' => 'Override: disable divider on desktop [X axis]',
    'name' => 'disableDividerX',
    'type' => 'true_false',
    'ui' => 1,
    'ui_off_text' => 'Keep',
    'ui_on_text' => 'Disable',
    'default_value' => 0,
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-featureColumn-setting-disable-divider-y',
    'label' => 'Override: disable divider on mobile [Y axis]',
    'name' => 'disableDividerY',
    'type' => 'true_false',
    'ui' => 1,
    'ui_off_text' => 'Keep',
    'ui_on_text' => 'Disable',
    'default_value' => 0,
    'wrapper' => [
      'width' => '50%',
    ],
  ],
];
add_action(
  'acf/include_fields',
  static function () use ($featureColumnSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-featureColumn-settings',
      'title' => 'Feature Column settings',
      'fields' => $featureColumnSettingsFields,
    ]);
  },
  20
);


$featureColumnFields = [
  [
    'key' => 'field-featureColumn-flexible',
    'label' => '',
    'name' => '_featureColumn_content',
    'type' => 'flexible_content',
    'acfe_flexible_async' => [
      0 => 'layout',
    ],
    'acfe_flexible_add_actions' => [
      0 => 'toggle',
      1 => 'copy',
    ],
    'layouts' => [
      ...$adwp->get_nested_layouts(),
    ],
    'min' => 1,
    'max' => '',
    'button_label' => 'Add item',
    'acfe_flexible_layouts_settings' => 1,
  ],
];

$featureColumnLayout = [
  'key' => 'layout-featureColumn',
  'label' => $icon . ' Column',
  'name' => '_featureColumn',
  'display' => 'block',
  'sub_fields' => $featureColumnFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-featureColumn-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->register_layout('featureColumn', $featureColumnLayout);
