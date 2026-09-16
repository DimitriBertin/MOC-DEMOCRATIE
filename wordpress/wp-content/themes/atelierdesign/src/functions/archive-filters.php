<?php
/**
 * Archive Filter System Setup
 * 
 * Enqueues scripts and includes filter functions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include filter functions
require_once get_template_directory() . '/src/components/archive-header/filter-functions.php';

/**
 * Enqueue archive filter assets
 */
function enqueue_archive_filter_assets() {
    // Only enqueue on archive pages or home page
    if (is_home() || is_archive()) {
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'archive-filters',
            get_template_directory_uri() . '/src/components/archive-header/archive-filters.js',
            [],
            filemtime(get_template_directory() . '/src/components/archive-header/archive-filters.js'),
            true
        );
        
        // Localize script with AJAX data
        wp_localize_script('archive-filters', 'archiveFilterAjax', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('archive_filter_nonce')
        ]);
    }
}

add_action('wp_enqueue_scripts', 'enqueue_archive_filter_assets');

/**
 * Register AJAX handlers for the filter system
 */
add_action('wp_ajax_filter_archive_posts', 'handle_archive_filter_ajax');
add_action('wp_ajax_nopriv_filter_archive_posts', 'handle_archive_filter_ajax');

/**
 * Handle archive page title updates with active filters
 * 
 * @param string $title
 * @return string
 */
function update_archive_title_with_filters($title) {
    if (is_home() || is_archive()) {
        $current_filters = [];
        
        // Get filters using WordPress standard parameters
        if (!empty($_GET['type_enjeu'])) {
            $term = get_term_by('slug', $_GET['type_enjeu'], 'type_enjeu');
            if ($term) $current_filters[] = $term->name;
        }
        if (!empty($_GET['type_document'])) {
            $term = get_term_by('slug', $_GET['type_document'], 'type_document');
            if ($term) $current_filters[] = $term->name;
        }
        if (!empty($_GET['category'])) {
            $term = get_term_by('slug', $_GET['category'], 'category');
            if ($term) $current_filters[] = $term->name;
        }
        if (!empty($_GET['theme'])) {
            $term = get_term_by('slug', $_GET['theme'], 'theme');
            if ($term) $current_filters[] = $term->name;
        }
        
        if (!empty($current_filters)) {
            $title .= ' - ' . implode(', ', $current_filters);
        }
    }
    
    return $title;
}

add_filter('wp_title', 'update_archive_title_with_filters');
add_filter('document_title_parts', function($title_parts) {
    if (isset($title_parts['title'])) {
        $title_parts['title'] = update_archive_title_with_filters($title_parts['title']);
    }
    return $title_parts;
});

/**
 * Add structured data for filtered results
 */
function add_filter_structured_data() {
    if ((is_home() || is_archive()) && !empty(array_filter($_GET))) {
        $current_url = home_url($_SERVER['REQUEST_URI']);
        $clean_url = strtok($current_url, '?');
        
        ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "CollectionPage",
            "url": "<?php echo esc_url($current_url); ?>",
            "isPartOf": {
                "@type": "WebSite",
                "url": "<?php echo esc_url($clean_url); ?>"
            }
        }
        </script>
        <?php
    }
}

add_action('wp_head', 'add_filter_structured_data');