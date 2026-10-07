<?php

/**
 * Recherche du site
 * ─────────────────────────────────────────────────────────────────────────────
 * 1. Étend la recherche WordPress à tous les CPTs du thème
 * 2. Ajoute une tolérance aux fautes de frappe (Levenshtein) sur les titres
 * 3. Retrouve les articles d'une personne en cherchant son nom
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

/**
 * ── Recherche par auteur·rice ────────────────────────────────────────────────
 *
 * Taper le nom d'une personne ("dupont", "Jeanne Dupont", "dupnt") remonte sa
 * fiche ET tous les articles qu'elle signe (champ ACF `auteurs` de l'article).
 *
 * Un·e auteur·rice correspond si CHAQUE mot de la recherche (≥ 2 car.) est
 * present dans son nom : mot entier, debut de mot, ou avec une faute de frappe
 * (memes seuils Levenshtein que la recherche floue).
 *
 * @return int[] IDs des articles des auteur·rices correspondant·es.
 */
function moc_auteur_article_ids(string $raw_term): array {
  $words = array_values(array_filter(
    preg_split('/\s+/', moc_normalize(trim($raw_term))),
    fn($w) => mb_strlen($w) >= 2
  ));
  if (empty($words)) return [];

  global $wpdb;
  $rows = $wpdb->get_results(
    "SELECT ID, post_title FROM {$wpdb->posts}
     WHERE post_type = 'auteur' AND post_status = 'publish'"
  );

  $auteur_ids = [];

  foreach ($rows as $row) {
    $name_norm  = moc_normalize($row->post_title);
    $name_words = preg_split('/[\s\-_\/,;:.!?\']+/', $name_norm);

    foreach ($words as $word) {
      $len      = mb_strlen($word);
      $max_dist = $len <= 3 ? 0 : ($len <= 7 ? 1 : 2);
      $found    = false;

      foreach ($name_words as $nw) {
        if (mb_strlen($nw) < 2) continue;

        // Debut de mot : "dup" -> "Dupont" (mais "les" ne remonte pas "Charles")
        if (strpos($nw, $word) === 0) {
          $found = true;
          break;
        }

        // Faute de frappe : "dupnt" -> "Dupont"
        if ($max_dist > 0 && levenshtein($word, $nw) <= $max_dist) {
          $found = true;
          break;
        }
      }

      if (! $found) continue 2; // ce mot ne correspond pas : auteur·rice suivant·e
    }

    $auteur_ids[] = (int) $row->ID;
  }

  if (empty($auteur_ids) || ! function_exists('ad_get_auteur_article_ids')) return [];

  return ad_get_auteur_article_ids($auteur_ids);
}

add_filter('posts_search', function (string $search, WP_Query $query): string {
  if (! $query->is_search() || ! $query->is_main_query() || is_admin()) return $search;

  $term = trim(get_query_var('s'));
  if (mb_strlen($term) < 3) return $search;

  $fuzzy_ids = array_values(array_unique(array_merge(
    moc_fuzzy_post_ids($term, moc_search_post_types()),
    moc_auteur_article_ids($term)
  )));

  if (empty($fuzzy_ids) || trim($search) === '') return $search;

  global $wpdb;
  $ids_sql = implode(',', array_map('intval', $fuzzy_ids));

  // Retire le "AND" initial du $search WP, puis englobe les deux
  // conditions (LIKE exact + IDs fuzzy) dans un seul AND (... OR ...).
  $inner  = trim(preg_replace('/^\s*AND\s*/i', '', trim($search)));
  $search = " AND ( {$inner} OR {$wpdb->posts}.ID IN ({$ids_sql}) )";

  return $search;
}, 10, 2);
