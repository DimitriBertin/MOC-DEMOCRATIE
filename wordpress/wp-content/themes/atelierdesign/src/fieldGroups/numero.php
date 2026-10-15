<?php

/**
 * ACF Fields for Numero CPT
 * Base identique aux Articles (post.php)
 */

global $adwp;

acf_add_local_field_group([
    'key' => 'group_numero_template',
    'title' => 'Numero Template',
    'fields' => [
        [
            'key' => 'custom-post-type-value-numero',
            'label' => 'Custom Post Type',
            'name' => 'custom_post_type',
            'type' => 'acfe_hidden',
            'default_value' => 'numero',
        ],
        // Hero Section
        [
            'key' => 'field_numero_info_tab',
            'label' => 'Information',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
          'key' => 'field_numero_numero',
          'label' => 'Numero de la revue',
          'name' => 'numero_numero',
          'type' => 'number',
          'instructions' => 'Numero de parution (ex : 142).',
          'required' => 0,
          'min' => 0,
          'step' => 1,
          'placeholder' => '142',
      ],
      [
          'key' => 'field_numero_date_parution',
          'label' => 'Date de parution',
          'name' => 'date_parution',
          'type' => 'date_picker',
          'instructions' => 'Mois de parution du numero.',
          'required' => 0,
          'display_format' => 'F Y',
          'return_format' => 'Y-m-d',
          'first_day' => 1,
      ],
      [
          'key' => 'field_numero_dans_ce_numero_court',
          'label' => 'Dans ce numero (version courte)',
          'name' => 'dans_ce_numero_court',
          'type' => 'textarea',
          'instructions' => 'Optionnel. Si vide, la version courte est generee automatiquement (500 signes max.) a partir du texte complet.',
          'required' => 0,
          'rows' => 5,
          'new_lines' => '',
      ],
      [
        'key' => 'field_numero_dans_ce_numero',
        'label' => 'Dans ce numero (texte complet)',
        'name' => 'dans_ce_numero',
        'type' => 'wysiwyg',
        'instructions' => 'Edito / presentation du numero. Sur la page de la revue, seule une version raccourcie (500 signes) est affichee, avec un lien pour lire le texte complet.',
        'required' => 0,
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 0,
        'delay' => 0,
    ],
      [
          'key' => 'field_numero_pdf',
          'label' => 'PDF du numero complet',
          'name' => 'pdf',
          'type' => 'file',
          'instructions' => 'Bouton "Telecharger le numero complet (PDF)".',
          'required' => 0,
          'return_format' => 'array',
          'library' => 'all',
          'mime_types' => 'pdf',
      ],
      [
          'key' => 'field_numero_liseuse',
          'label' => 'Lien vers la liseuse en ligne',
          'name' => 'liseuse',
          'type' => 'link',
          'instructions' => 'Lien vers la version feuilletable de la revue (Calameo, Issuu...). Sert aussi de lien par defaut pour "Lire l\'article et le telecharger en PDF" des articles du numero.',
          'required' => 0,
          'return_format' => 'array',
      ],

        // Flexible Content Section
        // [
        //     'key' => 'field_numero_flexible_tab',
        //     'label' => 'Flexible Content',
        //     'type' => 'tab',
        //     'no_preference' => 0,
        // ],
        // [
        //     'key' => 'field-numero-flexible-layout',
        //     'label' => 'Flexible Layout',
        //     'name' => 'flexible-layout',
        //     'type' => 'flexible_content',
        //     'acfe_flexible_async' => [
        //         0 => 'layout',
        //     ],
        //     'acfe_flexible_add_actions' => [
        //         0 => 'toggle',
        //         1 => 'copy',
        //     ],
        //     'min' => 0,
        //     'max' => '',
        //     'layouts' => $adwp->get_block_layouts(),
        //     'button_label' => 'Add section',
        //     'acfe_flexible_layouts_settings' => 1,
        // ],
        // Pour aller plus loin : articles connectes
        [
            'key' => 'field_numero_related_tab',
            'label' => 'Pour aller plus loin',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field_numero_numeros_lies',
            'label' => 'Numeros connectes',
            'name' => 'numeros_lies',
            'type' => 'relationship',
            'instructions' => 'Revues affichees en bas de la page, sous "Pour aller plus loin". Si vide, la page propose automatiquement les revues des memes thematiques (sinon les plus recentes). Ordre conserve.',
            'required' => 0,
            'post_type' => [0 => 'numero'],
            'taxonomy' => [],
            'filters' => [0 => 'search'],
            'min' => '',
            'max' => 6,
            'return_format' => 'id',
        ],
        // CTA Footer Section
        [
            'key' => 'field_numero_cta_footer_tab',
            'label' => 'CTA Footer',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field_numero_clone_cta_footer',
            'label' => 'CTA Footer',
            'name' => 'cta_footer',
            'type' => 'clone',
            'clone' => [
                0 => 'field-group-cta-footer',
            ],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'numero',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => [
        0 => 'the_content',
        1 => 'excerpt',
        2 => 'discussion',
        3 => 'comments',
        5 => 'slug',
        6 => 'author',
        10 => 'tags',
        11 => 'send-trackbacks',
    ],
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
]);
