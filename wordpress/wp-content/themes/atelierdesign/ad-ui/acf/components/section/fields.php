<?php

/**
 * ACF Fields for Section
 */

// $icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="16" viewBox="0 0 24 24" width="16"><path d="M3,3v18h18V3H3z M14,17H7v-2h7V17z M17,13H7v-2h10V13z M17,9H7V7h10V9z" fill="currentColor" /></svg>';
$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960" width="16" height="16"><path d="M80-160v-640h800v640H80Zm80-80h640v-480H160v480Zm0 0v-480 480Z"/></svg>';

global $adwp, $adui_tokens;

// Collect color system mode names
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$layoutNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['layout'] ?? []);

// Build a palette mapping hex => "{mode}/main" and a sensible default
$colors = [];
foreach ($colorSystemModes as $mode) {
  foreach ($layoutNames as $layout) {
    $color = getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layout, $mode);
    $colors[$color] = $mode . '/' . $layout;
  }
}

$sectionFields = [
  [
    'key' => 'field-section-color',
    'label' => '',
    'name' => 'section_color',
    'type' => 'color_picker',
    'required' => 1,
    'default_value' => getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layoutNames[0], $colorSystemModes[0]),
    'enable_opacity' => 0,
    'return_format' => 'label',
    'display' => 'palette',
    'color_picker' => 0,
    'allow_null' => 0,
    'theme_colors' => 0,
    'colors' => $colors,
    'button_label' => 'Select Color',
    'absolute' => false,
    'input' => false,
  ],
  [
    'key' => 'field-section-flexible',
    'label' => '',
    'name' => 'section',
    'type' => 'flexible_content',
    'acfe_flexible_async' => [
      0 => 'layout',
    ],
    'acfe_flexible_add_actions' => [
      0 => 'toggle',
      1 => 'copy',
    ],
    'layouts' => $adwp->get_inline_layouts(),
    'min' => 1,
    'max' => '',
    'button_label' => 'Add item',
    'acfe_flexible_layouts_settings' => 1,
  ],
];

$sectionLayout = [
  'key' => 'layout-section',
  'label' => $icon . ' Section',
  'name' => 'section',
  'display' => 'block',
  'sub_fields' => $sectionFields,
];

$adwp->add_block_layout('layoutSection', $sectionLayout, 10);

$adwp->register_layout('section', $sectionLayout);
