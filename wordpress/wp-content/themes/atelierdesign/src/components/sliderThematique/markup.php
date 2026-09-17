<?php
/**
 * Slider Thematique Component
 *
 * Slider (Swiper) alimente automatiquement par le CPT `thematique`.
 * 4 elements visibles en desktop, 3 en tablette, 1 en mobile.
 *
 * Chaque slide :
 *   image  = featured image
 *   badge  = titre de la thematique
 *   texte  = hero.title (description) ou excerpt
 *
 * Usage: get_template_part('src/components/sliderThematique/markup', null, $section_data);
 */

global $adwp;

require_once get_template_directory() . '/src/components/_thematique-card/helpers.php';

$section = is_array($args) ? $args : [];

// Theme -> classes theme-* / bg-layout-*
$theme = $section['theme'] ?? 'primary/main';
if (is_array($theme)) {
  $theme = $theme['value'] ?? $theme['label'] ?? 'primary/main';
}
$theme = is_string($theme) ? $theme : 'primary/main';
$colorParts = explode('/', $theme);
$themeClass  = 'theme-' . mb_strtolower($colorParts[0] ?? 'primary', 'UTF-8');
$layoutClass = 'bg-layout-' . mb_strtolower($colorParts[1] ?? 'main', 'UTF-8');

$label        = $section['label'] ?? '';
$title        = $section['title'] ?? '';
$only_parents = !isset($section['onlyParents']) ? true : (bool) $section['onlyParents'];
$orderby      = $section['orderby'] ?? 'menu_order';

// Toutes les thematiques remontent (-1)
$query_args = [
  'numberposts' => -1,
  'post_status' => 'publish',
  'post_type'   => 'thematique',
];

if ($only_parents) {
  $query_args['post_parent'] = 0;
}

switch ($orderby) {
  case 'title':
    $query_args['orderby'] = 'title';
    $query_args['order']   = 'ASC';
    break;
  case 'date':
    $query_args['orderby'] = 'date';
    $query_args['order']   = 'DESC';
    break;
  case 'rand':
    $query_args['orderby'] = 'rand';
    break;
  default:
    $query_args['orderby'] = ['menu_order' => 'ASC', 'title' => 'ASC'];
}

$thematiques = get_posts($query_args);

// Identifiant unique de cette instance de slider
$slider_id = 'slider-thematique-' . uniqid();

// Depuis Swiper 9, `loop` reordonne les vraies slides au lieu de cloner : il lui
// faut donc assez de slides reelles, sinon il insere des blancs (d'ou les sauts).
// On repete la liste jusqu'a 3x le plus grand slidesPerView.
$slides_count = count($thematiques);
$max_per_view = 5; // valeur du plus grand breakpoint ci-dessous
$repeat = $slides_count > 0
  ? max(1, (int) ceil(($max_per_view * 3) / $slides_count))
  : 1;
?>

