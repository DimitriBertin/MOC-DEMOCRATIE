<?php
/**
 * Custom Taxonomies Registration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Custom Taxonomies
 */
function register_custom_taxonomies() {
    
    // Type taxonomy for enjeu (post)
    register_taxonomy('type_enjeu', array('post'), array(
        'labels' => array(
            'name' => __('Types Enjeu', 'textdomain'),
            'singular_name' => __('Type Enjeu', 'textdomain'),
            'menu_name' => __('Types Enjeu', 'textdomain'),
            'all_items' => __('Tous les types enjeu', 'textdomain'),
            'edit_item' => __('Modifier le type enjeu', 'textdomain'),
            'view_item' => __('Voir le type enjeu', 'textdomain'),
            'update_item' => __('Mettre à jour le type enjeu', 'textdomain'),
            'add_new_item' => __('Ajouter un nouveau type enjeu', 'textdomain'),
            'new_item_name' => __('Nom du nouveau type enjeu', 'textdomain'),
            'parent_item' => __('Type enjeu parent', 'textdomain'),
            'parent_item_colon' => __('Type enjeu parent :', 'textdomain'),
            'search_items' => __('Rechercher des types enjeu', 'textdomain'),
            'popular_items' => __('Types enjeu populaires', 'textdomain'),
            'separate_items_with_commas' => __('Séparer les types enjeu avec des virgules', 'textdomain'),
            'add_or_remove_items' => __('Ajouter ou supprimer des types enjeu', 'textdomain'),
            'choose_from_most_used' => __('Choisir parmi les plus utilisés', 'textdomain'),
            'not_found' => __('Aucun type enjeu trouvé.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'type-enjeu',
            'with_front' => false,
        ),
    ));

    // Type taxonomy for document
    register_taxonomy('type_document', array('document'), array(
        'labels' => array(
            'name' => __('Types Document', 'textdomain'),
            'singular_name' => __('Type Document', 'textdomain'),
            'menu_name' => __('Types Document', 'textdomain'),
            'all_items' => __('Tous les types document', 'textdomain'),
            'edit_item' => __('Modifier le type document', 'textdomain'),
            'view_item' => __('Voir le type document', 'textdomain'),
            'update_item' => __('Mettre à jour le type document', 'textdomain'),
            'add_new_item' => __('Ajouter un nouveau type document', 'textdomain'),
            'new_item_name' => __('Nom du nouveau type document', 'textdomain'),
            'parent_item' => __('Type document parent', 'textdomain'),
            'parent_item_colon' => __('Type document parent :', 'textdomain'),
            'search_items' => __('Rechercher des types document', 'textdomain'),
            'popular_items' => __('Types document populaires', 'textdomain'),
            'separate_items_with_commas' => __('Séparer les types document avec des virgules', 'textdomain'),
            'add_or_remove_items' => __('Ajouter ou supprimer des types document', 'textdomain'),
            'choose_from_most_used' => __('Choisir parmi les plus utilisés', 'textdomain'),
            'not_found' => __('Aucun type document trouvé.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'type-document',
            'with_front' => false,
        ),
    ));

    // Category taxonomy for document
    register_taxonomy('category_document', array('document'), array(
        'labels' => array(
            'name' => __('Catégories', 'textdomain'),
            'singular_name' => __('Catégorie', 'textdomain'),
            'menu_name' => __('Catégories', 'textdomain'),
            'all_items' => __('Toutes les catégories', 'textdomain'),
            'edit_item' => __('Modifier la catégorie', 'textdomain'),
            'view_item' => __('Voir la catégorie', 'textdomain'),
            'update_item' => __('Mettre à jour la catégorie', 'textdomain'),
            'add_new_item' => __('Ajouter une nouvelle catégorie', 'textdomain'),
            'new_item_name' => __('Nom de la nouvelle catégorie', 'textdomain'),
            'parent_item' => __('Catégorie parente', 'textdomain'),
            'parent_item_colon' => __('Catégorie parente :', 'textdomain'),
            'search_items' => __('Rechercher des catégories', 'textdomain'),
            'popular_items' => __('Catégories populaires', 'textdomain'),
            'separate_items_with_commas' => __('Séparer les catégories avec des virgules', 'textdomain'),
            'add_or_remove_items' => __('Ajouter ou supprimer des catégories', 'textdomain'),
            'choose_from_most_used' => __('Choisir parmi les plus utilisées', 'textdomain'),
            'not_found' => __('Aucune catégorie trouvée.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'categorie-document',
            'with_front' => false,
        ),
    ));

    // Category taxonomy for enjeu (post)
    register_taxonomy('category_enjeu', array('post'), array(
        'labels' => array(
            'name' => __('Catégories', 'textdomain'),
            'singular_name' => __('Catégorie', 'textdomain'),
            'menu_name' => __('Catégories', 'textdomain'),
            'all_items' => __('Toutes les catégories', 'textdomain'),
            'edit_item' => __('Modifier la catégorie', 'textdomain'),
            'view_item' => __('Voir la catégorie', 'textdomain'),
            'update_item' => __('Mettre à jour la catégorie', 'textdomain'),
            'add_new_item' => __('Ajouter une nouvelle catégorie', 'textdomain'),
            'new_item_name' => __('Nom de la nouvelle catégorie', 'textdomain'),
            'parent_item' => __('Catégorie parente', 'textdomain'),
            'parent_item_colon' => __('Catégorie parente :', 'textdomain'),
            'search_items' => __('Rechercher des catégories', 'textdomain'),
            'popular_items' => __('Catégories populaires', 'textdomain'),
            'separate_items_with_commas' => __('Séparer les catégories avec des virgules', 'textdomain'),
            'add_or_remove_items' => __('Ajouter ou supprimer des catégories', 'textdomain'),
            'choose_from_most_used' => __('Choisir parmi les plus utilisées', 'textdomain'),
            'not_found' => __('Aucune catégorie trouvée.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'categorie-enjeu',
            'with_front' => false,
        ),
    ));

    // Category taxonomy for evenement
    register_taxonomy('category_evenement', array('evenement'), array(
        'labels' => array(
            'name' => __('Catégories', 'textdomain'),
            'singular_name' => __('Catégorie', 'textdomain'),
            'menu_name' => __('Catégories', 'textdomain'),
            'all_items' => __('Toutes les catégories', 'textdomain'),
            'edit_item' => __('Modifier la catégorie', 'textdomain'),
            'view_item' => __('Voir la catégorie', 'textdomain'),
            'update_item' => __('Mettre à jour la catégorie', 'textdomain'),
            'add_new_item' => __('Ajouter une nouvelle catégorie', 'textdomain'),
            'new_item_name' => __('Nom de la nouvelle catégorie', 'textdomain'),
            'parent_item' => __('Catégorie parente', 'textdomain'),
            'parent_item_colon' => __('Catégorie parente :', 'textdomain'),
            'search_items' => __('Rechercher des catégories', 'textdomain'),
            'popular_items' => __('Catégories populaires', 'textdomain'),
            'separate_items_with_commas' => __('Séparer les catégories avec des virgules', 'textdomain'),
            'add_or_remove_items' => __('Ajouter ou supprimer des catégories', 'textdomain'),
            'choose_from_most_used' => __('Choisir parmi les plus utilisées', 'textdomain'),
            'not_found' => __('Aucune catégorie trouvée.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'categorie-evenement',
            'with_front' => false,
        ),
    ));

    // Category taxonomy for campagne
    register_taxonomy('category_campagne', array('campagne'), array(
        'labels' => array(
            'name' => __('Catégories', 'textdomain'),
            'singular_name' => __('Catégorie', 'textdomain'),
            'menu_name' => __('Catégories', 'textdomain'),
            'all_items' => __('Toutes les catégories', 'textdomain'),
            'edit_item' => __('Modifier la catégorie', 'textdomain'),
            'view_item' => __('Voir la catégorie', 'textdomain'),
            'update_item' => __('Mettre à jour la catégorie', 'textdomain'),
            'add_new_item' => __('Ajouter une nouvelle catégorie', 'textdomain'),
            'new_item_name' => __('Nom de la nouvelle catégorie', 'textdomain'),
            'parent_item' => __('Catégorie parente', 'textdomain'),
            'parent_item_colon' => __('Catégorie parente :', 'textdomain'),
            'search_items' => __('Rechercher des catégories', 'textdomain'),
            'popular_items' => __('Catégories populaires', 'textdomain'),
            'separate_items_with_commas' => __('Séparer les catégories avec des virgules', 'textdomain'),
            'add_or_remove_items' => __('Ajouter ou supprimer des catégories', 'textdomain'),
            'choose_from_most_used' => __('Choisir parmi les plus utilisées', 'textdomain'),
            'not_found' => __('Aucune catégorie trouvée.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'categorie-campagne',
            'with_front' => false,
        ),
    ));

    // Category taxonomy for job
    register_taxonomy('category_job', array('job'), array(
        'labels' => array(
            'name' => __('Catégories', 'textdomain'),
            'singular_name' => __('Catégorie', 'textdomain'),
            'menu_name' => __('Catégories', 'textdomain'),
            'all_items' => __('Toutes les catégories', 'textdomain'),
            'edit_item' => __('Modifier la catégorie', 'textdomain'),
            'view_item' => __('Voir la catégorie', 'textdomain'),
            'update_item' => __('Mettre à jour la catégorie', 'textdomain'),
            'add_new_item' => __('Ajouter une nouvelle catégorie', 'textdomain'),
            'new_item_name' => __('Nom de la nouvelle catégorie', 'textdomain'),
            'parent_item' => __('Catégorie parente', 'textdomain'),
            'parent_item_colon' => __('Catégorie parente :', 'textdomain'),
            'search_items' => __('Rechercher des catégories', 'textdomain'),
            'popular_items' => __('Catégories populaires', 'textdomain'),
            'separate_items_with_commas' => __('Séparer les catégories avec des virgules', 'textdomain'),
            'add_or_remove_items' => __('Ajouter ou supprimer des catégories', 'textdomain'),
            'choose_from_most_used' => __('Choisir parmi les plus utilisées', 'textdomain'),
            'not_found' => __('Aucune catégorie trouvée.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'categorie-job',
            'with_front' => false,
        ),
    ));

    // Theme taxonomy for evenement, document, and post (renamed to enjeu)
    register_taxonomy('theme', array('document', 'post'), array(
        'labels' => array(
            'name' => __('Thèmes', 'textdomain'),
            'singular_name' => __('Thème', 'textdomain'),
            'menu_name' => __('Thèmes', 'textdomain'),
            'all_items' => __('Tous les thèmes', 'textdomain'),
            'edit_item' => __('Modifier le thème', 'textdomain'),
            'view_item' => __('Voir le thème', 'textdomain'),
            'update_item' => __('Mettre à jour le thème', 'textdomain'),
            'add_new_item' => __('Ajouter un nouveau thème', 'textdomain'),
            'new_item_name' => __('Nom du nouveau thème', 'textdomain'),
            'parent_item' => __('Thème parent', 'textdomain'),
            'parent_item_colon' => __('Thème parent :', 'textdomain'),
            'search_items' => __('Rechercher des thèmes', 'textdomain'),
            'popular_items' => __('Thèmes populaires', 'textdomain'),
            'separate_items_with_commas' => __('Séparer les thèmes avec des virgules', 'textdomain'),
            'add_or_remove_items' => __('Ajouter ou supprimer des thèmes', 'textdomain'),
            'choose_from_most_used' => __('Choisir parmi les plus utilisés', 'textdomain'),
            'not_found' => __('Aucun thème trouvé.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'theme',
            'with_front' => false,
        ),
    ));

    // Federation taxonomy for job
    register_taxonomy('federation', array('job'), array(
        'labels' => array(
            'name' => __('Fédérations', 'textdomain'),
            'singular_name' => __('Fédération', 'textdomain'),
            'menu_name' => __('Fédérations', 'textdomain'),
            'all_items' => __('Toutes les fédérations', 'textdomain'),
            'edit_item' => __('Modifier la fédération', 'textdomain'),
            'view_item' => __('Voir la fédération', 'textdomain'),
            'update_item' => __('Mettre à jour la fédération', 'textdomain'),
            'add_new_item' => __('Ajouter une nouvelle fédération', 'textdomain'),
            'new_item_name' => __('Nom de la nouvelle fédération', 'textdomain'),
            'parent_item' => __('Fédération parente', 'textdomain'),
            'parent_item_colon' => __('Fédération parente :', 'textdomain'),
            'search_items' => __('Rechercher des fédérations', 'textdomain'),
            'popular_items' => __('Fédérations populaires', 'textdomain'),
            'separate_items_with_commas' => __('Séparer les fédérations avec des virgules', 'textdomain'),
            'add_or_remove_items' => __('Ajouter ou supprimer des fédérations', 'textdomain'),
            'choose_from_most_used' => __('Choisir parmi les plus utilisées', 'textdomain'),
            'not_found' => __('Aucune fédération trouvée.', 'textdomain'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'federation',
            'with_front' => false,
        ),
    ));
}

add_action('init', 'register_custom_taxonomies');

/**
 * Remove default category taxonomy from custom post types
 * Each post type now has its own category taxonomy
 */
function remove_default_category_from_custom_post_types() {
    // Unregister default category from all CPTs - they now use their own category taxonomies
    unregister_taxonomy_for_object_type('category', 'post'); // Uses category_enjeu
    unregister_taxonomy_for_object_type('category', 'evenement'); // Uses category_evenement
    unregister_taxonomy_for_object_type('category', 'campagne'); // Uses category_campagne
    // document already uses category_document
    // job uses category_job
}

add_action('init', 'remove_default_category_from_custom_post_types', 20);

/**
 * Remove tags taxonomy from custom post types and renamed post type
 */
function remove_tags_from_custom_post_types() {
    // Unregister post_tag from custom post types and the renamed 'post' type
    unregister_taxonomy_for_object_type('post_tag', 'post'); // This is our renamed 'enjeu'
    unregister_taxonomy_for_object_type('post_tag', 'evenement');
    unregister_taxonomy_for_object_type('post_tag', 'document');
    unregister_taxonomy_for_object_type('post_tag', 'campagne');
    unregister_taxonomy_for_object_type('post_tag', 'job');
}

add_action('init', 'remove_tags_from_custom_post_types', 20);