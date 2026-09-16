<?php

/**
 * Add custom admin menu for direct access to menus
 */

function add_custom_menus_admin_page() {
  add_menu_page(
    __('Menus', 'atelierdesign'),        // Page title
    __('Menus', 'atelierdesign'),        // Menu title
    'edit_theme_options',                // Capability required
    'nav-menus.php',                     // Menu slug (WordPress built-in menus page)
    '',                                  // Function (empty since we're redirecting to existing page)
    'dashicons-menu',                    // Icon
    25                                   // Position (after Comments)
  );
}
add_action('admin_menu', 'add_custom_menus_admin_page');