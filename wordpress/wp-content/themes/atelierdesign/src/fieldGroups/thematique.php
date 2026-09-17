<?php

/**
 * ACF Fields for Thematique CPT
 * Base identique aux Articles (post.php)
 */

global $adwp;

acf_add_local_field_group([
    'key' => 'group_thematique_template',
    'title' => 'Thematique Template',
    'fields' => [
        [
            'key' => 'custom-post-type-value-thematique',
            'label' => 'Custom Post Type',
            'name' => 'custom_post_type',
            'type' => 'acfe_hidden',
            'default_value' => 'thematique',
        ],
        // Hero Section
        [
            'key' => 'field_thematique_hero_tab',
            'label' => 'Hero',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field_thematique_clone_hero',
            'label' => 'Hero',
            'name' => 'hero',
            'type' => 'clone',
            'clone' => [
                0 => 'field-group-hero',
            ],
        ],
        [
            'key' => 'field_thematique_hero_image_layout',
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
            'key' => 'field_thematique_flexible_tab',
            'label' => 'Flexible Content',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field-thematique-flexible-layout',
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
        // CTA Footer Section
        [
            'key' => 'field_thematique_cta_footer_tab',
            'label' => 'CTA Footer',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field_thematique_clone_cta_footer',
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
                'value' => 'thematique',
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
