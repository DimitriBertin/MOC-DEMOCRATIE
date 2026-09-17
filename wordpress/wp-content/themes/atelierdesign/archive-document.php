<?php
/**
 * Archive Template
 * 
 * Displays archive pages for posts, categories, etc.
 */

global $adwp;
?>

<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header'); ?>

<main id="archive-page" class="article">
  
  <?php
  // Archive Header with Title and Filters
  $archive_data = [
    'title' => 'Se documenter',
  ];
  get_template_part('src/components/archive-header/markup', null, $archive_data);
  ?>

  <!-- Posts Grid -->
  <section class="archive-posts py-section pt-0 theme-white bg-layout-main">
    <div class="container">
      
      <?php if (have_posts()): ?>
        <div class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-y-8 @md/lg:gap-y-12 @sm:gap-x-3 @md/lg:gap-x-3">
          <?php while (have_posts()): the_post(); ?>
            <?php get_template_part('src/components/_post-card/markup'); ?>
          <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php 
        // Use the pagination component
        get_template_part('src/components/pagination/markup');
        ?>

      <?php else: ?>
        <div class="no-posts text-center py-16">
          <p class="paragraph-lg text-yellow">Aucun contenu trouvé.</p>
        </div>
      <?php endif; ?>

    </div>
  </section>

  <?php
  // CTA Footer
  $cta_data = [
    'ctaFooter_items' => [
      [
        'title' => 'Envie d\'en savoir plus ?',
        'text' => 'Contactez-nous pour plus d\'informations',
        'link' => [
          'url' => '/contact',
          'title' => 'Nous contacter',
          'target' => '_self'
        ]
      ]
    ]
  ];
  get_template_part('src/components/ctaFooter/markup', null, $cta_data);
  ?>

</main>

<?php get_template_part('src/components/footer/markup', 'footer'); ?>
<?php get_footer(); ?>
