<?php
/**
 * Thematique Card Component
 *
 * Carte d'une Thematique (CPT hierarchique).
 * Badge   = titre de la thematique
 * Texte   = hero.title (description courte) ou excerpt
 * Image   = featured image
 *
 * Usage: get_template_part('src/components/_thematique-card/markup', null, ['post_id' => $id]);
 */

require_once get_template_directory() . '/src/components/_thematique-card/helpers.php';

$card    = is_array($args) ? $args : [];
$post_id = $card['post_id'] ?? get_the_ID();
$data    = ad_get_thematique_card_data($post_id);
?>

<article class="post-card thematique-card">
  <a href="<?php echo esc_url($data['permalink']); ?>" class="flex flex-col @sm:gap-4 @md/lg:gap-4 group">
    <!-- Image -->
    <div class="post-image relative @sm:rounded-xl @md/lg:rounded-xl overflow-hidden bg-dark-green aspect-[370/308]">
      <?php echo wp_get_attachment_image($data['image_id'], 'medium_large', false, [
        'class' => 'w-full h-full object-cover group-hover:scale-105 duration-300 group-hover:duration-1000 transition-transform',
        'loading' => 'lazy',
        'alt' => esc_attr($data['title']),
      ]); ?>
    </div>

    <!-- Badge = titre de la thematique -->
    <div class="post-meta autoscale flex flex-wrap items-start @sm:gap-2 @md/lg:gap-2">
      <div class="badge-wrapper flex justify-start">
        <div class="badge-surface">
          <?php echo esc_html($data['title']); ?>
        </div>
      </div>
    </div>

    <!-- Description -->
    <?php if (!empty($data['description'])): ?>
      <h3 class="post-title autoscale heading-md heading-primary @sm:pr-4 @lg/md:pr-4 group-hover:opacity-80 transition-opacity duration-200">
        <?php echo esc_html($data['description']); ?>
      </h3>
    <?php endif; ?>
  </a>
</article>
