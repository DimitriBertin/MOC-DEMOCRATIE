<?php

/**
 * ACF Fields for Job CPT
 */

global $adwp;

acf_add_local_field_group([
    'key' => 'group_job_template',
    'title' => 'Job Template',
    'fields' => [
        [
            'key' => 'custom-post-type-value-job',
            'label' => 'Custom Post Type',
            'name' => 'custom_post_type',
            'type' => 'acfe_hidden',
            'default_value' => 'job',
        ],
        // Hero Section
        [
            'key' => 'field_job_hero_tab',
            'label' => 'Hero',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field_job_clone_hero',
            'label' => 'Hero',
            'name' => 'hero',
            'type' => 'clone',
            'clone' => [
                0 => 'field-group-hero',
            ],
        ],
        // Flexible Content Section
        [
            'key' => 'field_job_flexible_tab',
            'label' => 'Flexible Content',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field-job-flexible-layout',
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
            'key' => 'field_job_cta_footer_tab',
            'label' => 'CTA Footer',
            'type' => 'tab',
            'no_preference' => 0,
        ],
        [
            'key' => 'field_job_clone_cta_footer',
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
                'value' => 'job',
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
        8 => 'featured_image',
        10 => 'tags',
        11 => 'send-trackbacks',
    ],
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
]);

acf_add_local_field_group([
    'key' => 'group_job_details',
    'title' => 'Détails de l\'emploi',
    'fields' => [
        [
            'key' => 'field_job_federation',
            'label' => 'Fédération',
            'name' => 'federation',
            'type' => 'text',
            'instructions' => 'Nom de la fédération ou organisation.',
            'required' => 0,
        ],
        [
            'key' => 'field_job_description',
            'label' => 'Description',
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => 'Ajoutez une description pour la preview de l\'emploi.',
            'maxlength' => 200,
            'rows' => 3,
            'new_lines' => 'no_format',
        ]
    ],
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'job',
            ],
        ],
    ],
    'menu_order' => 1,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
]);