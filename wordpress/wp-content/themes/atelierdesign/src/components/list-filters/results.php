<?php
/**
 * List Filters - Resultats (grille + pagination)
 *
 * Fragment re-rendu tel quel par l'AJAX (voir filter-functions.php).
 *
 * $args :
 *   - context : ['type','id','per_page']
 *   - active  : [param => valeur]
 *   - paged   : int
 */

$data    = is_array($args) ? $args : [];
$context = ad_list_context($data['context'] ?? []);
$active  = is_array($data['active'] ?? null) ? $data['active'] : [];
$paged   = max(1, (int) ($data['paged'] ?? 1));

$query_args = ad_list_query_args($context, $active, $paged);
$query      = new WP_Query($query_args);

$card_part = $context['type'] === 'numero'
    ? 'src/components/_numero-card/markup'
    : 'src/components/_article-card/markup';

$empty_text = $context['type'] === 'numero'
    ? 'Aucune revue ne correspond à votre sélection.'
    : 'Aucun article ne correspond à votre sélection.';
?>

<div class="list-results">

  <?php if ($query->have_posts()): ?>

    <div class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-y-8 @md/lg:gap-y-12 @sm:gap-x-3 @md/lg:gap-x-3">
      <?php while ($query->have_posts()): $query->the_post(); ?>
        <?php get_template_part($card_part, null, ['post_id' => get_the_ID()]); ?>
      <?php endwhile; ?>
    </div>

    <?php
    if ($query->max_num_pages > 1) {
      get_template_part('src/components/pagination/markup', null, [
        'base'      => trailingslashit(ad_list_base_url($context)) . '%_%',
        'format'    => '?paged=%#%',
        'total'     => $query->max_num_pages,
        'current'   => $paged,
        'add_args'  => $active,
        'mid_size'  => 1,
        'end_size'  => 2,
      ]);
    }
    ?>

  <?php else: ?>

    <div class="no-posts @sm:py-16 @md/lg:py-16 text-center">
      <p class="paragraph-lg paragraph-primary autoscale"><?php echo esc_html($empty_text); ?></p>
      <?php if (!empty($active)): ?>
        <button type="button" class="reset-filters button-underline button-primary border-b p-0 border-dark-green hover:border-yellow hover:text-yellow @sm:mt-6 @md/lg:mt-6">
          <span class="button-title font-semibold autoscale">Reset all</span>
        </button>
      <?php endif; ?>
    </div>

  <?php endif; ?>

</div>

<?php wp_reset_postdata(); ?>
