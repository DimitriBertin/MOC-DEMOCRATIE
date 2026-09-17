<?php
/**
 * Helpers du composant Thematique Card
 */

if (!defined('ABSPATH')) {
  exit;
}

if (!function_exists('ad_get_thematique_card_data')) {
  /**
   * Normalise les donnees d'affichage d'une thematique.
   *
   * Badge = titre de la thematique
   * Texte = hero.title (description courte) ou excerpt
   * Image = featured image
   */
  function ad_get_thematique_card_data($post_id) {
    $hero = get_field('hero', $post_id);
    $hero = is_array($hero) ? $hero : [];

    $description = $hero['title'] ?? '';
    if (empty($description)) {
      $description = get_the_excerpt($post_id);
    }

    $image_id = has_post_thumbnail($post_id) ? get_post_thumbnail_id($post_id) : null;

    return [
      'title'       => get_the_title($post_id),
      'permalink'   => get_permalink($post_id),
      'description' => $description,
      'image_id'    => $image_id,
    ];
  }
}
