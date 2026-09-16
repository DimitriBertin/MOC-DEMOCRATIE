<?php

/**
 * ACF Fields for Numbers & Images block layout
 */

// Global variable coming from the AD UI plugin
global $adwp, $adui_tokens;

// Icon of the block
$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm80-80h400v-80H280v80Zm0-160h400v-80H280v80Zm0-160h400v-80H280v80Zm-80 400v-560 560Z"/></svg>';

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

// Fields of the "Numbers & Images" block
$numbersImagesFields = [
  [
    'key' => 'field-numbers-images-theme',
    'label' => '',
    'name' => 'numbersImages_theme',
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
    'key' => 'field-numbers-images-label',
    'label' => 'Label',
    'name' => 'numbersImages_label',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Small text above the main title',
  ],
  [
    'key' => 'field-numbers-images-title',
    'label' => 'Title',
    'name' => 'numbersImages_title',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Main section title',
  ],
  [
    'key' => 'field-numbers-images-group',
    'label' => 'Content Items',
    'name' => 'numbersImages_group',
    'type' => 'group',
    'layout' => 'block',
    'sub_fields' => [
      [
        'key' => 'field-numbers-images-image1',
        'label' => 'Image 1',
        'name' => 'image1',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ],
      [
        'key' => 'field-numbers-images-title1',
        'label' => 'Title 1',
        'name' => 'title1',
        'type' => 'text',
      ],
      [
        'key' => 'field-numbers-images-text1',
        'label' => 'Text 1',
        'name' => 'text1',
        'type' => 'textarea',
        'rows' => 3,
      ],
      [
        'key' => 'field-numbers-images-image2',
        'label' => 'Image 2',
        'name' => 'image2',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ],
      [
        'key' => 'field-numbers-images-title2',
        'label' => 'Title 2',
        'name' => 'title2',
        'type' => 'text',
      ],
      [
        'key' => 'field-numbers-images-text2',
        'label' => 'Text 2',
        'name' => 'text2',
        'type' => 'textarea',
        'rows' => 3,
      ],
      [
        'key' => 'field-numbers-images-image3',
        'label' => 'Image 3',
        'name' => 'image3',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'thumbnail',
        'library' => 'all',
      ],
      [
        'key' => 'field-numbers-images-title3',
        'label' => 'Title 3',
        'name' => 'title3',
        'type' => 'text',
      ],
      [
        'key' => 'field-numbers-images-text3',
        'label' => 'Text 3',
        'name' => 'text3',
        'type' => 'textarea',
        'rows' => 3,
      ],
      [
        'key' => 'field-numbers-images-title4',
        'label' => 'Title 4',
        'name' => 'title4',
        'type' => 'text',
      ],
      [
        'key' => 'field-numbers-images-text4',
        'label' => 'Text 4',
        'name' => 'text4',
        'type' => 'textarea',
        'rows' => 3,
      ],
    ],
  ],
];

// Adding the "Numbers & Images" to all the other flexible sections
$numbersImagesLayout = [
  'key' => 'layout-numbersImages',
  'label' => $icon . ' Numbers & Images',
  'name' => 'numbersImages',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('block-', $numbersImagesFields),
];

$adwp->add_block_layout('layoutNumbersImages', $numbersImagesLayout, 99);
