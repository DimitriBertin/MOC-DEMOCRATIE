<?php

/**
 * ACF Fields for Slider block layout
 */

// Global variable coming from the AD UI plugin
global $adwp, $adui_tokens;

// Icon of the block
$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="m380-300 280-180-280-180v360ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg>';

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

// Fields of the "Slider" block
$sliderFields = [
  [
    'key' => 'field-slider-theme',
    'label' => '',
    'name' => 'slider_theme',
    'type' => 'color_picker',
    'required' => 1,
    'default_value' => $colors,
    'enable_opacity' => 0,
    'return_format' => 'label',
    'display' => 'palette',
    'color_picker' => 0,
    'allow_null' => 0,
    'theme_colors' => 0,
    'colors' => $colors,
    'button_label' => 'Select Theme',
    'absolute' => false,
    'input' => false,
  ],
  [
    'key' => 'field-slider-slides',
    'label' => 'Slides',
    'name' => 'slider_slides',
    'type' => 'repeater',
    'layout' => 'block',
    'button_label' => 'Add Slide',
    'sub_fields' => [
      [
        'key' => 'field-slider-slide-image',
        'label' => 'Image',
        'name' => 'image',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'thumbnail',
        'library' => 'all',
        'required' => 0,
      ],
      [
        'key' => 'field-slider-slide-title',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
        'required' => 0,
      ],
      [
        'key' => 'field-slider-slide-link',
        'label' => 'Link',
        'name' => 'link',
        'type' => 'link',
        'return_format' => 'array',
        'required' => 0,
      ],
    ],
  ],
];

// Adding the "Slider" to all the other flexible sections
$sliderLayout = [
  'key' => 'layout-slider',
  'label' => $icon . ' Slider',
  'name' => 'slider',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('block-', $sliderFields),
];

$adwp->add_block_layout('layoutSlider', $sliderLayout, 99);
