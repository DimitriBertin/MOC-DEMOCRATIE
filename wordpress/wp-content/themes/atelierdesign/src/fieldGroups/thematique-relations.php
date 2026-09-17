<?php

/**
 * ACF Relations for Thematique CPT
 *
 * RELATIONS BIDIRECTIONNELLES (ACF Pro 6.2+) :
 *  - Thematique.articles <-> Article.thematiques   (post-relations.php)
 *  - Thematique.numeros  <-> Numero.thematiques    (numero-relations.php)
 *
 * Chaque paire de champs doit se cibler mutuellement via 'bidirectional_target'.
 */

acf_add_local_field_group([
    'key' => 'group_thematique_relations',
    'title' => 'Associations',
    'fields' => [
        [
            'key' => 'field_thematique_rel_articles',
            'label' => 'Articles',
            'name' => 'articles',
            'type' => 'relationship',
            'instructions' => 'Articles associes a cette thematique. Ajouter un article ici renseigne automatiquement le champ "Thematiques" de cet article.',
            'required' => 0,
            'post_type' => [
                0 => 'post',
            ],
            'taxonomy' => [],
            'filters' => [
                0 => 'search',
                1 => 'taxonomy',
            ],
            'min' => '',
            'max' => '',
            'return_format' => 'object',
            // Relation bidirectionnelle vers Article.thematiques
            'bidirectional' => 1,
            'bidirectional_target' => [
                0 => 'field_post_rel_thematiques',
            ],
        ],
        [
            'key' => 'field_thematique_rel_numeros',
            'label' => 'Revues',
            'name' => 'numeros',
            'type' => 'relationship',
            'instructions' => 'Numeros de la revue portant sur cette thematique.',
            'required' => 0,
            'post_type' => [
                0 => 'numero',
            ],
            'taxonomy' => [],
            'filters' => [
                0 => 'search',
            ],
            'min' => '',
            'max' => '',
            'return_format' => 'object',
            // Relation bidirectionnelle vers Numero.thematiques
            'bidirectional' => 1,
            'bidirectional_target' => [
                0 => 'field_numero_rel_thematiques',
            ],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'thematique',
            ],
        ],
    ],
    'menu_order' => 1,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
]);
