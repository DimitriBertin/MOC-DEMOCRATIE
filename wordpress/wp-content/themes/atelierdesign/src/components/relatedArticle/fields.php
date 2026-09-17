<?php

/**
 * ACF Fields for Related Article block layout
 *
 * Variante de "Related Content" dediee au CPT natif `post` (Articles).
 */

global $adwp, $adui_tokens;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="M320-240h320v-80H320v80Zm0-160h320v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>';

// Collect color system mode names
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$layoutNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['layout'] ?? []);

$colors = [];
foreach ($colorSystemModes as $mode) {
  foreach ($layoutNames as $layout) {
    $color = getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layout, $mode);
    $colors[$color] = $mode . '/' . $layout;
  }
}

$relatedArticleFields = [
  [
    'key' => 'field-related-article-theme',
    'label' => '',
    'name' => 'relatedArticle_theme',
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
    'key' => 'field-related-article-label',
    'label' => 'Label',
    'name' => 'relatedArticle_label',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Petit texte au-dessus du titre principal (ex : "Nos articles")',
  ],
  [
    'key' => 'field-related-article-title',
    'label' => 'Title',
    'name' => 'relatedArticle_title',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Titre principal de la section',
  ],
  [
    'key' => 'field-related-article-button',
    'label' => 'Button',
    'name' => 'relatedArticle_button',
    'type' => 'link',
    'return_format' => 'array',
    'required' => 0,
    'instructions' => 'Bouton call-to-action',
  ],
  [
    'key' => 'field-related-article-count',
    'label' => 'Nombre d\'articles',
    'name' => 'relatedArticle_count',
    'type' => 'number',
    'default_value' => 3,
    'min' => 1,
    'max' => 12,
    'required' => 0,
    'instructions' => 'Nombre d\'articles affiches (3 par defaut)',
  ],
  [
    'key' => 'field-related-article-orderby',
    'label' => 'Ordre',
    'name' => 'relatedArticle_orderby',
    'type' => 'select',
    'choices' => [
      'date' => 'Plus recents',
      'date_asc' => 'Plus anciens',
      'title' => 'Titre (A-Z)',
      'rand' => 'Aleatoire',
      'menu_order' => 'Ordre manuel',
    ],
    'default_value' => 'date',
    'required' => 0,
  ],
];

$relatedArticleLayout = [
  'key' => 'layout-relatedArticle',
  'label' => $icon . ' Related Article',
  'name' => 'relatedArticle',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('block-', $relatedArticleFields),
];

$adwp->add_block_layout('layoutRelatedArticle', $relatedArticleLayout, 99);
