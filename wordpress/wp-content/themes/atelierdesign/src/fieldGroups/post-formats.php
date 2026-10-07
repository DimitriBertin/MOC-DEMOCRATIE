<?php

/**
 * ACF - Format d'un Article (Post)
 *
 * Permet d'afficher une icone "Podcast" et/ou "Debat" sur la carte
 * et l'en-tete de l'article. Voir ad_article_format_definitions()
 * dans src/functions/article-helpers.php pour ajouter un format.
 */

$choices = [];
foreach (ad_article_format_definitions() as $slug => $format) {
    $choices[$slug] = $format['label'];
}

acf_add_local_field_group([
    'key' => 'group_post_formats',
    'title' => 'Format du contenu',
    'fields' => [
        [
            'key' => 'field_post_formats',
            'label' => 'Icone(s) a afficher',
            'name' => 'formats',
            'type' => 'checkbox',
            'instructions' => 'Cocher si le contenu est un podcast et/ou un debat : une icone sera affichee sur la carte et en tete de l\'article.',
            'required' => 0,
            'choices' => $choices,
            'default_value' => [],
            'layout' => 'vertical',
            'toggle' => 0,
            'return_format' => 'value',
            'allow_custom' => 0,
            'save_custom' => 0,
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
    'menu_order' => 2,
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
]);
