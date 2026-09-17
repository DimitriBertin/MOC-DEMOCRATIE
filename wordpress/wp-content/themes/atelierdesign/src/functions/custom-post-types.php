<?php
/**
 * Custom Post Types Registration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * LEGACY POST TYPES
 *
 * Le backoffice ne gere plus que : Articles, Thematiques, Auteur-rices, Numeros.
 * Les types Evenement / Document / Campagne / Emploi ne sont plus enregistres,
 * mais leurs definitions sont conservees ci-dessous pour reference.
 * Passer la constante a true pour les reactiver.
 */
if (!defined('AD_ENABLE_LEGACY_POST_TYPES')) {
    define('AD_ENABLE_LEGACY_POST_TYPES', false);
}

function register_legacy_post_types() {

    if (!AD_ENABLE_LEGACY_POST_TYPES) {
        return;
    }

    
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

add_action('init', 'register_legacy_post_types');


/**
 * Register Custom Post Types (actifs)
 */
function register_custom_post_types() {

    // Thematique CPT (hierarchique : permet des sous-thematiques)
    register_post_type('thematique', array(
        'labels' => array(
            'name' => __('Thematiques', 'textdomain'),
            'singular_name' => __('Thematique', 'textdomain'),
            'menu_name' => __('Thematiques', 'textdomain'),
            'name_admin_bar' => __('Thematique', 'textdomain'),
            'add_new' => __('Ajouter nouveau', 'textdomain'),
            'add_new_item' => __('Ajouter une nouvelle thematique', 'textdomain'),
            'new_item' => __('Nouvelle thematique', 'textdomain'),
            'edit_item' => __('Modifier la thematique', 'textdomain'),
            'view_item' => __('Voir la thematique', 'textdomain'),
            'all_items' => __('Toutes les thematiques', 'textdomain'),
            'search_items' => __('Rechercher des thematiques', 'textdomain'),
            'parent_item' => __('Thematique parente', 'textdomain'),
            'parent_item_colon' => __('Thematique parente :', 'textdomain'),
            'not_found' => __('Aucune thematique trouvee.', 'textdomain'),
            'not_found_in_trash' => __('Aucune thematique trouvee dans la corbeille.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'thematiques', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-category',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes'),
        'show_in_rest' => true,
    ));

    // Auteur / Autrice CPT
    register_post_type('auteur', array(
        'labels' => array(
            'name' => __('Auteur-rices', 'textdomain'),
            'singular_name' => __('Auteur-rice', 'textdomain'),
            'menu_name' => __('Auteur-rices', 'textdomain'),
            'name_admin_bar' => __('Auteur-rice', 'textdomain'),
            'add_new' => __('Ajouter nouveau', 'textdomain'),
            'add_new_item' => __('Ajouter un-e nouvel-le auteur-rice', 'textdomain'),
            'new_item' => __('Nouvel-le auteur-rice', 'textdomain'),
            'edit_item' => __('Modifier l\'auteur-rice', 'textdomain'),
            'view_item' => __('Voir l\'auteur-rice', 'textdomain'),
            'all_items' => __('Tous les auteur-rices', 'textdomain'),
            'search_items' => __('Rechercher des auteur-rices', 'textdomain'),
            'parent_item_colon' => __('Auteur-rices parents :', 'textdomain'),
            'not_found' => __('Aucun-e auteur-rice trouve-e.', 'textdomain'),
            'not_found_in_trash' => __('Aucun-e auteur-rice trouve-e dans la corbeille.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'auteurs', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-admin-users',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
    ));

    // Numero CPT
    register_post_type('numero', array(
        'labels' => array(
            'name' => __('Numeros', 'textdomain'),
            'singular_name' => __('Numero', 'textdomain'),
            'menu_name' => __('Numeros', 'textdomain'),
            'name_admin_bar' => __('Numero', 'textdomain'),
            'add_new' => __('Ajouter nouveau', 'textdomain'),
            'add_new_item' => __('Ajouter un nouveau numero', 'textdomain'),
            'new_item' => __('Nouveau numero', 'textdomain'),
            'edit_item' => __('Modifier le numero', 'textdomain'),
            'view_item' => __('Voir le numero', 'textdomain'),
            'all_items' => __('Tous les numeros', 'textdomain'),
            'search_items' => __('Rechercher des numeros', 'textdomain'),
            'parent_item_colon' => __('Numeros parents :', 'textdomain'),
            'not_found' => __('Aucun numero trouve.', 'textdomain'),
            'not_found_in_trash' => __('Aucun numero trouve dans la corbeille.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'numeros', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 8,
        'menu_icon' => 'dashicons-book-alt',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
    ));
}

add_action('init', 'register_custom_post_types');

/**
 * Flush rewrite rules on theme activation
 */
function flush_rewrite_rules_on_activation() {
    register_custom_post_types();
    flush_rewrite_rules();
}

add_action('after_switch_theme', 'flush_rewrite_rules_on_activation');
