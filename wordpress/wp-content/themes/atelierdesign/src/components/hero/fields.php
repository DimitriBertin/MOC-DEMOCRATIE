<?php

$heroFields = [
  [
    'key' => 'field-hero-label',
    'label' => 'Label',
    'name' => 'label',
    'type' => 'text',
    'required' => 0,
  ],
  [
    'key' => 'field-hero-title',
    'label' => 'Title',
    'name' => 'title',
    'type' => 'text',
    'default_value' => '',
  ],
  [
    'key' => 'field-hero-background-image',
    'label' => 'Background Image',
    'name' => 'background_image',
    'type' => 'image',
    'instructions' => 'Upload a background image for the hero section. This will be displayed with a screen blend mode.',
    'required' => 0,
    'return_format' => 'array',
    'preview_size' => 'medium',
    'library' => 'all',
  ],
  [
    'key' => 'field-hero-cta-button',
    'label' => 'CTA Button',
    'name' => 'cta_button',
    'type' => 'link',
    'instructions' => 'Add a call-to-action button link.',
    'required' => 0,
    'return_format' => 'array',
  ],
];

$heroFieldGroup = [
  'key' => 'field-group-hero',
  'title' => 'Hero',
  'fields' => $heroFields,
];

acf_add_local_field_group($heroFieldGroup);
