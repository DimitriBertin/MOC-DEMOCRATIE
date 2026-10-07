<?php
/**
 * Article helpers
 *
 *  - Thematiques principales d'un article (thematiques racines, sans les sous-thematiques)
 *  - Auteur·rices d'un article (liens vers leur fiche)
 *  - Formats d'un article (icone "podcast" / "debat")
 *  - Archive des auteur·rices (tri alphabetique, sans pagination)
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ─────────────────────────────────────────────
 * Thematiques principales
 * ───────────────────────────────────────────── */

if (!function_exists('ad_get_main_thematiques')) {
    /**
     * Thematiques principales (racines) d'un article.
     *
     * Un article peut etre rattache a des sous-thematiques : on remonte
     * chacune jusqu'a sa thematique racine, puis on dedoublonne en gardant
     * l'ordre de saisie.
     *
     * @param int $post_id
     * @return WP_Post[]
     */
    function ad_get_main_thematiques($post_id) {
        $thematiques = function_exists('get_field') ? get_field('thematiques', $post_id) : null;

        if (empty($thematiques)) {
            return [];
        }

        $main = [];

        foreach ((array) $thematiques as $thematique) {
            $id = $thematique instanceof WP_Post ? $thematique->ID : (int) $thematique;
            if (!$id) {
                continue;
            }

            $ancestors = get_post_ancestors($id);
            $root_id   = !empty($ancestors) ? (int) end($ancestors) : $id;

            if (isset($main[$root_id]) || get_post_status($root_id) !== 'publish') {
                continue;
            }

            $root = get_post($root_id);
            if ($root instanceof WP_Post) {
                $main[$root_id] = $root;
            }
        }

        return array_values($main);
    }
}

/* ─────────────────────────────────────────────
 * Auteur·rices
 * ───────────────────────────────────────────── */

if (!function_exists('ad_get_article_auteurs')) {
    /**
     * Auteur·rices publie·es d'un article (aucune distinction principal / secondaire).
     *
     * @param int $post_id
     * @return WP_Post[]
     */
    function ad_get_article_auteurs($post_id) {
        $auteurs = function_exists('get_field') ? get_field('auteurs', $post_id) : null;

        if (empty($auteurs)) {
            return [];
        }

        $result = [];

        foreach ((array) $auteurs as $auteur) {
            $auteur = $auteur instanceof WP_Post ? $auteur : get_post((int) $auteur);

            if ($auteur instanceof WP_Post && $auteur->post_type === 'auteur' && $auteur->post_status === 'publish') {
                $result[$auteur->ID] = $auteur;
            }
        }

        return array_values($result);
    }
}

if (!function_exists('ad_join_names')) {
    /**
     * "A", "A et B", "A, B et C"
     *
     * @param string[] $items
     * @return string
     */
    function ad_join_names($items) {
        $items = array_values($items);
        $count = count($items);

        if ($count <= 1) {
            return $items[0] ?? '';
        }

        $last = array_pop($items);

        return implode(', ', $items) . ' et ' . $last;
    }
}

if (!function_exists('ad_render_article_auteurs')) {
    /**
     * Affiche "Par <auteur·rice>, <auteur·rice> et <auteur·rice>".
     *
     * @param int   $post_id
     * @param array $args {
     *   @type bool   $links  Noms cliquables vers la fiche (false dans un element deja cliquable, ex. une carte).
     *   @type string $class  Classes du conteneur.
     *   @type string $prefix Texte avant les noms.
     * }
     */
    function ad_render_article_auteurs($post_id, $args = []) {
        $args = wp_parse_args($args, [
            'links'  => true,
            'class'  => '',
            'prefix' => 'Par ',
        ]);

        $auteurs = ad_get_article_auteurs($post_id);

        if (empty($auteurs)) {
            return;
        }

        $names = array_map(function ($auteur) use ($args) {
            $name = esc_html(get_the_title($auteur->ID));

            if (!$args['links']) {
                return $name;
            }

            return sprintf(
                '<a href="%s" class="article-auteurs__link">%s</a>',
                esc_url(get_permalink($auteur->ID)),
                $name
            );
        }, $auteurs);

        printf(
            '<p class="article-auteurs %s">%s%s</p>',
            esc_attr($args['class']),
            esc_html($args['prefix']),
            ad_join_names($names) // deja echappe
        );
    }
}

if (!function_exists('ad_get_auteur_article_ids')) {
    /**
     * Ids des articles publies d'un·e ou plusieurs auteur·rices.
     *
     * La relation est portee par le champ ACF `auteurs` de l'article
     * (tableau serialise d'IDs) : on interroge donc en LIKE '"ID"'.
     *
     * @param int|int[] $auteur_ids
     * @return int[]
     */
    function ad_get_auteur_article_ids($auteur_ids) {
        $auteur_ids = array_filter(array_map('intval', (array) $auteur_ids));

        if (empty($auteur_ids)) {
            return [];
        }

        $meta_query = ['relation' => 'OR'];

        foreach ($auteur_ids as $auteur_id) {
            $meta_query[] = [
                'key'     => 'auteurs',
                'value'   => '"' . $auteur_id . '"',
                'compare' => 'LIKE',
            ];
        }

        return get_posts([
            'post_type'        => 'post',
            'post_status'      => 'publish',
            'posts_per_page'   => -1,
            'fields'           => 'ids',
            'no_found_rows'    => true,
            'suppress_filters' => true,
            'meta_query'       => $meta_query,
        ]);
    }
}

