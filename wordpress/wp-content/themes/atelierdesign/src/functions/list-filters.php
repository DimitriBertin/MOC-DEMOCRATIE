<?php
/**
 * List Filters - Integration WordPress
 *
 * Charge les fonctions du composant et enqueue le JS sur les pages de liste
 * (page thematique, archive des revues).
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/src/components/list-filters/filter-functions.php';

/**
 * Les pages qui utilisent le composant.
 */
function ad_is_list_filters_page() {
    return is_singular('thematique') || is_post_type_archive('numero');
}

function ad_enqueue_list_filters_assets() {
    if (!ad_is_list_filters_page()) {
        return;
    }

    $path = get_template_directory() . '/src/components/list-filters/list-filters.js';

    wp_enqueue_script(
        'ad-list-filters',
        get_template_directory_uri() . '/src/components/list-filters/list-filters.js',
        [],
        file_exists($path) ? filemtime($path) : null,
        true
    );

    wp_localize_script('ad-list-filters', 'adListFilters', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('ad_list_filters'),
    ]);
}

add_action('wp_enqueue_scripts', 'ad_enqueue_list_filters_assets');
