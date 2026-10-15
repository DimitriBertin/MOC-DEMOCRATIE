<?php
/**
 * Single Numero (Revue)
 *
 * Structure (brief client) :
 *   1. En-tete : "La revue n°XX - Titre du numero - date de parution" + couverture
 *   2. « Dans ce numero » : version raccourcie (500 signes) + lien « Lire le "Dans ce numero" complet »
 *   3. Au sommaire : pour chaque article -> titre, chapeau (2 lignes), bouton « Lire l'article »
 *      puis, sous le sommaire : telecharger le PDF, liseuse
 *   4. Pour aller plus loin : numeros connectes (champ numeros_lies, sinon auto par thematique)
 *   5. CTA Footer (si renseigne sur la revue)
 *
 * Les articles viennent du champ "Articles du numero" (relation bidirectionnelle, ordre conserve).
 * Champs : src/fieldGroups/numero.php, src/fieldGroups/numero-relations.php
 */

global $adwp;
?>

<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="numero" class="article">

  <?php while (have_posts()): the_post(); ?>

    <?php
    $numero_id    = get_the_ID();
    $numero_num   = get_field('numero_numero', $numero_id);
    $numero_title = get_field('title', $numero_id) ?: get_the_title($numero_id);
    $parution     = function_exists('ad_numero_parution_label') ? ad_numero_parution_label($numero_id) : '';
    $dcn_full     = get_field('dans_ce_numero', $numero_id);
    $dcn          = function_exists('ad_numero_dans_ce_numero_short') ? ad_numero_dans_ce_numero_short($numero_id) : ['text' => '', 'truncated' => false];
    $liseuse      = get_field('liseuse', $numero_id);
    $pdf          = get_field('pdf', $numero_id);
    $article_ids  = function_exists('ad_get_numero_article_ids') ? ad_get_numero_article_ids($numero_id) : [];
    $archive_url  = get_post_type_archive_link('numero');
    ?>

    <!-- 1. En-tete + couverture -->
    <section class="numero-sommaire @sm:mt-[70px] @md/lg:mt-[130px] py-section theme-white bg-layout-main">
      <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-12 @sm:gap-8 @md/lg:gap-x-16 items-start">

          <!-- Couverture -->
          <div class="numero-cover md:col-span-5 md:sticky md:top-[160px]">
            <div class="@sm:rounded-xl @md/lg:rounded-xl overflow-hidden bg-dark-green shadow-[19px_19px_43px_rgba(0,0,0,0.09)]">
              <?php if (has_post_thumbnail($numero_id)): ?>
                <?php echo get_the_post_thumbnail($numero_id, 'large', [
                  'class' => 'w-full h-auto block',
                  'alt'   => esc_attr($numero_title),
                ]); ?>
              <?php else: ?>
                <div class="relative aspect-[3/4]">
                  <?php ad_render_card_placeholder($numero_id, $numero_title, 'publication'); ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <div class="numero-content md:col-span-7 autoscale flex flex-col @sm:gap-10 @md/lg:gap-14">

            <!-- En-tete du numero -->
            <div class="flex flex-col @sm:gap-3 @md/lg:gap-4">
              <p class="numero-meta label label-primary">
                <?php if ($numero_num !== '' && $numero_num !== null): ?>
                  La revue n°<?php echo esc_html($numero_num); ?>
                <?php endif; ?>
                <?php if ($parution): ?>
                  <span class="numero-meta__sep">/</span> <?php echo esc_html($parution); ?>
                <?php endif; ?>
              </p>
              <h1 class="heading-2xl heading-primary"><?php echo esc_html($numero_title); ?></h1>
            </div>

            <!-- 2. Dans ce numero : version courte + lien pour lire le texte complet -->
            <?php if ($dcn['text'] !== ''): ?>
              <div class="numero-dans-ce-numero flex flex-col @sm:gap-4 @md/lg:gap-5" id="dans-ce-numero">
                <h2 class="numero-sommaire__title text-display text-yellow">Dans ce numéro</h2>
                <p class="numero-dans-ce-numero__text paragraph-md"><?php echo esc_html($dcn['text']); ?></p>

                <?php if ($dcn['truncated'] && !empty($dcn_full)): ?>
                  <div class="numero-dans-ce-numero__full wysiwyg paragraph-md" id="dans-ce-numero-full" hidden>
                    <?php echo wp_kses_post(balanceTags($dcn_full, true)); ?>
                  </div>
                  <button type="button" class="numero-dans-ce-numero__toggle" aria-expanded="false" aria-controls="dans-ce-numero-full"
                          data-label-open="Lire le « Dans ce numéro » complet" data-label-close="Réduire le « Dans ce numéro »">
                    <span class="numero-dans-ce-numero__toggle-label">Lire le « Dans ce numéro » complet</span>
                    <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                  </button>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <!-- 3. Au sommaire : articles du numero -->
            <?php if (!empty($article_ids)): ?>
              <div class="numero-au-sommaire" id="sommaire">
                <h2 class="numero-sommaire__title text-display text-yellow">Au sommaire</h2>
                <ul class="numero-au-sommaire__list">
                  <?php foreach ($article_ids as $article_id): ?>
                    <?php
                    $chapo       = function_exists('ad_get_article_chapo') ? ad_get_article_chapo($article_id) : '';
                    $own_liseuse = get_field('liseuse_article', $article_id);
                    ?>
                    <li class="numero-au-sommaire__item">
                      <h3 class="heading-md heading-primary">
                        <a href="<?php echo esc_url(get_permalink($article_id)); ?>"><?php echo esc_html(get_the_title($article_id)); ?></a>
                      </h3>
                      <?php if ($chapo !== ''): ?>
                        <p class="numero-au-sommaire__chapo paragraph-md paragraph-primary"><?php echo esc_html($chapo); ?></p>
                      <?php endif; ?>
                      <div class="numero-au-sommaire__actions">
                        <div class="theme-yellow">
                          <a href="<?php echo esc_url(get_permalink($article_id)); ?>" class="button-flat button-primary">
                            <span class="button-title">Lire l’article</span>
                          </a>
                        </div>
                        <?php if (!empty($own_liseuse['url'])): ?>
                          <a href="<?php echo esc_url($own_liseuse['url']); ?>" class="numero-au-sommaire__liseuse paragraph-primary"
                             target="<?php echo esc_attr($own_liseuse['target'] ?: '_blank'); ?>" rel="noopener noreferrer">
                            <span class="material-symbols-outlined" aria-hidden="true">picture_as_pdf</span>
                            Liseuse PDF
                          </a>
                        <?php endif; ?>
                      </div>
                    </li>
                  <?php endforeach; ?>
                </ul>

                <!-- Sous le sommaire : PDF puis liseuse -->
                <?php if (!empty($pdf['url']) || !empty($liseuse['url'])): ?>
                  <div class="numero-more theme-yellow @sm:mt-6 @md/lg:mt-8 flex-col items-start">
                    <?php if (!empty($pdf['url'])): ?>
                      <a href="<?php echo esc_url($pdf['url']); ?>" class="numero-liseuse button-flat button-primary" download>
                        <span class="material-symbols-outlined" aria-hidden="true">download</span>
                        <span class="button-title">Télécharger le numéro complet (PDF)</span>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($liseuse['url'])): ?>
                      <a href="<?php echo esc_url($liseuse['url']); ?>" class="numero-liseuse button-flat button-primary"
                         target="<?php echo esc_attr($liseuse['target'] ?: '_blank'); ?>" rel="noopener noreferrer">
                        <span class="material-symbols-outlined" aria-hidden="true">auto_stories</span>
                        <span class="button-title"><?php echo esc_html($liseuse['title'] ?: 'Lire la revue dans la liseuse PDF'); ?></span>
                      </a>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </section>

    <!-- 4. Pour aller plus loin : numeros connectes -->
    <?php $related_ids = function_exists('ad_get_numero_related_ids') ? ad_get_numero_related_ids($numero_id, 3) : []; ?>
    <?php if (!empty($related_ids)): ?>
      <section class="numero-articles py-section theme-white bg-layout-main pt-0">
        <div class="container">
          <h2 class="heading-lg heading-primary autoscale @sm:mb-8 @md/lg:mb-12">Pour aller plus loin</h2>
          <div class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @lg:grid-cols-3 @sm:gap-y-8 @md/lg:gap-y-12 @sm:gap-x-3 @md/lg:gap-x-3">
            <?php foreach ($related_ids as $related_id): ?>
              <?php get_template_part('src/components/_numero-card/markup', null, ['post_id' => (int) $related_id]); ?>
            <?php endforeach; ?>
          </div>
          <?php if ($archive_url): ?>
            <div class="theme-yellow @sm:mt-8 @md/lg:mt-12">
              <a href="<?php echo esc_url($archive_url); ?>" class="numero-liseuse button-flat button-primary">
                <span class="material-symbols-outlined" aria-hidden="true">library_books</span>
                <span class="button-title">Voir les autres numéros de la revue</span>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </section>
    <?php endif; ?>

    <script>
      (function () {
        var btn = document.querySelector('.numero-dans-ce-numero__toggle');
        if (!btn) return;
        var full = document.getElementById(btn.getAttribute('aria-controls'));
        var label = btn.querySelector('.numero-dans-ce-numero__toggle-label');
        btn.addEventListener('click', function () {
          var open = btn.getAttribute('aria-expanded') === 'true';
          btn.setAttribute('aria-expanded', open ? 'false' : 'true');
          if (open) { full.setAttribute('hidden', ''); } else { full.removeAttribute('hidden'); }
          label.textContent = open ? btn.dataset.labelOpen : btn.dataset.labelClose;
        });
      })();
    </script>

    <?php
    /* 5. CTA Footer */
    $cta_footer_data = get_field('cta_footer');

    if (!empty($cta_footer_data) && !empty($cta_footer_data['ctaFooter_items'])) {
      get_template_part('src/components/ctaFooter/markup', null, $cta_footer_data);
    }
    ?>

  <?php endwhile; ?>

</main>

<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
