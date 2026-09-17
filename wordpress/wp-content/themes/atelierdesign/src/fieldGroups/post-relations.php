<?php

/**
 * ACF Relations for Post (Article) CPT
 *
 * Champs de liaison entre un Article et :
 *  - une ou plusieurs Thematiques (CPT hierarchique)
 *  - un ou plusieurs Auteur-rices
 *  - un Numero
 *
 * Volontairement separe de post.php pour garder les relations isolees
 * du template (hero / flexible content / cta footer).
 */

acf_add_local_field_group([
    'key' => 'group_post_relations',
    'title' => 'Associations',
    'fields' => [
        [
            'key' => 'field_post_rel_thematiques',
            'label' => 'Thematiques',
            'name' => 'thematiques',
            'type' => 'relationship',
            'instructions' => 'Associer une ou plusieurs thematiques (ou sous-thematiques) a cet article.',
            'required' => 0,
            'post_type' => [
                0 => 'thematique',
            ],
            'taxonomy' => [],
            'filters' => [
                0 => 'search',
            ],
            'min' => '',
            'max' => '',
            'return_format' => 'object',
            'bidirectional' => 0,
        ],
        [
            'key' => 'field_post_rel_auteurs',
            'label' => 'Auteur-rice(s)',
            'name' => 'auteurs',
            'type' => 'relationship',
            'instructions' => 'Associer un ou plusieurs auteur-rices a cet article.',
            'required' => 0,
            'post_type' => [
                0 => 'auteur',
            ],
            'taxonomy' => [],
            'filters' => [
                0 => 'search',
            ],

            'min' => '',
            'max' => '',
            'return_format' => 'object',
            'bidirectional' => 0,
        ],
        [
            'key' => 'field_post_rel_numero',
            'label' => 'Numero',
            'name' => 'numero',
            'type' => 'post_object',
            'instructions' => 'Numero dans lequel cet article est publie.',
            'required' => 0,
            'post_type' => [
                0 => 'numero',
            ],
            'taxonomy' => [],
            'allow_null' => 1,
            'multiple' => 0,
            'ui' => 1,
            'return_format' => 'object',
            'bidirectional' => 0,
        ],
    ],
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ],
        ],
    ],
    'menu_order' => 1,
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
]);
