<?php
/**
 * List Filters - Backend
 *
 * Systeme de filtres generique (AJAX) pour les pages de liste :
 *  - Page Thematique  : articles d'une thematique (+ sous-thematiques)
 *      filtres : sous-thematique, annee (+ mois), auteur-rice, tag
 *  - Page Revues      : archive du CPT numero
 *      filtres : annee (+ mois)
 *
 * Principe : tout est rendu cote serveur (barre de filtres + resultats).
 * L'AJAX renvoie simplement les deux fragments HTML, ce qui evite de dupliquer
 * la logique d'affichage en JS.
 *
 * Contexte attendu partout :
 *   ['type' => 'thematique'|'numero', 'id' => int, 'per_page' => int]
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Normalise un contexte.
 */
function ad_list_context($context = []) {
    $context = is_array($context) ? $context : [];

    return [
        'type'     => $context['type'] ?? 'thematique',
        'id'       => (int) ($context['id'] ?? 0),
        'per_page' => (int) ($context['per_page'] ?? 9),
    ];
}

/**
 * Mois en francais.
 */
function ad_list_months() {
    return [
        '01' => 'Janvier',
        '02' => 'Fevrier',
        '03' => 'Mars',
        '04' => 'Avril',
        '05' => 'Mai',
        '06' => 'Juin',
        '07' => 'Juillet',
        '08' => 'Aout',
        '09' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'Decembre',
    ];
}

/**
 * Parametres de filtre reconnus pour un type de liste.
 */
function ad_list_filter_params($type) {
    if ($type === 'numero') {
        return ['annee', 'mois'];
    }

    return ['sous_thematique', 'annee', 'mois', 'auteur', 'tag'];
}

/**
 * Filtres actifs (depuis $_GET ou depuis un tableau fourni, ex. AJAX).
 *
 * @return array param => valeur (string)
 */
function ad_list_active_filters($context, $source = null) {
    $context = ad_list_context($context);
    $source  = is_array($source) ? $source : $_GET;
    $active  = [];

    foreach (ad_list_filter_params($context['type']) as $param) {
        if (!empty($source[$param])) {
            $active[$param] = sanitize_text_field(wp_unslash($source[$param]));
        }
    }

    // Le mois n'a de sens qu'avec une annee selectionnee.
    if (empty($active['annee'])) {
        unset($active['mois']);
    }

    return $active;
}

/**
 * Ids des sous-thematiques (enfants directs) d'une thematique.
 */
function ad_thematique_children_ids($thematique_id) {
    $children = get_posts([
        'post_type'      => 'thematique',
        'post_parent'    => (int) $thematique_id,
        'posts_per_page' => -1,
        'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
        'fields'         => 'ids',
        'post_status'    => 'publish',
    ]);

    return is_array($children) ? $children : [];
}

/**
 * Ids de TOUTES les descendances d'une thematique (enfants, petits-enfants, ...).
 *
 * Un article rattache uniquement a une sous-thematique doit remonter dans la
 * page de sa thematique parente : le perimetre d'une thematique est donc
 * toujours "elle-meme + tous ses descendants".
 */
function ad_thematique_descendant_ids($thematique_id, $depth = 0) {
    if ($depth > 5) {
        return [];
    }

    $ids = [];

    foreach (ad_thematique_children_ids($thematique_id) as $child_id) {
        $ids[] = (int) $child_id;
        $ids = array_merge($ids, ad_thematique_descendant_ids($child_id, $depth + 1));
    }

    return array_values(array_unique($ids));
}

/**
 * Perimetre d'une liste thematique : la thematique (ou la sous-thematique
 * selectionnee) et tous ses descendants.
 */
function ad_thematique_scope_ids($thematique_id, $sous_thematique_id = 0) {
    $root = $sous_thematique_id ? (int) $sous_thematique_id : (int) $thematique_id;

    return array_merge([$root], ad_thematique_descendant_ids($root));
}

/**
 * Meta query "article lie a l'une de ces thematiques".
 * Le champ ACF relationship stocke un tableau serialise d'IDs.
 */
function ad_thematique_meta_query($thematique_ids) {
    $thematique_ids = array_filter(array_map('intval', (array) $thematique_ids));

    if (empty($thematique_ids)) {
        return [];
    }

    $meta_query = ['relation' => 'OR'];

    foreach ($thematique_ids as $id) {
        $meta_query[] = [
            'key'     => 'thematiques',
            'value'   => '"' . $id . '"',
            'compare' => 'LIKE',
        ];
    }

    return $meta_query;
}

/**
 * Arguments WP_Query pour une liste filtree.
 */
