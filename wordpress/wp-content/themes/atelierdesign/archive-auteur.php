<?php
/**
 * Archive Auteur·rices (/auteurs/)
 *
 *  - Titre
 *  - Index alphabetique (lettres cliquables)
 *  - Liste de tou·tes les auteur·rices, sans photo, groupee par lettre
 *    (tri sur le nom de famille, voir ad_auteur_sort_key()),
 *    avec le nombre d'articles de chacun·e
 *  - CTA Footer global
 */

global $adwp;

$groups = ad_get_auteurs_grouped();
?>

<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="auteurs-archive" class="article">

  <!-- Titre + index -->
  <section class="archive-header @sm:mt-[70px] @md/lg:mt-[130px] py-section theme-white bg-layout-main">
    <div class="container">
      <h1 class="archive-title text-yellow text-display autoscale">Auteur·rices</h1>

      <?php if (!empty($groups)): ?>
        <nav class="auteurs-index autoscale flex flex-wrap @sm:gap-2 @md/lg:gap-3 @sm:mt-6 @md/lg:mt-10" aria-label="Index alphabetique">
          <?php foreach (array_keys($groups) as $letter): ?>
            <a href="#lettre-<?php echo esc_attr($letter === '#' ? 'autres' : strtolower($letter)); ?>" class="auteurs-index__letter menu">
              <?php echo esc_html($letter); ?>
            </a>
          <?php endforeach; ?>
        </nav>
      <?php endif; ?>
    </div>
  </section>

  <!-- Liste -->
  <section class="auteurs-list py-section pt-0 theme-white bg-layout-main">
    <div class="container">

      <?php if (empty($groups)): ?>
        <p class="paragraph-md paragraph-primary autoscale">Aucun·e auteur·rice pour le moment.</p>
      <?php endif; ?>

      <?php foreach ($groups as $letter => $rows): ?>
        <div class="auteurs-group autoscale" id="lettre-<?php echo esc_attr($letter === '#' ? 'autres' : strtolower($letter)); ?>">
          <h2 class="auteurs-group__letter heading-lg heading-primary"><?php echo esc_html($letter); ?></h2>

          <ul class="auteurs-group__list grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-x-3 @md/lg:gap-x-3">
            <?php foreach ($rows as $row):
              $auteur = $row['post'];
              $count  = (int) $row['count'];
            ?>
              <li class="auteurs-group__item">
                <a href="<?php echo esc_url(get_permalink($auteur->ID)); ?>" class="auteur-link group flex items-baseline justify-between @sm:gap-4 @md/lg:gap-4">
                  <span class="auteur-link__name heading-sm heading-primary group-hover:opacity-80 transition-opacity duration-200">
                    <?php echo esc_html(get_the_title($auteur->ID)); ?>
                  </span>
                  <span class="auteur-link__count label label-primary whitespace-nowrap">
                    <?php echo $count; ?> <?php echo $count > 1 ? 'articles' : 'article'; ?>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>

    </div>
  </section>

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
