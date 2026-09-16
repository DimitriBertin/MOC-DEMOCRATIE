<?php
$section = is_array($args) ? $args : [];
$today = date('Y-m-d');

$events_query_args = [
  'post_type'      => 'evenement',
  'posts_per_page' => -1, // on filtre en PHP après
  'post_status'    => 'publish',
  'orderby'        => 'meta_value',
  'meta_type'      => 'DATE',
  'order'          => 'ASC',
  'meta_key'       => 'date_start',
  'meta_query'     => [
      [
          'key'     => 'date_start',
          'value'   => $today,
          'compare' => '>=',
          'type'    => 'DATE',
      ],
  ],
];

if (!empty($section['events_category'])) {
  $events_query_args['tax_query'] = [
      [
          'taxonomy' => 'category_evenement',
          'field'    => 'slug',
          'terms'    => (array) $section['events_category'],
      ],
  ];
}

$all_events = get_posts($events_query_args);

// Étape 2 : filtrer en PHP — exclure ceux dont date_end est passée
$events = array_filter($all_events, function($post) use ($today) {
  $date_end = get_post_meta($post->ID, 'date_end', true);
  
  // Pas de date_end → on garde
  if (empty($date_end)) return true;
  
  // date_end >= aujourd'hui → on garde
  return $date_end >= $today;
});

// Étape 3 : limiter à 3
$events = array_slice(array_values($events), 0, 3);

// Determine news count based on events availability
$news_count = empty($events) ? 3 : 2;

// Prepare news query args
$news_query_args = [
  'numberposts' => $news_count,
  'post_status' => 'publish',
];

// Add category filter if specified (using tax_query for custom taxonomy)
if (!empty($section['news_category'])) {
  $news_query_args['tax_query'] = [
    [
      'taxonomy' => 'category_enjeu',
      'field' => 'slug',
      'terms' => $section['news_category'],
    ],
  ];
}

// Get recent posts for news section
$news_posts = get_posts($news_query_args);
?>

<section class="news-events theme-light-green-70 bg-layout-main py-section">
  <div class="container">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-end @sm:gap-6 @md/lg:gap-8 @sm:mb-12 @md/lg:mb-16">
      <div class="flex flex-col items-start @md/lg:max-w-[680px]">
        <?php if (!empty($section['section_subtitle'])): ?>
          <div class="label label-primary autoscale @sm:mb-4 @md/lg:mb-4">
            <?php echo esc_html($section['section_subtitle']); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($section['section_title'])): ?>
          <h2 class="heading-2xl heading-primary autoscale">
            <?php echo esc_html($section['section_title']); ?>
          </h2>
        <?php endif; ?>
      </div>

      <?php if (!empty($section['cta_button'])): $cta = $section['cta_button']; ?>
        <a
          href="<?php echo esc_url($cta['url']); ?>"
          target="<?php echo esc_attr($cta['target'] ?: '_self'); ?>"
          class="button-primary button-flat autoscale">
          <span class="button-title"><?php echo esc_html($cta['title']); ?></span>
        </a>
      <?php endif; ?>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 @sm:gap-10 @md/lg:gap-3">

      <!-- News Articles -->
      <?php if (!empty($news_posts)): ?>
        <?php foreach (array_slice($news_posts, 0, $news_count) as $post): setup_postdata($post); ?>
          <?php get_template_part('src/components/_post-card/markup'); ?>
        <?php endforeach;
        wp_reset_postdata(); ?>
      <?php endif; ?>

      <!-- Events Sidebar -->
      <?php if (!empty($events)): ?>
        <div class="events-sidebar theme-white bg-layout-main @sm:rounded-xl @md/lg:rounded-xl @sm:p-8 @md/lg:p-8 flex flex-col">
          <h3 class="label label-primary autoscale mb-4">
            Événements
          </h3>
          <?php if (!empty($events)): ?>
            <div class="events-list">
              <?php foreach ($events as $index => $event): setup_postdata($event); ?>
                <a href="<?php echo get_permalink($event->ID); ?>" class="event-item group block border-t border-yellow @sm:py-4 @md/lg:py-4">
                  <div class="event-date paragraph-sm paragraph-primary autoscale uppercase @sm:mb-2 @md/lg:mb-2">
                    <?php
                    $date_start = get_field('date_start', $event->ID);
                    $date_end = get_field('date_end', $event->ID);

                    if (!empty($date_start)) {
                      $start_formatted = date('d.m.y', strtotime($date_start));

                      if (!empty($date_end) && $date_start != $date_end) {
                        $end_formatted = date('d.m.y', strtotime($date_end));
                        echo "du {$start_formatted} au {$end_formatted}";
                      } else {
                        echo $start_formatted;
                      }
                    } else {
                      echo get_the_date('d.m.y', $event->ID);
                    }
                    ?>
                  </div>
                  <h4 class="heading-sm heading-primary autoscale group-hover:text-yellow transition-colors">
                    <?php echo esc_html(get_the_title($event->ID)); ?>
                  </h4>
                </a>

              <?php endforeach;
              wp_reset_postdata(); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>