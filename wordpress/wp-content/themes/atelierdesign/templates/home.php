<?php

/**
 * Template Name: Home
 * Template Post Type: page
 */

?>
<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header'); ?>
<main id="template-home">
  <?php $fields = get_fields(); ?>
  <article class="article">
    <?php get_template_part('src/components/hero/include'); ?>
    <?php if (!empty($fields['services_cards'])) { get_template_part('src/components/services-cards/markup', 'services-cards', $fields['services_cards']); } ?>
    <?php $adwp->render_flexible_layout($fields['flexible-layout-1']); ?>
    <?php if (!empty($fields['news_events'])) { 
      get_template_part('src/components/news-events/markup', 'news-events', $fields['news_events']);
    } ?>
    <?php $adwp->render_flexible_layout($fields['flexible-layout-2']); ?>
  </article>
</main>
<?php get_template_part('src/components/footer/markup', 'footer'); ?>
<?php get_footer(); ?>
