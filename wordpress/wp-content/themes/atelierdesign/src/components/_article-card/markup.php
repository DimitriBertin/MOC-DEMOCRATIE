<?php
/**
 * Article Card Component
 *
 * Carte d'un Article (post) basee sur le nouveau modele de donnees :
 * relations Thematique / Auteur-rice / Numero (voir src/fieldGroups/post-relations.php)
 *
 * Usage: get_template_part('src/components/_article-card/markup', null, ['post_id' => $id]);
 */
$card = is_array($args) ? $args : [];
$post_id = $card['post_id'] ?? get_the_ID();

$post_title     = get_the_title($post_id);
$post_permalink = get_permalink($post_id);
$post_date      = get_the_date('d.m.Y', $post_id);

// Premiere thematique associee -> sert de badge
$thematiques = get_field('thematiques', $post_id);
$primary_thematique = (is_array($thematiques) && !empty($thematiques)) ? $thematiques[0] : null;

// Numero associe (optionnel, affiche a cote de la date)
$numero = get_field('numero', $post_id);
?>

<article class="post-card article-card">
  <a href="<?php echo esc_url($post_permalink); ?>" class="flex flex-col @sm:gap-4 @md/lg:gap-4 group">
    <!-- Image -->
    <div class="post-image relative @sm:rounded-xl @md/lg:rounded-xl overflow-hidden bg-dark-green aspect-[370/308]">
      <?php echo get_the_post_thumbnail($post_id, 'medium_large', [
        'class' => 'w-full h-full object-cover group-hover:scale-105 duration-300 group-hover:duration-1000 transition-transform',
        'loading' => 'lazy',
        'alt' => esc_attr($post_title),
      ]); ?>
    </div>

    <!-- Meta -->
    <div class="post-meta autoscale flex flex-wrap justify-between items-start @sm:gap-2 @md/lg:gap-2">
      <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>" class="block label paragraph-primary">
        <?php echo esc_html($post_date); ?>
        <?php if ($numero instanceof WP_Post): ?>
          &middot; <?php echo esc_html(get_the_title($numero->ID)); ?>
        <?php endif; ?>
      </time>

      <?php if ($primary_thematique instanceof WP_Post): ?>
        <div class="badge-wrapper flex justify-end">
          <div class="badge-surface">
            <?php echo esc_html(get_the_title($primary_thematique->ID)); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Titre -->
    <h3 class="post-title autoscale heading-md heading-primary @sm:pr-4 @lg/md:pr-4 group-hover:opacity-80 transition-opacity duration-200">
      <?php echo esc_html($post_title); ?>
    </h3>
  </a>
</article>
