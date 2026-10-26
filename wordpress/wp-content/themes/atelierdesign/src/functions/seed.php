<?php
/**
 * Données de démo (seed)
 * ─────────────────────────────────────────────────────────────────────────────
 * Outils > Données de démo
 *
 *  - 20 auteur·rices (avec courte présentation)
 *  - Thématiques : celles déjà créées sont CONSERVÉES (jamais supprimées).
 *    Les sous-thématiques de l'ancien site (revue-democratie.be) sont ajoutées
 *    sous leur thématique (créée si absente).
 *  - 6 numéros de revue (« Dans ce numéro », liseuse, articles ordonnés)
 *  - 24 articles (cover, chapeau, flexible content, thématiques, auteur·rices,
 *    numéro, tags, formats podcast / débat), dates étalées sur ~18 mois
 *  - « Réinitialiser » : supprime TOUS les articles existants avant de générer
 *  - « Enrichir À propos » : ajoute le contenu « Qui sommes-nous » à la page À propos
 *  - Relations miroirs (Thematique.articles, Auteur.articles, Numero.articles,
 *    Numero.thematiques, Thematique.numeros) remplies
 *
 * Tout le contenu créé est marqué par la meta `_moc_seed` : le bouton
 * "Supprimer" retire uniquement ces contenus (jamais le contenu réel).
 *
 * Passe par l'API ACF (update_field) : les field groups du thème ne sont pas
 * chargés sous WP-CLI, d'où une page d'admin plutôt qu'une commande.
 *
 * À désactiver en production :  define('MOC_ENABLE_SEED', false);  (wp-config.php)
 */

if (!defined('ABSPATH')) {
    exit;
}

if (defined('MOC_ENABLE_SEED') && !MOC_ENABLE_SEED) {
    return;
}

if (!defined('MOC_SEED_META')) {
    define('MOC_SEED_META', '_moc_seed');
}

/* ─────────────────────────────────────────────
 * Admin : page + actions
 * ───────────────────────────────────────────── */

add_action('admin_menu', function () {
    add_management_page(
        'Données de démo',
        'Données de démo',
        'manage_options',
        'moc-seed',
        'moc_seed_admin_page'
    );
});

function moc_seed_admin_page() {
    $counts = moc_seed_counts();
    $total  = array_sum($counts);
    $notice = isset($_GET['moc_seed_done']) ? sanitize_key(wp_unslash($_GET['moc_seed_done'])) : '';
    $images = moc_seed_images();
    ?>
    <div class="wrap">
      <h1>Données de démo</h1>

      <?php if ($notice === 'created'): ?>
        <div class="notice notice-success"><p>Contenus de démo créés.</p></div>
      <?php elseif ($notice === 'deleted'): ?>
        <div class="notice notice-success"><p>Contenus de démo supprimés.</p></div>
      <?php elseif ($notice === 'reset'): ?>
        <div class="notice notice-success"><p>Articles existants supprimés, contenus de démo créés.</p></div>
      <?php elseif ($notice === 'about'): ?>
        <div class="notice notice-success"><p>Page À propos enrichie.</p></div>
      <?php elseif ($notice === 'about_missing'): ?>
        <div class="notice notice-error"><p>Impossible de créer la page À propos.</p></div>
      <?php elseif ($notice === 'about_done'): ?>
        <div class="notice notice-warning"><p>La page À propos a déjà été enrichie.</p></div>
      <?php elseif ($notice === 'exists'): ?>
        <div class="notice notice-warning"><p>Des contenus de démo existent déjà : utilisez « Régénérer ».</p></div>
      <?php endif; ?>

      <p>
        Crée 20 auteur·rices, 6 revues et 24 articles (cover, chapeau, contenu, thématiques), reliés entre eux.<br>
        Les thématiques existantes sont conservées ; les sous-thématiques manquantes sont ajoutées.<br>
        Images utilisées pour les covers : <?php echo count($images); ?> image(s) de la médiathèque.<br>
        <strong>« Réinitialiser et générer » supprime TOUS les articles existants</strong> (démo ou non). « Supprimer » ne retire que les contenus de démo.
      </p>

      <table class="widefat striped" style="max-width:420px">
        <tbody>
          <?php foreach ($counts as $label => $count): ?>
            <tr><td><?php echo esc_html($label); ?></td><td><?php echo (int) $count; ?> de démo</td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-top:20px;display:flex;gap:8px">
        <?php wp_nonce_field('moc_seed'); ?>
        <input type="hidden" name="action" value="moc_seed">
        <button class="button button-primary" name="moc_seed_op" value="reset" onclick="return confirm('Supprimer TOUS les articles existants (et les revues / auteur·rices de démo), puis générer les contenus de démo ?')">Réinitialiser et générer</button>
        <?php if ($total > 0): ?>
          <button class="button" name="moc_seed_op" value="delete" onclick="return confirm('Supprimer tous les contenus de démo ?')">Supprimer</button>
        <?php endif; ?>
        <button class="button" name="moc_seed_op" value="about" onclick="return confirm('Créer (si besoin) et remplir la page À propos ?')">Enrichir la page À propos</button>
      </form>
    </div>
    <?php
}

