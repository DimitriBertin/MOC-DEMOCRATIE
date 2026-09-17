<?php
/**
 * Numero Card Component
 *
 * Carte d'un Numero : featured image, date, titre.
 * Le numero fait partie du titre (ex : "Numero 42 - Democratie"), il n'y a pas
 * de champ dedie.
 *
 * Usage: get_template_part('src/components/_numero-card/markup', null, ['post_id' => $id]);
 */

$card    = is_array($args) ? $args : [];
$post_id = $card['post_id'] ?? get_the_ID();

$label     = get_field('label', $post_id);
$post_title     = get_field('title', $post_id);
$post_permalink = get_permalink($post_id);
$post_date      = get_the_date('d.m.Y', $post_id);
?>

<article class="post-card numero-card @sm:rounded-xl @md/lg:rounded-xl overflow-hidden">
  <a href="<?php echo esc_url($post_permalink); ?>" class="flex flex-col group">
    <!-- Featured image -->
    <div class="post-image relative overflow-hidden bg-dark-green aspect-[534/434]">
      <?php echo get_the_post_thumbnail($post_id, 'large', [
        'class' => 'w-full h-full object-cover group-hover:scale-105 duration-300 group-hover:duration-1000 transition-transform',
        'loading' => 'lazy',
        'alt' => esc_attr($post_title),
      ]); ?>
    </div>
    <div class="bg-white @@:py-[20px] @@:px-[30px] @sm:gap-4 @md/lg:gap-4 flex-col flex post-content autoscale-children">
      <div class="badge-wrapper flex justify-start">
        <div class="badge-surface">
          <?php echo esc_html($label); ?>
        </div>
      </div>
      <!-- Titre (contient le numero) -->
      <h3 class="post-title autoscale heading-md heading-primary @sm:pr-4 @lg/md:pr-4 group-hover:opacity-80 transition-opacity duration-200">
        <?php echo esc_html($post_title); ?>
      </h3>
    </div>

    <!-- Date -->
    <!-- <div class="post-meta autoscale flex flex-wrap justify-between items-start @sm:gap-2 @md/lg:gap-2">
      <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>" class="block label paragraph-primary">
        <?php echo esc_html($post_date); ?>
      </time>
    </div> -->

  </a>
</article>
