<?php

/**
 * Register navigation menus
 */

function register_theme_menus() {
  register_nav_menus([
    'primary-menu' => __('Primary Menu', 'atelierdesign'),
    'secondary-menu' => __('Secondary Menu', 'atelierdesign'),
    'services-menu' => __('Services Menu', 'atelierdesign'),
    'cta-menu' => __('CTA Menu', 'atelierdesign'),
    'policy-menu' => __('Policy Menu', 'atelierdesign'),
  ]);
}
add_action('after_setup_theme', 'register_theme_menus');