add_action('admin_post_moc_seed', function () {
    if (!current_user_can('manage_options')) {
        wp_die('Accès refusé.');
    }
    check_admin_referer('moc_seed');

    @set_time_limit(300);

    $op   = sanitize_key($_POST['moc_seed_op'] ?? '');
    $done = '';

    if ($op === 'reset') {
        moc_seed_delete();
        moc_seed_delete_all_articles();
        moc_seed_run();
        $done = 'reset';
    }

    if ($op === 'about') {
        $done = moc_seed_enrich_about();
    }

    if ($op === 'delete' || $op === 'regenerate') {
        moc_seed_delete();
        $done = 'deleted';
    }

    if ($op === 'create' || $op === 'regenerate') {
        if (array_sum(moc_seed_counts()) > 0) {
            $done = 'exists';
        } else {
            moc_seed_run();
            $done = 'created';
        }
    }

    wp_safe_redirect(admin_url('tools.php?page=moc-seed&moc_seed_done=' . $done));
    exit;
});

/* ─────────────────────────────────────────────
 * Utilitaires
 * ───────────────────────────────────────────── */

function moc_seed_types() {
    return [
        'auteur'     => 'Auteur·rices',
        'thematique' => 'Thématiques',
        'numero'     => 'Revues',
        'post'       => 'Articles',
    ];
}

function moc_seed_ids($post_type) {
    return get_posts([
        'post_type'        => $post_type,
        'post_status'      => 'any',
        'posts_per_page'   => -1,
        'fields'           => 'ids',
        'meta_key'         => MOC_SEED_META,
        'suppress_filters' => true,
        'no_found_rows'    => true,
    ]);
}

function moc_seed_counts() {
    $counts = [];
    foreach (moc_seed_types() as $type => $label) {
        $counts[$label] = count(moc_seed_ids($type));
    }
    return $counts;
}

function moc_seed_delete() {
    foreach (array_keys(moc_seed_types()) as $type) {
        foreach (moc_seed_ids($type) as $id) {
            wp_delete_post($id, true);
        }
    }

    // Tags créés par le seed et devenus orphelins
    $terms = get_terms([
        'taxonomy'   => 'post_tag',
        'hide_empty' => false,
        'meta_key'   => MOC_SEED_META,
    ]);
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            if ((int) $term->count === 0) {
                wp_delete_term($term->term_id, 'post_tag');
            }
        }
    }
}

/**
 * Supprime TOUS les articles (post), quel que soit leur statut (corbeille comprise).
 * Les thématiques, pages et médias ne sont pas touchés.
 */
function moc_seed_delete_all_articles() {
    $ids = get_posts([
        'post_type'        => 'post',
        'post_status'      => 'any',
        'posts_per_page'   => -1,
        'fields'           => 'ids',
        'suppress_filters' => true,
        'no_found_rows'    => true,
    ]);
    $ids = array_merge($ids, get_posts([
        'post_type'        => 'post',
        'post_status'      => 'trash',
        'posts_per_page'   => -1,
        'fields'           => 'ids',
        'suppress_filters' => true,
        'no_found_rows'    => true,
    ]));

    foreach (array_unique($ids) as $id) {
        wp_delete_post($id, true);
    }
}

/**
 * Images de cover : médias #72 à #76 s'ils existent, sinon les images
 * les plus récentes de la médiathèque.
 */
function moc_seed_images() {
    $images = array_values(array_filter([72, 73, 74, 75, 76], 'wp_attachment_is_image'));

    if (count($images) >= 3) {
        return $images;
    }

    return array_map('intval', get_posts([
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'post_mime_type' => ['image/jpeg', 'image/png', 'image/webp'],
        'posts_per_page' => 12,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ]));
}

function moc_seed_norm($text) {
    $text = remove_accents(mb_strtolower((string) $text));
    return trim(preg_replace('/[^a-z0-9]+/', ' ', $text));
}

/**
 * Thématique existante (racine ou sous-thématique) dont le titre correspond :
 * égalité d'abord, puis inclusion. Retourne l'ID ou 0.
 */
function moc_seed_find_thematique($name, $parent = null) {
    $needle = moc_seed_norm($name);
    $posts  = get_posts([
        'post_type'        => 'thematique',
        'post_status'      => ['publish', 'draft', 'pending', 'private'],
        'posts_per_page'   => -1,
        'orderby'          => 'menu_order title',
        'order'            => 'ASC',
        'suppress_filters' => true,
        'no_found_rows'    => true,
    ]);

    $contains = 0;
    foreach ($posts as $post) {
        if ($parent !== null && (int) $post->post_parent !== (int) $parent) {
            continue;
        }
        $title = moc_seed_norm($post->post_title);
        if ($title === $needle) {
            return (int) $post->ID;
        }
        if (!$contains && $needle !== '' && $title !== '' && (strpos($title, $needle) !== false || strpos($needle, $title) !== false)) {
            $contains = (int) $post->ID;
        }
    }

    return $contains;
}

/**
 * Garantit les sous-thématiques de l'ancien site sous leur thématique.
 * Thématiques existantes conservées ; créées (et marquées seed) seulement si absentes.
 *
 * @return int[] IDs de toutes les thématiques (racines + sous) disponibles
 */
