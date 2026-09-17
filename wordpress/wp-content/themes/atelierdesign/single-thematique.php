<?php
/**
 * Single Thematique
 *
 * Deux cas de figure :
 *
 * 1. THEMATIQUE PARENTE (pas de post_parent) -> page de liste
 *    - Titre de la thematique
 *    - Filtres : Les sous thematiques / Annees (+ Mois) / Auteur / Tags
 *    - Liste de TOUS les articles de la thematique ET de ses sous-thematiques
 *    - Pagination
 *
 * 2. SOUS-THEMATIQUE (post_parent renseigne) -> page editoriale
 *    - Hero + flexible content (comme une page classique)
 *    - Pas de liste d'articles pour l'instant : l'emplacement reste a definir.
 *      Pour l'activer, il suffit de retourner true sur le filtre
 *      'ad_sous_thematique_show_list' (voir plus bas).
 */

global $adwp;

$is_sous_thematique = (bool) wp_get_post_parent_id(get_the_ID());
?>

<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="thematique" class="article">

  <?php while (have_posts()): the_post(); ?>

    <?php if (!$is_sous_thematique): ?>

      <?php
      /* ---------- Thematique parente : page de liste ---------- */
      get_template_part('src/components/list-filters/markup', null, [
        'context' => [
          'type'     => 'thematique',
          'id'       => get_the_ID(),
          'per_page' => 9,
        ],
      ]);
      ?>

    <?php else: ?>

      <?php
      /* ---------- Sous-thematique : titre + flexible content ---------- */
      get_template_part('src/components/hero/include');

      $fields = get_fields();

      if (!empty($fields['flexible-layout'])) {
        $adwp->render_flexible_layout($fields['flexible-layout']);
      }

      /**
       * Liste des articles de la sous-thematique.
       *
       * Desactivee par defaut : l'emplacement dans la page reste a definir.
       * Pour l'activer :
       *   add_filter('ad_sous_thematique_show_list', '__return_true');
       */
      if (apply_filters('ad_sous_thematique_show_list', false, get_the_ID())) {
        get_template_part('src/components/list-filters/markup', null, [
          'context' => [
            'type'     => 'thematique',
            'id'       => get_the_ID(),
            'per_page' => 9,
          ],
        ]);
      }
      ?>

    <?php endif; ?>

    <?php
    // CTA Footer (si renseigne sur la thematique)
    $cta_footer_data = get_field('cta_footer');

    if (!empty($cta_footer_data) && !empty($cta_footer_data['ctaFooter_items'])) {
      get_template_part('src/components/ctaFooter/markup', null, $cta_footer_data);
    }
    ?>

  <?php endwhile; ?>

</main>

<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
