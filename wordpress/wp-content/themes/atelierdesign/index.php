<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>
<main id="index">
  <?php $fields = get_fields(); ?>
  <article class="article">
    <?php get_template_part('src/components/hero/include'); ?>
    <?php $adwp->render_flexible_layout($fields['flexible-layout']); ?>

    <?php
    // Articles lies (onglet "Articles lies" de l'article)
    if (is_singular('post') && function_exists('ad_get_related_article_ids')) {
      $related_ids = ad_get_related_article_ids(get_the_ID(), 3);

      if (!empty($related_ids)) {
        get_template_part('src/components/relatedArticle/markup', null, [
          'title' => get_field('related_title') ?: 'À lire aussi',
          'posts' => $related_ids,
        ]);
      }
    }
    ?>
    
    <?php
    // CTA Footer - get data from ACF fields of current post
    $cta_footer_data = get_field('cta_footer');
    if (!empty($cta_footer_data) && !empty($cta_footer_data['ctaFooter_items'])) {
      get_template_part('src/components/ctaFooter/markup', null, $cta_footer_data);
    }
    ?>
  </article>
</main>
<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>