<section class="slider-section slider-thematique py-section overflow-hidden <?= $themeClass; ?> <?= $layoutClass; ?>">
  <?php if (!empty($thematiques)): ?>
    <div class="slider-wrapper container !overflow-visible relative">

      <?php if (!empty($label) || !empty($title)): ?>
        <div class="slider-header flex flex-col items-center text-center autoscale @sm:gap-4 @md/lg:gap-4 @sm:mb-10 @md/lg:mb-16">
          <?php if (!empty($label)): ?>
            <div class="label label-primary"><?php echo esc_html($label); ?></div>
          <?php endif; ?>
          <?php if (!empty($title)): ?>
            <h2 class="heading-2xl heading-primary"><?php echo esc_html($title); ?></h2>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="swiper !overflow-visible <?= $slider_id; ?>">
        <div class="swiper-wrapper">
          <?php for ($i = 0; $i < $repeat; $i++): ?>
          <?php foreach ($thematiques as $thematique):
              $data = ad_get_thematique_card_data($thematique->ID);
            ?>
              <div class="swiper-slide">
                <a href="<?php echo esc_url($data['permalink']); ?>" class="slide-card thematique-slide flex flex-col gap-4 group">
                  <div class="slide-image @sm:rounded-xl @md/lg:rounded-xl overflow-hidden bg-dark-green aspect-[256/187]">
                    <?php echo wp_get_attachment_image($data['image_id'], 'medium_large', false, [
                      'class' => 'w-full h-full object-cover group-hover:scale-105 duration-300 group-hover:duration-1000 transition-transform',
                      'loading' => 'lazy',
                      'draggable' => 'false',
                      'alt' => esc_attr($data['title']),
                    ]); ?>
                  </div>

                  <div class="slide-content flex flex-col @sm:gap-2 @md/lg:gap-2 autoscale-children">
                    <div class="badge-wrapper flex justify-start">
                      <div class="badge-surface">
                        <?php echo esc_html($data['title']); ?>
                      </div>
                    </div>

                    <?php if (!empty($data['description'])): ?>
                      <h3 class="slide-title heading-sm heading-primary autoscale group-hover:opacity-80 transition-opacity duration-200">
                        <?php echo esc_html($data['description']); ?>
                      </h3>
                    <?php endif; ?>
                  </div>
                </a>
              </div>
          <?php endforeach; ?>
          <?php endfor; ?>
        </div>
      </div>

      <div class="slider-navigation flex items-center justify-center @sm:gap-2 @md/lg:gap-2 @sm:mt-11 @md/lg:mt-16">
        <button class="slider-button-prev <?= $slider_id; ?>-prev @sm:w-[60px] @sm:h-[60px] @md/lg:w-[60px] @md/lg:h-[60px] rounded-full border border-yellow flex items-center justify-center bg-transparent hover:bg-yellow transition-colors" aria-label="Precedent">
          <svg class="@sm:w-[17px] @md/lg:w-[17px] @sm:h-[12px] @md/lg:h-[12px]" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.00098 1L1.00098 6L6.00098 11" stroke="#012E31" stroke-linecap="round" stroke-linejoin="bevel"/>
            <path d="M1 6L16 6" stroke="#012E31" stroke-linecap="round"/>
          </svg>
        </button>

        <button class="slider-button-next <?= $slider_id; ?>-next @sm:w-[60px] @sm:h-[60px] @md/lg:w-[60px] @md/lg:h-[60px] rounded-full border border-yellow flex items-center justify-center bg-transparent hover:bg-yellow transition-colors" aria-label="Suivant">
          <svg class="@sm:w-[17px] @md/lg:w-[17px] @sm:h-[12px] @md/lg:h-[12px]" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.999 11L15.999 6L10.999 1" stroke="#012E31" stroke-linecap="round" stroke-linejoin="bevel"/>
            <path d="M16 6L1 6" stroke="#012E31" stroke-linecap="round"/>
          </svg>
        </button>
      </div>
    </div>

    <script type="module">
      // En dev (Vite), build.js peut arriver apres DOMContentLoaded : on patiente.
      (function initSlider(attempt) {
        attempt = attempt || 0;
        if (typeof window.Swiper === 'undefined' || typeof window.SwiperNavigation === 'undefined') {
          if (attempt < 40) { return setTimeout(function () { initSlider(attempt + 1); }, 50); }
          return console.error('Swiper is not available. Make sure it\'s loaded in build.js');
        }
        new window.Swiper('.<?= $slider_id; ?>', {
            modules: [window.SwiperNavigation],
            slidesPerView: 1,
            slidesPerGroup: 1,
            spaceBetween: 16,
            loop: true,
            loopAddBlankSlides: false,
            grabCursor: true,
            watchOverflow: true,
            threshold: 5,
            preventClicks: true,
            preventClicksPropagation: true,
            navigation: {
              nextEl: '.<?= $slider_id; ?>-next',
              prevEl: '.<?= $slider_id; ?>-prev',
            },
            breakpoints: {
              600: {
                slidesPerView: 4,
                spaceBetween: 16,
              },
              1025: {
                slidesPerView: 5,
                spaceBetween: 16,
              },
            },
        });
      })();
    </script>
  <?php else: ?>
    <div class="text-center py-8">
      <p class="text-typography-heading-primary">Aucune thematique trouvee.</p>
    </div>
  <?php endif; ?>
</section>
