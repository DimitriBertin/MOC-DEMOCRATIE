<?php
/**
 * Search Results Template
 */

global $adwp, $wp_query;

$search_query = get_search_query();
$found_posts  = (int) $wp_query->found_posts;
?>

<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header'); ?>

<main id="search-results" class="article">

  <!-- Hero recherche -->
  <section class="search-results-page-hero @sm:mt-[70px] @md/lg:mt-[130px] py-section theme-white bg-layout-main">
    <div class="container">
      <div class="autoscale flex flex-col @sm:gap-4 @md/lg:gap-6">
        <p class="label label-primary">Rechercher &amp; appuyer sur Entr&eacute;e</p>

        <form
          role="search"
          method="get"
          action="<?php echo esc_url(home_url('/')); ?>"
          class="search-results-page-form"
        >
          <textarea
            name="s"
            class="heading-lg heading-primary"
            placeholder="Que recherchez-vous ?"
            autocomplete="off"
            rows="1"
            aria-label="Recherche"
            js-search-textarea
          ><?php echo esc_textarea($search_query); ?></textarea>

          <button type="submit" class="appearance-none" aria-label="Lancer la recherche">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
              <circle cx="10.5" cy="10.5" r="7.5"/>
              <path d="M16 16L21 21"/>
            </svg>
          </button>
        </form>

        <?php if ($search_query) : ?>
          <p class="paragraph-md paragraph-primary">
            <?php echo $found_posts; ?> <?php echo $found_posts > 1 ? 'r&eacute;sultats trouv&eacute;s' : 'r&eacute;sultat trouv&eacute;'; ?>
          </p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Resultats -->
  <section class="search-results py-section pt-0 theme-white bg-layout-main">
    <div class="container">

      <?php if (have_posts()) : ?>
        <div class="results-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-3 @md/lg:gap-3">
          <?php while (have_posts()) : the_post();
            $post_type_obj = get_post_type_object(get_post_type());
            $type_label    = $post_type_obj ? $post_type_obj->labels->singular_name : '';
            $show_label    = $type_label && ! in_array(get_post_type(), ['post', 'page'], true);
          ?>
            <article class="search-result-item">
              <a href="<?php echo esc_url(get_permalink()); ?>" class="search-result-item-link autoscale">
                <div class="flex flex-col @sm:gap-2 @md/lg:gap-3">
                  <?php if ($show_label) : ?>
                    <span class="label label-primary"><?php echo esc_html($type_label); ?></span>
                  <?php endif; ?>
                  <h2 class="search-result-item-title heading-md heading-primary">
                    <?php echo esc_html(get_the_title()); ?>
                  </h2>
                </div>

                <span class="menu inline-flex items-center !leading-tight text-dark-green">
                  Lire la suite
                  <span class="link-arrows flex items-center @sm:-space-x-1 @md/lg:-space-x-1 @sm:ml-2 @md/lg:ml-2">
                    <svg class="@sm:w-2.5 @md/lg:w-2.5 @sm:h-2.5 @md/lg:h-2.5 flex-shrink-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M2.40017 9.59312C2.01527 9.25255 1.96327 8.64515 2.28402 8.23646L4.82401 5.00009L2.28402 1.76371C1.96327 1.35503 2.01527 0.74763 2.40017 0.407056C2.78507 0.0664816 3.35712 0.1217 3.67787 0.530389L6.70183 4.38342C6.98219 4.74064 6.98219 5.25953 6.70183 5.61675L3.67787 9.46979C3.35712 9.87847 2.78507 9.93369 2.40017 9.59312Z" fill="currentColor" />
                    </svg>
                    <svg class="@sm:w-2.5 @md/lg:w-2.5 @sm:h-2.5 @md/lg:h-2.5 flex-shrink-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M2.61599 9.59312C2.23109 9.25255 2.17909 8.64515 2.49984 8.23646L5.03983 5.00009L2.49984 1.76371C2.17909 1.35503 2.23109 0.74763 2.61599 0.407056C3.00089 0.0664816 3.57294 0.1217 3.89369 0.530389L6.91765 4.38342C7.19801 4.74064 7.19801 5.25953 6.91765 5.61675L3.89369 9.46979C3.57294 9.87847 3.00089 9.93369 2.61599 9.59312Z" fill="currentColor" />
                    </svg>
                  </span>
                </span>
              </a>
            </article>
          <?php endwhile; ?>
        </div>

        <?php get_template_part('src/components/pagination/markup'); ?>

      <?php else : ?>
        <div class="no-results autoscale flex flex-col @sm:gap-4 @md/lg:gap-4">
          <h2 class="heading-md heading-primary">
            <?php if ($search_query) : ?>
              Aucun r&eacute;sultat pour &laquo;&nbsp;<?php echo esc_html($search_query); ?>&nbsp;&raquo;
            <?php else : ?>
              Veuillez saisir un terme de recherche.
            <?php endif; ?>
          </h2>
          <p class="paragraph-md paragraph-primary">
            Essayez d'autres mots-cl&eacute;s ou parcourez nos contenus.
          </p>
        </div>
      <?php endif; ?>

    </div>
  </section>

</main>

<?php get_template_part('src/components/footer/markup', 'footer'); ?>
<?php get_footer(); ?>
