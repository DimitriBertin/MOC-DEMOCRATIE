<?php

/**
 * ACF fields for 'templates/contact.php'
 */

global $adwp;

acf_add_local_field_group([
  'key' => 'field-group-template-contact',
  'title' => 'Contact Template',
  'fields' => [
    // Hero section
    [
      'key' => 'field-contact-hero-tab',
      'label' => 'Hero',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
        'key' => 'field_page_clone_hero',
        'label' => 'Hero',
        'name' => 'hero',
        'type' => 'clone',
        'clone' => [
            0 => 'field-group-hero',
        ],
    ],
    [
        'key' => 'field_page_hero_text_alignment',
        'label' => 'Text Alignment',
        'name' => 'hero_text_alignment',
        'type' => 'select',
        'choices' => [
            'center' => 'Center',
            'left' => 'Left',
        ],
        'default_value' => 'center',
        'instructions' => 'Choose text alignment for the hero section.',
    ],

    // Contact section
    [
      'key' => 'field-contact-contact-tab',
      'label' => 'Contact',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-contact-title',
      'label' => 'Title Contact',
      'name' => 'contact_title',
      'type' => 'text',
      'required' => 0,
      'default_value' => 'Secrétariat Général',
    ],
    [
      'key' => 'field-contact-information',
      'label' => 'Contact Information',
      'name' => 'contact_information',
      'type' => 'repeater',
      'required' => 0,
      'layout' => 'table',
      'button_label' => 'Add Contact Info',
      'sub_fields' => [
        [
          'key' => 'field-contact-info-title',
          'label' => 'Title',
          'name' => 'title',
          'type' => 'text',
          'required' => 1,
        ],
        [
          'key' => 'field-contact-info-value',
          'label' => 'Value',
          'name' => 'value',
          'type' => 'wysiwyg',
          'required' => 0,
          'toolbar' => 'basic',
          'media_upload' => 0,
        ],
      ],
    ],
    [
      'key' => 'field-contact-map',
      'label' => 'Map',
      'name' => 'map',
      'type' => 'google_map',
      'required' => 0,
      'center_lat' => '50.8503',
      'center_lng' => '4.3517',
      'zoom' => 12,
    ],

    // Other contacts section
    [
      'key' => 'field-contact-other-contacts-tab',
      'label' => 'Other Contacts',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    
    // Contact groups repeater (replaces contact_service and contact_federations)
    [
      'key' => 'field-contact-groups',
      'label' => 'Contact Groups',
      'name' => 'contact_groups',
      'type' => 'repeater',
      'required' => 0,
      'layout' => 'block',
      'button_label' => 'Add Contact Group',
      'sub_fields' => [
        [
          'key' => 'field-contact-group-title',
          'label' => 'Group Title',
          'name' => 'title',
          'type' => 'text',
          'required' => 1,
          'placeholder' => 'e.g., Contact service, Contact fédérations, etc.',
        ],
        [
          'key' => 'field-contact-group-items',
          'label' => 'Contact Items',
          'name' => 'items',
          'type' => 'repeater',
          'required' => 0,
          'layout' => 'block',
          'button_label' => 'Add Contact Item',
          'sub_fields' => [
            [
              'key' => 'field-contact-item-name',
              'label' => 'Name',
              'name' => 'name',
              'type' => 'text',
              'required' => 1,
              'placeholder' => 'e.g., Service name, Federation name, etc.',
            ],
            [
              'key' => 'field-contact-item-informations',
              'label' => 'Contact Information',
              'name' => 'informations',
              'type' => 'repeater',
              'required' => 0,
              'layout' => 'table',
              'button_label' => 'Add Information',
              'sub_fields' => [
                [
                  'key' => 'field-contact-item-info-title',
                  'label' => 'Title',
                  'name' => 'title',
                  'type' => 'text',
                  'required' => 1,
                  'placeholder' => 'e.g., Email, Phone, Address, etc.',
                ],
                [
                  'key' => 'field-contact-item-info-value',
                  'label' => 'Value',
                  'name' => 'value',
                  'type' => 'wysiwyg',
                  'required' => 0,
                  'toolbar' => 'basic',
                  'media_upload' => 0,
                ],
              ],
            ],
          ],
        ],
      ],
    ],

    // Jobs section (placeholder for future)
    [
      'key' => 'field-contact-jobs-tab',
      'label' => 'Jobs',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-contact-jobs-title',
      'label' => 'Title Jobs',
      'name' => 'jobs_title',
      'type' => 'text',
      'required' => 0,
      'default_value' => "Offres d'emplois",
    ],
  ],
  'location' => [
    [
      [
        'param' => 'page_template',
        'operator' => '==',
        'value' => 'templates/contact.php',
      ],
    ],
  ],
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'seamless',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'active' => 1,
  'hide_on_screen' => [
    0 => 'the_content',
    1 => 'excerpt',
    2 => 'discussion',
    3 => 'comments',
    5 => 'slug',
    6 => 'author',
    8 => 'featured_image',
    9 => 'categories',
    10 => 'tags',
    11 => 'send-trackbacks',
  ],
]);
