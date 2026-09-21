<?php
/**
 * List Filters - Composant complet (titre + filtres + liste + pagination)
 *
 * Arguments optionnels :
 *   'title' : titre affiche (sinon ad_list_default_title())
 *   'intro' : HTML de chapeau, affiche sous le titre/les filtres.
 *             Rendu HORS du fragment `.list-filters__bar`, il n'est donc pas
 *             re-rendu par l'AJAX et reste en place pendant le filtrage.
 *
 * Usage :
 *   get_template_part('src/components/list-filters/markup', null, [
 *     'title'   => 'Nos Revues',
 *     'context' => ['type' => 'numero', 'per_page' => 9],
 *   ]);
 *
 *   get_template_part('src/components/list-filters/markup', null, [
 *     'context' => ['type' => 'thematique', 'id' => get_the_ID(), 'per_page' => 9],
 *   ]);
 */

$data    = is_array($args) ? $args : [];
$context = ad_list_context($data['context'] ?? []);
$title   = $data['title'] ?? ad_list_default_title($context);
$intro   = $data['intro'] ?? '';
$active  = ad_list_active_filters($context);
$paged   = max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: ($_GET['paged'] ?? 1)));
?>

<div class="list-filters"
     data-list-type="<?php echo esc_attr($context['type']); ?>"
     data-context-id="<?php echo esc_attr($context['id']); ?>"
     data-per-page="<?php echo esc_attr($context['per_page']); ?>"
     data-base-url="<?php echo esc_url(ad_list_base_url($context)); ?>">

  <!-- Titre + filtres -->
  <section class="archive-header @sm:mt-[70px] @md/lg:mt-[130px] py-section theme-white bg-layout-main">
    <div class="container">
      <?php ad_list_render_bar($context, $active, $title); ?>

      <?php if (!empty($intro)): ?>
        <div class="list-filters__intro wysiwyg paragraph-primary autoscale @sm:mt-6 @md/lg:mt-10 @md/lg:max-w-[760px]">
          <?php echo wp_kses_post($intro); ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Liste + pagination -->
  <section class="archive-posts py-section pt-0 theme-white bg-layout-main">
    <div class="container">
      <?php ad_list_render_results($context, $active, $paged); ?>
    </div>
  </section>

  <!-- Overlay de chargement -->
  <div class="filter-loading fixed inset-0 bg-black/50 z-[9999] items-center justify-center backdrop-blur-sm hidden">
    <div class="bg-white @sm:rounded-lg @md/lg:rounded-lg @sm:p-6 @md/lg:p-6 flex items-center @sm:gap-4 @md/lg:gap-4">
      <div class="animate-spin rounded-full @sm:h-8 @md/lg:h-8 @sm:w-8 @md/lg:w-8 border-b-2 border-yellow"></div>
      <span class="paragraph-sm paragraph-primary">Chargement...</span>
    </div>
  </div>

</div>
