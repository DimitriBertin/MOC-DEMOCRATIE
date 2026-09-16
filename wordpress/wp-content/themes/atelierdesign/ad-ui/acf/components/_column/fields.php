<?php

/**
 * ACF Fields for Column
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="16" viewBox="0 0 20 20" width="16"><path d="M2,6.5h3v7H2V6.5z M15,6.5v7h3v-7H15z M6,5h8v10H6V5z"/></svg>';

$columnSettingsFields = [
  [
    'key' => 'field-column-setting-align-self',
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
    'key' => 'field-column-setting-sticky',
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
          'field' => 'field-column-setting-align-self',
          'operator' => '!=',
          'value' => 'inherit',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-column-setting-sticky-spacer',
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
          'field' => 'field-column-setting-align-self',
          'operator' => '==',
          'value' => 'inherit',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-column-setting-disable-divider-x',
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
    'key' => 'field-column-setting-disable-divider-y',
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
  static function () use ($columnSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-column-settings',
      'title' => 'Column settings',
      'fields' => $columnSettingsFields,
    ]);
  },
  60
);


$columnFields = [
  // [
  //   'key' => 'field-column-setting-width-notice',
  //   'label' => '',
  //   'name' => '',
  //   'type' => 'message',
  //   'esc_html' => 0,
  //   'message' => '<strong>Heads up:</strong> This column lives in a <code>1/4</code> width layout. Keep the content concise.',
  //   'ad_parent_conditional_logic' => [
  //     [
  //       [
  //         'field' => 'field-columns-setting-isFullWidth',
  //         'operator' => '==',
  //         'value' => 1,
  //       ],
  //       [
  //         'field' => 'field-columns-width-v2',
  //         'operator' => '==',
  //         'value' => '1/4',
  //       ],
  //     ],
  //   ],
  // ],
  // [
  //   'key' => 'field-column-isParent-quarter',
  //   'label' => 'Is parent quarter',
  //   'name' => 'isParentQuarter',
  //   'type' => 'acfe_hidden',
  //   'default_value' => 1,
  //   'ad_parent_conditional_logic' => [
  //     [
  //       [
  //         'field' => 'field-columns-setting-isFullWidth',
  //         'operator' => '==',
  //         'value' => 1,
  //       ],
  //       [
  //         'field' => 'field-columns-width-v2',
  //         'operator' => '==',
  //         'value' => '1/4',
  //       ],
  //     ],
  //   ],
  // ],
  [
    'key' => 'field-column-flexible',
    'label' => '',
    'name' => '_column_content',
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

$columnLayout = [
  'key' => 'layout-column',
  'label' => $icon . ' Column',
  'name' => '_column',
  'display' => 'block',
  'sub_fields' => $columnFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-column-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->register_layout('column', $columnLayout);