function ad_list_query_args($context, $active = [], $paged = 1) {
    $context = ad_list_context($context);
    $paged   = max(1, (int) $paged);

    if ($context['type'] === 'numero') {
        $args = [
            'post_type'      => 'numero',
            'post_status'    => 'publish',
            'posts_per_page' => $context['per_page'],
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        if (!empty($active['annee'])) {
            $ids = ad_numero_ids_for_period($active['annee'], $active['mois'] ?? '');
            $args['post__in'] = !empty($ids) ? $ids : [0];
            $args['orderby']  = 'post__in';
        }

        return $args;
    }

    // Thematique
    $scope_ids = ad_thematique_scope_ids($context['id'], $active['sous_thematique'] ?? 0);

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $context['per_page'],
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [],
        'tax_query'      => [],
    ];

    $thematique_mq = ad_thematique_meta_query($scope_ids);

    if (!empty($thematique_mq)) {
        $args['meta_query'][] = $thematique_mq;
    }

    if (!empty($active['auteur'])) {
        $args['meta_query'][] = [
            'key'     => 'auteurs',
            'value'   => '"' . (int) $active['auteur'] . '"',
            'compare' => 'LIKE',
        ];
    }

    if (count($args['meta_query']) > 1) {
        $args['meta_query']['relation'] = 'AND';
    }

    if (!empty($active['tag'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'post_tag',
            'field'    => 'slug',
            'terms'    => $active['tag'],
        ];
    }

    if (!empty($active['annee'])) {
        $date_query = ['year' => (int) $active['annee']];

        if (!empty($active['mois'])) {
            $date_query['month'] = (int) $active['mois'];
        }

        $args['date_query'] = [$date_query];
    }

    if (empty($args['tax_query'])) {
        unset($args['tax_query']);
    }

    if (empty($args['meta_query'])) {
        unset($args['meta_query']);
    }

    return $args;
}

/**
 * Ids des articles du perimetre d'une thematique, sans les filtres
 * annee / auteur / tag (sert a construire les options disponibles).
 */
function ad_thematique_scope_post_ids($context, $active = []) {
    $context = ad_list_context($context);

    $scope_ids = ad_thematique_scope_ids($context['id'], $active['sous_thematique'] ?? 0);

    $meta_query = ad_thematique_meta_query($scope_ids);

    if (empty($meta_query)) {
        return [];
    }

    $ids = get_posts([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [$meta_query],
    ]);

    return is_array($ids) ? $ids : [];
}

/**
 * Annees de publication presentes dans un ensemble d'articles.
 */
function ad_years_from_post_ids($post_ids) {
    global $wpdb;

    $post_ids = array_filter(array_map('intval', (array) $post_ids));

    if (empty($post_ids)) {
        return [];
    }

    $in = implode(',', $post_ids);

    $years = $wpdb->get_col(
        "SELECT DISTINCT YEAR(post_date) AS y FROM {$wpdb->posts} WHERE ID IN ({$in}) ORDER BY y DESC"
    );

    return array_values(array_filter(array_map('strval', (array) $years)));
}

/**
 * Mois presents dans un ensemble d'articles, pour une annee donnee.
 */
function ad_months_from_post_ids($post_ids, $year) {
    global $wpdb;

    $post_ids = array_filter(array_map('intval', (array) $post_ids));
    $year     = (int) $year;

    if (empty($post_ids) || !$year) {
        return [];
    }

    $in = implode(',', $post_ids);

    $months = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT DISTINCT LPAD(MONTH(post_date), 2, '0') AS m
             FROM {$wpdb->posts}
             WHERE ID IN ({$in}) AND YEAR(post_date) = %d
             ORDER BY m ASC",
            $year
        )
    );

    return array_values(array_filter((array) $months));
}

/**
 * Date de reference d'une revue : date_parution si renseignee, sinon date du post.
 */
function ad_numero_reference_date($post_id) {
    $parution = get_post_meta($post_id, 'date_parution', true);

    if (!empty($parution)) {
        $timestamp = strtotime($parution);

        if ($timestamp) {
            return date('Y-m-d', $timestamp);
        }
    }

    return get_the_date('Y-m-d', $post_id);
}

/**
 * Toutes les revues publiees, avec leur date de reference.
 *
 * @return array [post_id => 'Y-m-d'] trie du plus recent au plus ancien
 */