function moc_seed_ensure_thematiques($images = []) {
    $order   = 0;
    $has_any = (bool) get_posts([
        'post_type' => 'thematique', 'post_status' => 'any', 'posts_per_page' => 1,
        'fields' => 'ids', 'suppress_filters' => true, 'no_found_rows' => true,
    ]);

    foreach (moc_seed_data_thematiques() as $name => [$description, $children]) {
        $parent_id = moc_seed_find_thematique($name, 0);

        // Thématiques déjà recréées à la main : on n'ajoute que les sous-thématiques
        // des racines qui existent (une racine absente est ignorée).
        if (!$parent_id && $has_any) {
            $order++;
            continue;
        }

        if (!$parent_id) {
            $parent_id = moc_seed_insert('thematique', $name, ['menu_order' => 100 + $order]);
            if ($parent_id) {
                update_field('field_thematique_clone_hero', ['title' => $description], $parent_id);
                if ($images) set_post_thumbnail($parent_id, $images[$order % count($images)]);
            }
        }

        foreach ($children as $j => $child) {
            if (!$parent_id || moc_seed_find_thematique($child, $parent_id)) {
                continue;
            }
            $exact = false;
            foreach (get_children(['post_parent' => $parent_id, 'post_type' => 'thematique', 'post_status' => 'any']) as $c) {
                if (moc_seed_norm($c->post_title) === moc_seed_norm($child)) { $exact = true; }
            }
            if ($exact) continue;

            $child_id = moc_seed_insert('thematique', $child, ['post_parent' => $parent_id, 'menu_order' => $j]);
            if ($child_id) {
                update_field('field_thematique_clone_hero', ['title' => $child . ' : analyses et points de vue.'], $child_id);
                if ($images) set_post_thumbnail($child_id, $images[($order + $j + 1) % count($images)]);
            }
        }
        $order++;
    }

    return get_posts([
        'post_type'        => 'thematique',
        'post_status'      => 'publish',
        'posts_per_page'   => -1,
        'fields'           => 'ids',
        'orderby'          => 'menu_order title',
        'order'            => 'ASC',
        'suppress_filters' => true,
        'no_found_rows'    => true,
    ]);
}

function moc_seed_insert($post_type, $title, $args = []) {
    $id = wp_insert_post(array_merge([
        'post_type'   => $post_type,
        'post_title'  => $title,
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
    ], $args), true);

    if (is_wp_error($id) || !$id) {
        return 0;
    }

    update_post_meta($id, MOC_SEED_META, 1);

    return (int) $id;
}

/**
 * Valeur stockée du champ "couleur de section" pour un thème donné
 * (le champ stocke une couleur, son libellé est de la forme "white/main").
 */
function moc_seed_section_color($label = 'white/main') {
    $field = function_exists('acf_get_field') ? acf_get_field('field-section-color') : null;

    if (!$field) {
        return '';
    }

    foreach ((array) ($field['colors'] ?? []) as $value => $choice_label) {
        if (mb_strtolower($choice_label) === mb_strtolower($label)) {
            return $value;
        }
    }

    return $field['default_value'] ?? '';
}

/**
 * Flexible content d'un article : une section contenant un bloc texte.
 */
function moc_seed_body($html) {
    return [
        [
            'acf_fc_layout' => 'section',
            'section_color' => moc_seed_section_color('white/main'),
            'section'       => [
                [
                    'acf_fc_layout'    => '_wysiwyg',
                    '_wysiwyg_content' => $html,
                ],
            ],
        ],
    ];
}

/* ─────────────────────────────────────────────
 * Données (fictives)
 * ───────────────────────────────────────────── */

function moc_seed_data_auteurs() {
    return [
        ['Claire Vandenberghe', 'Sociologue du travail, elle s’intéresse aux nouvelles formes d’emploi et à leurs effets sur la protection sociale.'],
        ['Mehdi Benali', 'Animateur en éducation permanente, il accompagne des groupes citoyens dans des projets de participation locale.'],
        ['Sophie Lambert', 'Juriste spécialisée en droit social, elle suit les réformes de la sécurité sociale depuis plus de quinze ans.'],
        ['Thomas Dewitte', 'Économiste, il travaille sur la fiscalité juste et le financement des services publics.'],
        ['Aïcha Diallo', 'Militante associative, elle coordonne des actions autour du droit au logement et de l’accueil des personnes migrantes.'],
        ['Julien Marchal', 'Historien du mouvement ouvrier, il anime des formations sur l’histoire sociale belge.'],
        ['Nathalie Collignon', 'Chargée d’études, elle analyse les politiques de santé et l’accès aux soins.'],
        ['Karim El Amrani', 'Formateur, il travaille sur les enjeux du numérique et de l’inclusion digitale.'],
        ['Élise de Smet', 'Politologue, elle étudie la représentation politique et la montée des extrêmes droites en Europe.'],
        ['Pierre Lhoest', 'Ancien délégué syndical, il écrit sur la concertation sociale et la démocratie au travail.'],
        ['Fatou Ndiaye', 'Chercheuse en sciences de l’éducation, elle travaille sur les inégalités scolaires.'],
        ['Antoine Gilson', 'Ingénieur de formation, il suit les questions d’énergie et de transition juste.'],
        ['Marie Dubois', 'Journaliste indépendante, elle couvre les mobilisations sociales et féministes.'],
        ['Lucas Van den Broeck', 'Géographe, il s’intéresse à la mobilité et à l’aménagement du territoire.'],
        ['Inès Moreau', 'Animatrice jeunesse, elle accompagne des jeunes dans des projets d’engagement citoyen.'],
        ['Olivier Renard', 'Spécialiste de l’économie sociale, il étudie les coopératives et les alternatives économiques.'],
        ['Camille Hennaut', 'Chargée de plaidoyer, elle suit les politiques européennes et la solidarité internationale.'],
        ['Yasmina Haddad', 'Assistante sociale, elle témoigne des réalités de la précarité et du non-recours aux droits.'],
        ['Benoît Lejeune', 'Philosophe, il écrit sur la démocratie, les communs et l’éthique de la décision publique.'],
        ['Sarah Wouters', 'Agronome, elle travaille sur les systèmes alimentaires durables et l’agriculture paysanne.'],
    ];
}

