<?php

/**
 * ACF Fields for Post (Enjeu) CPT
 */

global $adwp;

acf_add_local_field_group([
    'key' => 'group_post_template',
    'title' => 'Post Template',
    'fields' => [
        [
            'key' => 'custom-post-type-value-post',
            'label' => 'Custom Post Type',
            'name' => 'custom_post_type',
            'type' => 'acfe_hidden',
            'default_value' => 'post',
        ],
        // Hero Section
        [
            'key' => 'field_post_hero_tab',
            'label' => 'Hero',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field_post_clone_hero',
            'label' => 'Hero',
            'name' => 'hero',
            'type' => 'clone',
            'clone' => [
                0 => 'field-group-hero',
            ],
        ],
        [
            'key' => 'field_post_hero_image_layout',
            'label' => 'Image Layout',
            'name' => 'hero_image_layout',
            'type' => 'select',
            'choices' => [
                'full-width' => 'Full Width',
                'content' => 'Content Width',
            ],
            'default_value' => 'full-width',
            'instructions' => 'Choose how the background image should be displayed.',
        ],
        // Flexible Content Section
        [
            'key' => 'field_post_flexible_tab',
            'label' => 'Flexible Content',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field-post-flexible-layout',
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
        // Articles lies
        [
            'key' => 'field_post_related_tab',
            'label' => 'Articles liés',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field_post_related_mode',
            'label' => 'Articles liés',
            'name' => 'related_mode',
            'type' => 'button_group',
            'instructions' => 'Par défaut : les 3 derniers articles des mêmes thématiques. Personnalisé : choisir les articles. Désactivé : le bloc n\'est pas affiché.',
            'choices' => [
                'default'  => 'Par défaut',
                'custom'   => 'Personnalisé',
                'disabled' => 'Désactivé',
            ],
            'default_value' => 'default',
            'return_format' => 'value',
            'layout' => 'horizontal',
        ],
        [
            'key' => 'field_post_related_title',
            'label' => 'Titre du bloc',
            'name' => 'related_title',
            'type' => 'text',
            'placeholder' => 'À lire aussi',
            'conditional_logic' => [[['field' => 'field_post_related_mode', 'operator' => '!=', 'value' => 'disabled']]],
        ],
        [
            'key' => 'field_post_related_articles',
            'label' => 'Articles',
            'name' => 'related_articles',
            'type' => 'relationship',
            'instructions' => 'Ordre conservé.',
            'post_type' => [0 => 'post'],
            'taxonomy' => [],
            'filters' => [0 => 'search', 1 => 'taxonomy'],
            'return_format' => 'id',
            'conditional_logic' => [[['field' => 'field_post_related_mode', 'operator' => '==', 'value' => 'custom']]],
        ],
        // CTA Footer Section
        [
            'key' => 'field_post_cta_footer_tab',
            'label' => 'CTA Footer',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field_post_clone_cta_footer',
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
                'value' => 'post',
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