<?php
/**
 * Données de démo (seed)
 * ─────────────────────────────────────────────────────────────────────────────
 * Outils > Données de démo
 *
 *  - 20 auteur·rices (avec courte présentation)
 *  - 20 thématiques + 10 sous-thématiques
 *  - 6 numéros de revue
 *  - 24 articles (texte, thématiques, auteur·rices, numéro, tags,
 *    formats podcast / débat), dates étalées sur ~18 mois
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
      <?php elseif ($notice === 'exists'): ?>
        <div class="notice notice-warning"><p>Des contenus de démo existent déjà : utilisez « Régénérer ».</p></div>
      <?php endif; ?>

      <p>
        Crée 20 auteur·rices, 20 thématiques + 10 sous-thématiques, 6 revues et 24 articles, reliés entre eux.<br>
        Images utilisées : médias #72 à #76 (<?php echo count($images); ?> trouvée(s)).<br>
        Le bouton « Supprimer » ne retire que les contenus de démo, jamais le contenu réel.
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
        <?php if ($total === 0): ?>
          <button class="button button-primary" name="moc_seed_op" value="create">Générer les contenus</button>
        <?php else: ?>
          <button class="button button-primary" name="moc_seed_op" value="regenerate" onclick="return confirm('Supprimer puis recréer les contenus de démo ?')">Régénérer</button>
          <button class="button" name="moc_seed_op" value="delete" onclick="return confirm('Supprimer tous les contenus de démo ?')">Supprimer</button>
        <?php endif; ?>
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

function moc_seed_images() {
    return array_values(array_filter([72, 73, 74, 75, 76], 'wp_attachment_is_image'));
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
 * thématique => [description, [sous-thématiques]]
 */
function moc_seed_data_thematiques() {
    return [
        'Travail et emploi'           => ['Comprendre les transformations du travail et défendre des emplois de qualité.', ['Conditions de travail', 'Chômage et activation']],
        'Sécurité sociale'            => ['Un pilier de la solidarité à protéger et à renforcer.', ['Pensions', 'Allocations familiales']],
        'Santé'                       => ['L’accès aux soins pour toutes et tous, un enjeu de justice sociale.', ['Santé mentale']],
        'Démocratie et participation' => ['Faire vivre la démocratie au-delà du vote.', ['Participation citoyenne', 'Extrême droite']],
        'Climat et transition juste'  => ['Une transition écologique qui ne laisse personne de côté.', ['Énergie']],
        'Migrations'                  => ['Accueil, droits et dignité des personnes migrantes.', []],
        'Logement'                    => ['Le droit à un logement décent et abordable.', []],
        'Éducation permanente'        => ['Apprendre ensemble pour comprendre et transformer la société.', []],
        'Enseignement'                => ['Une école qui réduit les inégalités au lieu de les reproduire.', []],
        'Fiscalité'                   => ['Une fiscalité plus juste pour financer le bien commun.', []],
        'Pauvreté et précarité'       => ['Lutter contre la pauvreté et le non-recours aux droits.', ['Non-recours aux droits']],
        'Féminisme et égalité'        => ['L’égalité entre les femmes et les hommes, dans les faits.', []],
        'Numérique'                   => ['Les enjeux démocratiques et sociaux du numérique.', []],
        'Europe'                      => ['Construire une Europe sociale et démocratique.', []],
        'Solidarité internationale'   => ['Des solidarités qui dépassent les frontières.', []],
        'Économie sociale'            => ['Entreprendre autrement, au service de l’intérêt général.', []],
        'Mobilité'                    => ['Se déplacer mieux, pour tous les publics.', []],
        'Alimentation'                => ['Des systèmes alimentaires justes et durables.', []],
        'Jeunesse'                    => ['Écouter et soutenir l’engagement des jeunes.', []],
        'Culture'                     => ['La culture comme droit et comme espace d’émancipation.', []],
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
        if (isset($nom_tri[$name])) {
            update_field('field_auteur_nom_tri', $nom_tri[$name], $id);
        }
        $auteur_ids[] = $id;
    }

    // 2. Thématiques + sous-thématiques
    $thematique_ids = []; // nom => id
    $order = 0;
    foreach (moc_seed_data_thematiques() as $name => [$description, $children]) {
        $id = moc_seed_insert('thematique', $name, ['menu_order' => $order]);
        if (!$id) continue;
        update_field('field_thematique_clone_hero', ['title' => $description], $id);
        if ($img = $image($order)) set_post_thumbnail($id, $img);
        $thematique_ids[$name] = $id;

        foreach ($children as $j => $child) {
            $child_id = moc_seed_insert('thematique', $child, ['post_parent' => $id, 'menu_order' => $j]);
            if (!$child_id) continue;
            update_field('field_thematique_clone_hero', ['title' => $child . ' : analyses et points de vue.'], $child_id);
            if ($img = $image($order + $j + 1)) set_post_thumbnail($child_id, $img);
            $thematique_ids[$child] = $child_id;
        }
        $order++;
    }

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
        update_field('field_numero_introduction', '<p>Ce numéro de la revue Démocratie rassemble analyses, témoignages et points de vue autour du thème « ' . esc_html($title) . ' ».</p>', $id);
        if ($img = $image($i)) set_post_thumbnail($id, $img);
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

        // Thématiques
        $t_ids = [];
        foreach ($themes as $theme_name) {
            if (!empty($thematique_ids[$theme_name])) {
                $t_ids[] = $thematique_ids[$theme_name];
            }
        }
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
