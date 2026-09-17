<?php
/**
 * Archive Filter Functions
 * 
 * Helper functions for the archive filter system
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get available taxonomies for a given post type
 * 
 * @param string $post_type
 * @return array
 */
function get_archive_filter_taxonomies($post_type) {
    $taxonomies = [];
    
    // Define taxonomy mapping for each post type
    $taxonomy_map = [
        'post' => [
            'types' => 'type_enjeu',
            'categories' => 'category',
            'themes' => 'theme'
        ],
        'document' => [
            'types' => 'type_document',
            'categories' => 'category_document',
            'themes' => 'theme'
        ],
        'evenement' => [
            'categories' => 'category_evenement'
        ],
        'campagne' => [
            'categories' => 'category_campagne'
        ],
        'job' => [
            'categories' => 'category_job'
        ]
    ];
    
    return $taxonomy_map[$post_type] ?? [];
}

/**
 * Get filter terms with post counts considering current filters
 * 
 * @param string $post_type
 * @param array $current_filters
 * @return array
 */
function get_filter_terms_with_counts($post_type, $current_filters = []) {
    $available_taxonomies = get_archive_filter_taxonomies($post_type);
    $filter_data = [];
    
    foreach ($available_taxonomies as $filter_type => $taxonomy) {
        $terms = ad_get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC'
        ]);
        
        if (!is_wp_error($terms)) {
            $filter_data[$filter_type] = [];
            
            foreach ($terms as $term) {
                // Calculate post count with potential filters applied
                $count = get_filtered_post_count($post_type, $taxonomy, $term->term_id, $current_filters);
                
                $filter_data[$filter_type][] = [
                    'term_id' => $term->term_id,
                    'name' => $term->name,
                    'slug' => $term->slug,
                    'count' => $count
                ];
            }
        }
    }
    
    return $filter_data;
}

/**
 * Get post count for a specific term considering current filters
 * 
 * @param string $post_type
 * @param string $taxonomy
 * @param int $term_id
 * @param array $current_filters
 * @return int
 */
function get_filtered_post_count($post_type, $taxonomy, $term_id, $current_filters = []) {
    $args = [
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'tax_query' => []
    ];
    
    // Add the term we're checking
    $args['tax_query'][] = [
        'taxonomy' => $taxonomy,
        'field' => 'term_id',
        'terms' => $term_id
    ];
    
    // Add other active filters (excluding the one we're checking)
    $available_taxonomies = get_archive_filter_taxonomies($post_type);
    
    foreach ($current_filters as $filter_type => $filter_value) {
        if (!empty($filter_value) && isset($available_taxonomies[$filter_type])) {
            $filter_taxonomy = $available_taxonomies[$filter_type];
            
            // Skip if this is the same taxonomy we're checking
            if ($filter_taxonomy === $taxonomy) {
                continue;
            }
            
            $args['tax_query'][] = [
                'taxonomy' => $filter_taxonomy,
                'field' => 'term_id',
                'terms' => $filter_value
            ];
        }
    }
    
    // Set relation to AND if we have multiple tax queries
    if (count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }
    
    $query = new WP_Query($args);
    return $query->found_posts;
}

/**
 * Handle AJAX filter request
 */
