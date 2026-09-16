<?php

/**
 * ACF fields for 'templates/home.php'
 */

global $adwp;

acf_add_local_field_group([
  'key' => 'field-group-template-home',
  'title' => 'Home Template',
  'fields' => [
    // Hero section
    [
      'key' => 'field-home-hero-tab',
      'label' => 'Hero',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-group-hero',
      'label' => '',
      'name' => 'hero',
      'type' => 'group',
      'sub_fields' => [
        [
          'key' => 'field-home-clone-fieldgroup-hero',
          'label' => 'Hero',
          'name' => 'hero',
          'type' => 'clone',
          'clone' => [
            0 => 'field-group-hero',
          ],
        ],
      ],
    ],

    // Services Cards section
    [
      'key' => 'field-home-services-cards-tab',
      'label' => 'Services Cards',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-group-services-cards',
      'label' => '',
      'name' => 'services_cards',
      'type' => 'group',
      'sub_fields' => [
        [
          'key' => 'field-home-clone-services-cards',
          'label' => 'Services Cards',
          'name' => 'cards_group',
          'type' => 'clone',
          'clone' => [
            0 => 'field-group-services-cards',
          ],
          'display' => 'seamless',
          'layout' => 'block',
        ],
      ],
    ],

    // Flexible Content 1 Section (after Services Cards)
    [
      'key' => 'field-home-flexible-1-tab',
      'label' => 'Flexible Content 1',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-flexible-layout-1',
      'label' => 'Flexible Layout 1',
      'name' => 'flexible-layout-1',
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

    // News & Events Section
    [
      'key' => 'field-home-news-events-tab',
      'label' => 'News & Events',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-group-news-events',
      'label' => '',
      'name' => 'news_events',
      'type' => 'group',
      'sub_fields' => [
        [
          'key' => 'field-home-clone-news-events',
          'label' => 'News & Events',
          'name' => 'news_events_group',
          'type' => 'clone',
          'clone' => [
            0 => 'field-group-news-events',
          ],
          'display' => 'seamless',
          'layout' => 'block',
        ],
      ],
    ],

    // Flexible Content 2 Section (after News & Events)
    [
      'key' => 'field-home-flexible-2-tab',
      'label' => 'Flexible Content 2',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-flexible-layout-2',
      'label' => 'Flexible Layout 2',
      'name' => 'flexible-layout-2',
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
  ],
  'location' => [
    [
      [
        'param' => 'page_template',
        'operator' => '==',
        'value' => 'templates/home.php',
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
    // 4 => 'revisions',
    5 => 'slug',
    6 => 'author',
    // 7 => 'format',
    8 => 'featured_image',
    9 => 'categories',
    10 => 'tags',
    11 => 'send-trackbacks',
  ],
]);
