<?php
/**
 * Numero Card Component
 *
 * Carte d'un Numero : featured image, date, titre.
 * Le numero fait partie du titre (ex : "Numero 42 - Democratie"), il n'y a pas
 * de champ dedie.
 *
 * Sans image mise en avant, on affiche le visuel de repli du theme
 * (motif "moc moc" + icone ronde jaune) : voir src/functions/card-placeholder.php
 *
 * Usage: get_template_part('src/components/_numero-card/markup', null, ['post_id' => $id]);
 */

$card    = is_array($args) ? $args : [];
$post_id = $card['post_id'] ?? get_the_ID();

// Variante "archive" (page /revues/) : image pleine taille (hauteur auto, sans recadrage),
// puis lien "Decouvrir" + bouton "Recevoir" (page definie dans les options du site).
if (($card['variant'] ?? '') === 'archive') {
    $archive_title   = get_field('title', $post_id) ?: get_the_title($post_id);
    $recevoir        = get_field('recevoir_link', 'acf-options-global-fields');
    $recevoir_url    = is_array($recevoir) ? ($recevoir['url'] ?? '') : '';
    $recevoir_label  = is_array($recevoir) && !empty($recevoir['title']) ? $recevoir['title'] : 'Recevoir';
    $recevoir_target = is_array($recevoir) && !empty($recevoir['target']) ? $recevoir['target'] : '_self';
    ?>
    <article class="post-card numero-card numero-card--archive flex flex-col @sm:gap-4 @md/lg:gap-5">
      <div class="numero-card__image @sm:rounded-xl @md/lg:rounded-xl overflow-hidden bg-dark-green">
        <?php $archive_img = ad_numero_image($post_id, 'list', ['class' => 'w-full h-auto block', 'loading' => 'lazy', 'alt' => esc_attr($archive_title)]); ?>
        <?php if ($archive_img): ?>
          <?php echo $archive_img; ?>
        <?php else: ?>
          <div class="relative aspect-[3/4]">
            <?php ad_render_card_placeholder($post_id, $archive_title, 'publication'); ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="numero-card__actions flex flex-wrap items-center @sm:gap-3 @md/lg:gap-4">
        <a href="<?php echo esc_url($post_permalink = get_permalink($post_id)); ?>" class="numero-card__discover" aria-label="<?php echo esc_attr('Découvrir : ' . $archive_title); ?>">Découvrir</a>
        <?php if ($recevoir_url): ?>
          <div class="theme-yellow">
            <a href="<?php echo esc_url($recevoir_url); ?>" class="button-flat button-primary" target="<?php echo esc_attr($recevoir_target); ?>"<?php echo $recevoir_target === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>>
              <span class="button-title"><?php echo esc_html($recevoir_label); ?></span>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </article>
    <?php
    return;
}

$label     = get_field('label', $post_id);
$post_title     = get_field('title', $post_id) ?: get_the_title($post_id);
$post_num     = get_field('numero_numero', $post_id);
$post_permalink = get_permalink($post_id);
$post_date      = get_the_date('d.m.Y', $post_id);
?>

<article class="post-card numero-card overflow-hidden">
  <a href="<?php echo esc_url($post_permalink); ?>" class="flex flex-col group">
    <!-- Featured image -->
    <div class="post-image relative overflow-hidden bg-dark-green @sm:h-[160px] @md/lg:h-[308px] @sm:rounded-xl @md/lg:rounded-xl ">
      <?php $card_img = ad_numero_image($post_id, ($card['variant'] ?? '') === 'home' ? 'home' : 'list', [
        'class' => 'w-full h-full object-cover group-hover:scale-105 duration-300 group-hover:duration-1000 transition-transform',
        'loading' => 'lazy',
        'alt' => esc_attr($post_title),
      ]); ?>
      <?php if ($card_img): ?>
        <?php echo $card_img; ?>
      <?php else: ?>
        <?php ad_render_card_placeholder($post_id, $post_title, 'publication'); ?>
      <?php endif; ?>
    </div>
    <!-- @@:py-[20px] @@:px-[30px] bg-white -->
    <div class="@@:pt-[20px] @sm:gap-4 @md/lg:gap-4 flex-col flex post-content autoscale-children">
      <div class="badge-wrapper flex flex-wrap items-center justify-between @sm:gap-2 @md/lg:gap-2">
        <?php if ($post_num !== '' && $post_num !== null): ?>
          <div class="badge-surface">
            Revue numéro <?php echo esc_html($post_num); ?>
          </div>
        <?php endif; ?>
        <?php // Date de parution (meme reference que le tri et le filtre annee)
        $parution = function_exists('ad_numero_parution_label') ? ad_numero_parution_label($post_id) : ''; ?>
        <?php if ($parution): ?>
          <span class="label paragraph-primary"><?php echo esc_html($parution); ?></span>
        <?php endif; ?>
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
