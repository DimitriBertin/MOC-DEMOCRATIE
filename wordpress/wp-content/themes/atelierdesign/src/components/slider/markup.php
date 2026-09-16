<?php
/**
 * Slider Component
 *
 * A responsive slider/carousel using Swiper
 * Usage: get_template_part('src/components/slider/markup', null, $section_data);
 */

global $adwp;

$section = is_array($args) ? $args : [];

// Extract theme and split based on the '/' and get the theme-* and the bg-layout-* classes from it
$theme = $section['theme'] ?? 'primary/main';

// Handle case where theme might be an array (from color picker field)
if (is_array($theme)) {
  $theme = $theme['value'] ?? $theme['label'] ?? 'primary/main';
}

// Ensure theme is a string before exploding
$theme = is_string($theme) ? $theme : 'primary/main';
$colorParts = explode('/', $theme);
$themeClass = 'theme-' . mb_strtolower($colorParts[0] ?? 'primary', 'UTF-8');
$layoutClass = 'bg-layout-' . mb_strtolower($colorParts[1] ?? 'main', 'UTF-8');

// Get slides
$slides = $section['slides'] ?? [];

// Generate unique ID for this slider instance
$slider_id = 'slider-' . uniqid();

?>

<section class="slider-section py-section overflow-hidden <?= $themeClass; ?> <?= $layoutClass; ?>">
    <?php if (!empty($slides)): ?>
      <div class="slider-wrapper container !overflow-visible relative">
        <div class="swiper !overflow-visible <?= $slider_id; ?>">
          <div class="swiper-wrapper">
            <?php // call it 4 times to avoid loop issues ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
              <?php foreach ($slides as $slide):
                $image = $slide['image'] ?? null;
                $title = $slide['title'] ?? '';
                $link = $slide['link'] ?? null;
              ?>
                <div class="swiper-slide">
                  <div class="slide-card flex flex-col gap-4">
                    <?php if (!empty($image)): ?>
                      <div class="slide-image @sm:rounded-xl @md/lg:rounded-xl overflow-hidden aspect-[256/187]">
                        <?php echo wp_get_attachment_image(
                          $image['ID'], 
                          'medium', 
                          false, 
                          [
                            'class' => 'w-full h-full object-cover',
                            'alt' => $image['alt'] ?: $title
                          ]
                        ); ?>
                      </div>
                    <?php endif; ?>

                    <div class="slide-content flex flex-col @sm:gap-2 @md/lg:gap-2">
                      <?php if (!empty($title)): ?>
                        <h3 class="slide-title heading-sm autoscale">
                          <?php echo esc_html($title); ?>
                        </h3>
                      <?php endif; ?>

                      <?php if (!empty($link)): ?>
                        <a href="<?php echo esc_url($link['url']); ?>" target="<?php echo esc_attr($link['target'] ?: '_self'); ?>" class="button-primary button-title group flex items-center gap-2">
                          <span class="font-semibold"><?php echo esc_html($link['title']); ?></span>
                          <div class="link-arrows flex items-center -space-x-1 group-hover:opacity-80 group-hover:translate-x-2 transition-all">
                            <svg class="w-2.5 h-2.5 flex-shrink-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M2.40017 9.59312C2.01527 9.25255 1.96327 8.64515 2.28402 8.23646L4.82401 5.00009L2.28402 1.76371C1.96327 1.35503 2.01527 0.74763 2.40017 0.407056C2.78507 0.0664816 3.35712 0.1217 3.67787 0.530389L6.70183 4.38342C6.98219 4.74064 6.98219 5.25953 6.70183 5.61675L3.67787 9.46979C3.35712 9.87847 2.78507 9.93369 2.40017 9.59312Z" fill="currentColor" />
                            </svg>
                            <svg class="w-2.5 h-2.5 flex-shrink-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M2.61599 9.59312C2.23109 9.25255 2.17909 8.64515 2.49984 8.23646L5.03983 5.00009L2.49984 1.76371C2.17909 1.35503 2.23109 0.74763 2.61599 0.407056C3.00089 0.0664816 3.57294 0.1217 3.89369 0.530389L6.91765 4.38342C7.19801 4.74064 7.19801 5.25953 6.91765 5.61675L3.89369 9.46979C3.57294 9.87847 3.00089 9.93369 2.61599 9.59312Z" fill="currentColor" />
                            </svg>
                          </div>
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endfor; ?>
          </div>
        </div>

        <div class="slider-navigation flex items-center justify-center @sm:gap-2 @md/lg:gap-2 @sm:mt-11 @md/lg:mt-16">
          <button class="slider-button-prev <?= $slider_id; ?>-prev @sm:w-[60px] @sm:h-[60px] @md/lg:w-[60px] @md/lg:h-[60px] rounded-full border border-yellow flex items-center justify-center bg-transparent hover:bg-yellow transition-colors">
            <svg  class="@sm:w-[17px] @md/lg:w-[17px] @sm:h-[12px] @md/lg:h-[12px]" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.00098 1L1.00098 6L6.00098 11" stroke="#012E31" stroke-linecap="round" stroke-linejoin="bevel"/>
            <path d="M1 6L16 6" stroke="#012E31" stroke-linecap="round"/>
            </svg>
          </button>
          
          <button class="slider-button-next <?= $slider_id; ?>-next @sm:w-[60px] @sm:h-[60px] @md/lg:w-[60px] @md/lg:h-[60px] rounded-full border border-yellow flex items-center justify-center bg-transparent hover:bg-yellow transition-colors">
            <svg class="@sm:w-[17px] @md/lg:w-[17px] @sm:h-[12px] @md/lg:h-[12px]" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.999 11L15.999 6L10.999 1" stroke="#012E31" stroke-linecap="round" stroke-linejoin="bevel"/>
            <path d="M16 6L1 6" stroke="#012E31" stroke-linecap="round"/>
            </svg>
          </button>
        </div>
      </div>

      <script type="module">
        // Wait for Swiper to be available globally
        document.addEventListener('DOMContentLoaded', function() {
          if (typeof window.Swiper !== 'undefined' && typeof window.SwiperNavigation !== 'undefined') {
            const swiper_<?= str_replace('-', '_', $slider_id); ?> = new window.Swiper('.<?= $slider_id; ?>', {
              modules: [window.SwiperNavigation],
              slidesPerView: 1,
              slidePerGroup: 1,
              spaceBetween: 16,
              loop: true,
              grabCursor: true,
              centeredSlides: true,
              centerInsufficientSlides: true,
              loopAdditionalSlides: 3,
              navigation: {
                nextEl: '.<?= $slider_id; ?>-next',
                prevEl: '.<?= $slider_id; ?>-prev',
              },
              breakpoints: {
                600: {
                  slidesPerView: 3,
                  spaceBetween: 16,
                },
                1025: {
                  slidesPerView: 4,
                  spaceBetween: 16,
                },
              },
            });
          } else {
            console.error('Swiper is not available. Make sure it\'s loaded in build.js');
          }
        });
      </script>
    <?php else: ?>
      <div class="text-center py-8">
        <p>No slides available.</p>
      </div>
    <?php endif; ?>


</section>
