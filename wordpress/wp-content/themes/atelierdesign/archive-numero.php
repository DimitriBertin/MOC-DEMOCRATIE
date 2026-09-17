<?php
/**
 * Archive Numero (Revues)
 *
 *  - Titre : "Nos Revues"
 *  - Filtres : annee (+ mois si une annee est selectionnee)
 *  - Liste des revues (date / numero / titre)
 *  - Pagination
 */

global $adwp;
?>

<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="numero-archive" class="article">

  <?php
  get_template_part('src/components/list-filters/markup', null, [
    'title'   => 'Nos Revues',
    'context' => [
      'type'     => 'numero',
      'per_page' => 9,
    ],
  ]);
  ?>

  <?php
  // CTA Footer global (options)
  $cta_footer_data = get_field('cta_footer', 'acf-options-global-fields');

  if (!empty($cta_footer_data) && !empty($cta_footer_data['ctaFooter_items'])) {
    get_template_part('src/components/ctaFooter/markup', null, $cta_footer_data);
  }
  ?>

</main>

<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