function ad_numero_dates_map() {
    static $map = null;

    if ($map !== null) {
        return $map;
    }

    $ids = get_posts([
        'post_type'      => 'numero',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    $map = [];

    foreach ((array) $ids as $id) {
        $map[(int) $id] = ad_numero_reference_date($id);
    }

    arsort($map);

    return $map;
}

/**
 * Annees disponibles pour les revues.
 */
function ad_numero_years() {
    $years = [];

    foreach (ad_numero_dates_map() as $date) {
        $years[substr($date, 0, 4)] = true;
    }

    $years = array_keys($years);
    rsort($years);

    return $years;
}

/**
 * Mois disponibles pour les revues d'une annee.
 */
function ad_numero_months($year) {
    $months = [];

    foreach (ad_numero_dates_map() as $date) {
        if (substr($date, 0, 4) === (string) $year) {
            $months[substr($date, 5, 2)] = true;
        }
    }

    $months = array_keys($months);
    sort($months);

    return $months;
}

/**
 * Ids des revues d'une annee (et eventuellement d'un mois).
 */
function ad_numero_ids_for_period($year, $month = '') {
    $ids = [];

    foreach (ad_numero_dates_map() as $id => $date) {
        if (substr($date, 0, 4) !== (string) $year) {
            continue;
        }

        if (!empty($month) && substr($date, 5, 2) !== str_pad((string) $month, 2, '0', STR_PAD_LEFT)) {
            continue;
        }

        $ids[] = (int) $id;
    }

    return $ids;
}

/**
 * Auteur-rices (ids) presents dans un ensemble d'articles.
 */
function ad_auteurs_from_post_ids($post_ids) {
    global $wpdb;

    $post_ids = array_filter(array_map('intval', (array) $post_ids));

    if (empty($post_ids)) {
        return [];
    }

    $in = implode(',', $post_ids);

    $values = $wpdb->get_col(
        "SELECT meta_value FROM {$wpdb->postmeta}
         WHERE post_id IN ({$in}) AND meta_key = 'auteurs'"
    );

    $auteur_ids = [];

    foreach ((array) $values as $value) {
        $unserialized = maybe_unserialize($value);

        foreach ((array) $unserialized as $id) {
            $id = (int) $id;

            if ($id && get_post_status($id) === 'publish') {
                $auteur_ids[$id] = true;
            }
        }
    }

    $auteur_ids = array_keys($auteur_ids);

    usort($auteur_ids, function ($a, $b) {
        return strcasecmp(get_the_title($a), get_the_title($b));
    });

    return $auteur_ids;
}

/**
 * Tags (WP_Term) presents dans un ensemble d'articles.
 */
function ad_tags_from_post_ids($post_ids) {
    $post_ids = array_filter(array_map('intval', (array) $post_ids));

    if (empty($post_ids) || !taxonomy_exists('post_tag')) {
        return [];
    }

    $terms = wp_get_object_terms($post_ids, 'post_tag', ['orderby' => 'name', 'order' => 'ASC']);

    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    return $terms;
}

/**
 * Construit les groupes de filtres a afficher.
 *
 * @return array liste de groupes :
 *   ['key','label','param','color','options' => [['value','label']]]
 */
function ad_list_filter_groups($context, $active = []) {
    $context = ad_list_context($context);
    $groups  = [];
    $months  = ad_list_months();

    if ($context['type'] === 'numero') {
        $years = ad_numero_years();

        if (!empty($years)) {
            $groups[] = [
                'key'     => 'annee',
                'label'   => 'Années',
                'param'   => 'annee',
                'color'   => 'bg-yellow',
                'options' => array_map(function ($year) {
                    return ['value' => $year, 'label' => $year];
                }, $years),
            ];
        }

        if (!empty($active['annee'])) {
            $available = ad_numero_months($active['annee']);

            if (!empty($available)) {
                $groups[] = [
                    'key'     => 'mois',
                    'label'   => 'Mois',
                    'param'   => 'mois',
                    'color'   => 'bg-light-green-70',
                    'options' => array_map(function ($month) use ($months) {
                        return ['value' => $month, 'label' => $months[$month] ?? $month];
                    }, $available),
                ];
            }
        }

        return $groups;
    }

    // ---------- Thematique ----------

    // Sous-thematiques : uniquement si la thematique courante a des enfants.
    $children = ad_thematique_children_ids($context['id']);

    if (!empty($children)) {
        $groups[] = [
            'key'     => 'sous_thematique',
            'label'   => 'Les sous thématiques',
            'param'   => 'sous_thematique',
            'color'   => 'bg-orange',
            'options' => array_map(function ($id) {
                return ['value' => (string) $id, 'label' => get_the_title($id)];
            }, $children),
        ];
    }

    // Perimetre courant (tient compte de la sous-thematique selectionnee).
    $scope_ids = ad_thematique_scope_post_ids($context, $active);

    // Annees
    $years = ad_years_from_post_ids($scope_ids);

    if (!empty($years)) {
        $groups[] = [
            'key'     => 'annee',
            'label'   => 'Années',
            'param'   => 'annee',
            'color'   => 'bg-yellow',
            'options' => array_map(function ($year) {
                return ['value' => $year, 'label' => $year];
            }, $years),
        ];
    }

    // Mois (uniquement si une annee est selectionnee)
    if (!empty($active['annee'])) {
        $available = ad_months_from_post_ids($scope_ids, $active['annee']);

        if (!empty($available)) {
            $groups[] = [
                'key'     => 'mois',
                'label'   => 'Mois',
                'param'   => 'mois',
                'color'   => 'bg-light-green-70',
                'options' => array_map(function ($month) use ($months) {
                    return ['value' => $month, 'label' => $months[$month] ?? $month];
                }, $available),
            ];
        }
    }

    // Auteur-rices presents dans le perimetre
    $auteurs = ad_auteurs_from_post_ids($scope_ids);

    if (!empty($auteurs)) {
        $groups[] = [
            'key'     => 'auteur',
            'label'   => 'Auteur',
            'param'   => 'auteur',
            'color'   => 'bg-light-green-70',
            'options' => array_map(function ($id) {
                return ['value' => (string) $id, 'label' => get_the_title($id)];
            }, $auteurs),
        ];
    }

    // Tags presents dans le perimetre
    $tags = ad_tags_from_post_ids($scope_ids);

    if (!empty($tags)) {
        $groups[] = [
            'key'     => 'tag',
            'label'   => 'Tags',
            'param'   => 'tag',
            'color'   => 'bg-yellow',
            'options' => array_map(function ($term) {
                return ['value' => $term->slug, 'label' => $term->name];
            }, $tags),
        ];
    }

    return $groups;
}

/**
 * Libelle affiche pour une valeur de filtre active (utilise par les tags).
 */
function ad_list_filter_option_label($groups, $param, $value) {
    foreach ($groups as $group) {
        if ($group['param'] !== $param) {
            continue;
        }

        foreach ($group['options'] as $option) {
            if ((string) $option['value'] === (string) $value) {
                return $option['label'];
            }
        }
    }

    if ($param === 'mois') {
        $months = ad_list_months();
        return $months[str_pad((string) $value, 2, '0', STR_PAD_LEFT)] ?? $value;
    }

    return $value;
}

/**
 * Couleur du tag pour un parametre donne.
 */
function ad_list_filter_color($groups, $param) {
    foreach ($groups as $group) {
        if ($group['param'] === $param) {
            return $group['color'];
        }
    }

    return 'bg-yellow';
}

/**
 * URL de base d'une liste (sans parametres).
 */
function ad_list_base_url($context) {
    $context = ad_list_context($context);

    if ($context['type'] === 'numero') {
        return get_post_type_archive_link('numero') ?: home_url('/revues/');
    }

    return get_permalink($context['id']);
}

/**
 * Titre par defaut d'une liste.
 */
function ad_list_default_title($context) {
    $context = ad_list_context($context);

    if ($context['type'] === 'numero') {
        return 'Nos Revues';
    }

    if (!$context['id']) {
        return '';
    }

    // Sous-thematique : "Thematique parente / Sous-thematique"
    $parent_id = wp_get_post_parent_id($context['id']);

    if ($parent_id) {
        return get_the_title($parent_id) . ' / ' . get_the_title($context['id']);
    }

    return get_the_title($context['id']);
}

/**
 * Rend la barre de filtres (dropdowns + tags actifs + reset).
 */
function ad_list_render_bar($context, $active = [], $title = null) {
    get_template_part('src/components/list-filters/bar', null, [
        'context' => ad_list_context($context),
        'active'  => $active,
        'title'   => $title,
    ]);
}

/**
 * Rend les resultats (grille + pagination).
 */
function ad_list_render_results($context, $active = [], $paged = 1) {
    get_template_part('src/components/list-filters/results', null, [
        'context' => ad_list_context($context),
        'active'  => $active,
        'paged'   => $paged,
    ]);
}

/**
 * AJAX : renvoie la barre de filtres + les resultats.
 */
function ad_handle_list_filter_ajax() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ad_list_filters')) {
        wp_send_json_error('Security check failed');
    }

    $context = ad_list_context([
        'type'     => sanitize_key($_POST['list_type'] ?? 'thematique'),
        'id'       => intval($_POST['context_id'] ?? 0),
        'per_page' => intval($_POST['per_page'] ?? 9),
    ]);

    $filters = isset($_POST['filters']) && is_array($_POST['filters']) ? $_POST['filters'] : [];
    $active  = ad_list_active_filters($context, $filters);
    $paged   = max(1, intval($_POST['page'] ?? 1));

    ob_start();
    ad_list_render_bar($context, $active);
    $bar_html = ob_get_clean();

    ob_start();
    ad_list_render_results($context, $active, $paged);
    $results_html = ob_get_clean();

    wp_send_json_success([
        'bar_html'     => $bar_html,
        'results_html' => $results_html,
    ]);
}

add_action('wp_ajax_ad_filter_list', 'ad_handle_list_filter_ajax');
add_action('wp_ajax_nopriv_ad_filter_list', 'ad_handle_list_filter_ajax');
