<?php
/**
 * Related Numero Component
 *
 * Affiche une grille de Numeros (CPT `numero`).
 * Usage: get_template_part('src/components/relatedNumero/markup', null, $section_data);
 */

global $adwp;

$section = is_array($args) ? $args : [];

$theme = $section['theme'] ?? 'primary/main';
if (is_array($theme)) {
  $theme = $theme['value'] ?? $theme['label'] ?? 'primary/main';
}
$theme = is_string($theme) ? $theme : 'primary/main';
$colorParts = explode('/', $theme);
$themeClass  = 'theme-' . mb_strtolower($colorParts[0] ?? 'primary', 'UTF-8');
$layoutClass = 'bg-layout-' . mb_strtolower($colorParts[1] ?? 'main', 'UTF-8');

$label   = $section['label'] ?? '';
$title   = $section['title'] ?? '';
$button  = $section['button'] ?? null;
$count   = (int) ($section['count'] ?? 3);
$count   = $count > 0 ? $count : 3;
$orderby = $section['orderby'] ?? 'date';

// Plus recents / plus anciens : tri sur la date de parution (champ
// `date_parution`, repli sur la date du post) via ad_numero_dates_map(),
// comme la page "Nos Revues".
if (in_array($orderby, ['date', 'date_asc'], true) && function_exists('ad_numero_dates_map')) {
  $ids = array_keys(ad_numero_dates_map()); // plus recent d'abord
  if ($orderby === 'date_asc') {
    $ids = array_reverse($ids);
  }
  $ids = array_slice($ids, 0, $count);

  $related_numeros = !empty($ids) ? get_posts([
    'post_type'      => 'numero',
    'post_status'    => 'publish',
    'post__in'       => $ids,
    'orderby'        => 'post__in',
    'posts_per_page' => $count,
  ]) : [];
} else {
  $query_args = [
    'numberposts' => $count,
    'post_status' => 'publish',
    'post_type'   => 'numero',
    'orderby'     => $orderby === 'title' ? 'title' : 'rand',
    'order'       => 'ASC',
  ];

  $related_numeros = get_posts($query_args);
}
?>

<section class="related-content related-numero py-section <?= $themeClass; ?> <?= $layoutClass; ?>">
  <div class="container flex flex-wrap justify-between @sm:gap-10 @md/lg:gap-[52px]">
    <div class="flex flex-col autoscale @sm:gap-4 @md/lg:gap-4 md:order-1">
      <?php if (!empty($label)): ?>
        <div class="label label-primary"><?php echo esc_html($label); ?></div>
      <?php endif; ?>
      <?php if (!empty($title)): ?>
        <h2 class="heading-2xl heading-primary"><?php echo esc_html($title); ?></h2>
      <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 @sm:gap-10 @md/lg:gap-3 md:order-3 w-full">
      <?php if (!empty($related_numeros)): ?>
        <?php foreach ($related_numeros as $related_numero): ?>
          <?php get_template_part('src/components/_numero-card/markup', null, [
            'post_id' => $related_numero->ID,
          ]); ?>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-span-full text-center py-8">
          <p class="text-typography-heading-primary">Aucun numero trouve.</p>
        </div>
      <?php endif; ?>
    </div>

    <?php if (!empty($button)): ?>
      <div class="md:order-2 self-end mm-sm:w-full">
        <a
          href="<?php echo esc_url($button['url']); ?>"
          target="<?php echo esc_attr($button['target'] ?: '_self'); ?>"
          class="button-flat button-primary autoscale md:flex-shrink-0 mm-sm:w-full mm-sm:justify-center mm-sm:text-center"
        >
          <span class="button-title"><?php echo esc_html($button['title']); ?></span>
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>
