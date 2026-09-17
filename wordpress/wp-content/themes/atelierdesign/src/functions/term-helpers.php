<?php
/**
 * Term helpers
 *
 * Les composants du theme interrogent encore des taxonomies qui peuvent ne plus
 * etre enregistrees (type_enjeu, category_enjeu, category_document, federation,
 * theme, ...). get_the_terms() et get_terms() renvoient alors un WP_Error, ce qui
 * provoque une erreur fatale des qu'on traite le resultat comme un tableau.
 *
 * Ces wrappers renvoient toujours un tableau (vide si la taxonomie n'existe pas,
 * si le post n'a aucun terme, ou en cas d'erreur).
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!function_exists('ad_get_the_terms')) {
    /**
     * Version tolerante de get_the_terms().
     *
     * @param int|WP_Post $post_id
     * @param string      $taxonomy
     * @return WP_Term[]
     */
    function ad_get_the_terms($post_id, $taxonomy)
    {
        if (empty($taxonomy) || !taxonomy_exists($taxonomy)) {
            return [];
        }

        $terms = get_the_terms($post_id, $taxonomy);

        if (is_wp_error($terms) || empty($terms) || !is_array($terms)) {
            return [];
        }

        return $terms;
    }
}

if (!function_exists('ad_get_the_term')) {
    /**
     * Premier terme d'une taxonomie pour un post, ou null.
     *
     * @param int|WP_Post $post_id
     * @param string      $taxonomy
     * @return WP_Term|null
     */
    function ad_get_the_term($post_id, $taxonomy)
    {
        $terms = ad_get_the_terms($post_id, $taxonomy);

        return !empty($terms) ? reset($terms) : null;
    }
}

if (!function_exists('ad_get_terms')) {
    /**
     * Version tolerante de get_terms().
     *
     * @param array $args Arguments get_terms() standards.
     * @return WP_Term[]
     */
    function ad_get_terms($args = [])
    {
        $taxonomies = isset($args['taxonomy']) ? (array) $args['taxonomy'] : [];

        foreach ($taxonomies as $taxonomy) {
            if (!taxonomy_exists($taxonomy)) {
                return [];
            }
        }

        $terms = get_terms($args);

        if (is_wp_error($terms) || empty($terms) || !is_array($terms)) {
            return [];
        }

        return $terms;
    }
}
