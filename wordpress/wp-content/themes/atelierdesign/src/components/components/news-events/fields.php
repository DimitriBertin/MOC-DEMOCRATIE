<?php

$newsEventsFields = [
  [
    'key' => 'field-news-events-section-subtitle',
    'label' => 'Section Subtitle',
    'name' => 'section_subtitle',
    'type' => 'text',
    'required' => 0,
    'default_value' => 'Nos actualités & événements',
    'instructions' => 'Small text above the main title',
  ],
  [
    'key' => 'field-news-events-section-title',
    'label' => 'Section Title',
    'name' => 'section_title',
    'type' => 'text',
    'required' => 1,
    'default_value' => 'En ce moment au MOC CIEP',
  ],
  [
    'key' => 'field-news-events-cta-button',
    'label' => 'CTA Button',
    'name' => 'cta_button',
    'type' => 'link',
    'return_format' => 'array',
    'required' => 1,
    'instructions' => 'Button link (e.g., "découvrez nos actualités")',
  ],
  [
    'key' => 'field-news-events-news-category',
    'label' => 'News Category',
    'name' => 'news_category',
    'type' => 'select',
    'choices' => [], // Will be populated with categories
    'allow_null' => 1,
    'instructions' => 'Choose which category to pull news from (leave empty for all posts)',
  ],
  [
    'key' => 'field-news-events-events-category',
    'label' => 'Events Category',
    'name' => 'events_category',
    'type' => 'select',
    'choices' => [], // Will be populated with categories
    'allow_null' => 1,
    'instructions' => 'Choose which category to pull events from',
  ],
];

// Populate category choices
$categories = get_categories(['hide_empty' => false]);
$category_choices = [];
foreach ($categories as $category) {
  $category_choices[$category->slug] = $category->name;
}

// Update category fields with choices
$newsEventsFields[3]['choices'] = $category_choices;
$newsEventsFields[4]['choices'] = $category_choices;

$newsEventsFieldGroup = [
  'key' => 'field-group-news-events',
  'title' => 'News & Events Section',
  'fields' => $newsEventsFields,
];

acf_add_local_field_group($newsEventsFieldGroup);
