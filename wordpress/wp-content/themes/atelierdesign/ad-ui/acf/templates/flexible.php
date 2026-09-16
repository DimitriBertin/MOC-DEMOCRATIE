<?php

/**
 * Cloning ACF fields for 'page.php'
 */

global $adwp;

add_action('acf/include_fields', function () use ($adwp) {
  acf_add_local_field_group([
    'key' => 'field-group-template-flexible',
    'title' => 'Default Template (Flexible)',
    'fields' => [
      [
        'key' => 'field-flexible-flexible-layout',
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
        'min' => 1,
        'max' => '',
        'layouts' => $adwp->get_block_layouts(),
        'button_label' => 'Add section',
        'acfe_flexible_layouts_settings' => 1,
        'acfe_flexible_stylised_button' => 1,
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
      // 9 => 'categories',
      10 => 'tags',
      11 => 'send-trackbacks',
    ],
  ]);
}, 100);
