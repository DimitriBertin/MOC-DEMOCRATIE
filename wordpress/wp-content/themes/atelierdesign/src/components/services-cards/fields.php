<?php

$servicesCardsFields = [
  [
    'key' => 'field-services-cards-repeater',
    'label' => 'Cards',
    'name' => 'cards',
    'type' => 'repeater',
    'min' => 1,
    'max' => 6,
    'layout' => 'block',
    'button_label' => 'Add Card',
    'sub_fields' => [
      [
        'key' => 'field-services-card-badge',
        'label' => 'Badge',
        'name' => 'badge',
        'type' => 'text',
        'required' => 1,
      ],
      [
        'key' => 'field-services-card-title',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
        'required' => 1,
      ],
      [
        'key' => 'field-services-card-description',
        'label' => 'Description',
        'name' => 'description',
        'type' => 'textarea',
        'rows' => 4,
        'new_lines' => 'br',
      ],
      [
        'key' => 'field-services-card-primary-link',
        'label' => 'Primary Link',
        'name' => 'primary_link',
        'type' => 'link',
        'return_format' => 'array',
        'required' => 1,
      ],
      [
        'key' => 'field-services-card-secondary-link',
        'label' => 'Secondary Link (optional)',
        'name' => 'secondary_link',
        'type' => 'link',
        'return_format' => 'array',
        'required' => 0,
      ],
    ],
  ],
];

$servicesCardsFieldGroup = [
  'key' => 'field-group-services-cards',
  'title' => 'Services Cards',
  'fields' => $servicesCardsFields,
];

acf_add_local_field_group($servicesCardsFieldGroup);
