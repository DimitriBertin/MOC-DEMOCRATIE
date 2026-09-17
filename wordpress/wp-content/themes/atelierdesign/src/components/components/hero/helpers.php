<?php

/**
 * Hero Component Helper Functions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Detect the current template type for hero component
 */
function get_hero_template_type() {
    if (is_front_page()) {
        return 'homepage';
    }
    
    if (is_page()) {
        return 'page';
    }
    
    if (is_singular('job')) {
        return 'single-job';
    }
    
    if (is_singular('campagne')) {
        return 'single-campagne';
    }
    
    if (is_singular('post')) {
        return 'single-post';
    }
    
    if (is_singular('evenement')) {
        return 'single-evenement';
    }
    
    if (is_singular('document')) {
        return 'single-document';
    }
    
    return 'page'; // Default fallback
}

/**
 * Generate automatic label based on template type and post data
 * Returns only text labels (not taxonomies which are handled separately)
 */
function get_hero_auto_label($template_type = null, $post_id = null) {
    if (!$template_type) {
        $template_type = get_hero_template_type();
    }
    
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    switch ($template_type) {
        case 'single-campagne':
            // Display only start and end dates (categories will be badges)
            $date_start = get_field('date_start', $post_id);
            $date_end = get_field('date_end', $post_id);
            
            if ($date_start) {
                $start_formatted = date_i18n('d.m.Y', strtotime($date_start));
                if ($date_end) {
                    $end_formatted = date_i18n('d.m.Y', strtotime($date_end));
                    return 'du ' . $start_formatted . ' au ' . $end_formatted;
                } else {
                    return $start_formatted;
                }
            }
            return '';
            
        case 'single-post':
        case 'single-evenement':
        case 'single-document':
            // Display only the date (taxonomies will be badges)
            if ($template_type === 'single-evenement') {
                $date_start = get_field('date_start', $post_id);
                $date_end = get_field('date_end', $post_id);
                
                if ($date_start) {
                    $start_formatted = date_i18n('d.m.Y', strtotime($date_start));
                    if ($date_end && $date_end !== $date_start) {
                        $end_formatted = date_i18n('d.m.Y', strtotime($date_end));
                        return $start_formatted . ' - ' . $end_formatted;
                    } else {
                        return $start_formatted;
                    }
                }
            } else {
                $post_date = get_the_date('d.m.Y', $post_id);
                if ($post_date) {
                    return $post_date;
                }
            }
            return '';
            
        default:
            return '';
    }
}

/**
 * Get taxonomy badges for single posts
 */
function get_hero_taxonomy_badges($template_type = null, $post_id = null) {
    if (!$template_type) {
        $template_type = get_hero_template_type();
    }
    
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $badges = [];
    
    switch ($template_type) {
        case 'single-campagne':
            // Get categories for campaigns
            $categories = ad_get_the_terms($post_id, 'category_campagne');
            if ($categories && !is_wp_error($categories)) {
                foreach ($categories as $category) {
                    $badges[] = [
                        'name' => $category->name,
                        'type' => 'category'
                    ];
                }
            }
            break;
        case 'single-evenement':
            // Get categories for campaigns
            $categories = ad_get_the_terms($post_id, 'category_evenement');
            if ($categories && !is_wp_error($categories)) {
                foreach ($categories as $category) {
                    $badges[] = [
                        'name' => $category->name,
                        'type' => 'category'
                    ];
                }
            }
            break;
        case 'single-job':
            $federation_terms = ad_get_the_terms($post_id, 'federation');
            if ($federation_terms && !is_wp_error($federation_terms)) {
                foreach ($federation_terms as $federation) {
                    $badges[] = [
                        'name' => $federation->name,
                        'type' => 'federation'
                    ];
                }
            }
            break;
        case 'single-post':
        case 'single-document':
            // Get type taxonomy
            $type_taxonomy = '';
            if ($template_type === 'single-post') {
                $type_taxonomy = 'type_enjeu';
            } elseif ($template_type === 'single-document') {
                $type_taxonomy = 'type_document';
            }
            
            if ($type_taxonomy) {
                $types = ad_get_the_terms($post_id, $type_taxonomy);
                if ($types && !is_wp_error($types)) {
                    foreach ($types as $type) {
                        $badges[] = [
                            'name' => $type->name,
                            'type' => 'type'
                        ];
                    }
                }
            }

            // Get categories
            $categories = ad_get_the_terms($post_id, 'category');
            if ($categories && !is_wp_error($categories)) {
                foreach ($categories as $category) {
                    $badges[] = [
                        'name' => $category->name,
                        'type' => 'category'
                    ];
                }
            }

            // Get themes (only for post and document)
            if (in_array($template_type, ['single-post', 'single-document'])) {
                $themes = ad_get_the_terms($post_id, 'theme');
                if ($themes && !is_wp_error($themes)) {
                    foreach ($themes as $theme) {
                        $badges[] = [
                            'name' => $theme->name,
                            'type' => 'theme'
                        ];
                    }
                }
            }
        
            break;
    }
    
    return $badges;
}

/**
 * Get hero data with automatic detection and fallbacks
 */
function get_hero_data($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Get hero fields
    $hero_data = get_field('hero', $post_id);
    if (!$hero_data) {
        $hero_data = [];
    }
    
    // Detect template type
    $template_type = get_hero_template_type();
    
    // Set defaults based on template type
    $defaults = [
        'title' => get_the_title($post_id),
        'background_image' => null,
        'label' => '',
        'cta_button' => null,
        'text_alignment' => 'center',
        'image_layout' => 'full-width',
        'template_type' => $template_type,
    ];
    
    // Merge with actual data
    $hero_data = array_merge($defaults, $hero_data);
    
    // Get post-type specific fields
    if (in_array($template_type, ['single-post', 'single-evenement', 'single-document'])) {
        $image_layout = get_field('hero_image_layout', $post_id);
        if ($image_layout) {
            $hero_data['image_layout'] = $image_layout;
        }
    }
    
    if ($template_type === 'page') {
        $text_alignment = get_field('hero_text_alignment', $post_id);
        if ($text_alignment) {
            $hero_data['text_alignment'] = $text_alignment;
        }
    }
    
    // Auto-generate label if not manually set
    if (empty($hero_data['label'])) {
        $hero_data['label'] = get_hero_auto_label($template_type, $post_id);
    }
    
    // Get taxonomy badges for single posts
    $hero_data['taxonomy_badges'] = get_hero_taxonomy_badges($template_type, $post_id);
    
    // Set featured image as background if no background image is set and template supports it
    if (!$hero_data['background_image'] && in_array($template_type, ['homepage', 'single-campagne', 'single-post', 'single-evenement', 'single-document'])) {
        $featured_image_id = get_post_thumbnail_id($post_id);
        if ($featured_image_id) {
            $hero_data['background_image'] = [
                'ID' => $featured_image_id,
                'url' => wp_get_attachment_url($featured_image_id),
                'alt' => get_post_meta($featured_image_id, '_wp_attachment_image_alt', true),
            ];
        }
    }
    
    return $hero_data;
}

/**
 * Check if hero should be displayed for current template
 */
function should_display_hero() {
    $template_type = get_hero_template_type();
    
    // Hero should be displayed on all mentioned template types
    $supported_templates = [
        'homepage',
        'page', 
        'single-job',
        'single-campagne',
        'single-post',
        'single-evenement',
        'single-document'
    ];
    
    return in_array($template_type, $supported_templates);
}