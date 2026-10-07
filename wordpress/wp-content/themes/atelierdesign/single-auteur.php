<?php
/**
 * Single Auteur·rice
 *
 * Structure de la page :
 *   1. Nom de l'auteur·rice (titre du post) + courte presentation (champ `presentation`)
 *      + lien de retour vers la liste des auteur·rices (/auteurs/). Pas de photo.
 *   2. Flexible content (champ `flexible-layout`, voir src/fieldGroups/auteur.php)
 *   3. Tous les articles lies a l'auteur·rice, en grille 3 colonnes (comme
 *      le composant relatedArticle). Pas de pagination : on affiche tout.
 *   4. CTA Footer (si renseigne sur l'auteur·rice)
 *
 * La relation Article <-> Auteur·rice est portee par le champ ACF `auteurs`
 * de l'Article (voir src/fieldGroups/post-relations.php), stocke sous forme
 * de tableau serialise d'IDs : on interroge donc en meta_query LIKE, comme
 * le fait le filtre "Auteur·rice" des pages de liste.
 */

global $adwp;
?>

<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="auteur" class="article">

  <?php while (have_posts()): the_post(); ?>

    <?php $auteur_id = get_the_ID(); ?>

    <!-- 1. Nom -->
    <section class="archive-header auteur-header @sm:mt-[70px] @md/lg:mt-[130px] py-section theme-white bg-layout-main">
      <div class="container">
        <a href="<?php echo esc_url(get_post_type_archive_link('auteur')); ?>" class="auteur-back label label-primary autoscale inline-block @sm:mb-4 @md/lg:mb-6">
          &larr; Tou·tes les auteur·rices
        </a>

        <h1 class="archive-title text-yellow text-display autoscale">
          <?php echo esc_html(get_the_title($auteur_id)); ?>
        </h1>

        <?php $presentation = get_field('presentation', $auteur_id); ?>
        <?php if (!empty($presentation)): ?>
          <div class="auteur-presentation wysiwyg paragraph-lg paragraph-primary autoscale @sm:mt-6 @md/lg:mt-10 @md/lg:max-w-[760px]">
            <?php echo wp_kses_post($presentation); ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <?php
    /* 2. Flexible content */
    $fields = get_fields();

    if (!empty($fields['flexible-layout'])) {
      $adwp->render_flexible_layout($fields['flexible-layout']);
    }
    ?>

    <?php
    /* 3. Articles de l'auteur·rice */
    $auteur_posts = get_posts([
      'post_type'      => 'post',
      'post_status'    => 'publish',
      'posts_per_page' => -1,
      'orderby'        => 'date',
      'order'          => 'DESC',
      'no_found_rows'  => true,
      'meta_query'     => [
        [
          'key'     => 'auteurs',
          'value'   => '"' . (int) $auteur_id . '"',
          'compare' => 'LIKE',
        ],
      ],
    ]);
    ?>

    <?php if (!empty($auteur_posts)): ?>
      <section class="auteur-articles py-section pt-0 theme-white bg-layout-main">
        <div class="container">
          <h2 class="heading-lg heading-primary autoscale @sm:mb-8 @md/lg:mb-12">
            <?php echo count($auteur_posts) > 1 ? 'Ses articles' : 'Son article'; ?>
            <span class="label label-primary align-middle @sm:ml-2 @md/lg:ml-3">(<?php echo count($auteur_posts); ?>)</span>
          </h2>
          <div class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-y-8 @md/lg:gap-y-12 @sm:gap-x-3 @md/lg:gap-x-3">
            <?php foreach ($auteur_posts as $auteur_post): ?>
              <?php get_template_part('src/components/_article-card/markup', null, [
                'post_id' => $auteur_post->ID,
              ]); ?>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php
    /* 4. CTA Footer */
    $cta_footer_data = get_field('cta_footer');

    if (!empty($cta_footer_data) && !empty($cta_footer_data['ctaFooter_items'])) {
      get_template_part('src/components/ctaFooter/markup', null, $cta_footer_data);
    }
    ?>

  <?php endwhile; ?>

</main>

<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
