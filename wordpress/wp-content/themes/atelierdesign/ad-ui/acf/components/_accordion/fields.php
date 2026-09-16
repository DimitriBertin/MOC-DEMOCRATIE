<?php

/**
 * ACF Fields for Accordion
 */

global $adwp, $adui_tokens;

// Collect color variant names, and style names
$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$styleNames = array_keys($adui_tokens['responsiveSizing'][$responsiveSizingModes[0]]['accordion'] ?? []);
$colorNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['accordion'] ?? []);

// Build an array of choices for the color and style fields
$colorChoices = [];
$styleChoices = [];
foreach ($colorNames as $colorName) {
  $colorChoices[$colorName] = toTitleCase($colorName);
}
foreach ($styleNames as $styleName) {
  $styleChoices[$styleName] = toTitleCase($styleName);
}

$accordionSettingsFields = [
  [
    'key' => 'field-accordion-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
];

// From nested version of the layout, remove the wrapper width settings
$accordionNestedSettingsFields = $accordionSettingsFields;
unset($accordionNestedSettingsFields[0]);

add_action(
  'acf/include_fields',
  static function () use ($accordionSettingsFields, $accordionNestedSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-accordion-settings',
      'title' => 'Accordion settings',
      'fields' => $accordionSettingsFields,
    ]);

    acf_add_local_field_group([
      'key' => 'field-group-accordion-nested-settings',
      'title' => 'Accordion Nested Settings',
      'fields' => $accordionNestedSettingsFields,
    ]);
  },
  40
);

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16" fill="currentColor"><path d="M200-200v-160 4-4 160Zm-80 80v-320h720v80H200v160h400v80H120Zm0-400v-320h720v320H120Zm80-80h560v-160H200v160Zm0 0v-160 160Zm560 480h-80v-80h80v-80h80v80h80v80h-80v80h-80v-80Z"/></svg>';

$accordionFields = [
  [
    'key' => 'field-accordion-color',
    'label' => 'Color',
    'name' => '_accordion_color',
    'type' => 'radio',
    'required' => 1,
    'choices' => $colorChoices,
    'layout' => 'horizontal',
    'return_format' => 'value',
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-accordion-style',
    'label' => 'Style',
    'name' => '_accordion_style',
    'type' => 'radio',
    'required' => 1,
    'choices' => $styleChoices,
    'layout' => 'horizontal',
    'return_format' => 'value',
    'wrapper' => [
      'width' => '50%',
    ],
  ],
  [
    'key' => 'field-accordion-title',
    'label' => 'Title',
    'name' => '_accordion_title',
    'type' => 'text',
    'required' => 1,
  ],
  [
    'key' => 'field-accordion-content',
    'label' => 'Content',
    'name' => '_accordion_content',
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

$accordionLayout = [
  'key' => 'layout-accordion',
  'label' => $icon . ' Accordion',
  'name' => '_accordion',
  'display' => 'block',
  'sub_fields' => $accordionFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-accordion-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_inline_layout('layoutAccordion', $accordionLayout, 40);

// Nested layout variant (no full-width setting)
$nestedAccordionLayout = [
  'key' => 'layout-nestedAccordion',
  'label' => $icon . ' Accordion',
  'name' => '_accordion',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $accordionFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-accordion-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_nested_layout('layoutNestedAccordion', $nestedAccordionLayout, 40);

$adwp->register_layout('accordion', $nestedAccordionLayout);
