<?php

/**
 * ACF Fields for Related Thematique block layout
 *
 * Variante de "Related Content" dediee au CPT `thematique`.
 */

global $adwp, $adui_tokens;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="m260-520 220-360 220 360H260ZM700-80q-75 0-127.5-52.5T520-260q0-75 52.5-127.5T700-440q75 0 127.5 52.5T880-260q0 75-52.5 127.5T700-80Zm-580-20v-320h320v320H120Z"/></svg>';

$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$layoutNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['layout'] ?? []);

$colors = [];
foreach ($colorSystemModes as $mode) {
  foreach ($layoutNames as $layout) {
    $color = getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layout, $mode);
    $colors[$color] = $mode . '/' . $layout;
  }
}

$relatedThematiqueFields = [
  [
    'key' => 'field-related-thematique-theme',
    'label' => '',
    'name' => 'relatedThematique_theme',
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
    'key' => 'field-related-thematique-label',
    'label' => 'Label',
    'name' => 'relatedThematique_label',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Petit texte au-dessus du titre principal (ex : "Nos thematiques")',
  ],
  [
    'key' => 'field-related-thematique-title',
    'label' => 'Title',
    'name' => 'relatedThematique_title',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Titre principal de la section',
  ],
  [
    'key' => 'field-related-thematique-button',
    'label' => 'Button',
    'name' => 'relatedThematique_button',
    'type' => 'link',
    'return_format' => 'array',
    'required' => 0,
    'instructions' => 'Bouton call-to-action',
  ],
  [
    'key' => 'field-related-thematique-count',
    'label' => 'Nombre de thematiques',
    'name' => 'relatedThematique_count',
    'type' => 'number',
    'default_value' => 3,
    'min' => 1,
    'max' => 12,
    'required' => 0,
  ],
  [
    'key' => 'field-related-thematique-only-parents',
    'label' => 'Thematiques racines uniquement',
    'name' => 'relatedThematique_onlyParents',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'instructions' => 'Exclure les sous-thematiques',
  ],
  [
    'key' => 'field-related-thematique-orderby',
    'label' => 'Ordre',
    'name' => 'relatedThematique_orderby',
    'type' => 'select',
    'choices' => [
      'menu_order' => 'Ordre manuel',
      'title' => 'Titre (A-Z)',
      'date' => 'Plus recentes',
      'rand' => 'Aleatoire',
    ],
    'default_value' => 'menu_order',
    'required' => 0,
  ],
];

$relatedThematiqueLayout = [
  'key' => 'layout-relatedThematique',
  'label' => $icon . ' Related Thematique',
  'name' => 'relatedThematique',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('block-', $relatedThematiqueFields),
];

$adwp->add_block_layout('layoutRelatedThematique', $relatedThematiqueLayout, 99);