/**
 * Fonction / titre des auteur·rices de démo.
 */
function moc_seed_data_fonctions() {
    return [
        'Claire Vandenberghe'  => 'Sociologue du travail',
        'Mehdi Benali'         => 'Animateur en éducation permanente',
        'Sophie Lambert'       => 'Juriste en droit social',
        'Thomas Dewitte'       => 'Économiste',
        'Aïcha Diallo'         => 'Militante associative',
        'Julien Marchal'       => 'Historien du mouvement ouvrier',
        'Nathalie Collignon'   => 'Chargée d’études',
        'Karim El Amrani'      => 'Formateur',
        'Élise de Smet'        => 'Politologue',
        'Pierre Lhoest'        => 'Ancien délégué syndical',
        'Fatou Ndiaye'         => 'Chercheuse en sciences de l’éducation',
        'Antoine Gilson'       => 'Ingénieur',
        'Marie Dubois'         => 'Journaliste indépendante',
        'Lucas Van den Broeck' => 'Géographe',
        'Inès Moreau'          => 'Animatrice jeunesse',
        'Olivier Renard'       => 'Spécialiste de l’économie sociale',
        'Camille Hennaut'      => 'Chargée de plaidoyer',
        'Yasmina Haddad'       => 'Assistante sociale',
        'Benoît Lejeune'       => 'Philosophe',
        'Sarah Wouters'        => 'Agronome',
    ];
}

/**
 * Noms à particule / composés : nom utilisé pour le classement alphabétique.
 */
function moc_seed_data_nom_tri() {
    return [
        'Élise de Smet'        => 'Smet',
        'Lucas Van den Broeck' => 'Van den Broeck',
        'Karim El Amrani'      => 'El Amrani',
    ];
}

/**
 * Structure de l'ancien site (revue-democratie.be) :
 * thématique => [description, [sous-thématiques]]
 */
function moc_seed_data_thematiques() {
    return [
        'Social'         => ['Protection sociale, emploi, logement, handicap : les droits sociaux au quotidien.', ['Protection sociale', 'Emploi / Formation', 'Logement', 'Handicap']],
        'Société'        => ['Asile, enseignement, jeunesse, égalité : les grands débats de société.', ['Asile et immigration', 'Enseignement', 'Jeunesse / Enfance', 'Égalité / Discrimination']],
        'Économie'       => ['Comprendre l’économie pour la mettre au service de toutes et tous.', []],
        'Santé'          => ['L’accès aux soins pour toutes et tous, un enjeu de justice sociale.', []],
        'Politique belge' => ['Décrypter les choix politiques et institutionnels belges.', []],
        'Environnement'  => ['Une transition écologique qui ne laisse personne de côté.', ['Développement durable', 'Énergie', 'Aménagement du territoire']],
        'International'  => ['Europe, pays du Sud, mondialisation : regarder au-delà des frontières.', ['Union européenne', 'Pays du Sud', 'Mondialisation']],
        'Culture'        => ['La culture comme droit et comme espace d’émancipation.', []],
        'COVID-19'       => ['Les effets sociaux et démocratiques de la crise sanitaire.', []],
    ];
}

/**
 * [numéro, titre, décalage de date]
 */
function moc_seed_data_numeros() {
    return [
        [101, 'Le travail en mutation',                   '-17 months'],
        [102, 'Sécurité sociale : 80 ans de solidarité',  '-14 months'],
        [103, 'Démocratie sous tension',                  '-11 months'],
        [104, 'Pour une transition juste',                '-8 months'],
        [105, 'Habiter dignement',                        '-5 months'],
        [106, 'Le numérique, un enjeu démocratique',      '-2 months'],
    ];
}

/**
 * [titre, chapô, [thématiques ou sous-thématiques], index du numéro (0-5) ou null, [formats], [tags]]
 */
