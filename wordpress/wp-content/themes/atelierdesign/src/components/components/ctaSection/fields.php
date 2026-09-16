<?php

/**
 * ACF Fields for CTA Section block layout
 */

// Global variable coming from the AD UI plugin
global $adwp;

// Icon of the block (usually coming from Google's Material Symbols)
// Important to keep 
// - `style="vertical-align: bottom;"` to avoid the icon from being too high
// - `width="16" height="16"` to avoid the icon from being too large
// - `fill="currentColor"` to colorize it the same as other icons in ACF
$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-480H200v480Zm160-120h240v-80H360v80Zm0-120h240v-80H360v80ZM200-200v-560 560Z"/></svg>';

// Fields of the "CTA Section" block
$ctaSectionFields = [
  [
    'key' => 'field-cta-section-title',
    'label' => 'Title',
    'name' => 'ctaSection_title', // IMPORTANT: The field names must be prefixed with the same name as the layout, and how they what the folder is called in the `components` directory
    'type' => 'text',
    'required' => 1,
    'default_value' => 'Le MOC s\'engage pour une société plus juste et solidaire',
  ],
  [
    'key' => 'field-cta-section-subtitle',
    'label' => 'Subtitle',
    'name' => 'ctaSection_subtitle',
    'type' => 'textarea',
    'rows' => 3,
    'new_lines' => 'br',
    'required' => 1,
    'default_value' => 'Retrouvez ici nos analyses, revendications et outils pour mieux comprendre et agir.',
  ],
  [
    'key' => 'field-cta-section-background-image',
    'label' => 'Background Image',
    'name' => 'ctaSection_backgroundImage',
    'type' => 'image',
    'instructions' => 'Upload a background image for the CTA section.',
    'required' => 0,
    'return_format' => 'array',
    'preview_size' => 'medium',
    'library' => 'all',
  ],
  [
    'key' => 'field-cta-section-background-overlay-opacity',
    'label' => 'Background overlay opacity',
    'name' => 'ctaSection_backgroundOverlayOpacity',
    'type' => 'range',
    'instructions' => 'Set the opacity of the black overlay on the background image (0 = transparent, 100 = opaque).',
    'required' => 0,
    'default_value' => 0,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'prepend' => '',
    'append' => '%',
  ],
  [
    'key' => 'field-cta-section-button',
    'label' => 'CTA Button',
    'name' => 'ctaSection_ctaButton',
    'type' => 'link',
    'return_format' => 'array',
    'required' => 1,
  ],
];

// Adding the "CTA Section" to all the other flexible sections, with the $adwp->add_block_layout() function
$ctaSectionLayout = [
  'key' => 'layout-ctaSection',
  'label' => $icon . ' CTA Section',
  'name' => 'ctaSection',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('block-', $ctaSectionFields),
];

$adwp->add_block_layout('layoutCtaSection', $ctaSectionLayout, 99);
