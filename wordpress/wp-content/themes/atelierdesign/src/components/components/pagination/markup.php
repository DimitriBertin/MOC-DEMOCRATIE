<?php
/**
 * Pagination Component
 * 
 * Displays pagination navigation
 */

$pagination_args = is_array($args) ? $args : [];

// Default pagination arguments
$default_args = [
  'mid_size' => 2,
  'prev_text' => '⟵',
  'next_text' => '⟶',
  'type' => 'array',
  'echo' => false
];

// Merge with provided arguments
$pagination_args = array_merge($default_args, $pagination_args);

// Get pagination links
$pagination_links = paginate_links($pagination_args);

if ($pagination_links) {
  ?>
  <nav class="pagination">
    <ul class="pagination-list autoscale">
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
?>