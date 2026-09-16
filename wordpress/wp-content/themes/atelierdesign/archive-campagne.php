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
    'title' => 'Campagnes',
    'single' => true
  ];
  get_template_part('src/components/archive-header/markup', null, $archive_data);
  
  // Get current category filter if any
  $current_category = get_query_var('cat');
  $current_category_slug = '';
  if (!$current_category) {
    $current_category_slug = get_query_var('category_name');
  }
  
  // Also check for the filter system parameter (both standard WordPress and our filter system)
  if (!$current_category && !$current_category_slug) {
    if (!empty($_GET['category'])) {
      $current_category_slug = sanitize_text_field($_GET['category']);
    }
    // Also check WordPress standard category parameter
    if (!$current_category_slug && !empty($_GET['cat'])) {
      $current_category = intval($_GET['cat']);
    }
  }
  
  // Function to add category filter to query args
  function add_category_filter_to_args($args, $current_category, $current_category_slug) {
    if ($current_category || $current_category_slug) {
      $args['tax_query'] = [
        [
          'taxonomy' => 'category_campagne',
          'field' => $current_category ? 'term_id' : 'slug',
          'terms' => $current_category ? $current_category : $current_category_slug
        ]
      ];
    }
    return $args;
  }
  
  $today = date('Y-m-d');
  
  // Debug: Check what filters are being applied (remove in production)
  $has_filters = !empty($current_category) || !empty($current_category_slug);
  if (WP_DEBUG && $has_filters) {
    error_log('Archive-campagne filter detected: category=' . ($current_category ?: $current_category_slug));
  }
  
  // Store original query to prevent interference
  $original_query = $GLOBALS['wp_query'];
  ?>

  <!-- Upcoming Events Section -->
  <section class="upcoming-events py-section theme-white bg-layout-main">
    <div class="container">
      <?php
      // Query for upcoming events
      $upcoming_args = [
        'post_type' => 'campagne',
        'posts_per_page' => -1,
        'nopaging' => true,
        'post_status' => 'publish',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_key' => 'date_start',
        'meta_query' => [
          'relation' => 'OR',
          // Posts with date_end that is today or in the future
          [
            'key' => 'date_end',
            'value' => $today,
            'compare' => '>=',
            'type' => 'DATE'
          ],
          // Posts without date_end but with date_start today or in the future
          [
            'relation' => 'AND',
            [
              'relation' => 'OR',
              [
                'key' => 'date_end',
                'compare' => 'NOT EXISTS'
              ],
              [
                'key' => 'date_end',
                'value' => '',
                'compare' => '='
              ]
            ],
            [
              'key' => 'date_start',
              'value' => $today,
              'compare' => '>=',
              'type' => 'DATE'
            ]
          ]
        ]
      ];
      
      // Add category filter if present
      $upcoming_args = add_category_filter_to_args($upcoming_args, $current_category, $current_category_slug);
      
      $upcoming_query = new WP_Query($upcoming_args);
      
      // Temporarily set global query to upcoming query (which has no pagination)
      $GLOBALS['wp_query'] = $upcoming_query;
      ?>
      
      <?php if ($upcoming_query->have_posts()): ?>
        <div id="upcoming-events-grid" class="posts-grid flex flex-col @sm:gap-8 @md/lg:gap-20">
          <?php while ($upcoming_query->have_posts()): $upcoming_query->the_post(); ?>
            <?php get_template_part('src/components/_campaign-card/markup'); ?>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div id="upcoming-events-grid" class="no-posts text-center py-8">
          <p class="paragraph-lg text-gray-600">Aucune campagne à venir.</p>
        </div>
      <?php endif; ?>
      
      <?php wp_reset_postdata(); ?>
      <?php 
      // Reset global query before past events section
      $GLOBALS['wp_query'] = $original_query;
      ?>
    </div>
  </section>

  <!-- Past Events Section -->
  <section class="past-events py-section theme-light-green-70 bg-layout-main">
    <div class="container">
      <h2 class="heading-2xl @sm:mb-16 @md/lg:mb-16 heading-primary autoscale">Campagnes passées</h2>
      
      <?php
      // Query for past events
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
      
      $past_args = [
        'post_type' => 'campagne',
        'posts_per_page' => 6,
        'paged' => $paged,
        'post_status' => 'publish',
        'orderby' => 'meta_value',
        'order' => 'DESC',
        'meta_key' => 'date_start',
        'meta_query' => [
          'relation' => 'OR',
          // Posts with date_end that is before today
          [
            'key' => 'date_end',
            'value' => $today,
            'compare' => '<',
            'type' => 'DATE'
          ],
          // Posts without date_end but with date_start before today
          [
            'relation' => 'AND',
            [
              'relation' => 'OR',
              [
                'key' => 'date_end',
                'compare' => 'NOT EXISTS'
              ],
              [
                'key' => 'date_end',
                'value' => '',
                'compare' => '='
              ]
            ],
            [
              'key' => 'date_start',
              'value' => $today,
              'compare' => '<',
              'type' => 'DATE'
            ]
          ]
        ]
      ];
      
      // Add category filter if present
      $past_args = add_category_filter_to_args($past_args, $current_category, $current_category_slug);
      
      $past_query = new WP_Query($past_args);
      ?>
      
      <?php if ($past_query->have_posts()): ?>
        <div id="past-events-grid" class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-y-8 @md/lg:gap-y-12 @sm:gap-x-3 @md/lg:gap-x-3">
          <?php while ($past_query->have_posts()): $past_query->the_post(); ?>
            <?php get_template_part('src/components/_post-card/markup'); ?>
          <?php endwhile; ?>
        </div>

        <!-- Pagination for Past Events -->
        <?php 
        // Set up pagination for custom query
        $temp_query = $GLOBALS['wp_query'];
        $GLOBALS['wp_query'] = $past_query;
        get_template_part('src/components/pagination/markup');
        $GLOBALS['wp_query'] = $temp_query;
        ?>

      <?php else: ?>
        <div id="past-events-grid" class="no-posts text-center py-8">
          <p class="paragraph-lg text-gray-600">Aucune campagne passé.</p>
        </div>
      <?php endif; ?>
      
      <?php wp_reset_postdata(); ?>
      
      <?php
      // Restore original query to prevent any global interference
      $GLOBALS['wp_query'] = $original_query;
      ?>
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
