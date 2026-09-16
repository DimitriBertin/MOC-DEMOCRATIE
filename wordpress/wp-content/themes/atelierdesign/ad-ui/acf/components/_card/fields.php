<?php

/**
 * ACF Fields for Card
 */

global $adwp, $adui_tokens;

// Collect color variant names, and style names
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$styleNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['card'] ?? []);

// Build an array of choices for the color and style fields
$styleChoices = [];
foreach ($styleNames as $styleName) {
  $styleChoices[$styleName] = toTitleCase($styleName);
}

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path fill="currentColor" d="M11.99 18.54l-7.37-5.73L3 14.07l9 7 9-7-1.63-1.27-7.38 5.74zM12 16l7.36-5.73L21 9l-9-7-9 7 1.63 1.27L12 16z"/></svg>';

$cardSettingsFields = [
  [
    'key' => 'field-card-setting-isFullWidth',
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
  static function () use ($cardSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-card-settings',
      'title' => 'Card settings',
      'fields' => $cardSettingsFields,
    ]);
  },
  40
);

$cardFields = [
  [
    'key' => 'field-card-style',
    'label' => 'Style',
    'name' => 'style',
    'type' => 'select',
    'required' => 1,
    'choices' => $styleChoices,
    'return_format' => 'value',
  ],
  [
    'key' => 'field-card-content',
    'label' => '',
    'name' => '_card_content',
    'type' => 'flexible_content',
    'acfe_flexible_async' => [
      0 => 'layout',
    ],
    'acfe_flexible_add_actions' => [
      0 => 'toggle',
      1 => 'copy',
    ],
    'layouts' => $adwp->get_deep_layouts(),
    'min' => 1,
    'max' => '',
    'button_label' => 'Add item',
    'acfe_flexible_layouts_settings' => 1,
  ],
];

$cardLayout = [
  'key' => 'layout-card',
  'label' => $icon . ' Card',
  'name' => '_card',
  'display' => 'block',
  'sub_fields' => $cardFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-card-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutCard', $cardLayout, 40);

$adwp->register_layout('card', $cardLayout);
