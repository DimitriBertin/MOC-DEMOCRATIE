<?php
/**
 * Jobs Section Component
 * 
 * Displays all job posts with federation filtering
 */

$title = $args['title'] ?? 'Offres d\'emplois';

// Get all federations for filtering
$federations = get_terms([
    'taxonomy' => 'federation',
    'hide_empty' => false,
]);

// Get all job posts
$jobs_query = new WP_Query([
    'post_type' => 'job',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
]);

?>

<section class="jobs-section py-section theme-white bg-layout-main">
    <div class="container">
        <div class="flex mm-sm:flex-col md:flex-row @sm:gap-8 @md/lg:gap-20 md:justify-between md:items-start">
            <!-- Title -->
            <?php if (!empty($title)) : ?>
                <div class="md:basis-1/6">
                <h2 class="heading-2xl heading-primary autoscale">
                    <?php echo esc_html($title); ?>
                </h2>
                </div>
            <?php endif; ?>
            
            <!-- Jobs List Container -->
            <div class="flex flex-col @sm:gap-7 @md/lg:gap-7 md:basis-5/6">
                <!-- Filter -->
                <?php if (!empty($federations)) : ?>
                    <div class="jobs-filter flex items-center relative @sm:gap-4 @md/lg:gap-4">
                            <button 
                                class="filter-dropdown-btn button-outline autoscale button-primary mm-sm:w-full @sm:gap-2 @md/lg:gap-2 @sm:px-2.5 @md/lg:px-5 @sm:rounded-xl @md/lg:rounded-xl hover:bg-yellow hover:border-yellow hover:text-dark-green aria-expanded:bg-yellow aria-expanded:border-yellow"
                                onclick="toggleFilterDropdown()"
                            >
                                <span class="button-title font-semibold">
                                    Filtrer
                                </span>
                                <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.9804 1.6582L5.99739 6.34128L1 1.6582" stroke="#012E31" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round"/>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu (hidden by default) -->
                            <div id="filterDropdown" class="@sm:space-y-4 @md/lg:space-y-4 absolute top-full left-0 @sm:mt-2.5 @md/lg:mt-2.5 @sm:p-5 @md/lg:p-5 bg-yellow  @sm:rounded-xl @md/lg:rounded-xl z-[50] @sm:min-w-[300px] @md/lg:min-w-[300px] max-h-[300px] overflow-y-auto hidden @md:left-0 @md:right-auto">
                                <button 
                                    class="filter-option menu autoscale w-full text-left text-green-semi-light hover:text-dark-green  [.active&]:text-dark-green active"
                                    data-federation="all"
                                    onclick="filterJobs('all', 'Toutes les fédérations')"
                                >
                                    Toutes les fédérations
                                </button>
                                <?php foreach ($federations as $federation) : ?>
                                    <button 
                                        class="filter-option menu autoscale w-full text-left text-green-semi-light hover:text-dark-green  [.active&]:text-dark-green"
                                        data-federation="<?php echo esc_attr($federation->slug); ?>"
                                        onclick="filterJobs('<?php echo esc_js($federation->slug); ?>', '<?php echo esc_js($federation->name); ?>')"
                                    >
                                        <?php echo esc_html($federation->name); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                    </div>
                <?php endif; ?>
                
                <!-- Jobs List -->
                <?php if ($jobs_query->have_posts()) : ?>
                    <div class="jobs-list flex flex-col @sm:gap-7 @md/lg:gap-7">
                        <?php 
                        while ($jobs_query->have_posts()) : $jobs_query->the_post(); 
                            // Get job federations
                            $job_federations = get_the_terms(get_the_ID(), 'federation');
                            $federation_classes = '';
                            if ($job_federations && !is_wp_error($job_federations)) {
                                $federation_slugs = array_map(function($term) {
                                    return 'federation-' . $term->slug;
                                }, $job_federations);
                                $federation_classes = implode(' ', $federation_slugs);
                            }
                            
                            // Get job description
                            $description = get_field('description', get_the_ID());
                        ?>
                            <a href="<?php the_permalink(); ?>" class="job-card <?php echo esc_attr($federation_classes); ?> group flex flex-col justify-center items-center @sm:px-6 @sm:py-10 @md/lg:px-6 @md/lg:py-11 @sm:rounded-xl @md/lg:rounded-xl theme-light-green-70 bg-layout-main hover:theme-yellow transition-all no-underline">
                                <div class="job-card-inner autoscale flex flex-col max-sm:items-start @sm:gap-4 md:items-end @md/lg:gap-20 w-full relative">
                                    <!-- Top Section: Title & Arrow -->
                                    <div class="job-header flex justify-between items-center w-full max-sm:flex-col max-sm:gap-4 md:flex-row">
                                        <h3 class="job-title heading-xl heading-primary">
                                            <?php the_title(); ?>
                                        </h3>
                                        <div class="job-arrow-link button-flat button-primary hover:bg-white hover:border-white mm-sm:absolute mm-sm:bottom-0 mm-sm:right-0 @sm:w-12 @sm:h-12 @md/lg:w-12 @md/lg:h-12 rounded-full p-0 justify-center"
                                           aria-label="Voir les détails de <?php the_title(); ?>">
                                            <svg class="button-icon @sm:w-[17px] @md/lg:w-[17px] @sm:h-[12px] @md/lg:h-[12px]" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11 11.166L16 6.16602L11 1.16602" stroke="#012E31" stroke-linecap="round" stroke-linejoin="bevel"/>
                                            <path d="M16 6.16602L1 6.16601" stroke="#012E31" stroke-linecap="round"/>
                                            </svg>

                                        </div>
                                    </div>
                                    
                                    <!-- Bottom Section: Badge & Description -->
                                    <div class="job-footer flex mm-sm:flex-col md:justify-between md:items-end w-full  @sm:gap-6 @md/lg:gap-6">
                                        <!-- Federation Badge -->
                                        <?php if ($job_federations && !is_wp_error($job_federations)) : ?>
                                            <div class="federation-badge badge-wrapper mm-sm:order-2 md:basis-2/5 @sm:mb-2 @sm:pr-16 @md/lg:mb-0 @md/lg:pr-0">
                                                <span class="badge-surface group-hover:bg-white group-hover:border-white transition-colors">
                                                    <?php echo esc_html($job_federations[0]->name); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Job Description -->
                                        <?php if (!empty($description)) : ?>
                                            <div class="job-description paragraph-md paragraph-primary md:basis-3/5">
                                                <?php echo esc_html($description); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <div class="text-center py-section">
                        <p class="paragraph-md paragraph-primary">
                            Aucune offre d'emploi disponible actuellement.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php wp_reset_postdata(); ?>
</section>

<script>
function toggleFilterDropdown() {
    const dropdown = document.getElementById('filterDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('filterDropdown');
    const button = document.querySelector('.filter-dropdown-btn');
    
    if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

function filterJobs(federation, label) {
    const jobCards = document.querySelectorAll('.job-card');
    const dropdown = document.getElementById('filterDropdown');
    const filterOptions = document.querySelectorAll('.filter-option');
    
    // Remove active class from all filter options
    filterOptions.forEach(option => {
        option.classList.remove('active');
    });
    
    // Add active class to the selected filter option
    const selectedOption = document.querySelector(`[data-federation="${federation}"]`);
    if (selectedOption) {
        selectedOption.classList.add('active');
    }
    
    // Update button text
    const button = document.querySelector('.filter-dropdown-btn span');
    if (button && federation !== 'all') {
        button.textContent = label;
    } else if (button) {
        button.textContent = 'par fédérations';
    }
    
    // Filter jobs
    jobCards.forEach(card => {
        if (federation === 'all' || card.classList.contains('federation-' + federation)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
    
    // Close dropdown
    dropdown.classList.add('hidden');
}
</script>
