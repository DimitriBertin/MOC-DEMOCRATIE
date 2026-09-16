<?php

/**
 * ACF Fields for WYSIWYG
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path d="M2.5 4v3h5v12h3V7h5V4h-13zm19 5h-9v3h3v7h3v-7h3V9z" fill="currentColor" /></svg>';

$wysiwygSettingsFields = [
  [
    'key' => 'field-wysiwyg-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
];

add_action(
  'acf/include_fields',
  static function () use ($wysiwygSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-wysiwyg-settings',
      'title' => 'WYSIWYG Settings',
      'fields' => $wysiwygSettingsFields,
    ]);
  },
  5
);

$wysiwygFields = [
  [
    'key' => 'field-wysiwyg-content',
    'label' => '',
    'name' => '_wysiwyg_content',
    'type' => 'wysiwyg',
    'media_upload' => 0,
    'tabs' => 'visual',
    'acfe_wysiwyg_min_height' => 70,
    'acfe_wysiwyg_max_height' => '',
    // 'acfe_wysiwyg_valid_elements' => 'h2,h2[class],h3,h3[class],h4,h4[class],h5,h5[class],h6,h6[class],p,p[class],ul,ul[class],ol,ol[class],li,li[class],a,a[class],br,em,em[class],i,i[class],b,b[class],strong,strong[class],span,span[class],mark,mark[class]',
    'acfe_wysiwyg_custom_style' => '',
    'acfe_wysiwyg_disable_wp_style' => 0,
    'acfe_wysiwyg_autoresize' => 1,
    'acfe_wysiwyg_disable_resize' => 1,
    'acfe_wysiwyg_remove_path' => 1,
    'acfe_wysiwyg_menubar' => 0,
    'acfe_wysiwyg_transparent' => 0,
    'acfe_wysiwyg_merge_toolbar' => 0,
    'acfe_wysiwyg_custom_toolbar' => 0,
    'acfe_wysiwyg_auto_init' => 0,
    'acfe_wysiwyg_height' => 70,
    'acfe_wysiwyg_custom_toolbar' => 1,
    'acfe_wysiwyg_toolbar_buttons' => [
      'acfe_wysiwyg_toolbar_1' => [
        [
          'acfe_wysiwyg_toolbar_row' => 'typography-selector',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'bold',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'italic',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'heading-highlight',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'alignleft',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'aligncenter',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'alignright',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'balance-text',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'bullist',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'numlist',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'link',
        ],
      ],
    ],
    'ad_typography_config' => [
      [
        'text' => 'Heading',
        'items' => [
          [
            'key' => 'heading-2xl',
            'text' => 'Extra Extra Large',
            'format' => ['block' => 'h2', 'classes' => 'heading-2xl'],
          ],
          [
            'key' => 'heading-xl',
            'text' => 'Extra Large',
            'format' => ['block' => 'h3', 'classes' => 'heading-xl'],
          ],
          [
            'key' => 'heading-lg',
            'text' => 'Large',
            'format' => ['block' => 'h4', 'classes' => 'heading-lg'],
          ],
          [
            'key' => 'heading-md',
            'text' => 'Medium',
            'format' => ['block' => 'h5', 'classes' => 'heading-md'],
          ],
          [
            'key' => 'heading-sm',
            'text' => 'Small',
            'format' => ['block' => 'h6', 'classes' => 'heading-sm'],
          ],
        ],
      ],
      [
        'text' => 'Paragraph',
        'items' => [
          [
            'key' => 'paragraph-xl',
            'text' => 'Extra Large',
            'format' => ['block' => 'p', 'classes' => 'paragraph-xl'],
          ],
          [
            'key' => 'paragraph-lg',
            'text' => 'Large',
            'format' => ['block' => 'p', 'classes' => 'paragraph-lg'],
          ],
          [
            'key' => 'paragraph-md',
            'text' => 'Medium',
            'format' => ['block' => 'p', 'classes' => 'paragraph-md'],
            'default' => true,
          ],
          [
            'key' => 'paragraph-sm',
            'text' => 'Small',
            'format' => ['block' => 'p', 'classes' => 'paragraph-sm'],
          ],
        ],
      ],
    ],
  ],
];

/*
$nestedWysiwygFields = [
  [
    'key' => 'field-wysiwyg-content',
    'label' => '',
    'name' => '_wysiwyg_content',
    'type' => 'wysiwyg',
    'media_upload' => 0,
    'tabs' => 'visual',
    'acfe_wysiwyg_min_height' => 70,
    'acfe_wysiwyg_max_height' => '',
    // 'acfe_wysiwyg_valid_elements' => 'h4,h4[class],h5,h5[class],h6,h6[class],p,p[class<paragraph-lg?paragraph-md?paragraph-sm],ul,ul[class],ol,ol[class],li,li[class],a,a[class],br,em,em[class],i,i[class],b,b[class],strong,strong[class],span,span[class],mark,mark[class]',
    'acfe_wysiwyg_custom_style' => '',
    'acfe_wysiwyg_disable_wp_style' => 0,
    'acfe_wysiwyg_autoresize' => 1,
    'acfe_wysiwyg_disable_resize' => 1,
    'acfe_wysiwyg_remove_path' => 1,
    'acfe_wysiwyg_menubar' => 0,
    'acfe_wysiwyg_transparent' => 0,
    'acfe_wysiwyg_merge_toolbar' => 0,
    'acfe_wysiwyg_auto_init' => 0,
    'acfe_wysiwyg_height' => 70,
    'acfe_wysiwyg_custom_toolbar' => 1,
    'acfe_wysiwyg_toolbar_buttons' => [
      'acfe_wysiwyg_toolbar_1' => [
        [
          'acfe_wysiwyg_toolbar_row' => 'typography-selector',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'bold',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'italic',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'heading-highlight',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'alignleft',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'aligncenter',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'alignright',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'balance-text',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'bullist',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'numlist',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'link',
        ],
      ],
    ],
    'ad_typography_config' => [
      [
        'text' => 'Heading',
        'items' => [
          [
            'key' => 'heading-lg',
            'text' => 'Large',
            'format' => ['block' => 'h4', 'classes' => 'heading-lg'],
          ],
          [
            'key' => 'heading-md',
            'text' => 'Medium',
            'format' => ['block' => 'h5', 'classes' => 'heading-md'],
          ],
          [
            'key' => 'heading-sm',
            'text' => 'Small',
            'format' => ['block' => 'h6', 'classes' => 'heading-sm'],
          ],
        ],
      ],
      [
        'text' => 'Paragraph',
        'items' => [
          [
            'key' => 'paragraph-lg',
            'text' => 'Large',
            'format' => ['block' => 'p', 'classes' => 'paragraph-lg'],
          ],
          [
            'key' => 'paragraph-md',
            'text' => 'Medium',
            'format' => ['block' => 'p', 'classes' => 'paragraph-md'],
            'default' => true,
          ],
          [
            'key' => 'paragraph-sm',
            'text' => 'Small',
            'format' => ['block' => 'p', 'classes' => 'paragraph-sm'],
          ],
        ],
      ],
    ],
  ],
];
*/

$wysiwygLayout = [
  'key' => 'layout-wysiwyg',
  'label' => $icon . ' Text',
  'name' => '_wysiwyg',
  'display' => 'block',
  'sub_fields' => $wysiwygFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-wysiwyg-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutWysiwyg', $wysiwygLayout, 5);

// Nested layout variant (no full-width setting)
$nestedWysiwygLayout = [
  'key' => 'layout-nestedWysiwyg',
  'label' => $icon . ' Text',
  'name' => '_wysiwyg',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $wysiwygFields), // todo: here the $nestedWysiwygFields is temporarily not used, now wysiwyg have all styles on all levels
];

$adwp->add_nested_layout('layoutNestedWysiwyg', $nestedWysiwygLayout, 5);

// Deep layout variant (also no full-width setting)
$deepWysiwygLayout = [
  'key' => 'layout-deepWysiwyg',
  'label' => $icon . ' Text',
  'name' => '_wysiwyg',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $wysiwygFields),
];

$adwp->add_deep_layout('layoutDeepWysiwyg', $deepWysiwygLayout, 5);

$adwp->register_layout('wysiwyg', $nestedWysiwygLayout);
