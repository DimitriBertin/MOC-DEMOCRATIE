<?php

/**
 * ACF Fields for Related Content block layout
 */

// Global variable coming from the AD UI plugin
global $adwp, $adui_tokens;

// Icon of the block (usually coming from Google's Material Symbols)
// Important to keep 
// - `style="vertical-align: bottom;"` to avoid the icon from being too high
// - `width="16" height="16"` to avoid the icon from being too large
// - `fill="currentColor"` to colorize it the same as other icons in ACF
$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="M320-240h320v-80H320v80Zm0-160h320v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>';

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

// Fields of the "Related Content" block
$relatedContentFields = [
  [
    'key' => 'field-related-content-theme',
    'label' => '',
    'name' => 'relatedContent_theme', // IMPORTANT: The field names must be prefixed with the same name as the layout, and how they what the folder is called in the `components` directory
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
    'key' => 'field-related-content-label',
    'label' => 'Label',
    'name' => 'relatedContent_label',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Small text above the main title (e.g., "Nos publications")',
  ],
  [
    'key' => 'field-related-content-title',
    'label' => 'Title',
    'name' => 'relatedContent_title',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Main section title',
  ],
  [
    'key' => 'field-related-content-button',
    'label' => 'Button',
    'name' => 'relatedContent_button',
    'type' => 'link',
    'return_format' => 'array',
    'required' => 0,
    'instructions' => 'Call-to-action button',
  ],
  [
    'key' => 'field-related-content-post-type',
    'label' => 'Post Type',
    'name' => 'relatedContent_postType',
    'type' => 'select',
    'choices' => [
      'post' => 'Enjeu',
      'document' => 'Document',
      'evenement' => 'Événement',
      'campagne' => 'Campagne',
    ],
    'default_value' => 'post',
    'required' => 1,
    'instructions' => 'Select the type of content to display',
  ],
  [
    'key' => 'field-related-content-type-enjeu',
    'label' => 'Choisir type',
    'name' => 'relatedContent_typeEnjeu',
    'type' => 'taxonomy',
    'taxonomy' => 'type_enjeu',
    'field_type' => 'select',
    'allow_null' => 1,
    'instructions' => '(Optionnel) Select a specific type for Enjeu',
    'conditional_logic' => [
      [
        [
          'field' => 'block-field-related-content-post-type',
          'operator' => '==',
          'value' => 'post',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-related-content-type-document',
    'label' => 'Choisir type',
    'name' => 'relatedContent_typeDocument',
    'type' => 'taxonomy',
    'taxonomy' => 'type_document',
    'field_type' => 'select',
    'allow_null' => 1,
    'instructions' => '(Optionnel) Select a specific type for Document',
    'conditional_logic' => [
      [
        [
          'field' => 'block-field-related-content-post-type',
          'operator' => '==',
          'value' => 'document',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-related-content-category-filter-enjeu',
    'label' => 'Choisir catégorie',
    'name' => 'relatedContent_categoryFilterEnjeu',
    'type' => 'taxonomy',
    'taxonomy' => 'category_enjeu',
    'field_type' => 'select',
    'allow_null' => 1,
    'instructions' => '(Optionnel) Select a specific category for Enjeu',
    'conditional_logic' => [
      [
        [
          'field' => 'block-field-related-content-post-type',
          'operator' => '==',
          'value' => 'post',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-related-content-category-filter-document',
    'label' => 'Choisir catégorie',
    'name' => 'relatedContent_categoryFilterDocument',
    'type' => 'taxonomy',
    'taxonomy' => 'category_document',
    'field_type' => 'select',
    'allow_null' => 1,
    'instructions' => '(Optionnel) Select a specific category for Document',
    'conditional_logic' => [
      [
        [
          'field' => 'block-field-related-content-post-type',
          'operator' => '==',
          'value' => 'document',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-related-content-category-filter-evenement',
    'label' => 'Choisir catégorie',
    'name' => 'relatedContent_categoryFilterEvenement',
    'type' => 'taxonomy',
    'taxonomy' => 'category_evenement',
    'field_type' => 'select',
    'allow_null' => 1,
    'instructions' => '(Optionnel) Select a specific category for Événement',
    'conditional_logic' => [
      [
        [
          'field' => 'block-field-related-content-post-type',
          'operator' => '==',
          'value' => 'evenement',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-related-content-category-filter-campagne',
    'label' => 'Choisir catégorie',
    'name' => 'relatedContent_categoryFilterCampagne',
    'type' => 'taxonomy',
    'taxonomy' => 'category_campagne',
    'field_type' => 'select',
    'allow_null' => 1,
    'instructions' => '(Optionnel) Select a specific category for Campagne',
    'conditional_logic' => [
      [
        [
          'field' => 'block-field-related-content-post-type',
          'operator' => '==',
          'value' => 'campagne',
        ],
      ],
    ],
  ],
  [
    'key' => 'field-related-content-theme-filter',
    'label' => 'Choisir thème',
    'name' => 'relatedContent_themeFilter',
    'type' => 'taxonomy',
    'taxonomy' => 'theme',
    'field_type' => 'select',
    'allow_null' => 1,
    'instructions' => '(Optionnel) Select a specific theme (for Post/Document only)',
    'conditional_logic' => [
      [
        [
          'field' => 'block-field-related-content-post-type',
          'operator' => '==',
          'value' => 'post',
        ],
      ],
      [
        [
          'field' => 'block-field-related-content-post-type',
          'operator' => '==',
          'value' => 'document',
        ],
      ],
    ],
  ],
];

// Adding the "Related Content" to all the other flexible sections, with the $adwp->add_block_layout() function
$relatedContentLayout = [
  'key' => 'layout-relatedContent',
  'label' => $icon . ' Related Content',
  'name' => 'relatedContent',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('block-', $relatedContentFields),
];

$adwp->add_block_layout('layoutRelatedContent', $relatedContentLayout, 99);
