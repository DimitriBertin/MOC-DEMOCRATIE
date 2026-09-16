<?php
/**
 * Hero Component Include
 * Simple include for rendering hero on any template
 */

// Include helper functions if not already included
if (!function_exists('get_hero_data')) {
    require_once get_template_directory() . '/src/components/hero/helpers.php';
}

// Check if hero should be displayed
if (should_display_hero()) {
    include get_template_directory() . '/src/components/hero/markup.php';
}