<?php
/**
 * Related Content Component
 * 
 * A flexible component for displaying related content with theme variations
 * Usage: get_template_part('src/components/related-content/markup', null, $section_data);
 */

global $adwp;

$section = is_array($args) ? $args : [];

// Extract theme and split based on the '/' and get the theme-* and the bg-layout-* classes from it
$theme = $section['theme'] ?? 'primary/main';

// Handle case where theme might be an array (from color picker field)
if (is_array($theme)) {
  $theme = $theme['value'] ?? $theme['label'] ?? 'primary/main';
}

// Ensure theme is a string before exploding
$theme = is_string($theme) ? $theme : 'primary/main';
$colorParts = explode('/', $theme);
$themeClass = "theme-" . mb_strtolower($colorParts[0] ?? 'primary', 'UTF-8');
$layoutClass = "bg-layout-" . mb_strtolower($colorParts[1] ?? 'main', 'UTF-8');

// Handle field names
$label = $section['label'] ?? '';
$title = $section['title'] ?? '';
$button = $section['button'] ?? null;
$post_type = $section['postType'] ?? 'post';

// Handle taxonomy filters
$type_enjeu = $section['typeEnjeu'] ?? null;
$type_document = $section['typeDocument'] ?? null;
$category_filter_enjeu = $section['categoryFilterEnjeu'] ?? null;
$category_filter_document = $section['categoryFilterDocument'] ?? null;
$category_filter_evenement = $section['categoryFilterEvenement'] ?? null;
$category_filter_campagne = $section['categoryFilterCampagne'] ?? null;
$theme_filter = $section['themeFilter'] ?? null;

// Build query arguments
$query_args = [
  'numberposts' => 3,
  'post_status' => 'publish',
  'post_type' => $post_type,
];

// Add taxonomy filters based on post type
$tax_query = [];

// Add type taxonomy filter (only for post and document)
if ($post_type === 'post' && !empty($type_enjeu)) {
  $tax_query[] = [
    'taxonomy' => 'type_enjeu',
    'field'    => 'term_id',
    'terms'    => $type_enjeu,
  ];
} elseif ($post_type === 'document' && !empty($type_document)) {
  $tax_query[] = [
    'taxonomy' => 'type_document',
    'field'    => 'term_id',
    'terms'    => $type_document,
  ];
}

// Add category filter based on post type
if ($post_type === 'post' && !empty($category_filter_enjeu)) {
  $tax_query[] = [
    'taxonomy' => 'category_enjeu',
    'field'    => 'term_id',
    'terms'    => $category_filter_enjeu,
  ];
} elseif ($post_type === 'document' && !empty($category_filter_document)) {
  $tax_query[] = [
    'taxonomy' => 'category_document',
    'field'    => 'term_id',
    'terms'    => $category_filter_document,
  ];
} elseif ($post_type === 'evenement' && !empty($category_filter_evenement)) {
  $tax_query[] = [
    'taxonomy' => 'category_evenement',
    'field'    => 'term_id',
    'terms'    => $category_filter_evenement,
  ];
} elseif ($post_type === 'campagne' && !empty($category_filter_campagne)) {
  $tax_query[] = [
    'taxonomy' => 'category_campagne',
    'field'    => 'term_id',
    'terms'    => $category_filter_campagne,
  ];
}

// Add theme filter (only for post and document)
if (($post_type === 'post' || $post_type === 'document') && !empty($theme_filter)) {
  $tax_query[] = [
    'taxonomy' => 'theme',
    'field'    => 'term_id',
    'terms'    => $theme_filter,
  ];
}

// Add tax_query to main query if we have filters
if (!empty($tax_query)) {
  $query_args['tax_query'] = $tax_query;
  if (count($tax_query) > 1) {
    $query_args['tax_query']['relation'] = 'AND';
  }
}

// Get the posts
$related_posts = get_posts($query_args);

?>

<section class="related-content py-section <?= $themeClass; ?> <?= $layoutClass; ?>">

    <div class="container flex flex-wrap justify-between @sm:gap-10 @md/lg:gap-[52px]">
      <div class="flex flex-col autoscale  @sm:gap-4 @md/lg:gap-4 md:order-1">
        <?php if (!empty($label)): ?>
          <div class="label label-primary">
            <?php echo esc_html($label); ?>
          </div>
        <?php endif; ?>        
        <?php if (!empty($title)): ?>
          <h2 class="heading-2xl heading-primary">
            <?php echo esc_html($title); ?>
          </h2>
        <?php endif; ?>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 @sm:gap-10 @md/lg:gap-3 md:order-3 w-full">
        <?php if (!empty($related_posts)): ?>
          <?php foreach (array_slice($related_posts, 0, 3) as $post): 
            setup_postdata($post);
            $post_id = is_object($post) ? $post->ID : $post;
            $post_title = get_the_title($post_id);
            $post_permalink = get_permalink($post_id);
            $post_date = get_the_date('d.m.Y', $post_id);
            // Get categories based on post type
            $current_post_type = get_post_type($post_id);
            if ($current_post_type === 'document') {
              $post_categories = get_the_terms($post_id, 'category_document');
            } elseif ($current_post_type === 'post') {
              $post_categories = get_the_terms($post_id, 'category_enjeu');
            } elseif ($current_post_type === 'evenement') {
              $post_categories = get_the_terms($post_id, 'category_evenement');
            } elseif ($current_post_type === 'campagne') {
              $post_categories = get_the_terms($post_id, 'category_campagne');
            } elseif ($current_post_type === 'job') {
              $post_categories = get_the_terms($post_id, 'category_job');
            } else {
              $post_categories = false;
            }
            $primary_category = !empty($post_categories) ? $post_categories[0] : null;
          
            
          get_template_part('src/components/_post-card/markup', null);

          endforeach; wp_reset_postdata(); ?>
        <?php else: ?>
          <!-- Fallback content if no posts -->
          <div class="col-span-full text-center py-8">
            <p class="text-typography-heading-primary">No related content found.</p>
          </div>
        <?php endif; ?>
      </div>
      <?php if (!empty($button)): ?>
        <div class="md:order-2 self-end mm-sm:w-full">
        <a 
          href="<?php echo esc_url($button['url']); ?>" 
          target="<?php echo esc_attr($button['target'] ?: '_self'); ?>"
          class="button-flat button-primary autoscale md:flex-shrink-0 mm-sm:w-full mm-sm:justify-center mm-sm:text-center"
        >
          <span class="button-title">
            <?php echo esc_html($button['title']); ?>
          </span>
        </a>
        </div>
      <?php endif; ?>
    </div>

</section>