function moc_seed_data_articles() {
    return [
        ['Plateformes numériques : quand l’algorithme devient patron', 'Livreurs, chauffeurs, micro-travailleurs : les plateformes redessinent les contours du salariat.', ['Conditions de travail', 'Numérique'], 0, [], ['plateformes', 'salariat']],
        ['Fin de carrière : tenir jusqu’à 67 ans ?', 'Le recul de l’âge de la pension pose la question de la pénibilité et de la soutenabilité du travail.', ['Pensions', 'Conditions de travail'], 0, ['debat'], ['pensions', 'pénibilité']],
        ['Limiter les allocations de chômage dans le temps : quels effets ?', 'Retour sur les expériences étrangères et les risques de basculement vers l’aide sociale.', ['Chômage et activation'], 0, [], ['chômage']],
        ['La concertation sociale à l’épreuve des crises', 'Le modèle belge de concertation tient-il encore face aux crises successives ?', ['Travail et emploi', 'Démocratie et participation'], 0, ['podcast'], ['syndicats', 'concertation']],
        ['80 ans de sécurité sociale : une histoire de luttes', 'Retour sur le pacte social de 1944 et sur ce qu’il nous dit aujourd’hui.', ['Sécurité sociale'], 1, [], ['histoire', 'solidarité']],
        ['Allocations familiales : un droit de l’enfant', 'Depuis la régionalisation, les systèmes divergent. Quelles conséquences pour les familles ?', ['Allocations familiales'], 1, [], ['familles']],
        ['Santé mentale : l’urgence d’un accès réel aux soins', 'Délais d’attente, coûts, stigmatisation : les obstacles restent nombreux.', ['Santé mentale', 'Santé'], 1, ['podcast'], ['soins']],
        ['Le non-recours, angle mort des politiques sociales', 'Des milliers de personnes ne perçoivent pas les droits auxquels elles peuvent prétendre.', ['Non-recours aux droits', 'Sécurité sociale'], 1, [], ['droits', 'précarité']],
        ['Cordon sanitaire : une digue qui tient encore ?', 'Analyse du cordon médiatique et politique face à l’extrême droite.', ['Extrême droite'], 2, ['debat'], ['extrême droite', 'médias']],
        ['Budgets participatifs : promesses et limites', 'Plusieurs communes se sont lancées. Qui participe vraiment ?', ['Participation citoyenne'], 2, [], ['communes', 'participation']],
        ['Éducation permanente : former des citoyens critiques', 'Une tradition belge qui reste essentielle face à la défiance démocratique.', ['Éducation permanente', 'Démocratie et participation'], 2, ['podcast'], ['éducation permanente']],
        ['Jeunes et politique : un désamour en trompe-l’œil', 'Les jeunes s’engagent, mais autrement. Encore faut-il les écouter.', ['Jeunesse', 'Participation citoyenne'], 2, [], ['jeunes', 'engagement']],
        ['Transition juste : qui paie la facture ?', 'Sans justice sociale, la transition écologique risque de se heurter à un mur.', ['Climat et transition juste', 'Fiscalité'], 3, ['debat'], ['climat', 'inégalités']],
        ['Précarité énergétique : se chauffer ou manger', 'La hausse des prix de l’énergie a plongé de nombreux ménages dans la difficulté.', ['Énergie', 'Pauvreté et précarité'], 3, [], ['énergie', 'précarité']],
        ['Manger local, un luxe ?', 'Rendre l’alimentation durable accessible à toutes et tous.', ['Alimentation'], 3, [], ['alimentation', 'agriculture']],
        ['Mobilité : sortir de la dépendance à la voiture', 'Les zones rurales et les publics fragilisés ne doivent pas être oubliés.', ['Mobilité', 'Climat et transition juste'], 3, ['podcast'], ['mobilité']],
        ['Logement : la crise silencieuse', 'Loyers en hausse, logements sociaux insuffisants : une crise qui touche de plus en plus de ménages.', ['Logement'], 4, [], ['logement', 'loyers']],
        ['Habitat léger : une alternative à encadrer', 'Tiny houses, yourtes, habitats groupés : les alternatives se multiplient.', ['Logement', 'Économie sociale'], 4, [], ['habitat']],
        ['Accueil des demandeurs d’asile : l’État hors-la-loi ?', 'Des milliers de condamnations, et toujours des personnes à la rue.', ['Migrations'], 4, ['debat'], ['asile', 'droits']],
        ['Femmes et logement : une double peine', 'Les familles monoparentales sont en première ligne de la crise du logement.', ['Féminisme et égalité', 'Logement'], 4, [], ['femmes', 'logement']],
        ['Fracture numérique : quand les services publics se digitalisent', 'Le tout-numérique exclut une partie de la population de ses droits.', ['Numérique', 'Non-recours aux droits'], 5, [], ['numérique', 'services publics']],
        ['Intelligence artificielle et travail : remplacer ou assister ?', 'Ce que l’IA change déjà dans les métiers, et ce que les travailleurs peuvent négocier.', ['Numérique', 'Conditions de travail'], 5, ['podcast', 'debat'], ['IA', 'travail']],
        ['Une Europe sociale est-elle encore possible ?', 'À l’heure des politiques d’austérité, le socle européen des droits sociaux reste fragile.', ['Europe', 'Sécurité sociale'], null, [], ['Europe']],
        ['Coopératives : entreprendre ensemble', 'Portraits de coopératives qui réinventent la manière de produire et de décider.', ['Économie sociale', 'Solidarité internationale'], null, [], ['coopératives']],
    ];
}

