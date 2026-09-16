<?php

/**
 * ACF Fields for Quote
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path d="M5 17h3l2-4V7H4v6h3l-2 4zm10 0h3l2-4V7h-6v6h3l-2 4z" fill="currentColor" /></svg>';

$quoteSettingsFields = [
  [
    'key' => 'field-quote-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
  [
    'key' => 'field-quote-alignment',
    'label' => '',
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
  ],
  [
    'key' => 'field-quote-hasBorder',
    'label' => 'Show decorator border?',
    'name' => 'hasBorder',
    'type' => 'true_false',
    'message' => '',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Show',
    'ui_off_text' => 'Hide',
    'conditional_logic' => [
      [
        [
          'field' => 'field-quote-alignment',
          'operator' => '!=',
          'value' => 'center',
        ],
      ],
    ]
  ],
  [
    'key' => 'field-quote-hasIcon',
    'label' => 'Show quote symbol?',
    'name' => 'hasIcon',
    'type' => 'true_false',
    'message' => '',
    'default_value' => 1,
    'ui' => 1,
    'ui_on_text' => 'Show',
    'ui_off_text' => 'Hide',
  ],
];

// From nested version of the layout, remove the wrapper width settings
$quoteNestedSettingsFields = $quoteSettingsFields;
unset($quoteNestedSettingsFields[0]);

add_action(
  'acf/include_fields',
  static function () use ($quoteSettingsFields, $quoteNestedSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-quote-settings',
      'title' => 'Quote Settings',
      'fields' => $quoteSettingsFields,
    ]);

    acf_add_local_field_group([
      'key' => 'field-group-quote-nested-settings',
      'title' => 'Quote Nested Settings',
      'fields' => $quoteNestedSettingsFields,
    ]);
  },
  35
);

$quoteFields = [
  [
    'key' => 'field-quote-content',
    'label' => 'Content',
    'name' => '_quote_content',
    'type' => 'textarea',
    'required' => 1,
    'rows' => 4,
    'new_lines' => 'br',
  ],
  [
    'key' => 'field-quote-image',
    'label' => 'Image (optional)',
    'name' => '_quote_image',
    'type' => 'image',
    'return_format' => 'array',
    'preview_size' => 'thumbnail',
    'library' => 'all',
  ],
  [
    'key' => 'field-quote-label-primary',
    'label' => 'Primary label (optional)',
    'name' => '_quote_labelPrimary',
    'type' => 'text',
  ],
  [
    'key' => 'field-quote-labelSecondary',
    'label' => 'Secondary label (optional)',
    'name' => '_quote_labelSecondary',
    'type' => 'text',
  ],
];

$quoteLayout = [
  'key' => 'layout-quote',
  'label' => $icon . ' Quote',
  'name' => '_quote',
  'display' => 'block',
  'sub_fields' => $quoteFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-quote-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutQuote', $quoteLayout, 35);

// Nested layout variant (no full-width setting)
$nestedQuoteLayout = [
  'key' => 'layout-nestedQuote',
  'label' => $icon . ' Quote',
  'name' => '_quote',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $quoteFields, true),
  'acfe_flexible_settings' => [
    0 => 'field-group-quote-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedQuote', $nestedQuoteLayout, 35);

// Deep layout variant (also no full-width setting)
$deepQuoteLayout = [
  'key' => 'layout-deepQuote',
  'label' => $icon . ' Quote',
  'name' => '_quote',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $quoteFields, true),
  'acfe_flexible_settings' => [
    0 => 'field-group-quote-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_deep_layout('layoutDeepQuote', $deepQuoteLayout, 35);

$adwp->register_layout('quote', $deepQuoteLayout);
