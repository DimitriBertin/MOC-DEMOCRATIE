<?php

/**
 * ACF Field Group for CTA Footer Component
 */

// Fields of the "CTA Footer" section for cloning
$ctaFooterGroupFields = [
  [
    'key' => 'field-cta-footer-group-items',
    'label' => 'CTA Items',
    'name' => 'ctaFooter_items',
    'type' => 'repeater',
    'layout' => 'block',
    'button_label' => 'Add CTA',
    'min' => 0,
    'max' => 2,
    'sub_fields' => [
      [
        'key' => 'field-cta-footer-group-item-title',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
        'required' => 1,
        'default_value' => 'lorem ipsum dolor amet consectuor',
      ],
      [
        'key' => 'field-cta-footer-group-item-text',
        'label' => 'Text',
        'name' => 'text',
        'type' => 'textarea',
        'rows' => 3,
        'required' => 0,
        'default_value' => 'lorem ipsum dolor amet consectuor',
      ],
      [
        'key' => 'field-cta-footer-group-item-link',
        'label' => 'Link',
        'name' => 'link',
        'type' => 'link',
        'return_format' => 'array',
        'required' => 1,
      ],
    ],
  ],
];

$ctaFooterFieldGroup = [
  'key' => 'field-group-cta-footer',
  'title' => 'CTA Footer',
  'fields' => $ctaFooterGroupFields,
];

acf_add_local_field_group($ctaFooterFieldGroup);