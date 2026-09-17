<?php

/**
 * Recherche du site
 * ─────────────────────────────────────────────────────────────────────────────
 * 1. Étend la recherche WordPress à tous les CPTs du thème
 * 2. Ajoute une tolérance aux fautes de frappe (Levenshtein) sur les titres
 */

/**
 * Types de contenu inclus dans la recherche.
 */
function moc_search_post_types(): array {
  return [
    'post',
    'page',
    'document',
    'evenement',
    'campagne',
    'thematique',
    'numero',
    'job',
    'auteur',
  ];
}

/**
 * Inclure tous les CPTs dans la recherche WordPress
 *
 * Par défaut WP ne cherche que dans 'post' et 'page'.
 */
add_action('pre_get_posts', function (WP_Query $query) {

  if (! $query->is_search() || ! $query->is_main_query() || is_admin()) {
    return;
  }

  $query->set('post_type', moc_search_post_types());
  $query->set('posts_per_page', 24);

}, 1);


/**
 * ── Fuzzy search (tolérance aux fautes de frappe) ────────────────────────────
 *
 * Algorithme Levenshtein appliqué aux titres de tous les posts publiés.
 * Seuils type Algolia : 0 faute (≤ 3 car.), 1 faute (4-7 car.), 2 fautes (8+ car.)
 *   → "documnt"   → matche "document"
 *   → "evenemnt"  → matche "evenement"
 *
 * Les IDs trouvés sont injectés dans le WHERE via OR ID IN (...),
 * sans toucher à la recherche LIKE existante (titre + contenu + extrait).
 */

/**
 * Normalise une chaîne : minuscules + suppression des accents.
 * "Événement" → "evenement"
 */
function moc_normalize(string $str): string {
  $str = mb_strtolower($str);
  $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
  return preg_replace('/[^a-z0-9\s]/', '', $str);
}

/**
 * Retourne les IDs des posts dont le titre contient un mot
 * à distance Levenshtein ≤ seuil du terme recherché.
 *
 * @param string $raw_term   Terme brut saisi par l'utilisateur.
 * @param array  $post_types CPTs à inclure dans la recherche.
 * @return int[]
 */
function moc_fuzzy_post_ids(string $raw_term, array $post_types): array {
  $raw_term = trim($raw_term);
  if (mb_strlen($raw_term) < 3) return [];

  global $wpdb;

  $types_in = implode("','", array_map('esc_sql', $post_types));
  $rows     = $wpdb->get_results(
    "SELECT ID, post_title
     FROM {$wpdb->posts}
     WHERE post_status = 'publish'
     AND post_type IN ('{$types_in}')"
  );

  // Découpe la requête en mots (≥ 3 caractères)
  $words = array_filter(
    preg_split('/\s+/', moc_normalize($raw_term)),
    fn($w) => mb_strlen($w) >= 3
  );
  if (empty($words)) return [];

  $matching = [];

  foreach ($rows as $row) {
    $title_norm  = moc_normalize($row->post_title);
    $title_words = preg_split('/[\s\-_\/,;:.!?]+/', $title_norm);

    foreach ($words as $word) {
      $len = mb_strlen($word);

      $max_dist = match (true) {
        $len <= 3 => 0,
        $len <= 7 => 1,
        default   => 2,
      };

      foreach ($title_words as $tw) {
        if (mb_strlen($tw) < 2) continue;

        // Correspondance exacte : déjà couverte par le LIKE de WP
        if ($word === $tw) {
          continue 3;
        }

        if ($max_dist > 0 && levenshtein($word, $tw) <= $max_dist) {
          $matching[] = (int) $row->ID;
          continue 3;
        }
      }

      // Sous-chaîne : "docu" retrouve "document" → déjà couvert par le LIKE
      if ($len >= 4 && strpos($title_norm, $word) !== false) {
        continue 2;
      }
    }
  }

  return array_unique($matching);
}

add_filter('posts_search', function (string $search, WP_Query $query): string {
  if (! $query->is_search() || ! $query->is_main_query() || is_admin()) return $search;

  $term = trim(get_query_var('s'));
  if (mb_strlen($term) < 3) return $search;

  $fuzzy_ids = moc_fuzzy_post_ids($term, moc_search_post_types());

  if (empty($fuzzy_ids)) return $search;

  global $wpdb;
  $ids_sql = implode(',', $fuzzy_ids);

  // Retire le "AND" initial du $search WP, puis englobe les deux
  // conditions (LIKE exact + IDs fuzzy) dans un seul AND (... OR ...).
  $inner  = trim(preg_replace('/^\s*AND\s*/i', '', trim($search)));
  $search = " AND ( {$inner} OR {$wpdb->posts}.ID IN ({$ids_sql}) )";

  return $search;
}, 10, 2);
