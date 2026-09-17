<?php

/**
 * ACF Fields for Slider Thematique block layout
 *
 * Slider alimente automatiquement par le CPT `thematique`
 * (pas de repeater : toutes les thematiques remontent).
 */

global $adwp, $adui_tokens;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="M240-160q-33 0-56.5-23.5T160-240v-480q0-33 23.5-56.5T240-800h480q33 0 56.5 23.5T800-720v480q0 33-23.5 56.5T720-160H240Zm0-80h480v-480H240v480ZM80-240v-480h80v480H80Zm720 0v-480h80v480h-80Z"/></svg>';

$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$layoutNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['layout'] ?? []);

$colors = [];
foreach ($colorSystemModes as $mode) {
  foreach ($layoutNames as $layout) {
    $color = getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layout, $mode);
    $colors[$color] = $mode . '/' . $layout;
  }
}

$sliderThematiqueFields = [
  [
    'key' => 'field-slider-thematique-theme',
    'label' => '',
    'name' => 'sliderThematique_theme',
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
    'key' => 'field-slider-thematique-label',
    'label' => 'Label',
    'name' => 'sliderThematique_label',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Petit texte au-dessus du titre (optionnel)',
  ],
  [
    'key' => 'field-slider-thematique-title',
    'label' => 'Title',
    'name' => 'sliderThematique_title',
    'type' => 'text',
    'required' => 0,
    'default_value' => 'Nos thematiques',
    'instructions' => 'Titre de la section (ex : "Nos thematiques")',
  ],
  [
    'key' => 'field-slider-thematique-only-parents',
    'label' => 'Thematiques racines uniquement',
    'name' => 'sliderThematique_onlyParents',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'instructions' => 'Exclure les sous-thematiques',
  ],
  [
    'key' => 'field-slider-thematique-orderby',
    'label' => 'Ordre',
    'name' => 'sliderThematique_orderby',
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

$sliderThematiqueLayout = [
  'key' => 'layout-sliderThematique',
  'label' => $icon . ' Slider Thematique',
  'name' => 'sliderThematique',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('block-', $sliderThematiqueFields),
];

$adwp->add_block_layout('layoutSliderThematique', $sliderThematiqueLayout, 99);
