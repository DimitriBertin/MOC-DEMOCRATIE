<?php
/**
 * Post Card Component
 * 
 * A reusable component for displaying post cards
 */

$post_type= get_post_type();

// Setup post data
$post_title = get_the_title();
$post_permalink = get_permalink();

// Handle date display based on post type
if ($post_type == 'evenement' || $post_type == 'campagne') {
  $date_start = get_field('date_start');
  $date_end = get_field('date_end');
  
  if (!empty($date_start)) {
    $start_formatted = date('d.m.Y', strtotime($date_start));
    
    if (!empty($date_end) && $date_start != $date_end) {
      $end_formatted = date('d.m.Y', strtotime($date_end));
      $post_date = "du {$start_formatted} au {$end_formatted}";
    } else {
      $post_date = "{$start_formatted}";
    }
  } else {
    $post_date = get_the_date('d.m.Y');
  }
} else {
  $post_date = get_the_date('d.m.Y');
}

// Get categories based on post type
if ($post_type == 'document') {
  $post_categories = ad_get_the_terms(get_the_ID(), 'category_document');
} elseif ($post_type == 'post') {
  $post_categories = ad_get_the_terms(get_the_ID(), 'category');
} elseif ($post_type == 'evenement') {
  $post_categories = ad_get_the_terms(get_the_ID(), 'category_evenement');
} elseif ($post_type == 'campagne') {
  $post_categories = ad_get_the_terms(get_the_ID(), 'category_campagne');
} elseif ($post_type == 'job') {
  $post_categories = ad_get_the_terms(get_the_ID(), 'category_job');
} else {
  $post_categories = false;
}
$post_tax_type = null;

if ($post_type == 'post') {
  $post_tax_type = ad_get_the_term(get_the_ID(), 'type_enjeu');
} elseif ($post_type == 'document') {
  $post_tax_type = ad_get_the_term(get_the_ID(), 'type_document');
}
?>

<article class="post-card group flex flex-col @sm:gap-4 md:flex-row md:items-end md:justify-center @md/lg:gap-14">
  
  <!-- Image Container -->
  <a href="<?php echo esc_url($post_permalink); ?>" class="md:group-even:order-2 post-image [&:hover>img:first-child]:scale-105 [&>img:first-child]:duration-300 [&:hover>img:first-child]:duration-1000 [&>img:first-child]:transition-transform relative @sm:rounded-xl @md/lg:rounded-xl overflow-hidden bg-dark-green mm-sm:max-h-[300px] mm-sm:aspect-[350/300] md:w-full md:aspect-[709/479] md:max-h-[600px]">
    <?php if (has_post_thumbnail()): ?>
      <?php echo get_the_post_thumbnail(null,'medium_large', [
        'class' => 'w-full h-full object-cover',
        'loading' => 'lazy',
        'alt' => esc_attr($post_title)
      ]); ?>
    <?php elseif ($post_type == 'evenement' || $post_type == 'campagne'): ?>
      <?php print_r(get_field('default_preview_image', 'option')); ?>
      <?php echo wp_get_attachment_image( get_field('default_preview_image_alt', 'acf-options-global-fields'), 'medium_large', false, [
        'class' => 'w-full h-full object-cover',
        'loading' => 'lazy',
        'alt' => esc_attr($post_title)
      ]); ?>
    <?php else: ?>
      <?php echo wp_get_attachment_image( get_field('default_preview_image', 'acf-options-global-fields'), 'medium_large', false, [
        'class' => 'w-full h-full object-cover',
        'loading' => 'lazy',
        'alt' => esc_attr($post_title)
      ]); ?>
      <?php if ($post_tax_type): ?>
        <img src="<?php echo get_template_directory_uri(); ?>/src/assets/svg/<?php echo esc_attr($post_tax_type->slug); ?>.svg" class="@sm:w-[164px] @sm:h-[164px] @md/lg:w-[164px] @md/lg:h-[164px] absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" alt="<?php echo esc_attr($post_tax_type->name); ?>">
      <?php endif; ?>
    <?php endif; ?>
  </a>

  <!-- Post Meta -->
<div class="autoscale flex flex-col @sm:gap-4 @md/lg:gap-4 md:max-w-[560px]">
  <div class="post-meta flex flex-wrap justify-between items-start @sm:gap-2 @md/lg:gap-2">
    
    <!-- Date -->
    <time datetime="<?php echo get_the_date('c', ); ?>" class="<?php if ($post_type == 'evenement' || $post_type == 'campagne') echo 'w-full'; ?> block label paragraph-primary"> 
      <?php echo esc_html($post_date); ?>
    </time>

    <!-- Category Badge -->
    <?php if ($post_type == 'evenement' || $post_type == 'campagne'): ?>
      <?php if (!empty($post_categories)): ?>
        <div class="badge-wrapper flex-wrap flex justify-start @sm:gap-2 @md/lg:gap-2">
          <?php foreach ($post_categories as $category): ?>
            <div class="badge-surface">
              <?php echo esc_html($category->name); ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <?php if ($post_tax_type): ?>
        <div class="badge-wrapper flex justify-end">
          <div class="badge-surface">
            <?php echo esc_html($post_tax_type->name); ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>
    
  </div>

  <!-- Post Title -->
  <h3 class="post-title heading-lg @sm:pr-4 @lg/md:pr-4">
    <a href="<?php echo esc_url($post_permalink); ?>" class="hover:opacity-80 transition-opacity duration-200 paragraph-primary">
      <?php echo esc_html($post_title); ?>
    </a>
  </h3>
  <?php if ($description = get_field('description')): ?>
    <p class="paragraph-sm paragraph-primary text-balance">
      <?php echo esc_html($description); ?>
    </p>
    <?php endif; ?>
</div>
</article>
