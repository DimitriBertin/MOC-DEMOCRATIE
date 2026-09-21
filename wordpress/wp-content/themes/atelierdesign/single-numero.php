<?php
/**
 * Single Numero (Revue) - Sommaire
 *
 * Page de liste des articles composant la revue :
 *   1. Titre de la revue + filtre Années (affiche uniquement si le sommaire
 *      couvre plusieurs annees de publication)
 *   2. Texte d'introduction (champ ACF `introduction`, voir src/fieldGroups/numero.php)
 *   3. Grille des articles du numero (3 colonnes, tout le sommaire)
 *   4. CTA Footer (si renseigne sur la revue)
 *
 * La liste s'appuie sur le composant list-filters, contexte `numero_articles`
 * (voir src/components/list-filters/filter-functions.php).
 */

global $adwp;
?>

<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="numero" class="article">

  <?php while (have_posts()): the_post(); ?>

    <?php
    $numero_id = get_the_ID();

    // Le titre affiche vient de ad_list_default_title() (champ hero `title`,
    // repli sur le titre du post) : il reste ainsi identique lors des
    // re-rendus AJAX de la barre de filtres.
    $numero_intro = get_field('introduction', $numero_id);
    ?>

    <?php
    get_template_part('src/components/list-filters/markup', null, [
      'intro'   => $numero_intro,
      'context' => [
        'type'     => 'numero_articles',
        'id'       => $numero_id,
        'per_page' => -1, // sommaire complet, pas de pagination
      ],
    ]);
    ?>

    <?php
    /* CTA Footer */
    $cta_footer_data = get_field('cta_footer');

    if (!empty($cta_footer_data) && !empty($cta_footer_data['ctaFooter_items'])) {
      get_template_part('src/components/ctaFooter/markup', null, $cta_footer_data);
    }
    ?>

  <?php endwhile; ?>

</main>

<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
