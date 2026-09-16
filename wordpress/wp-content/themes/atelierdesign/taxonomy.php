<?php
/**
 * Custom Taxonomy Archive Template
 * 
 * Displays custom taxonomy archive pages (type_enjeu, type_document, theme)
 */

global $adwp;

// Get current term
$current_term = get_queried_object();
$taxonomy = $current_term->taxonomy ?? '';

// Set appropriate title based on taxonomy
$title_map = [
    'type_enjeu' => 'Types d\'Enjeu',
    'type_document' => 'Types de Document', 
    'theme' => 'Thèmes'
];

$archive_title = $title_map[$taxonomy] ?? $current_term->name;
?>

<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="taxonomy-archive" class="theme-dark-green" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
  
  <?php
  // Archive Header with Title and Filters
  $archive_data = [
    'title' => $current_term->name,
    'description' => $current_term->description
  ];
  get_template_part('src/components/archive-header/markup', null, $archive_data);
  ?>

  <!-- Posts Grid -->
  <section class="archive-posts py-section">
    <div class="container">
      
      <?php if (have_posts()): ?>
        <div class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-8 @md/lg:gap-12">
          <?php while (have_posts()): the_post(); ?>
            <?php get_template_part('src/components/_post-card/markup'); ?>
          <?php endwhile; ?>
        </div>

        <?php
        // Pagination
        $pagination = paginate_links(array(
            'type' => 'array',
            'prev_text' => '← Précédent',
            'next_text' => 'Suivant →',
            'end_size' => 2,
            'mid_size' => 1
        ));
        
        if ($pagination): ?>
          <nav class="pagination mt-16 flex justify-center">
            <ul class="flex items-center gap-2">
              <?php foreach ($pagination as $page): ?>
                <li><?php echo $page; ?></li>
              <?php endforeach; ?>
            </ul>
          </nav>
        <?php endif; ?>

      <?php else: ?>
        <div class="no-results text-center py-16">
          <h2 class="text-3xl font-safiro font-bold text-dark-green mb-4">
            Aucun résultat trouvé
          </h2>
          <p class="text-gray-600 mb-8">
            Aucun contenu n'est disponible pour "<?php echo esc_html($current_term->name); ?>" pour le moment.
          </p>
          <a href="<?php echo esc_url(home_url('/')); ?>" 
             class="inline-flex items-center px-6 py-3 bg-yellow text-dark-green font-safiro font-semibold @sm:rounded-xl @md/lg:rounded-xl hover:bg-yellow/90 transition-colors">
            Retour à l'accueil
          </a>
        </div>
      <?php endif; ?>

    </div>
  </section>

</main>

<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>