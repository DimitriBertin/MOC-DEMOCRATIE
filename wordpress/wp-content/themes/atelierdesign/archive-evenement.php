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
    'title' => 'Événements',
    'single' => true
  ];
  get_template_part('src/components/archive-header/markup', null, $archive_data);
  
  // Get current category filter if any
  $current_category = get_query_var('cat');
  $current_category_slug = '';
  if (!$current_category) {
    $current_category_slug = get_query_var('category_name');
  }
  
  // Also check for the filter system parameter
  if (!$current_category && !$current_category_slug && !empty($_GET['category'])) {
    $current_category_slug = sanitize_text_field($_GET['category']);
  }
  
  $today     = date('Y-m-d'); // pour la meta_query SQL
  $today_raw = date('Ymd');   // pour les comparaisons PHP avec get_post_meta (format ACF)
  
  // Store original query to prevent interference
  $original_query = $GLOBALS['wp_query'];

  // Tax query partagée
  $tax_query = [];
  if ($current_category || $current_category_slug) {
    $tax_query = [
      [
        'taxonomy' => 'category_evenement',
        'field'    => $current_category ? 'term_id' : 'slug',
        'terms'    => $current_category ? $current_category : $current_category_slug,
      ]
    ];
  }

  // Récupérer TOUS les événements en une seule query, triés par date_start ASC
  $all_args = [
    'post_type'      => 'evenement',
    'posts_per_page' => -1,
    'nopaging'       => true,
    'post_status'    => 'publish',
    'orderby'        => 'meta_value',
    'meta_type'      => 'DATE',
    'order'          => 'ASC',
    'meta_key'       => 'date_start',
  ];

  if (!empty($tax_query)) {
    $all_args['tax_query'] = $tax_query;
  }

  $all_events = get_posts($all_args);

  // Séparer en PHP avec $today_raw (format Ymd = format brut ACF)
  // En cours / à venir : date_end >= today OU (date_end vide ET date_start >= today)
  // Passé : tout le reste
  $upcoming_posts = [];
  $past_posts     = [];

  foreach ($all_events as $post) {
    $date_start = get_post_meta($post->ID, 'date_start', true); // ex: "20260115"
    $date_end   = get_post_meta($post->ID, 'date_end', true);

    if (!empty($date_end)) {
      // A une date de fin → elle fait foi
      if ($date_end >= $today_raw) {
        $upcoming_posts[] = $post;
      } else {
        $past_posts[] = $post;
      }
    } else {
      // Pas de date de fin → on se base sur date_start
      if ($date_start >= $today_raw) {
        $upcoming_posts[] = $post;
      } else {
        $past_posts[] = $post;
      }
    }
  }

  // Les passés : ordre DESC (plus récent en premier)
  $past_posts = array_reverse($past_posts);
  ?>

  <!-- Upcoming Events Section -->
  <section class="upcoming-events py-section theme-white bg-layout-main">
    <div class="container">
      <?php if (!empty($upcoming_posts)): ?>
        <div id="upcoming-events-grid" class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-y-8 @md/lg:gap-y-12 @sm:gap-x-3 @md/lg:gap-x-3">
          <?php foreach ($upcoming_posts as $post): setup_postdata($post); ?>
            <?php get_template_part('src/components/_post-card/markup'); ?>
          <?php endforeach; wp_reset_postdata(); ?>
        </div>
      <?php else: ?>
        <div id="upcoming-events-grid" class="no-posts text-center py-8">
          <p class="paragraph-lg text-gray-600">Aucun événement à venir.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Past Events Section -->
  <section class="past-events py-section theme-light-green-70 bg-layout-main">
    <div class="container">
      <h2 class="heading-2xl @sm:mb-16 @md/lg:mb-16 heading-primary autoscale">Événements passés</h2>
      
      <?php
      // Pagination manuelle
      $per_page   = 6;
      $paged      = max(1, (int) get_query_var('paged'));
      $total      = count($past_posts);
      $past_paged = array_slice($past_posts, ($paged - 1) * $per_page, $per_page);
      ?>
      
      <?php if (!empty($past_paged)): ?>
        <div id="past-events-grid" class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-y-8 @md/lg:gap-y-12 @sm:gap-x-3 @md/lg:gap-x-3">
          <?php foreach ($past_paged as $post): setup_postdata($post); ?>
            <?php get_template_part('src/components/_post-card/markup'); ?>
          <?php endforeach; wp_reset_postdata(); ?>
        </div>

        <!-- Pagination for Past Events -->
        <?php
        $fake_query = new WP_Query();
        $fake_query->max_num_pages = ceil($total / $per_page);
        $fake_query->found_posts   = $total;
        $GLOBALS['wp_query'] = $fake_query;
        get_template_part('src/components/pagination/markup');
        $GLOBALS['wp_query'] = $original_query;
        ?>

      <?php else: ?>
        <div id="past-events-grid" class="no-posts text-center py-8">
          <p class="paragraph-lg text-gray-600">Aucun événement passé.</p>
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
        'text'  => 'Contactez-nous pour plus d\'informations',
        'link'  => [
          'url'    => '/contact',
          'title'  => 'Nous contacter',
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