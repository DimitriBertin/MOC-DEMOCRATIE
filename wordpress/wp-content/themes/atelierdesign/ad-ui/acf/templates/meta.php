<?php
/**
 * ACF Fields for Meta tags
 */

 acf_add_local_field_group( array(

  'key' => 'seo-tags',
  'title' => 'SEO',
  'fields' => array(
    // Meta & OG Title
    array(
      'key' => 'meta-title',
      'label' => 'Meta Title',
      'name' => 'meta_title',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => 'This will be the title of the page in the browser tab and in search results. If left blank, the page title will be used.',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
      'maxlength' => 60,
    ),
    // Meta Description
    array(
      'key' => 'meta-description',
      'label' => 'Meta Description',
      'name' => 'meta_description',
      'aria-label' => '',
      'type' => 'textarea',
      'instructions' => 'This will be the description of the page in search results.',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => '',
      'maxlength' => 160,
      'rows' => 3,
      'new_lines' => '',
    ),
    // OG Image
    array(
      'key' => 'og-image',
      'label' => 'OG Image',
      'name' => 'og_image',
      'aria-label' => '',
      'type' => 'image',
      'instructions' => 'This will be the image that appears when sharing the page on social media. Recommended: 1200x630px.',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'return_format' => 'url',
      'preview_size' => 'medium',
      'library' => 'all',
      'min_width' => 1200,
      'min_height' => 630,
      'min_size' => '',
      'max_width' => '',
      'max_height' => '',
      'max_size' => '',
      'mime_types' => '',
    ),
  ),

  'location' => array(
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'page',
      ),
    ),
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'insights',
      ),
    ),
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'team',
      ),
    ),
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'companies',
      ),
    ),
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'careers',
      ),
    ),
  ),

  'menu_order' => 9,
  'position' => 'side',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => true,
  'description' => '',
  'show_in_rest' => 0,

) );