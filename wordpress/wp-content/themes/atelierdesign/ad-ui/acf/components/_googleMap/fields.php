<?php

/**
 * ACF Fields for Google Maps
 */

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 24 24" width="16"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>';

$googleMapSettingsFields = [
  [
    'key' => 'field-googleMap-setting-isFullWidth',
    'label' => 'Full Width',
    'name' => 'isFullWidth',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Yes',
    'ui_off_text' => 'No',
    'default_value' => 0,
  ],
  [
    'key' => 'field-googleMap-aspect',
    'label' => 'Aspect Ratio',
    'name' => 'aspect',
    'type' => 'select',
    'choices' => [
      '1/1' => 'square - 1/1',
      '16/9' => 'video - 16/9',
      '3/2' => 'landscape photo - 3/2',
      '4/3'  => 'landscape photo - 4/3',
      '21/9' => 'cinema - 21/9',
      'md:21/9' => 'cinema - 21/9 (only on desktop)',
      '4/5' => 'instagram - 4/5',
      '3/4' => 'portrait photo - 3/4',
      // '5/4' - => '5/4',
      // '2/1' => '2/1',
      // '2/3' => '2/3',
      // '1/2' => '1/2',
    ],
    'default_value' => '16/9',
  ],
];

// From nested version of the layout, remove the wrapper width settings
$googleMapNestedSettingsFields = $googleMapSettingsFields;
unset($googleMapNestedSettingsFields[0]);

add_action(
  'acf/include_fields',
  static function () use ($googleMapSettingsFields) {
    acf_add_local_field_group([
      'key' => 'field-group-googleMap-settings',
      'title' => 'Google Map settings',
      'fields' => $googleMapSettingsFields,
    ]);
  },
  60
);

$googleMapFields = [
  [
    'key' => ($keyPrefix ?? '') . 'field-googleMap-map',
    'label' => 'Location on Google Maps',
    'name' => '_googleMap_map',
    'type' => 'google_map',
    'acfe_save_meta' => 0,
    'default_value' => '',
    'center_lat' => '50.8552021',
    'center_lng' => '4.293016747',
    'height' => 400,
    'acfe_google_map_zooms' => [
      'zoom' => '13',
      'min_zoom' => '8',
      'max_zoom' => '18',
    ],
    'acfe_google_map_marker_icon' => '',
    'acfe_google_map_marker_width' => 50,
    'acfe_google_map_type' => 'roadmap',
    'acfe_google_map_disable_ui' => 0,
    'acfe_google_map_disable_zoom_control' => 0,
    'acfe_google_map_disable_map_type' => 1,
    'acfe_google_map_disable_fullscreen' => 1,
    'acfe_google_map_disable_streetview' => 1,
    'acfe_google_map_style' => '',
    'acfe_google_map_key' => "AIzaSyCdaOxRiQZYZg_uL_8L4JP1ZEjm8BIF79A",
    'zoom' => '14',
    'acfe_google_map_marker_height' => 50,
  ],
];

$googleMapLayout = [
  'key' => 'layout-googleMap',
  'label' => $icon . ' Google Maps',
  'name' => '_googleMap',
  'display' => 'block',
  'sub_fields' => $googleMapFields,
  'acfe_flexible_settings' => [
    0 => 'field-group-googleMap-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

// $adwp->add_inline_layout('layoutGoogleMap', $googleMapLayout, 60);

// Nested layout variant (no full-width setting)
$nestedGoogleMapLayout = [
  'key' => 'layout-nestedGoogleMap',
  'label' => $icon . ' Google Maps',
  'name' => '_googleMap',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $googleMapFields),
  'acfe_flexible_settings' => [
    0 => 'field-group-googleMap-nested-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

// $adwp->add_nested_layout('layoutNestedGoogleMap', $nestedGoogleMapLayout, 60);

// $adwp->register_layout('googleMap', $nestedGoogleMapLayout);
