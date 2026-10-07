<?php
/**
 * Single Numero (Revue)
 *
 * Pas de hero. Structure :
 *   1. Desktop : couverture a gauche / sommaire a droite
 *      Mobile  : couverture puis sommaire
 *      - Ligne "Revue n°105 / Octobre 2026"
 *      - Titre de la revue + introduction (champ `introduction`, optionnel)
 *      - "Sommaire" (champ wysiwyg `sommaire`)
 *      - Bouton vers la liseuse en ligne (champ lien `liseuse`)
 *   2. Articles lies (champ relationship `articles_lies`, choix manuel), si renseignes
 *   3. CTA Footer (si renseigne sur la revue)
 *
 * Champs : src/fieldGroups/numero.php
 */

global $adwp;
?>

<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="numero" class="article">

  <?php while (have_posts()): the_post(); ?>

    <?php
    $numero_id    = get_the_ID();
    $numero_num   = get_field('numero_numero', $numero_id);
    $numero_title = get_field('title', $numero_id) ?: get_the_title($numero_id);
    $parution     = function_exists('ad_numero_parution_label') ? ad_numero_parution_label($numero_id) : '';
    $intro        = get_field('introduction', $numero_id);
    $sommaire     = get_field('sommaire', $numero_id);
    $liseuse      = get_field('liseuse', $numero_id);
    // Articles lies : choisis a la main dans le champ relationship `articles_lies`
    $article_ids  = array_values(array_filter((array) get_field('articles_lies', $numero_id), function ($id) {
      return get_post_status((int) $id) === 'publish';
    }));
    ?>

    <!-- 1. Couverture + sommaire -->
    <section class="numero-sommaire @sm:mt-[70px] @md/lg:mt-[130px] py-section theme-white bg-layout-main">
      <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-12 @sm:gap-8 @md/lg:gap-x-16 items-start">

          <!-- Couverture -->
          <div class="numero-cover md:col-span-5 md:sticky md:top-[160px]">
            <div class="@sm:rounded-xl @md/lg:rounded-xl overflow-hidden bg-dark-green shadow-[19px_19px_43px_rgba(0,0,0,0.09)]">
              <?php if (has_post_thumbnail($numero_id)): ?>
                <?php echo get_the_post_thumbnail($numero_id, 'large', [
                  'class' => 'w-full h-auto block',
                  'alt'   => esc_attr($numero_title),
                ]); ?>
              <?php else: ?>
                <div class="relative aspect-[3/4]">
                  <?php ad_render_card_placeholder($numero_id, $numero_title, 'publication'); ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Sommaire -->
          <div class="numero-content md:col-span-7 autoscale flex flex-col @sm:gap-6 @md/lg:gap-8">

            <div class="flex flex-col @sm:gap-3 @md/lg:gap-4">
              <p class="numero-meta label label-primary">
                <?php if ($numero_num !== '' && $numero_num !== null): ?>
                  Revue n°<?php echo esc_html($numero_num); ?>
                <?php endif; ?>
                <?php if ($parution): ?>
                  <span class="numero-meta__sep">/</span> <?php echo esc_html($parution); ?>
                <?php endif; ?>
              </p>

              <h1 class="heading-2xl heading-primary"><?php echo esc_html($numero_title); ?></h1>

              <?php if (!empty($intro)): ?>
                <div class="wysiwyg paragraph-lg paragraph-primary">
                  <?php echo wp_kses_post($intro); ?>
                </div>
              <?php endif; ?>
            </div>

            <?php if (!empty($sommaire)): ?>
              <div class="numero-sommaire__block">
                <h2 class="numero-sommaire__title text-display text-yellow">Sommaire</h2>
                <div class="numero-sommaire__content wysiwyg paragraph-md paragraph-primary">
                  <?php echo wp_kses_post($sommaire); ?>
                </div>
              </div>
            <?php endif; ?>

            <?php if (!empty($liseuse['url'])): ?>
              <div class="theme-yellow">
                <a href="<?php echo esc_url($liseuse['url']); ?>"
                   class="numero-liseuse button-flat button-primary"
                   target="<?php echo esc_attr($liseuse['target'] ?: '_blank'); ?>"
                   rel="noopener noreferrer">
                  <span class="material-symbols-outlined" aria-hidden="true">auto_stories</span>
                  <span class="button-title"><?php echo esc_html($liseuse['title'] ?: 'Lire la revue en ligne'); ?></span>
                </a>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </section>

    <!-- 2. Articles lies -->
    <?php if (!empty($article_ids)): ?>
      <section class="numero-articles py-section theme-white bg-layout-main pt-0">
        <div class="container">
          <h2 class="heading-lg heading-primary autoscale @sm:mb-8 @md/lg:mb-12">Articles liés</h2>
          <div class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-y-8 @md/lg:gap-y-12 @sm:gap-x-3 @md/lg:gap-x-3">
            <?php foreach ($article_ids as $article_id): ?>
              <?php get_template_part('src/components/_article-card/markup', null, ['post_id' => (int) $article_id]); ?>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <?php
    /* 3. CTA Footer */
    $cta_footer_data = get_field('cta_footer');

    if (!empty($cta_footer_data) && !empty($cta_footer_data['ctaFooter_items'])) {
      get_template_part('src/components/ctaFooter/markup', null, $cta_footer_data);
    }
    ?>

  <?php endwhile; ?>

</main>

<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