function handle_archive_filter_ajax() {
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_send_json_error('Invalid request method');
        return;
    }
    
    // Check if nonce exists
    if (!isset($_POST['nonce'])) {
        wp_send_json_error('No security token provided');
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'archive_filter_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $post_type = sanitize_text_field($_POST['post_type'] ?? 'post');
    $filters = $_POST['filters'] ?? [];
    $page = intval($_POST['page'] ?? 1);
    
    // Validate post type
    if (!post_type_exists($post_type)) {
        wp_send_json_error('Invalid post type');
    }
    
    // Sanitize filters
    $sanitized_filters = [];
    foreach ($filters as $key => $value) {
        if (!empty($value)) {
            $sanitized_key = sanitize_key($key);
            $term_id = intval($value);
            
            // Verify term exists
            if (term_exists($term_id)) {
                $sanitized_filters[$sanitized_key] = $term_id;
            }
        }
    }
    
    // Build query arguments
    $args = [
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => get_option('posts_per_page', 10),
        'paged' => max(1, $page),
        'no_found_rows' => false,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => true
    ];
    
    // Special handling for events and campaigns
    if ($post_type === 'evenement') {
        // For events, we need to return both upcoming and past events separately
        $today = date('Y-m-d');
        
        // Get upcoming events (no pagination, all results)
        $upcoming_args = [
            'post_type' => 'evenement',
            'posts_per_page' => -1,
            'nopaging' => true,
            'post_status' => 'publish',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_key' => 'date_start',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'date_end',
                    'value' => $today,
                    'compare' => '>=',
                    'type' => 'DATE'
                ],
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
        
        // Get past events (with pagination)
        $past_args = [
            'post_type' => 'evenement',
            'posts_per_page' => 6,
            'paged' => max(1, $page),
            'post_status' => 'publish',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_key' => 'date_start',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'date_end',
                    'value' => $today,
                    'compare' => '<',
                    'type' => 'DATE'
                ],
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
        
        $args = $past_args; // Use past events args for pagination calculation
    } elseif ($post_type === 'campagne') {
        // For campaigns, we need to return both upcoming and past campaigns separately
        $today = date('Y-m-d');
        
        // Get upcoming campaigns (no pagination, all results)
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
                [
                    'key' => 'date_end',
                    'value' => $today,
                    'compare' => '>=',
                    'type' => 'DATE'
                ],
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
        
        // Get past campaigns (with pagination)
        $past_args = [
            'post_type' => 'campagne',
            'posts_per_page' => 6,
            'paged' => max(1, $page),
            'post_status' => 'publish',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_key' => 'date_start',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'date_end',
                    'value' => $today,
                    'compare' => '<',
                    'type' => 'DATE'
                ],
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
        
        $args = $past_args; // Use past events args for pagination calculation
    }
    
    // Add tax query if filters exist
    if (!empty($sanitized_filters)) {
        $available_taxonomies = get_archive_filter_taxonomies($post_type);
        $tax_query = [];
        
        foreach ($sanitized_filters as $filter_type => $term_id) {
            if (isset($available_taxonomies[$filter_type])) {
                $taxonomy = $available_taxonomies[$filter_type];
                
                // Verify taxonomy exists and term belongs to it
                $term = get_term($term_id, $taxonomy);
                if (!is_wp_error($term) && $term) {
                    $tax_query[] = [
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $term_id,
                        'operator' => 'IN'
                    ];
                }
            }
        }
        
        if (!empty($tax_query)) {
            $final_tax_query = $tax_query;
            if (count($tax_query) > 1) {
                $final_tax_query['relation'] = 'AND';
            }
            
            $args['tax_query'] = $final_tax_query;
            
            // For events and campaigns, also add to upcoming query
            if (($post_type === 'evenement' || $post_type === 'campagne') && isset($upcoming_args)) {
                $upcoming_args['tax_query'] = $final_tax_query;
            }
        }
    }
    
    // Execute queries
    if ($post_type === 'evenement') {
        $upcoming_query = new WP_Query($upcoming_args);
        $past_query = new WP_Query($args); // $args is the past events query
        $query = $past_query; // Use past query for pagination
    } elseif ($post_type === 'campagne') {
        $upcoming_query = new WP_Query($upcoming_args);
        $past_query = new WP_Query($args); // $args is the past campaigns query
        $query = $past_query; // Use past query for pagination
    } else {
        $query = new WP_Query($args);
    }
    
    $response = [
        'success' => true,
        'posts' => [],
        'pagination' => [
            'current_page' => max(1, $query->query_vars['paged']),
            'total_pages' => $query->max_num_pages,
            'total_posts' => $query->found_posts,
            'posts_per_page' => $query->query_vars['posts_per_page']
        ],
        'filters' => get_filter_terms_with_counts($post_type, $sanitized_filters),
        'debug' => [
            'query_args' => $args,
            'sql' => $query->request
        ]
    ];
    
    // Get posts HTML
    ob_start();
    
    if ($post_type === 'evenement') {
        // For events, generate HTML for upcoming events
        if ($upcoming_query->have_posts()) {
            while ($upcoming_query->have_posts()) {
                $upcoming_query->the_post();
                get_template_part('src/components/_post-card/markup');
            }
        }
        wp_reset_postdata();
        $upcoming_html = ob_get_clean();
        
        // Generate HTML for past events
        ob_start();
        if ($past_query->have_posts()) {
            while ($past_query->have_posts()) {
                $past_query->the_post();
                get_template_part('src/components/_post-card/markup');
            }
        }
        wp_reset_postdata();
        $past_html = ob_get_clean();
        
        // For events, we'll return structured data
        $response['upcoming_html'] = $upcoming_html;
        $response['past_html'] = $past_html;
        $response['upcoming_count'] = $upcoming_query->found_posts;
        $response['past_count'] = $past_query->found_posts;
        
        // Set posts_html to past events for backward compatibility
        $response['posts_html'] = $past_html;
        
        ob_start(); // Start fresh for standard flow
    } elseif ($post_type === 'campagne') {
        // For campaigns, generate HTML for upcoming campaigns using campaign-card template
        if ($upcoming_query->have_posts()) {
            while ($upcoming_query->have_posts()) {
                $upcoming_query->the_post();
                get_template_part('src/components/_campaign-card/markup');
            }
        }
        wp_reset_postdata();
        $upcoming_html = ob_get_clean();
        
        // Generate HTML for past campaigns using post-card template
        ob_start();
        if ($past_query->have_posts()) {
            while ($past_query->have_posts()) {
                $past_query->the_post();
                get_template_part('src/components/_post-card/markup');
            }
        }
        wp_reset_postdata();
        $past_html = ob_get_clean();
        
        // For campaigns, we'll return structured data
        $response['upcoming_html'] = $upcoming_html;
        $response['past_html'] = $past_html;
        $response['upcoming_count'] = $upcoming_query->found_posts;
        $response['past_count'] = $past_query->found_posts;
        
        // Set posts_html to past campaigns for backward compatibility
        $response['posts_html'] = $past_html;
        
        ob_start(); // Start fresh for standard flow
    } else {
        // Standard posts handling
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                get_template_part('src/components/_post-card/markup');
            }
        } else {
            ?>
            <div class="no-results col-span-full text-center py-12">
                <h3 class="text-2xl font-safiro font-bold text-dark-green mb-4">Aucun résultat trouvé</h3>
                <p class="text-gray-600 mb-6">Essayez de modifier vos filtres pour voir plus de résultats.</p>
                <button class="reset-filters-cta inline-flex items-center px-6 py-3 bg-yellow text-dark-green font-safiro font-semibold @sm:rounded-xl @md/lg:rounded-xl hover:bg-yellow/90 transition-colors">
                    Réinitialiser les filtres
                </button>
            </div>
            <?php
        }
    }
    wp_reset_postdata();
    
    if ($post_type !== 'evenement' && $post_type !== 'campagne') {
        $response['posts_html'] = ob_get_clean();
    } else {
        ob_end_clean(); // Clean up the empty buffer
    }    // Generate pagination HTML using the component
    if ($query->max_num_pages > 1) {
        ob_start();
        
        // Set up pagination arguments for paginate_links
        $pagination_args = [
            'total' => $query->max_num_pages,
            'current' => max(1, $query->query_vars['paged']),
            'prev_text' => '⟵',
            'next_text' => '⟶',
            'type' => 'array',
            'end_size' => 2,
            'mid_size' => 1,
            'add_args' => false, // Prevent WordPress from adding current URL params
            'format' => '?paged=%#%' // Format for pagination URLs
        ];
        
        // Get pagination links directly
        $pagination_links = paginate_links($pagination_args);
        
        if ($pagination_links) {
            ?>
            <nav class="pagination">
                <ul class="pagination-list">
                    <?php foreach ($pagination_links as $link): ?>
                        <li class="pagination-item">
                            <?php 
                            // Check if it's the current page
                            if (strpos($link, 'current') !== false) {
                                // Convert link to span for current page
                                $current_link = str_replace('<a ', '<span ', $link);
                                $current_link = str_replace('</a>', '</span>', $current_link);
                                $current_link = str_replace('class="', 'class="pagination-current ', $current_link);
                                echo $current_link;
                            } else {
                                // Add pagination-link class and data-page attribute for AJAX handling
                                $regular_link = str_replace('<a ', '<a class="pagination-link" ', $link);
                                
                                // Extract page number and add data-page attribute for AJAX
                                $regular_link = preg_replace_callback(
                                    '/href=["\']([^"\']*)["\']/',
                                    function($matches) {
                                        $href = $matches[1];
                                        $page = 1;
                                        
                                        // Extract page number from URL
                                        if (preg_match('/[?&]paged=(\d+)/', $href, $page_matches)) {
                                            $page = $page_matches[1];
                                        } elseif (preg_match('/\/page\/(\d+)/', $href, $page_matches)) {
                                            $page = $page_matches[1];
                                        }
                                        
                                        return 'href="' . $href . '" data-page="' . $page . '"';
                                    },
                                    $regular_link
                                );
                                
                                echo $regular_link;
                            }
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <?php
        }
        
        $response['pagination_html'] = ob_get_clean();
    } else {
        $response['pagination_html'] = '';
    }
    
    // Remove debug info in production
    if (!WP_DEBUG) {
        unset($response['debug']);
    }
    
    wp_send_json($response);
}

