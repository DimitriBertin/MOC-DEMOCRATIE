<?php

/**
 * ACF Relations for Numero (Revue) CPT
 *
 * Champ de liaison entre un Numero et ses Articles.
 *
 * RELATION BIDIRECTIONNELLE (ACF Pro 6.2+) :
 *  - Numero.articles  <->  Article.numero
 *  Ajouter un article dans le champ "Articles" du numero renseigne
 *  automatiquement le champ "Numero" de cet article, et inversement.
 *
 * Les deux champs doivent se cibler mutuellement via 'bidirectional_target'.
 * Voir aussi : src/fieldGroups/post-relations.php (field_post_rel_numero)
 */

acf_add_local_field_group([
    'key' => 'group_numero_relations',
    'title' => 'Associations',
    'fields' => [
        [
            'key' => 'field_numero_rel_articles',
            'label' => 'Articles du numero',
            'name' => 'articles',
            'type' => 'relationship',
            'instructions' => 'Articles composant le sommaire de ce numero. Ajouter un article ici renseigne automatiquement le champ "Numero" de cet article.',
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
            // Relation bidirectionnelle vers Article.numero
            'bidirectional' => 1,
            'bidirectional_target' => [
                0 => 'field_post_rel_numero',
            ],
        ],
        [
            'key' => 'field_numero_rel_thematiques',
            'label' => 'Thematique(s)',
            'name' => 'thematiques',
            'type' => 'relationship',
            'instructions' => 'Thematique(s) generale(s) de ce numero.',
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
            // Relation bidirectionnelle vers Thematique.numeros
            // Voir : src/fieldGroups/thematique-relations.php (field_thematique_rel_numeros)
            'bidirectional' => 1,
            'bidirectional_target' => [
                0 => 'field_thematique_rel_numeros',
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
    'menu_order' => 1,
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
]);
