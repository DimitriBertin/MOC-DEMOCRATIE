<?php

/**
 * ACF Fields for Layout
 */

global $adwp;

require __DIR__ . '/../_nestedWrapper/fields.php';
require __DIR__ . '/../_nestedLayout/fields.php';

global $nestedLayoutLayout;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16" fill="currentColor"><path d="M200-200v80q-33 0-56.5-23.5T120-200h80Zm-80-80v-80h80v80h-80Zm0-160v-80h80v80h-80Zm0-160v-80h80v80h-80Zm80-160h-80q0-33 23.5-56.5T200-840v80Zm80 640v-80h80v80h-80Zm0-640v-80h80v80h-80Zm160 640v-80h80v80h-80Zm0-640v-80h80v80h-80Zm160 640v-80h80v80h-80Zm0-640v-80h80v80h-80Zm160 560h80q0 33-23.5 56.5T760-120v-80Zm0-80v-80h80v80h-80Zm0-160v-80h80v80h-80Zm0-160v-80h80v80h-80Zm0-160v-80q33 0 56.5 23.5T840-760h-80Z"/></svg>';

$wrapperSettingsFields = [
  [
    'key' => 'field-wrapper-setting-spacing',
    'label' => 'Size',
    'name' => 'span',
    'type' => 'radio',
    'required' => 1,
    'default_value' => '12/12',
    'choices' => [
      // '1/12' => '1:12',
      // '2/12' => '1:6',
      '3/12' => '1/4',
      '4/12' => '1/3',
      // '5/12' => '5:12',
      '6/12' => '1/2',
      // '7/12' => '7/12',
      '8/12' => '2/3',
      '9/12' => '3/4',
      // '10/12' => '5:6',
      // '11/12' => '11:12',
      '12/12' => '1/1',
    ],
    'layout' => 'horizontal',
    'return_format' => 'value',
  ],
  [
    'key' => 'field-wrapper-setting-align-self',
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
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-wrapper-setting-sticky',
    'label' => 'Sticky',
    'name' => 'isSticky',
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
          'field' => 'field-wrapper-setting-align-self',
          'operator' => '!=',
          'value' => 'inherit',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-wrapper-setting-sticky-spacer',
    'label' => '',
    'name' => 'isStickySpacer',
    'type' => 'message',
    'message' => '&nbsp;',
    'wrapper' => [
      'width' => '50%',
    ],
    'conditional_logic' => [
      [
        [
          'field' => 'field-wrapper-setting-align-self',
          'operator' => '==',
          'value' => 'inherit',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-wrapper-setting-disable-divider-x',
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
    'key' => 'field-wrapper-setting-disable-divider-y',
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
  static function () use ($wrapperSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-wrapper-settings',
      'title' => 'Wrapper settings',
      'fields' => $wrapperSettingsFields,
    ]);
  },
  70
);

$wrapperFields = [
  [
    'key' => 'field-wrapper-flexible',
    'label' => '',
    'name' => '_wrapper_content',
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
      'layoutNestedLayout' => $nestedLayoutLayout,
    ],
    'min' => 1,
    'max' => '',
    'button_label' => 'Add item',
    'acfe_flexible_layouts_settings' => 1,
  ],
];

$wrapperLayout = [
  'key' => 'layout-wrapper',
  'label' => $icon . ' Layout',
  'name' => '_wrapper',
  'display' => 'block',
  'sub_fields' => $wrapperFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-wrapper-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];