function moc_seed_paragraphs($title, $chapo, $thematique_names) {
    $theme = mb_strtolower(implode(' et ', array_slice($thematique_names, 0, 2)));

    return implode("\n", [
        '<p class="paragraph-lg">' . esc_html($chapo) . '</p>',
        '<h3 class="heading-xl">Un constat partagé</h3>',
        '<p>Depuis plusieurs années, les acteurs de terrain observent les mêmes tendances. Les questions liées à ' . esc_html($theme) . ' ne sont plus des sujets de spécialistes : elles traversent le quotidien de milliers de personnes et interrogent directement nos choix collectifs.</p>',
        '<p>Les chiffres disponibles confirment ce que les associations et les organisations sociales relaient depuis longtemps. Derrière les statistiques, ce sont des trajectoires de vie, des renoncements et parfois des ruptures qui se dessinent.</p>',
        '<h3 class="heading-xl">Des réponses à construire ensemble</h3>',
        '<p>Face à ces constats, les réponses ne peuvent être uniquement individuelles. Elles appellent des politiques publiques ambitieuses, mais aussi une mobilisation des corps intermédiaires, des mouvements sociaux et des citoyennes et citoyens eux-mêmes.</p>',
        '<ul><li>Renforcer les droits existants et leur effectivité ;</li><li>Associer les personnes concernées à la décision ;</li><li>Évaluer les politiques à l’aune de leurs effets sur les plus fragiles.</li></ul>',
        '<p>C’est à cette condition que « ' . esc_html($title) . ' » pourra devenir autre chose qu’un constat : un levier de transformation sociale.</p>',
    ]);
}

/**
 * Texte « Dans ce numéro » (édito du numéro), plus long que 500 signes
 * pour que la version raccourcie + le lien « lire en entier » soient visibles.
 */
function moc_seed_dans_ce_numero($title) {
    return implode("\n", [
        '<p>Ce numéro de la revue Démocratie est consacré à « ' . esc_html($title) . ' ». Nous y avons rassemblé des analyses, des témoignages de terrain et des points de vue contradictoires, pour comprendre ce qui se joue aujourd’hui et ce que nous pouvons y faire collectivement.</p>',
        '<p>Dans un contexte marqué par les crises successives, les politiques publiques peinent à répondre aux attentes sociales. Les autrices et auteurs de ce dossier interrogent les choix qui sont faits, mettent en lumière des alternatives et donnent la parole à celles et ceux qui les construisent au quotidien.</p>',
        '<p>Au sommaire, vous retrouverez des enquêtes, des décryptages et des entretiens. Chaque article peut se lire séparément, mais c’est leur mise en perspective qui dessine, nous l’espérons, des pistes d’action pour une société plus juste et plus démocratique.</p>',
        '<p>Bonne lecture, et n’hésitez pas à réagir : la revue est aussi un espace de débat.</p>',
    ]);
}

/**
 * Page « À propos » : la crée si elle n'existe pas, puis ajoute le contenu « Qui sommes-nous » (reformulé à partir de
 * la page revue-democratie.be) en fin de flexible content. Une seule fois.
 *
 * @return string code de notice
 */
function moc_seed_enrich_about() {
    if (!function_exists('update_field') || !function_exists('get_field')) {
        return 'about_missing';
    }

    $page = null;
    foreach (['a-propos', 'a-propos-de-nous', 'qui-sommes-nous', 'about', 'a-propos-de-la-revue'] as $slug) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if ($page) break;
    }
    if (!$page) {
        $found = get_posts([
            'post_type' => 'page', 'post_status' => ['publish', 'draft', 'private'],
            's' => 'propos', 'posts_per_page' => 1,
        ]);
        $page = $found ? $found[0] : null;
    }
    // Page absente : on la crée (publiée, slug « a-propos »)
    if (!$page) {
        $page_id = wp_insert_post([
            'post_type'   => 'page',
            'post_title'  => 'Qui sommes-nous ?',
            'post_name'   => 'a-propos',
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
        ], true);
        if (is_wp_error($page_id) || !$page_id) {
            return 'about_missing';
        }
        update_field('field_page_clone_hero', ['title' => 'Qui sommes-nous ?'], $page_id);
        $page = get_post($page_id);
    }
    if (get_post_meta($page->ID, '_moc_seed_about', true)) {
        return 'about_done';
    }

    $existing = get_field('flexible-layout', $page->ID, false);
    $existing = is_array($existing) ? $existing : [];

    $team = '<p><strong>Rédactrice en chef :</strong> Stéphanie Baudot<br><strong>Journaliste :</strong> Manon Legrand<br><strong>Éditrice responsable :</strong> Dominique Decoux, CIEP, BP 50, 1031 Bruxelles</p>'
          . '<h3 class="heading-xl">Comité de rédaction</h3>'
          . '<p>Mario Bucci, Benoît Dassy, Dominique Decoux, Ariane Estenne, Patrick Feltesse, Julien Gras, Pierre Ledecq, Antoinette Maia, Thomas Miessen, Véronique Oruba, François Reman, Louis Stalins, Nabil Sheikh Hassan, Svetlana Sholokhova, Christine Steinbach, Sandrine Warsztacki.</p>';

    $sections = [
        '<h2 class="heading-2xl">Qui sommes-nous ?</h2>'
        . '<p>La revue <em>Démocratie</em> existe depuis 1996. Elle succède au quotidien <em>La Cité</em>, journal du Mouvement ouvrier chrétien (MOC), dont la parution a cessé pour des raisons financières. Avec le soutien de la CSC et de la Mutualité chrétienne, le MOC a lancé une nouvelle revue qui reprend le nom d’une ancienne publication du mouvement.</p>'
        . '<p>Aujourd’hui, <em>Démocratie</em> est un mensuel tiré à plus de 3 000 exemplaires. Il propose 16 pages de réflexion politique, économique et sociale afin de susciter le débat. Il est édité par le CIEP, mouvement d’éducation permanente du MOC.</p>',

        '<h3 class="heading-xl">Un site pour consulter et partager</h3>'
        . '<p>Ce site archive les articles de la revue, consultables gratuitement par les étudiant·es, enseignant·es, formateur·rices et journalistes. Leur reproduction nécessite une autorisation préalable. La version papier comprend en plus des schémas, des recensions et des chroniques.</p>',

        '<h3 class="heading-xl">L’équipe</h3>' . $team,

        '<h3 class="heading-xl">Contact</h3>'
        . '<p>Revue Démocratie<br>Chaussée de Haecht, 579<br>1030 Bruxelles<br>Téléphone : 02/246.38.43<br>E-mail : <a href="mailto:democratie@moc.be">democratie@moc.be</a></p>',
    ];

    $rows = [];
    foreach ($sections as $html) {
        foreach (moc_seed_body($html) as $row) {
            $rows[] = $row;
        }
    }

    update_field('field-page-flexible-layout', array_merge($existing, $rows), $page->ID);
    update_post_meta($page->ID, '_moc_seed_about', 1);

    return 'about';
}

