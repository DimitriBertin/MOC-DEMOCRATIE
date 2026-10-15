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
    $dcn_full     = get_field('dans_ce_numero', $numero_id);
    $dcn          = function_exists('ad_numero_dans_ce_numero_short') ? ad_numero_dans_ce_numero_short($numero_id) : ['text' => '', 'truncated' => false];
    $liseuse      = get_field('liseuse', $numero_id);
    $pdf          = get_field('pdf', $numero_id);
    $article_ids  = function_exists('ad_get_numero_article_ids') ? ad_get_numero_article_ids($numero_id) : [];
    $related_ids  = function_exists('ad_get_numero_related_ids') ? ad_get_numero_related_ids($numero_id) : [];
    $archive_url  = get_post_type_archive_link('numero');

    $has_intro    = $dcn['text'] !== '';
    $has_sommaire = !empty($article_ids) || !empty($dcn_full) || !empty($pdf['url']) || !empty($liseuse['url']);
    // Sans "Dans ce numero", le sommaire est visible d'emblee (rien a deplier)
    $sommaire_open = !$has_intro;
    ?>

    <section class="numero-sommaire @sm:mt-[70px] @md/lg:mt-[130px] py-section theme-white bg-layout-main">
      <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-12 @sm:gap-8 @md/lg:gap-x-16 items-start">

          <!-- Gauche : couverture -->
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

          <!-- Droite : infos -->
          <div class="numero-content md:col-span-7 autoscale flex flex-col @sm:gap-10 @md/lg:gap-14">

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

            <!-- Dans ce numero (disparait au clic sur "Lire le numero complet") -->
            <?php if ($has_intro): ?>
              <div class="numero-dans-ce-numero flex flex-col @sm:gap-4 @md/lg:gap-5" id="dans-ce-numero">
                <h2 class="numero-sommaire__title text-display text-yellow">Dans ce numéro</h2>
                <p class="numero-dans-ce-numero__text paragraph-md"><?php echo esc_html($dcn['text']); ?></p>

                <?php if ($has_sommaire): ?>
                  <div class="theme-yellow">
                    <button type="button" class="numero-toggle button-flat button-primary" data-numero-open aria-expanded="false" aria-controls="numero-au-sommaire">
                      <span class="button-title">Lire le numéro complet</span>
                    </button>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <!-- Au sommaire (cache tant qu'on n'a pas clique) -->
            <?php if ($has_sommaire): ?>
              <div class="numero-au-sommaire flex flex-col @sm:gap-6 @md/lg:gap-8" id="numero-au-sommaire" <?php echo $sommaire_open ? '' : 'hidden'; ?>>

                <?php if (!empty($dcn_full)): ?>
                  <div class="numero-au-sommaire__intro wysiwyg paragraph-md paragraph-primary">
                    <?php echo wp_kses_post(balanceTags($dcn_full, true)); ?>
                  </div>
                <?php endif; ?>

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
              </div>
            <?php endif; ?>

            <!-- Pour aller plus loin : numeros choisis dans le backoffice -->
            <?php if (!empty($related_ids)): ?>
              <div class="numero-plus-loin">
                <h2 class="numero-sommaire__title text-display text-yellow">Pour aller plus loin</h2>
                <div class="posts-grid grid @sm:grid-cols-1 @md/lg:grid-cols-2 @sm:gap-y-8 @md/lg:gap-y-10 @sm:gap-x-3 @md/lg:gap-x-3">
                  <?php foreach ($related_ids as $related_id): ?>
                    <?php get_template_part('src/components/_numero-card/markup', null, ['post_id' => (int) $related_id]); ?>
                  <?php endforeach; ?>
                </div>
                <?php if ($archive_url): ?>
                  <div class="theme-yellow @sm:mt-6 @md/lg:mt-8">
                    <a href="<?php echo esc_url($archive_url); ?>" class="numero-liseuse button-flat button-primary">
                      <span class="material-symbols-outlined" aria-hidden="true">library_books</span>
                      <span class="button-title">Voir les autres numéros de la revue</span>
                    </a>
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
          var intro = document.getElementById('dans-ce-numero');
          var sommaire = document.getElementById(btn.getAttribute('aria-controls'));
          if (intro) intro.setAttribute('hidden', '');
          if (sommaire) {
            sommaire.removeAttribute('hidden');
            sommaire.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      })();
    </script>

  <?php endwhile; ?>

</main>

<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