/**
 * Get proper WordPress query parameter name for filter type
 * 
 * @param string $filter_type
 * @param string $post_type
 * @return string
 */
function get_wp_query_param_for_filter($filter_type, $post_type) {
    if ($filter_type === 'types') {
        return $post_type === 'post' ? 'type_enjeu' : 'type_document';
    }
    if ($filter_type === 'categories') {
        $available_taxonomies = get_archive_filter_taxonomies($post_type);
        return $available_taxonomies['categories'] ?? 'category';
    }
    return 'theme';
}

/**
 * Get filter URL parameters using WordPress standard names
 * 
 * @param array $filters
 * @param string $post_type
 * @return string
 */
function get_filter_url_params($filters, $post_type) {
    $params = [];
    
    foreach ($filters as $filter_type => $term_id) {
        if (!empty($term_id)) {
            $param_name = get_wp_query_param_for_filter($filter_type, $post_type);
            $term = get_term($term_id);
            if ($term && !is_wp_error($term)) {
                $params[$param_name] = $term->slug;
            }
        }
    }
    
    return http_build_query($params);
}

/**
 * Update main query on archive pages with filters
 * 
 * @param WP_Query $query
 */
function modify_archive_query_with_filters($query) {
    if (!is_admin() && $query->is_main_query() && (is_home() || is_archive())) {
        $post_type = $query->get('post_type') ?: 'post';
        
        // Get category filter based on post type
        $category_filter = '';
        $available_taxonomies = get_archive_filter_taxonomies($post_type);
        if (isset($available_taxonomies['categories'])) {
            $category_taxonomy = $available_taxonomies['categories'];
            // Try multiple possible parameter names
            $category_filter = $_GET[$category_taxonomy] ?? 
                              $_GET[str_replace('_', '-', $category_taxonomy)] ?? 
                              $_GET['category'] ?? '';
        }
        
        $current_filters = [
            'types' => $_GET['type_enjeu'] ?? $_GET['type_document'] ?? '',
            'categories' => $category_filter,
            'themes' => $_GET['theme'] ?? ''
        ];
        
        // Remove empty filters
        $current_filters = array_filter($current_filters);
        
        if (!empty($current_filters)) {
            $available_taxonomies = get_archive_filter_taxonomies($post_type);
            $tax_query = [];
            
            foreach ($current_filters as $filter_type => $term_slug) {
                if (isset($available_taxonomies[$filter_type])) {
                    $taxonomy = $available_taxonomies[$filter_type];
                    $term = get_term_by('slug', $term_slug, $taxonomy);
                    
                    if ($term && !is_wp_error($term)) {
                        $tax_query[] = [
                            'taxonomy' => $taxonomy,
                            'field' => 'term_id',
                            'terms' => $term->term_id
                        ];
                    }
                }
            }
            
            if (!empty($tax_query)) {
                if (count($tax_query) > 1) {
                    $tax_query['relation'] = 'AND';
                }
                $query->set('tax_query', $tax_query);
            }
        }
    }
}

add_action('pre_get_posts', 'modify_archive_query_with_filters');