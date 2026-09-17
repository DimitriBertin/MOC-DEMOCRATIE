<?php
/**
 * Archive Header Component
 * 
 * Displays the archive title with filters
 */

$section = is_array($args) ? $args : [];
$title = $section['title'] ?? get_the_archive_title();
$single = $section['single'] ?? false;

// Get current post type from query
$current_post_type = get_query_var('post_type') ?: 'post';

// Get current filters from URL using WordPress standard parameters
$current_filters = [];

// Get type filter based on post type
if ($current_post_type === 'post' && !empty($_GET['type_enjeu'])) {
    $term = get_term_by('slug', $_GET['type_enjeu'], 'type_enjeu');
    if ($term) $current_filters['types'] = $term->term_id;
} elseif ($current_post_type === 'document' && !empty($_GET['type_document'])) {
    $term = get_term_by('slug', $_GET['type_document'], 'type_document');
    if ($term) $current_filters['types'] = $term->term_id;
}

// Get category filter
if (!empty($_GET['category'])) {
    $term = get_term_by('slug', $_GET['category'], 'category');
    if ($term) $current_filters['categories'] = $term->term_id;
}

// Get theme filter
if (!empty($_GET['theme'])) {
    $term = get_term_by('slug', $_GET['theme'], 'theme');
    if ($term) $current_filters['themes'] = $term->term_id;
}

// Get available taxonomies based on current context
$available_taxonomies = get_archive_filter_taxonomies($current_post_type);

// Get filter terms with counts
$filter_data = get_filter_terms_with_counts($current_post_type, $current_filters);
?>

