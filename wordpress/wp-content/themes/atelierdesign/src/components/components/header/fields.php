<?php

$headerFields = [
  [
    'key' => 'field-header-logo',
    'label' => 'Logo',
    'name' => 'logo',
    'type' => 'image',
    'return_format' => 'array',
    'preview_size' => 'thumbnail',
    'library' => 'all',
  ],
  [
    'key' => 'field-header-logo-contrasted',
    'label' => 'Logo (Contrasted)',
    'name' => 'logo_contrasted',
    'type' => 'image',
    'return_format' => 'array',
    'preview_size' => 'thumbnail',
    'library' => 'all',
  ],
  [
    'key' => 'field-header-primary-menu-link',
    'label' => 'Primary Menu',
    'name' => 'primary_menu_link',
    'type' => 'message',
    'message' => '<a href="' . admin_url('nav-menus.php?action=edit&menu=18') . '" target="_blank" class="button button-primary">Edit Primary Menu</a>',
    'new_lines' => '',
    'esc_html' => 0,
  ],
  [
    'key' => 'field-header-secondary-menu-link',
    'label' => 'Secondary Menu',
    'name' => 'secondary_menu_link',
    'type' => 'message',
    'message' => '<a href="' . admin_url('nav-menus.php?action=edit&menu=19') . '" target="_blank" class="button button-primary">Edit Secondary Menu</a>',
    'new_lines' => '',
    'esc_html' => 0,
  ],
  [
    'key' => 'field-header-services-menu-link',
    'label' => 'Services Menu',
    'name' => 'services_menu_link',
    'type' => 'message',
    'message' => '<a href="' . admin_url('nav-menus.php?action=edit&menu=20') . '" target="_blank" class="button button-primary">Edit Services Menu</a>',
    'new_lines' => '',
    'esc_html' => 0,
  ],
  [
    'key' => 'field-header-cta-menu-link',
    'label' => 'CTA Menu',
    'name' => 'cta_menu_link',
    'type' => 'message',
    'message' => '<a href="' . admin_url('nav-menus.php?action=edit&menu=21') . '" target="_blank" class="button button-primary">Edit CTA Menu</a>',
    'new_lines' => '',
    'esc_html' => 0,
  ],
];

$headerFieldGroup = [
  'key' => 'field-group-header',
  'title' => 'header',
  'fields' => $headerFields,
];

acf_add_local_field_group($headerFieldGroup);
