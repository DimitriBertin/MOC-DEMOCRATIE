<?php

/**
 * ACF Relations for Auteur CPT
 *
 * RELATION BIDIRECTIONNELLE (ACF Pro 6.2+) :
 *  - Auteur.articles <-> Article.auteurs   (post-relations.php)
 */

acf_add_local_field_group([
    'key' => 'group_auteur_relations',
    'title' => 'Associations',
    'fields' => [
        [
            'key' => 'field_auteur_rel_articles',
            'label' => 'Articles',
            'name' => 'articles',
            'type' => 'relationship',
            'instructions' => 'Articles signes par cette personne. Ajouter un article ici renseigne automatiquement le champ "Auteur-rice(s)" de cet article.',
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
            // Relation bidirectionnelle vers Article.auteurs
            'bidirectional' => 1,
            'bidirectional_target' => [
                0 => 'field_post_rel_auteurs',
            ],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'auteur',
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