<section class="archive-header @sm:mt-[70px] @md/lg:mt-[130px] py-section theme-white bg-layout-main" data-post-type="<?php echo esc_attr($current_post_type); ?>" data-single="<?php echo $single ? 'true' : 'false'; ?>">
  <div class="container">
    
    <!-- Header with Title and Filters -->
    <div class="header-top flex flex-col items-start @sm:gap-6 @md/lg:gap-8 @sm:mb-5 @md/lg:mb-12">
      
      <!-- Title -->
      <h1 class="archive-title text-yellow text-display autoscale">
        <?php echo esc_html($title); ?>
      </h1>

      <!-- Filters Section -->
      <div class="filters-wrapper flex items-center @sm:gap-2 @md/lg:gap-4 mm-sm:flex-wrap <?php echo $single ? 'mm-sm:block mm-sm:w-full' : ''; ?>">
        <?php if (!$single): ?>
        <span class="filter-label menu autoscale !leading-tight mm-sm:w-full md:text-right">
          Filtrer par
        </span>
        <?php endif; ?>

        <!-- Filter Dropdowns -->
        <div class="filter-dropdowns flex @sm:gap-1 @md/lg:gap-4 <?php echo $single ? 'mm-sm:block ' : ''; ?>">
          
          <?php if (!empty($available_taxonomies['types'])): ?>
          <!-- Types Filter -->
          <?php 
          $types_has_active = isset($current_filters['types']);
          $types_wrapper_class = $types_has_active ? 'active' : '';
          ?>
          <div class="filter-dropdown-wrapper relative group/wrapper <?php echo $types_wrapper_class; ?>">
            <button class="filter-dropdown button-outline autoscale button-primary @sm:gap-2 @md/lg:gap-2 @sm:px-2.5 @md/lg:px-5 @sm:rounded-xl @md/lg:rounded-xl hover:bg-yellow hover:border-yellow hover:text-dark-green aria-expanded:bg-yellow aria-expanded:border-yellow" 
                    data-filter="types" 
                    aria-expanded="false" 
                    aria-haspopup="true">
              <span class="button-title font-semibold">Types</span>
            <svg class="mm-sm:group-is-[.active]/wrapper:hidden @sm:w-2 @sm:h-[14px] @md/lg:w-2 @md/lg:h-[14px] rotate-90 transition-transform" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.70859 7.70469C8.09922 7.31406 8.09922 6.67969 7.70859 6.28906L1.70859 0.289062C1.31797 -0.101562 0.683594 -0.101562 0.292969 0.289063C-0.0976562 0.679688 -0.0976562 1.31406 0.292969 1.70469L5.58672 6.99844L0.296094 12.2922C-0.094531 12.6828 -0.094531 13.3172 0.296094 13.7078C0.686719 14.0984 1.32109 14.0984 1.71172 13.7078L7.71172 7.70781L7.70859 7.70469Z" fill="#012E31"/>
            </svg>
            <span class="hidden mm-sm:group-is-[.active]/wrapper:block @sm:w-2 @sm:h-2 group-is-[.active]/wrapper:bg-orange rounded-full"></span>
            </button>
            <div class="filter-dropdown-menu @sm:space-y-4 @md/lg:space-y-4 absolute top-full left-0 @sm:mt-2.5 @md/lg:mt-2.5 @sm:p-5 @md/lg:p-5 bg-yellow  @sm:rounded-xl @md/lg:rounded-xl z-[50] @sm:min-w-[300px] @md/lg:min-w-[300px] max-h-[300px] overflow-y-auto hidden @md:left-0 @md:right-auto">
              <?php foreach ($filter_data['types'] as $term): ?>
                <?php 
                $is_active = isset($current_filters['types']) && $current_filters['types'] == $term['term_id'];
                $active_class = $is_active ? 'active' : '';
                ?>
                <button class="filter-option menu autoscale w-full text-left text-green-semi-light hover:text-dark-green  [.active&]:text-dark-green <?php echo $active_class; ?> <?php echo $term['count'] === 0 ? 'opacity-50 cursor-not-allowed' : ''; ?>" 
                        data-taxonomy="<?php echo esc_attr($available_taxonomies['types']); ?>"
                        data-term-id="<?php echo esc_attr($term['term_id']); ?>"
                        data-term-slug="<?php echo esc_attr($term['slug']); ?>"
                        <?php echo $term['count'] === 0 ? 'disabled' : ''; ?>>
                  <span class=""><?php echo esc_html($term['name']); ?></span>
                  <span class="ml-2">(<?php echo $term['count']; ?>)</span>
                </button>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <?php if (!empty($available_taxonomies['categories'])): ?>
          <!-- Categories Filter -->
          <?php 
          $categories_has_active = isset($current_filters['categories']);
          $categories_wrapper_class = $categories_has_active ? 'active' : '';
          ?>
          <div class="filter-dropdown-wrapper relative group/wrapper <?php echo $categories_wrapper_class; ?>">
            <button class="filter-dropdown button-outline autoscale button-primary @sm:gap-2 @md/lg:gap-2 @sm:px-2.5 @md/lg:px-5 @sm:rounded-xl @md/lg:rounded-xl hover:bg-yellow hover:border-yellow hover:text-dark-green aria-expanded:bg-yellow aria-expanded:border-yellow <?php echo $single ? 'mm-sm:w-full mm-sm:justify-center' : ''; ?>" 
                    data-filter="categories" 
                    aria-expanded="false" 
                    aria-haspopup="true">
              <span class="button-title font-semibold"><?php echo $single ? 'Filtrer' : 'Catégories'; ?></span>
            <svg class="mm-sm:group-is-[.active]/wrapper:hidden @sm:w-2 @sm:h-[14px] @md/lg:w-2 @md/lg:h-[14px] rotate-90 transition-transform" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.70859 7.70469C8.09922 7.31406 8.09922 6.67969 7.70859 6.28906L1.70859 0.289062C1.31797 -0.101562 0.683594 -0.101562 0.292969 0.289063C-0.0976562 0.679688 -0.0976562 1.31406 0.292969 1.70469L5.58672 6.99844L0.296094 12.2922C-0.094531 12.6828 -0.094531 13.3172 0.296094 13.7078C0.686719 14.0984 1.32109 14.0984 1.71172 13.7078L7.71172 7.70781L7.70859 7.70469Z" fill="#012E31"/>
            </svg>
            <span class="hidden mm-sm:group-is-[.active]/wrapper:block @sm:w-2 @sm:h-2 group-is-[.active]/wrapper:<?php echo $single ? 'bg-orange' : 'bg-yellow'; ?> rounded-full"></span>
            </button>
            <div class="filter-dropdown-menu @sm:space-y-4 @md/lg:space-y-4 absolute top-full left-0 @sm:mt-2.5 @md/lg:mt-2.5 @sm:p-5 @md/lg:p-5 bg-yellow  @sm:rounded-xl @md/lg:rounded-xl z-[50] @sm:min-w-[300px] @md/lg:min-w-[300px] max-h-[300px] overflow-y-auto hidden @md:left-auto @md:right-0">
              <?php foreach ($filter_data['categories'] as $term): ?>
                <?php 
                $is_active = isset($current_filters['categories']) && $current_filters['categories'] == $term['term_id'];
                $active_class = $is_active ? 'active' : '';
                ?>
                <button class="filter-option menu autoscale w-full  text-left text-green-semi-light hover:text-dark-green  [.active&]:text-dark-green <?php echo $active_class; ?> <?php echo $term['count'] === 0 ? 'opacity-50 cursor-not-allowed' : ''; ?>" 
                        data-taxonomy="category"
                        data-term-id="<?php echo esc_attr($term['term_id']); ?>"
                        data-term-slug="<?php echo esc_attr($term['slug']); ?>"
                        <?php echo $term['count'] === 0 ? 'disabled' : ''; ?>>
                  <span class=""><?php echo esc_html($term['name']); ?></span>
                  <span class="ml-2">(<?php echo $term['count']; ?>)</span>
                </button>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <?php if (!empty($available_taxonomies['themes'])): ?>
          <!-- Themes Filter -->
          <?php 
          $themes_has_active = isset($current_filters['themes']);
          $themes_wrapper_class = $themes_has_active ? 'active' : '';
          ?>
          <div class="filter-dropdown-wrapper relative group/wrapper <?php echo $themes_wrapper_class; ?>">
            <button class="filter-dropdown button-outline autoscale button-primary @sm:gap-2 @md/lg:gap-2 @sm:px-2.5 @md/lg:px-5 @sm:rounded-xl @md/lg:rounded-xl hover:bg-yellow hover:border-yellow hover:text-dark-green aria-expanded:bg-yellow aria-expanded:border-yellow" 
                    data-filter="themes" 
                    aria-expanded="false" 
                    aria-haspopup="true">
              <span class="button-title font-semibold">Thèmes</span>
            <svg class="mm-sm:group-is-[.active]/wrapper:hidden @sm:w-2 @sm:h-[14px] @md/lg:w-2 @md/lg:h-[14px] rotate-90 transition-transform" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.70859 7.70469C8.09922 7.31406 8.09922 6.67969 7.70859 6.28906L1.70859 0.289062C1.31797 -0.101562 0.683594 -0.101562 0.292969 0.289063C-0.0976562 0.679688 -0.0976562 1.31406 0.292969 1.70469L5.58672 6.99844L0.296094 12.2922C-0.094531 12.6828 -0.094531 13.3172 0.296094 13.7078C0.686719 14.0984 1.32109 14.0984 1.71172 13.7078L7.71172 7.70781L7.70859 7.70469Z" fill="#012E31"/>
            </svg>
            <span class="hidden mm-sm:group-is-[.active]/wrapper:block @sm:w-2 @sm:h-2 group-is-[.active]/wrapper:bg-light-green-70 rounded-full"></span>
            </button>
            <div class="filter-dropdown-menu @sm:space-y-4 @md/lg:space-y-4 absolute top-full @sm:mt-2.5 @md/lg:mt-2.5 @sm:p-5 @md/lg:p-5 bg-yellow  @sm:rounded-xl @md/lg:rounded-xl z-[50] @sm:min-w-[300px] @md/lg:min-w-[300px] max-h-[300px] overflow-y-auto hidden left-auto right-0">              <?php foreach ($filter_data['themes'] as $term): ?>
                <?php 
                $is_active = isset($current_filters['themes']) && $current_filters['themes'] == $term['term_id'];
                $active_class = $is_active ? 'active' : '';
                ?>
                <button class="filter-option menu autoscale text-left w-full text-green-semi-light hover:text-dark-green  [.active&]:text-dark-green <?php echo $active_class; ?> <?php echo $term['count'] === 0 ? 'opacity-50 cursor-not-allowed' : ''; ?>" 
                        data-taxonomy="theme"
                        data-term-id="<?php echo esc_attr($term['term_id']); ?>"
                        data-term-slug="<?php echo esc_attr($term['slug']); ?>"
                        <?php echo $term['count'] === 0 ? 'disabled' : ''; ?>>
                  <span class=""><?php echo esc_html($term['name']); ?></span>
                  <span class="@sm:ml-2 @md/lg:ml-2">(<?php echo $term['count']; ?>)</span>
                </button>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

        </div>
      </div>
    </div>

    <!-- Active Filters and Reset -->
    <div class="active-filters flex items-center @sm:gap-6 @md/lg:gap-[42px] @sm:flex-wrap @md/lg:flex-nowrap <?php echo $single ? 'mm-sm:justify-center ' : ''; ?>" <?php echo empty(array_filter($current_filters)) ? 'style="display: none;"' : ''; ?>>
      
      <!-- Filter Tags -->
      <div class="filter-tags flex items-start flex-wrap @sm:gap-2 @md/lg:gap-2 badge-wrapper">
        
        <?php
        // Display active filter tags
        $tag_colors = [
            'types' => 'bg-orange',
            'categories' => $single ? 'bg-orange' : 'bg-yellow',
            'themes' => 'bg-light-green-70'
        ];
        
        foreach ($current_filters as $filter_type => $filter_value) {
            if (!empty($filter_value)) {
                $term = get_term($filter_value);
                if ($term && !is_wp_error($term)) {
                    $color_class = $tag_colors[$filter_type] ?? 'bg-gray-200';
                    $filter_label = ucfirst($filter_type === 'categories' ? 'Catégories' : $filter_type);
                    ?>
                    <div class="filter-tag badge-surface border-0 autoscale flex items-center @sm:gap-2.5 @md/lg:gap-2.5 <?php echo $color_class; ?>" data-filter="<?php echo esc_attr($filter_type); ?>" data-term="<?php echo esc_attr($filter_value); ?>">
                      <span><?php echo esc_html($term->name); ?></span>
                      <button class="remove-tag @sm:w-[9px] @md/lg:w-[9px] @sm:h-[8px] @md/lg:h-[8px] relative" aria-label="Remove filter" data-filter="<?php echo esc_attr($filter_type); ?>">
                        <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 @sm:w-[11px] @sm:h-[1px] @md/lg:w-[11px] @md/lg:h-[1px] bg-dark-green rotate-45"></span>
                        <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 @sm:w-[11px] @sm:h-[1px] @md/lg:w-[11px] @md/lg:h-[1px] bg-dark-green -rotate-45"></span>
                      </button>
                    </div>
                    <?php
                }
            }
        }
        ?>

      </div>

      <!-- Reset All Button -->
      <button class="mm-sm:hidden reset-filters button-underline button-primary border-b p-0 border-dark-green hover:border-yellow hover:text-yellow">
        <span class="button-title font-semibold autoscale">
          Réinitialiser les filtres
        </span>
      </button>

    </div>

  </div>

  <!-- Loading overlay -->
  <div class="filter-loading fixed inset-0 bg-black/50 z-[9999] items-center justify-center backdrop-blur-sm hidden">
    <div class="bg-white @sm:rounded-lg @md/lg:rounded-lg  @sm:p-6 @md/lg:p-6 flex items-center @sm:gap-4 @md/lg:gap-4">
      <div class="animate-spin rounded-full @sm:h-8 @md/lg:h-8 @sm:w-8 @md/lg:w-8 border-b-2 border-yellow"></div>
      <span class="paragraph-sm paragraph-primary">Chargement...</span>
    </div>
  </div>
</section>
