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
          'key' => 'field_numero_image_longue',
          'label' => 'Image longue (bandeau)',
          'name' => 'image_longue',
          'type' => 'image',
          'instructions' => 'Image principale de la revue : utilisee sur la page /revues/, la page de la revue et les autres listes. L\'image mise en avant (couverture) ne sert que pour la carte de la home.',
          'required' => 0,
          'return_format' => 'array',
          'preview_size' => 'medium',
          'library' => 'all',
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

        // Onglet Sommaire : texte "Dans ce numero" compose en flexible (Wysiwyg / Quote / Separateur)
        [
            'key' => 'field_numero_sommaire_tab',
            'label' => 'Dans ce numéro',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field_numero_sommaire_flexible',
            'label' => 'Dans ce numéro',
            'name' => 'sommaire_flexible',
            'type' => 'flexible_content',
            'instructions' => 'Texte, citation ou séparateur. Sur la page, un extrait de 500 signes est affiché avec un bouton "Lire plus" qui déplie le contenu complet.',
            'acfe_flexible_async' => [0 => 'layout'],
            'acfe_flexible_add_actions' => [0 => 'toggle', 1 => 'copy'],
            'acfe_flexible_layouts_settings' => 1,
            'min' => 0,
            'max' => '',
            'layouts' => array_values(array_filter(
                $adwp->get_nested_layouts(),
                function ($layout) {
                    return in_array($layout['name'] ?? '', ['_wysiwyg', '_quote', '_separator'], true);
                }
            )),
            'button_label' => 'Ajouter un element',
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
            'instructions' => 'Choisir les revues affichees sous "Pour aller plus loin" (colonne de droite de la page). Rien n\'est affiche si aucune revue n\'est choisie. Ordre conserve.',
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
