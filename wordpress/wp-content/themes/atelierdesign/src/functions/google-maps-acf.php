<?php

/**
 * Google Maps API Configuration for ACF
 */

// Register Google Maps API Key for ACF
// Note: You'll need to add your Google Maps API key in WordPress admin
// or define it in wp-config.php as: define('GOOGLE_MAPS_API_KEY', 'your-api-key-here');

function register_google_maps_api() {
  // Check if API key is defined
  if (defined('GOOGLE_MAPS_API_KEY')) {
    acf_update_setting('google_api_key', GOOGLE_MAPS_API_KEY);
  }
}
add_action('acf/init', 'register_google_maps_api');

// Enqueue Google Maps JavaScript API
function enqueue_google_maps_script() {
  // Only enqueue on pages using the contact template
  if (is_page_template('templates/contact.php')) {
    $api_key = defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : '';
    
    if (!empty($api_key)) {
      wp_enqueue_script(
        'google-maps',
        'https://maps.googleapis.com/maps/api/js?key=' . $api_key,
        [],
        null,
        true
      );
    }
  }
}
add_action('wp_enqueue_scripts', 'enqueue_google_maps_script');
