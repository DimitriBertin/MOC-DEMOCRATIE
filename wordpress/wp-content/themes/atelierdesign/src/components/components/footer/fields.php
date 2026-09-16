<?php

/**
 * ACF Fields for Footer
 */

$footerFields = [
  // Newsletter section
  [
    'key' => 'field-footer-newsletter',
    'label' => 'Newsletter',
    'name' => 'footer_newsletter',
    'type' => 'group',
    'layout' => 'block',
    'sub_fields' => [
      [
        'key' => 'field-footer-newsletter-title',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
        'default_value' => 'Restez informé',
      ],
      [
        'key' => 'field-footer-newsletter-text',
        'label' => 'Text',
        'name' => 'text',
        'type' => 'text',
        'default_value' => 'lorem ipsum dolor amet consectuor',
      ],
      [
        'key' => 'field-footer-newsletter-cta',
        'label' => 'CTA Link',
        'name' => 'cta',
        'type' => 'link',
      ],
    ],
  ],
  
  // Address section
  [
    'key' => 'field-footer-address',
    'label' => 'Address & Contact',
    'name' => 'footer_address',
    'type' => 'group',
    'sub_fields' => [
      [
        'key' => 'field-footer-address-title',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
        'default_value' => 'Adresse & Contact',
      ],
      [
        'key' => 'field-footer-address-content',
        'label' => 'Address',
        'name' => 'content',
        'type' => 'wysiwyg',
        'toolbar' => 'basic',
        'media_upload' => 0,
        'delay' => 0,
      ],
    ],
  ],

  // Social networks
  [
    'key' => 'field-footer-socials',
    'label' => 'Social Networks',
    'name' => 'footer_socials',
    'type' => 'group',
    'sub_fields' => [
      [
        'key' => 'field-footer-socials-title',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
        'default_value' => 'Suivez-nous',
      ],
      [
        'key' => 'field-footer-socials-facebook',
        'label' => 'Facebook',
        'name' => 'facebook',
        'type' => 'url',
      ],
      [
        'key' => 'field-footer-socials-linkedin',
        'label' => 'LinkedIn',
        'name' => 'linkedin',
        'type' => 'url',
      ],
      [
        'key' => 'field-footer-socials-instagram',
        'label' => 'Instagram',
        'name' => 'instagram',
        'type' => 'url',
      ],
      [
        'key' => 'field-footer-socials-youtube',
        'label' => 'YouTube',
        'name' => 'youtube',
        'type' => 'url',
      ],
      [
        'key' => 'field-footer-socials-soundcloud',
        'label' => 'SoundCloud',
        'name' => 'soundcloud',
        'type' => 'url',
      ],
    ],
  ],
  [
    'key' => 'field-footer-policy-menu-link',
    'label' => 'Policies Menu',
    'name' => 'primary_menu_link',
    'type' => 'message',
    'message' => '<a href="' . admin_url('nav-menus.php?action=edit&menu=22') . '" target="_blank" class="button button-primary">Edit Policies Menu</a>',
    'new_lines' => '',
    'esc_html' => 0,
  ],
];

$footerFieldGroup = [
  'key' => 'field-group-footer',
  'title' => 'footer',
  'fields' => $footerFields,
];

acf_add_local_field_group($footerFieldGroup);