if (!function_exists('ad_auteur_sort_key')) {
    /**
     * Cle de tri alphabetique d'un·e auteur·rice.
     *
     * Champ ACF "nom_tri" s'il est renseigne, sinon le dernier mot du nom
     * ("Jeanne Dupont" -> "Dupont").
     *
     * @param int $auteur_id
     * @return string
     */
    function ad_auteur_sort_key($auteur_id) {
        $key = function_exists('get_field') ? trim((string) get_field('nom_tri', $auteur_id)) : '';

        if ($key === '') {
            $parts = preg_split('/\s+/u', trim(get_the_title($auteur_id)));
            $key   = (string) end($parts);
        }

        return function_exists('moc_normalize') ? moc_normalize($key) : remove_accents(mb_strtolower($key));
    }
}

if (!function_exists('ad_count_articles_by_auteur')) {
    /**
     * Nombre d'articles publies par auteur·rice, en une seule requete.
     *
     * @return array auteur_id => int
     */
    function ad_count_articles_by_auteur() {
        global $wpdb;

        $values = $wpdb->get_col(
            "SELECT pm.meta_value
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
             WHERE pm.meta_key = 'auteurs'
             AND p.post_type = 'post'
             AND p.post_status = 'publish'"
        );

        $counts = [];

        foreach ($values as $value) {
            $ids = maybe_unserialize($value);
            foreach (array_unique(array_map('intval', (array) $ids)) as $id) {
                if ($id > 0) {
                    $counts[$id] = ($counts[$id] ?? 0) + 1;
                }
            }
        }

        return $counts;
    }
}

if (!function_exists('ad_get_auteurs_grouped')) {
    /**
     * Tou·tes les auteur·rices publie·es, triees et groupees par lettre.
     *
     * @return array lettre => [ ['post' => WP_Post, 'count' => int], ... ]
     */
    function ad_get_auteurs_grouped() {
        $auteurs = get_posts([
            'post_type'      => 'auteur',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'no_found_rows'  => true,
        ]);

        $counts = ad_count_articles_by_auteur();
        $rows   = [];

        foreach ($auteurs as $auteur) {
            $rows[] = [
                'post'  => $auteur,
                'key'   => ad_auteur_sort_key($auteur->ID) . ' ' . moc_normalize_safe(get_the_title($auteur->ID)),
                'count' => $counts[$auteur->ID] ?? 0,
            ];
        }

        usort($rows, function ($a, $b) {
            return strcmp($a['key'], $b['key']);
        });

        $groups = [];

        foreach ($rows as $row) {
            $letter = strtoupper(substr($row['key'], 0, 1));
            if (!preg_match('/[A-Z]/', $letter)) {
                $letter = '#';
            }
            $groups[$letter][] = $row;
        }

        return $groups;
    }
}

if (!function_exists('moc_normalize_safe')) {
    function moc_normalize_safe($str) {
        return function_exists('moc_normalize') ? moc_normalize((string) $str) : remove_accents(mb_strtolower((string) $str));
    }
}

/**
 * Archive des auteur·rices : tout sur une seule page.
 */
add_action('pre_get_posts', function ($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_post_type_archive('auteur')) {
        return;
    }

    $query->set('posts_per_page', -1);
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
});

/* ─────────────────────────────────────────────
 * Formats (podcast / debat)
 * ───────────────────────────────────────────── */

if (!function_exists('ad_article_format_definitions')) {
    /**
     * Formats disponibles. "icon" = nom d'une icone Material Symbols.
     */
    function ad_article_format_definitions() {
        return apply_filters('ad_article_format_definitions', [
            'podcast' => ['label' => 'Podcast', 'icon' => 'podcasts'],
            'debat'   => ['label' => 'Débat',   'icon' => 'forum'],
        ]);
    }
}

if (!function_exists('ad_get_article_formats')) {
    /**
     * Formats coches sur un article.
     *
     * @param int $post_id
     * @return array slug => ['label' => string, 'icon' => string]
     */
    function ad_get_article_formats($post_id) {
        $selected = function_exists('get_field') ? get_field('formats', $post_id) : null;

        if (empty($selected)) {
            return [];
        }

        $definitions = ad_article_format_definitions();
        $formats     = [];

        foreach ((array) $selected as $value) {
            $slug = is_array($value) ? ($value['value'] ?? '') : (string) $value;

            if (isset($definitions[$slug])) {
                $formats[$slug] = $definitions[$slug];
            }
        }

        return $formats;
    }
}

if (!function_exists('ad_render_article_formats')) {
    /**
     * Affiche les icones de format d'un article.
     *
     * @param int    $post_id
     * @param string $variant 'card' (pastilles sur l'image) | 'hero' (pastille + libelle) | 'inline'
     */
    function ad_render_article_formats($post_id, $variant = 'card') {
        $formats = ad_get_article_formats($post_id);

        if (empty($formats)) {
            return;
        }

        echo '<div class="article-formats autoscale article-formats--' . esc_attr($variant) . '">';

        foreach ($formats as $slug => $format) {
            printf(
                '<span class="article-format article-format--%1$s" title="%2$s"><span class="material-symbols-outlined" aria-hidden="true">%3$s</span><span class="%4$s">%2$s</span></span>',
                esc_attr($slug),
                esc_html($format['label']),
                esc_html($format['icon']),
                $variant === 'hero' ? 'article-format__label' : 'article-format__sr'
            );
        }

        echo '</div>';
    }
}
