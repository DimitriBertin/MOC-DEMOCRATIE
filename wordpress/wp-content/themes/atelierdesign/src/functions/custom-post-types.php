<?php
/**
 * Custom Post Types Registration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Custom Post Types
 */
function register_custom_post_types() {
    
    // Evenement CPT
    register_post_type('evenement', array(
        'labels' => array(
            'name' => __('Événements', 'textdomain'),
            'singular_name' => __('Événement', 'textdomain'),
            'menu_name' => __('Événements', 'textdomain'),
            'name_admin_bar' => __('Événement', 'textdomain'),
            'add_new' => __('Ajouter nouveau', 'textdomain'),
            'add_new_item' => __('Ajouter un nouvel événement', 'textdomain'),
            'new_item' => __('Nouvel événement', 'textdomain'),
            'edit_item' => __('Modifier l\'événement', 'textdomain'),
            'view_item' => __('Voir l\'événement', 'textdomain'),
            'all_items' => __('Tous les événements', 'textdomain'),
            'search_items' => __('Rechercher des événements', 'textdomain'),
            'parent_item_colon' => __('Événements parents :', 'textdomain'),
            'not_found' => __('Aucun événement trouvé.', 'textdomain'),
            'not_found_in_trash' => __('Aucun événement trouvé dans la corbeille.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'evenements', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
    ));

    // Document CPT
    register_post_type('document', array(
        'labels' => array(
            'name' => __('Documents', 'textdomain'),
            'singular_name' => __('Document', 'textdomain'),
            'menu_name' => __('Documents', 'textdomain'),
            'name_admin_bar' => __('Document', 'textdomain'),
            'add_new' => __('Ajouter nouveau', 'textdomain'),
            'add_new_item' => __('Ajouter un nouveau document', 'textdomain'),
            'new_item' => __('Nouveau document', 'textdomain'),
            'edit_item' => __('Modifier le document', 'textdomain'),
            'view_item' => __('Voir le document', 'textdomain'),
            'all_items' => __('Tous les documents', 'textdomain'),
            'search_items' => __('Rechercher des documents', 'textdomain'),
            'parent_item_colon' => __('Documents parents :', 'textdomain'),
            'not_found' => __('Aucun document trouvé.', 'textdomain'),
            'not_found_in_trash' => __('Aucun document trouvé dans la corbeille.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'se-documenter', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-media-document',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
    ));

    // Campagne CPT
    register_post_type('campagne', array(
        'labels' => array(
            'name' => __('Campagnes', 'textdomain'),
            'singular_name' => __('Campagne', 'textdomain'),
            'menu_name' => __('Campagnes', 'textdomain'),
            'name_admin_bar' => __('Campagne', 'textdomain'),
            'add_new' => __('Ajouter nouveau', 'textdomain'),
            'add_new_item' => __('Ajouter une nouvelle campagne', 'textdomain'),
            'new_item' => __('Nouvelle campagne', 'textdomain'),
            'edit_item' => __('Modifier la campagne', 'textdomain'),
            'view_item' => __('Voir la campagne', 'textdomain'),
            'all_items' => __('Toutes les campagnes', 'textdomain'),
            'search_items' => __('Rechercher des campagnes', 'textdomain'),
            'parent_item_colon' => __('Campagnes parentes :', 'textdomain'),
            'not_found' => __('Aucune campagne trouvée.', 'textdomain'),
            'not_found_in_trash' => __('Aucune campagne trouvée dans la corbeille.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'campagnes', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 8,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
    ));

    // Job CPT
    register_post_type('job', array(
        'labels' => array(
            'name' => __('Emplois', 'textdomain'),
            'singular_name' => __('Emploi', 'textdomain'),
            'menu_name' => __('Emplois', 'textdomain'),
            'name_admin_bar' => __('Emploi', 'textdomain'),
            'add_new' => __('Ajouter nouveau', 'textdomain'),
            'add_new_item' => __('Ajouter un nouvel emploi', 'textdomain'),
            'new_item' => __('Nouvel emploi', 'textdomain'),
            'edit_item' => __('Modifier l\'emploi', 'textdomain'),
            'view_item' => __('Voir l\'emploi', 'textdomain'),
            'all_items' => __('Tous les emplois', 'textdomain'),
            'search_items' => __('Rechercher des emplois', 'textdomain'),
            'parent_item_colon' => __('Emplois parents :', 'textdomain'),
            'not_found' => __('Aucun emploi trouvé.', 'textdomain'),
            'not_found_in_trash' => __('Aucun emploi trouvé dans la corbeille.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'emplois', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 9,
        'menu_icon' => 'dashicons-businessman',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
    ));
}

add_action('init', 'register_custom_post_types');

/**
 * Rename default 'post' post type to 'enjeu'
 */
function rename_post_type_to_enjeu() {
    global $wp_post_types;
    
    $labels = &$wp_post_types['post']->labels;
    $labels->name = __('Enjeux', 'textdomain');
    $labels->singular_name = __('Enjeu', 'textdomain');
    $labels->menu_name = __('Enjeux', 'textdomain');
    $labels->name_admin_bar = __('Enjeu', 'textdomain');
    $labels->add_new = __('Ajouter nouveau', 'textdomain');
    $labels->add_new_item = __('Ajouter un nouvel enjeu', 'textdomain');
    $labels->new_item = __('Nouvel enjeu', 'textdomain');
    $labels->edit_item = __('Modifier l\'enjeu', 'textdomain');
    $labels->view_item = __('Voir l\'enjeu', 'textdomain');
    $labels->all_items = __('Tous les enjeux', 'textdomain');
    $labels->search_items = __('Rechercher des enjeux', 'textdomain');
    $labels->not_found = __('Aucun enjeu trouvé.', 'textdomain');
    $labels->not_found_in_trash = __('Aucun enjeu trouvé dans la corbeille.', 'textdomain');
    
    // Change the menu icon
    $wp_post_types['post']->menu_icon = 'dashicons-lightbulb';
    
    // Update rewrite rules to use 'enjeux' slug
    $wp_post_types['post']->rewrite = array('slug' => 'enjeux');
}

add_action('init', 'rename_post_type_to_enjeu');

/**
 * Flush rewrite rules on theme activation
 */
function flush_rewrite_rules_on_activation() {
    register_custom_post_types();
    flush_rewrite_rules();
}

add_action('after_switch_theme', 'flush_rewrite_rules_on_activation');
