<?php

/**
 * ACF Fields for Script
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z" fill="currentColor" /></svg>';

$scriptSettingsFields = [
  [
    'key' => 'field-script-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
];

add_action(
  'acf/include_fields',
  static function () use ($scriptSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-script-settings',
      'title' => 'Script Settings',
      'fields' => $scriptSettingsFields,
    ]);
  },
  50
);

$scriptFields = [
  [
    'key' => 'field-script-script',
    'label' => 'Insert script',
    'name' => '_script_script',
    'aria-label' => '',
    'type' => 'acfe_code_editor',
    'instructions' => '',
    'required' => 1,
    'mode' => 'text/html',
    'lines' => 1,
    'indent_unit' => 2,
    'maxlength' => '',
    'rows' => 6,
  ],
];

$scriptLayout = [
  'key' => 'layout-script',
  'label' => $icon . ' Script',
  'name' => '_script',
  'display' => 'block',
  'sub_fields' => $scriptFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-script-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

// $adwp->add_inline_layout('layoutScript', $scriptLayout, 50);

// $adwp->register_layout('script', $scriptLayout);