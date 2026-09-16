<?php

/**
 * Override ad-ui tiny_mce_remove_unused_formats function to add 'label' format
 * This file overrides the TinyMCE formats from ad-ui without modifying the ad-ui folder
 */

// Override ad-ui tiny_mce_remove_unused_formats function to add 'label' format
function override_tiny_mce_remove_unused_formats() {
  // Remove the original filter from ad-ui
  remove_filter('tiny_mce_before_init', 'tiny_mce_remove_unused_formats');
  
  // Add our custom version
  add_filter('tiny_mce_before_init', 'custom_tiny_mce_remove_unused_formats');
}
add_action('init', 'override_tiny_mce_remove_unused_formats', 20);

function custom_tiny_mce_remove_unused_formats($init) {
  // Add block format elements including the Label format
  $init['block_formats'] = 'LABEL=p; Heading/XL=h2; Heading/L=h3; Heading/M=h4Paragraph/L=h6; Paragraph/M=p; Paragraph/S=pre';
  
  // Style formats
  $style_formats = array(
    [
      'title' => 'LABEL',
      'block' => 'p',
      'exact' => true,
      'attributes' => [
        'class' => 'label',
      ],
    ],
    [
      'title' => 'Heading/XL',
      'block' => 'h2',
      'exact' => true,
    ],
    [
      'title' => 'Heading/L',
      'block' => 'h3',
      'exact' => true,
    ],
    [
      'title' => 'Heading/M',
      'block' => 'h4',
      'exact' => true,
    ],
    [
      'title' => 'Heading/S',
      'block' => 'h5',
      'exact' => true,
    ],
    [
      'title' => 'Paragraph/XL',
      'block' => 'p',
      'exact' => true,
      'attributes' => [
        'class' => 'xlarge',
      ],
    ],
    [
      'title' => 'Paragraph/L',
      'block' => 'p',
      'exact' => true,
      'attributes' => [
        'class' => 'lead',
      ],
    ],
    [
      'title' => 'Paragraph/M',
      'block' => 'p',
      'exact' => true,
      'attributes' => [
        'class' => 'medium',
      ],
    ],
    [
      'title' => 'Paragraph/S',
      'block' => 'p',
      'exact' => true,
      'attributes' => [
        'class' => 'small',
      ],
    ],
  );
  
  // Insert the array, JSON ENCODED, into 'style_formats'
  $init['style_formats'] = wp_json_encode($style_formats);

  return $init;
}