<?php

/**
 * ACF fields for 'templates/home.php'
 */

global $adwp;

acf_add_local_field_group([
  'key' => 'field-group-template-home',
  'title' => 'Home Template',
  'fields' => [

  

    // Thematiques a la une
    [
      'key' => 'field-home-thematique-tab',
      'label' => 'Thematique',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-thematiques-featured',
      'label' => 'Thematiques a la une',
      'name' => 'thematiques_featured',
      'type' => 'relationship',
      'instructions' => 'Selectionner 4 thematiques a mettre en avant. L\'ordre de selection est conserve (glisser-deposer pour reordonner).',
      'required' => 0,
      'post_type' => ['thematique',],
      'taxonomy' => [],
      'filters' => [
        0 => 'search',
      ],
      'elements' => [],
      'min' => 4,
      'max' => 4,
      'return_format' => 'object',
      'bidirectional' => 0,
    ],

    [
      'key' => 'field-home-inscription-tab',
      'label' => 'Inscription',
      'type' => 'tab',
      'no_preference' => 0,
    ],

    [
      'key' => 'field-home-press-review-group',
      'label' => 'Inscription',
      'type' => 'group',
      'name' => 'press-review',
      'sub_fields' => [
        [
          'key' => 'field-home-thematiques-other',
          'label' => 'Autres Thematiques',
          'name' => 'thematiques_other',
          'type' => 'relationship',
          'instructions' => 'Selectionner 2 thematiques a mettre en avant. L\'ordre de selection est conserve (glisser-deposer pour reordonner).',
          'required' => 0,
          'post_type' => ['post'],
          'taxonomy' => [],
          'filters' => [
            0 => 'search',
          ],
          'elements' => [],
          'min' => 2,
          'max' => 2,
          'return_format' => 'object',
          'bidirectional' => 0,
        ],
        [
          'key' => 'field-home-press-review-label-bloc',
          'label' => 'Label',
          'type' => 'text',
          'name' => 'text'
        ],
        [
          'key' => 'field-home-press-review-link-bloc',
          'label' => 'CTA lien',
          'type' => 'link',
          'name' => 'link',
        ],
      ]
    ],

    

    // Flexible Content 1 Section (after Services Cards)
    [
      'key' => 'field-home-flexible-tab',
      'label' => 'Flexible Content',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-flexible-layout',
      'label' => 'Flexible Layout',
      'name' => 'flexible-layout',
      'type' => 'flexible_content',
      'acfe_flexible_async' => [
        0 => 'layout',
      ],
      'acfe_flexible_add_actions' => [
        0 => 'toggle',
        1 => 'copy',
      ],
      'min' => 0,
      'max' => '',
      'layouts' => $adwp->get_block_layouts(),
      'button_label' => 'Add section',
      'acfe_flexible_layouts_settings' => 1,
    ],

   
  ],
  'location' => [
    [
      [
        'param' => 'page_template',
        'operator' => '==',
        'value' => 'templates/home.php',
      ],
    ],
  ],
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'seamless',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'active' => 1,
  'hide_on_screen' => [
    0 => 'the_content',
    1 => 'excerpt',
    2 => 'discussion',
    3 => 'comments',
    // 4 => 'revisions',
    5 => 'slug',
    6 => 'author',
    // 7 => 'format',
    8 => 'featured_image',
    9 => 'categories',
    10 => 'tags',
    11 => 'send-trackbacks',
  ],
]);
