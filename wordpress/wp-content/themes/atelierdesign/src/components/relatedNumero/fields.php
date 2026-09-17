<?php

/**
 * ACF Fields for Related Numero block layout
 *
 * Variante de "Related Content" dediee au CPT `numero`.
 * Les cartes affichent le numero (N°xx) a la place de la categorie.
 */

global $adwp, $adui_tokens;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="M300-240h60v-120h60v-60H300v-60h120v-60H300q-25 0-42.5 17.5T240-480v180q0 25 17.5 42.5T300-240Zm240 0h120q25 0 42.5-17.5T720-300v-360q0-25-17.5-42.5T660-720H540q-25 0-42.5 17.5T480-660v360q0 25 17.5 42.5T540-240Zm0-60v-360h120v360H540ZM160-120q-33 0-56.5-23.5T80-200v-560q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v560q0 33-23.5 56.5T800-120H160Z"/></svg>';

$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$layoutNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['layout'] ?? []);

$colors = [];
foreach ($colorSystemModes as $mode) {
  foreach ($layoutNames as $layout) {
    $color = getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layout, $mode);
    $colors[$color] = $mode . '/' . $layout;
  }
}

$relatedNumeroFields = [
  [
    'key' => 'field-related-numero-theme',
    'label' => '',
    'name' => 'relatedNumero_theme',
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
    'key' => 'field-related-numero-label',
    'label' => 'Label',
    'name' => 'relatedNumero_label',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Petit texte au-dessus du titre principal (ex : "Nos numeros")',
  ],
  [
    'key' => 'field-related-numero-title',
    'label' => 'Title',
    'name' => 'relatedNumero_title',
    'type' => 'text',
    'required' => 0,
    'instructions' => 'Titre principal de la section',
  ],
  [
    'key' => 'field-related-numero-button',
    'label' => 'Button',
    'name' => 'relatedNumero_button',
    'type' => 'link',
    'return_format' => 'array',
    'required' => 0,
    'instructions' => 'Bouton call-to-action',
  ],
  [
    'key' => 'field-related-numero-count',
    'label' => 'Nombre de numeros',
    'name' => 'relatedNumero_count',
    'type' => 'number',
    'default_value' => 3,
    'min' => 1,
    'max' => 12,
    'required' => 0,
  ],
  [
    'key' => 'field-related-numero-orderby',
    'label' => 'Ordre',
    'name' => 'relatedNumero_orderby',
    'type' => 'select',
    'choices' => [
      'date' => 'Plus recents',
      'date_asc' => 'Plus anciens',
      'title' => 'Titre (A-Z)',
      'rand' => 'Aleatoire',
    ],
    'default_value' => 'date',
    'required' => 0,
  ],
];

$relatedNumeroLayout = [
  'key' => 'layout-relatedNumero',
  'label' => $icon . ' Related Numero',
  'name' => 'relatedNumero',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('block-', $relatedNumeroFields),
];

$adwp->add_block_layout('layoutRelatedNumero', $relatedNumeroLayout, 99);
