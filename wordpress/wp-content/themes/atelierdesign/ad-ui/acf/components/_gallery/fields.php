<?php

/**
 * ACF Fields for Gallery
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path d="M14,2H2v12h2V4h10V2z M18,6H6v12h2V8h10V6z M22,10H10v12h12V10z" fill="currentColor" /></svg>';

$galleryFields = [
  [
    'key' => 'field-gallery-items',
    'label' => 'Select files',
    'name' => '_gallery_items',
    'type' => 'gallery',
    'required' => 1,
    'preview_size' => 'thumbnail',
    'library' => 'all',
    'min' => 1,
    'max' => '',
    'insert' => 'append',
    'return_format' => 'array',
    'conditional_logic' => 0,
    'wrapper' => [
      'width' => '100',
    ],
  ],
];

$galleryLayout = [
  'key' => 'layout-gallery',
  'label' => $icon . ' Gallery',
  'name' => '_gallery',
  'display' => 'block',
  'sub_fields' => $galleryFields,
];

$adwp->add_inline_layout('layoutGallery', $galleryLayout, 27);

$adwp->register_layout('gallery', $galleryLayout);
