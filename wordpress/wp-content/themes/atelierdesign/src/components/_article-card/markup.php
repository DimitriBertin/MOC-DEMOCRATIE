<?php
/**
 * Article Card Component
 *
 * Carte d'un Article (post) basee sur le nouveau modele de donnees :
 * relations Thematique / Auteur·rice / Numero (voir src/fieldGroups/post-relations.php)
 *
 * Sans image mise en avant, on affiche le visuel de repli du theme
 * (motif "moc moc" + icone ronde jaune) : voir src/functions/card-placeholder.php
 *
 * Usage: get_template_part('src/components/_article-card/markup', null, ['post_id' => $id]);
 */
$card = is_array($args) ? $args : [];
$post_id = $card['post_id'] ?? get_the_ID();

$post_title     = get_the_title($post_id);
$post_permalink = get_permalink($post_id);
$post_date      = get_the_date('d.m.Y', $post_id);

// Thematiques principales uniquement (les sous-thematiques sont remontees
// a leur thematique racine) -> badges
$main_thematiques = function_exists('ad_get_main_thematiques') ? ad_get_main_thematiques($post_id) : [];

// Numero associe (optionnel, affiche a cote de la date)
$numero = get_field('numero', $post_id);
?>

<article class="post-card article-card">
  <a href="<?php echo esc_url($post_permalink); ?>" class="flex flex-col @sm:gap-4 @md/lg:gap-4 group">
    <!-- Image -->
    <div class="post-image relative @sm:rounded-xl @md/lg:rounded-xl overflow-hidden bg-dark-green aspect-[370/308]">
      <?php if (has_post_thumbnail($post_id)): ?>
        <?php echo get_the_post_thumbnail($post_id, 'medium_large', [
          'class' => 'w-full h-full object-cover group-hover:scale-105 duration-300 group-hover:duration-1000 transition-transform',
          'loading' => 'lazy',
          'alt' => esc_attr($post_title),
        ]); ?>
      <?php else: ?>
        <?php ad_render_card_placeholder($post_id, $post_title); ?>
      <?php endif; ?>

      <?php // Icone(s) Podcast / Debat
      if (function_exists('ad_render_article_formats')) {
        ad_render_article_formats($post_id, 'card');
      } ?>
    </div>

    <!-- Meta -->
    <div class="post-meta autoscale flex flex-wrap justify-between items-start @sm:gap-2 @md/lg:gap-2">
      <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>" class="block label paragraph-primary">
        <?php echo esc_html($post_date); ?>
      </time>

      <?php if (!empty($main_thematiques)): ?>
        <div class="badge-wrapper flex flex-wrap justify-end @sm:gap-1 @md/lg:gap-1">
          <?php foreach ($main_thematiques as $main_thematique): ?>
            <div class="badge-surface">
              <?php echo esc_html(get_the_title($main_thematique->ID)); ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Titre -->
    <h3 class="post-title autoscale heading-md heading-primary @sm:pr-4 @lg/md:pr-4 group-hover:opacity-80 transition-opacity duration-200">
      <?php echo esc_html($post_title); ?>
    </h3>
  </a>
</article>