/* ─────────────────────────────────────────────
 * Génération
 * ───────────────────────────────────────────── */

function moc_seed_run() {
    if (!function_exists('update_field')) {
        return;
    }

    $images = moc_seed_images();
    $image  = function ($i) use ($images) {
        return $images ? $images[$i % count($images)] : 0;
    };

    // 1. Auteur·rices
    $auteur_ids = [];
    $nom_tri    = moc_seed_data_nom_tri();
    foreach (moc_seed_data_auteurs() as [$name, $presentation]) {
        $id = moc_seed_insert('auteur', $name);
        if (!$id) continue;
        update_field('field_auteur_presentation', $presentation, $id);
        $fonctions = moc_seed_data_fonctions();
        if (isset($fonctions[$name])) {
            update_field('field_auteur_fonction', $fonctions[$name], $id);
        }
        if (isset($nom_tri[$name])) {
            update_field('field_auteur_nom_tri', $nom_tri[$name], $id);
        }
        $auteur_ids[] = $id;
    }

    // 2. Thématiques existantes conservées + sous-thématiques de l'ancien site
    $all_thematiques = moc_seed_ensure_thematiques($images);
    $thematique_pool = [];   // id => titre
    foreach ($all_thematiques as $t_id) {
        $thematique_pool[$t_id] = get_the_title($t_id);
    }
    $pool_ids = array_keys($thematique_pool);
    $resolve_theme = function ($name, $i) use ($thematique_pool, $pool_ids) {
        $needle = moc_seed_norm($name);
        foreach ($thematique_pool as $id => $title) {
            if (moc_seed_norm($title) === $needle) return $id;
        }
        foreach ($thematique_pool as $id => $title) {
            $t = moc_seed_norm($title);
            if ($t !== '' && (strpos($t, $needle) !== false || strpos($needle, $t) !== false)) return $id;
        }
        return 0;
    };

    // 3. Numéros
    $numero_ids = [];
    foreach (moc_seed_data_numeros() as $i => [$number, $title, $offset]) {
        $date = date('Y-m-d 09:00:00', strtotime('first day of ' . $offset));
        $id   = moc_seed_insert('numero', $title, [
            'post_date'     => $date,
            'post_date_gmt' => get_gmt_from_date($date),
        ]);
        if (!$id) continue;
        update_field('field_numero_numero', $number, $id);
        update_field('field_numero_date_parution', date('Ymd', strtotime($date)), $id);
        update_field('field_numero_dans_ce_numero', moc_seed_dans_ce_numero($title), $id);
        if ($img = $image($i)) set_post_thumbnail($id, $img);
        if ($img = $image($i + 1)) update_field('field_numero_image_longue', $img, $id);
        $numero_ids[$i] = ['id' => $id, 'date' => $date];
    }

    // 4. Articles
    $rel_thematique = []; // thematique_id => [article_ids]
    $rel_auteur     = [];
    $rel_numero     = [];
    $numero_themes  = []; // numero_id => [thematique_ids racines]

    foreach (moc_seed_data_articles() as $i => [$title, $chapo, $themes, $numero_index, $formats, $tags]) {
        // Date : dans le mois du numéro, sinon récente
        $base = ($numero_index !== null && isset($numero_ids[$numero_index]))
            ? strtotime($numero_ids[$numero_index]['date'])
            : strtotime('-' . (10 + $i) . ' days');
        $date = date('Y-m-d H:i:s', $base + ($i % 4) * DAY_IN_SECONDS + 8 * HOUR_IN_SECONDS);

        $id = moc_seed_insert('post', $title, [
            'post_date'     => $date,
            'post_date_gmt' => get_gmt_from_date($date),
            'post_excerpt'  => $chapo,
        ]);
        if (!$id) continue;

        // Image (1 article sur 6 sans image : teste le visuel de repli)
        if ($i % 6 !== 5 && ($img = $image($i))) {
            set_post_thumbnail($id, $img);
        }

        // Contenu
        update_field('field_post_hero_image_layout', $i % 2 ? 'content' : 'full-width', $id);
        update_field('field-post-flexible-layout', moc_seed_body(moc_seed_paragraphs($title, $chapo, $themes)), $id);
        update_field('field_post_chapo', $chapo, $id);
        // 1 article sur 2 a sa propre liseuse (sinon : liseuse du numéro)
        if ($i % 2 === 0) {
            update_field('field_post_liseuse', [
                'title'  => 'Lire l’article et le télécharger en PDF',
                'url'    => 'https://www.calameo.com/',
                'target' => '_blank',
            ], $id);
        }

        // Thématiques
        $t_ids = [];
        foreach ($themes as $theme_name) {
            if ($found = $resolve_theme($theme_name, $i)) {
                $t_ids[] = $found;
            }
        }
        // Aucune correspondance : on répartit les articles sur les thématiques existantes
        // (une racine + parfois une de ses sous-thématiques)
        if (!$t_ids && $pool_ids) {
            $roots = array_values(array_filter($pool_ids, function ($t) { return !wp_get_post_parent_id($t); }));
            $root  = $roots ? $roots[$i % count($roots)] : $pool_ids[$i % count($pool_ids)];
            $t_ids[] = $root;
            $kids = get_children(['post_parent' => $root, 'post_type' => 'thematique', 'post_status' => 'publish']);
            if ($kids && $i % 3 !== 0) {
                $kids = array_values($kids);
                $t_ids[] = $kids[$i % count($kids)]->ID;
            }
        }
        $t_ids = array_values(array_unique($t_ids));
        update_field('field_post_rel_thematiques', $t_ids, $id);
        foreach ($t_ids as $t) {
            $rel_thematique[$t][] = $id;
        }

        // Auteur·rices : 1 à 3, sans distinction principal / secondaire
        $nb    = [1, 1, 2, 1, 3, 2][$i % 6];
        $a_ids = [];
        for ($k = 0; $k < $nb && $auteur_ids; $k++) {
            $a_ids[] = $auteur_ids[($i * 3 + $k * 7) % count($auteur_ids)];
        }
        $a_ids = array_values(array_unique($a_ids));
        update_field('field_post_rel_auteurs', $a_ids, $id);
        foreach ($a_ids as $a) {
            $rel_auteur[$a][] = $id;
        }

        // Numéro
        if ($numero_index !== null && isset($numero_ids[$numero_index])) {
            $n_id = $numero_ids[$numero_index]['id'];
            update_field('field_post_rel_numero', $n_id, $id);
            $rel_numero[$n_id][] = $id;
            foreach ($t_ids as $t) {
                $ancestors = get_post_ancestors($t);
                $numero_themes[$n_id][] = $ancestors ? (int) end($ancestors) : $t;
            }
        }

        // Formats (podcast / débat)
        if ($formats) {
            update_field('field_post_formats', $formats, $id);
        }

        // Tags
        $term_ids = [];
        foreach ($tags as $tag) {
            $term = term_exists($tag, 'post_tag');
            if (!$term) {
                $term = wp_insert_term($tag, 'post_tag');
                if (!is_wp_error($term)) {
                    add_term_meta((int) $term['term_id'], MOC_SEED_META, 1, true);
                }
            }
            if ($term && !is_wp_error($term)) {
                $term_ids[] = (int) (is_array($term) ? $term['term_id'] : $term);
            }
        }
        wp_set_post_terms($id, $term_ids, 'post_tag');
    }

    // 4b. Liseuse + "Pour aller plus loin" (2 autres numéros)
    $numero_list = array_values($numero_ids);
    foreach ($numero_list as $pos => $numero) {
        $more = [];
        foreach ([1, 2] as $step) {
            $more[] = $numero_list[($pos + $step) % count($numero_list)]['id'];
        }
        update_field('field_numero_numeros_lies', array_values(array_diff(array_unique($more), [$numero['id']])), $numero['id']);
    }
    foreach ($numero_ids as $numero) {
        update_field('field_numero_liseuse', [
            'title'  => 'Lire la revue en ligne',
            'url'    => 'https://www.calameo.com/',
            'target' => '_blank',
        ], $numero['id']);
    }

    // 5. Relations miroirs (écrites explicitement, en plus de la synchro bidirectionnelle ACF)
    foreach ($rel_thematique as $t => $ids) {
        update_field('field_thematique_rel_articles', array_values(array_unique($ids)), $t);
    }
    foreach ($rel_auteur as $a => $ids) {
        update_field('field_auteur_rel_articles', array_values(array_unique($ids)), $a);
    }
    foreach ($rel_numero as $n => $ids) {
        update_field('field_numero_rel_articles', array_values(array_unique($ids)), $n);
    }

    $thematique_numeros = [];
    foreach ($numero_themes as $n => $t_ids) {
        $t_ids = array_values(array_unique($t_ids));
        update_field('field_numero_rel_thematiques', $t_ids, $n);
        foreach ($t_ids as $t) {
            $thematique_numeros[$t][] = $n;
        }
    }
    foreach ($thematique_numeros as $t => $n_ids) {
        update_field('field_thematique_rel_numeros', array_values(array_unique($n_ids)), $t);
    }
}
