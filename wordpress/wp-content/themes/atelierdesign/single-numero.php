<?php
/**
 * Single Numero (Revue)
 *
 * Deux colonnes : couverture a gauche / infos a droite.
 *
 * Colonne de droite :
 *   - En-tete : "La revue n°XX / date" + titre
 *   - « Dans ce numero » (version courte, 500 signes) + bouton « Lire le numero complet »
 *       -> au clic, la zone « Dans ce numero » disparait et laisse place au sommaire :
 *          texte complet + articles du numero (titre, chapeau, « Lire l'article » + liseuse PDF de l'article)
 *          + bouton PDF du numero + bouton liseuse
 *   - « Pour aller plus loin » : numeros choisis dans le backoffice (champ `numeros_lies`)
 *
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
    $dcn_rows     = get_field('sommaire_flexible', $numero_id);
    $liseuse      = get_field('liseuse', $numero_id);
    $pdf          = get_field('pdf', $numero_id);
    $article_ids  = function_exists('ad_get_numero_article_ids') ? ad_get_numero_article_ids($numero_id) : [];
    $related_ids  = function_exists('ad_get_numero_related_ids') ? ad_get_numero_related_ids($numero_id) : [];
    $archive_url  = get_post_type_archive_link('numero');

    $has_intro    = !empty($dcn_rows) && is_array($dcn_rows);
    $has_sommaire = !empty($article_ids) || !empty($pdf['url']) || !empty($liseuse['url']) || !empty($related_ids);

    // Extrait 500 signes du flexible (textes des wysiwyg + citations)
    $dcn_text = '';
    if ($has_intro) {
      foreach ($dcn_rows as $r) {
        $html = $r['_wysiwyg_content'] ?? ($r['_quote_content'] ?? '');
        $html = preg_replace('/<\/(p|h[1-6]|li|div|blockquote)>/i', '$0 ', (string) $html);
        $dcn_text .= ' ' . wp_strip_all_tags($html);
      }
      $dcn_text = trim(preg_replace('/\s+/u', ' ', html_entity_decode($dcn_text, ENT_QUOTES, 'UTF-8')));
    }
    $dcn_has_more = mb_strlen($dcn_text) > 500;
    $dcn_excerpt  = $dcn_text;
    if ($dcn_has_more) {
      $cut = mb_substr($dcn_text, 0, 500);
      $sp  = mb_strrpos($cut, ' ');
      $dcn_excerpt = rtrim(($sp !== false && $sp > 400) ? mb_substr($cut, 0, $sp) : $cut, " ,;:.-") . '...';
    }
    ?>

    <section class="numero-sommaire @sm:mt-[70px] @md/lg:mt-[130px] py-section theme-white bg-layout-main">
      <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-12 @sm:gap-8 @md/lg:gap-x-16 items-start">

          <!-- Gauche : couverture -->
          <div class="numero-cover md:col-span-5 md:sticky" style="top:200px;">
            <div class="numero-cover__frame @sm:rounded-xl @md/lg:rounded-xl overflow-hidden bg-dark-green ">
              <?php $cover_img = ad_numero_image($numero_id, 'list', ['class' => 'numero-cover__img w-full h-auto block', 'alt' => esc_attr($numero_title)]); ?>
              <?php if ($cover_img): ?>
                <?php echo $cover_img; ?>
              <?php else: ?>
                <div class="relative aspect-[3/4]">
                  <?php ad_render_card_placeholder($numero_id, $numero_title, 'publication'); ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Droite : infos -->
          <div class="numero-content md:col-span-7 autoscale flex flex-col @sm:gap-10 @md/lg:gap-14">

            <!-- En-tete : La revue n°XX  /  date (justify between) -->
            <h1 class="sr-only"><?php echo esc_html($numero_title); ?></h1>
            <p class="numero-meta label label-primary flex flex-wrap items-center justify-between @sm:gap-2 @md/lg:gap-4">
              <span>
                <?php if ($numero_num !== '' && $numero_num !== null): ?>
                  La revue n°<?php echo esc_html($numero_num); ?>
                <?php endif; ?>
              </span>
              <?php if ($parution): ?>
                <span><?php echo esc_html($parution); ?></span>
              <?php endif; ?>
            </p>

            <!-- Dans ce numero : champ "Sommaire" en entier, toujours visible. "Lire la suite" revele les articles -->
            <?php if ($has_intro): ?>
              <div class="numero-dans-ce-numero theme-dark-green flex flex-col @sm:gap-4 @md/lg:gap-5" id="dans-ce-numero">
                <h2 class="numero-sommaire__title text-display text-yellow">Dans ce numéro</h2>
                <?php if ($dcn_has_more): ?>
                  <div class="numero-dans-ce-numero__text numero-dans-ce-numero__excerpt" id="dcn-excerpt">
                    <p class="paragraph-md paragraph-primary"><?php echo esc_html($dcn_excerpt); ?></p>
                  </div>
                <?php endif; ?>
                <div class="numero-dans-ce-numero__text flex flex-col @sm:gap-4 @md/lg:gap-5" id="dcn-full" <?php echo $dcn_has_more ? 'hidden' : ''; ?>>
                  <?php
                  // Reglages par defaut si absents (lignes creees par le seed ou jamais ouvertes en admin)
                  $dcn_defaults = [
                    '_quote'     => ['alignment' => 'left', 'hasBorder' => false, 'hasIcon' => false],
                    '_separator' => ['color' => 'default', 'marginY' => 'sm'],
                  ];
                  foreach ($dcn_rows as $dcn_row) {
                    $layout = $dcn_row['acf_fc_layout'] ?? '';
                    $key    = $layout . '_layout_settings';
                    if (isset($dcn_defaults[$layout]) && empty($dcn_row[$key])) {
                      $dcn_row[$key] = $dcn_defaults[$layout];
                    }
                    $adwp->render_flexible_layout([$dcn_row], ['isNested' => true]);
                  }
                  ?>
                </div>

                <?php if ($dcn_has_more): ?>
                  <div class="theme-yellow" data-numero-open-wrapper>
                    <button type="button" class="numero-toggle button-flat button-primary" data-numero-open aria-expanded="false" aria-controls="dcn-full">
                      <span class="button-title">Lire plus</span>
                    </button>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <!-- Au sommaire : toujours visible -->
            <?php if ($has_sommaire): ?>
              <div class="numero-au-sommaire flex flex-col @sm:gap-6 @md/lg:gap-8" id="numero-au-sommaire">

                <?php if (!empty($article_ids)): ?>
                  <div>
                    <h2 class="numero-sommaire__title text-display text-yellow">Au sommaire</h2>
                    <ul class="numero-au-sommaire__list">
                      <?php foreach ($article_ids as $article_id): ?>
                        <?php
                        $chapo       = function_exists('ad_get_article_chapo') ? ad_get_article_chapo($article_id) : '';
                        $own_liseuse = get_field('liseuse_article', $article_id);
                        ?>
                        <li class="numero-au-sommaire__item">
                          <h3 class="numero-au-sommaire__title">
                            <a href="<?php echo esc_url(get_permalink($article_id)); ?>"><?php echo esc_html(get_the_title($article_id)); ?></a>
                          </h3>
                          <?php if ($chapo !== ''): ?>
                            <p class="numero-au-sommaire__chapo paragraph-md paragraph-primary"><?php echo esc_html($chapo); ?></p>
                          <?php endif; ?>
                          <div class="numero-au-sommaire__actions">
                            <a href="<?php echo esc_url(get_permalink($article_id)); ?>" class="numero-au-sommaire__read">Lire l’article</a>
                            <?php if (!empty($own_liseuse['url'])): ?>
                              <a href="<?php echo esc_url($own_liseuse['url']); ?>" class="numero-au-sommaire__liseuse"
                                 target="<?php echo esc_attr($own_liseuse['target'] ?: '_blank'); ?>" rel="noopener noreferrer">
                                <span class="material-symbols-outlined" aria-hidden="true">picture_as_pdf</span>
                                PDF
                              </a>
                            <?php endif; ?>
                          </div>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endif; ?>

                <?php if (!empty($pdf['url']) || !empty($liseuse['url'])): ?>
                  <div class="numero-more theme-yellow flex-col items-start">
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

                <!-- Pour aller plus loin : numeros choisis dans le backoffice (visible apres clic sur "Lire le numero complet") -->
                <?php if (!empty($related_ids)): ?>
                  <div class="numero-plus-loin">
                    <h2 class="numero-sommaire__title text-display text-yellow">Pour aller plus loin</h2>
                    <div class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @sm:gap-y-8 @md/lg:gap-y-10 @sm:gap-x-3 @md/lg:gap-x-3">
                      <?php foreach ($related_ids as $related_id): ?>
                        <?php get_template_part('src/components/_numero-card/markup', null, ['post_id' => (int) $related_id]); ?>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </section>

    <?php
    /* CTA Footer */
    $cta_footer_data = get_field('cta_footer');

    if (!empty($cta_footer_data) && !empty($cta_footer_data['ctaFooter_items'])) {
      get_template_part('src/components/ctaFooter/markup', null, $cta_footer_data);
    }
    ?>

    <script>
      (function () {
        var btn = document.querySelector('[data-numero-open]');
        if (!btn) return;
        btn.addEventListener('click', function () {
          var wrapper = btn.closest('[data-numero-open-wrapper]') || btn;
          var full = document.getElementById('dcn-full');
          var excerpt = document.getElementById('dcn-excerpt');
          wrapper.setAttribute('hidden', '');
          btn.setAttribute('aria-expanded', 'true');
          if (excerpt) excerpt.setAttribute('hidden', '');
          if (full) full.removeAttribute('hidden');
        });
      })();
    </script>

  <?php endwhile; ?>

</main>

<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
