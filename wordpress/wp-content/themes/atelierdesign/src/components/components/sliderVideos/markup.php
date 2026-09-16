<?php
/**
 * Slider Videos Component
 *
 * A responsive video slider using Swiper with pagination and navigation
 * Usage: get_template_part('src/components/sliderVideos/markup', null, $section_data);
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
$themeClass = "theme-" . mb_strtolower($colorParts[0] ?? 'primary', 'UTF-8');
$layoutClass = "bg-layout-" . mb_strtolower($colorParts[1] ?? 'main', 'UTF-8');

// Get component data
$title = $section['title'] ?? '';
$text = $section['text'] ?? '';
$videos = $section['videos'] ?? [];

// Generate unique ID for this slider instance
$slider_id = 'slider-videos-' . uniqid();

// Total slides count
$total_slides = count($videos);

?>

<section class="slider-videos py-section <?= $themeClass; ?> <?= $layoutClass; ?> overflow-hidden">
  <div class="container overflow-visible">
    <!-- Header Section -->
    <div class="slider-videos-header flex flex-col @sm:gap-5 @md/lg:gap-5 @sm:mb-5 @md/lg:mb-5 autoscale">
      <?php if (!empty($title)): ?>
        <h2 class="heading-2xl heading-primary md:w-2/3">
          <?php echo esc_html($title); ?>
        </h2>
      <?php endif; ?>
      
      <?php if (!empty($text)): ?>
        <p class="paragraph-md paragraph-primary md:w-2/3">
          <?php echo nl2br(esc_html($text)); ?>
        </p>
      <?php endif; ?>
    </div>

    <?php if (!empty($videos)): ?>
      <!-- Pagination and Progress Bar -->
      <div class="slider-pagination-wrapper flex justify-between items-center @sm:mb-5 @md/lg:mb-10">
        <div class="slider-pagination-numbers paragraph-sm paragraph-primary autoscale">
          <span class="current-slide">01</span> / <span class="total-slides"><?php echo str_pad($total_slides, 2, '0', STR_PAD_LEFT); ?></span>
        </div>
        
        <div class="slider-progress mm-sm:hidden flex-1 @md/lg:mx-7 h-[1px] bg-[#012E31] bg-opacity-50 relative">
          <div class="slider-progress-bar absolute top-0 left-0 h-full bg-yellow transition-all duration-300" style="width: 0%;"></div>
        </div>
        
        <div class="slider-navigation flex items-center @sm:gap-2 @md/lg:gap-2 autoscale">
          <button class="slider-button-prev [.swiper-button-disabled&]:opacity-50 [.swiper-button-disabled&]:pointer-events-none <?= $slider_id; ?>-prev @sm:w-[60px] @sm:h-[60px] @md/lg:w-[60px] @md/lg:h-[60px] rounded-full border border-light-green-70 bg-white flex items-center justify-center hover:bg-yellow hover:border-yellow transition-colors">
            <svg class="@sm:w-[17px] @md/lg:w-[17px] @sm:h-[12px] @md/lg:h-[12px]" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6.00098 1L1.00098 6L6.00098 11" stroke="#012E31" stroke-linecap="round" stroke-linejoin="bevel"/>
              <path d="M1 6L16 6" stroke="#012E31" stroke-linecap="round"/>
            </svg>
          </button>
          
          <button class="slider-button-next [.swiper-button-disabled&]:opacity-50 <?= $slider_id; ?>-next @sm:w-[60px] @sm:h-[60px] @md/lg:w-[60px] @md/lg:h-[60px] rounded-full border border-light-green-70 bg-white flex items-center justify-center hover:bg-yellow hover:border-yellow transition-colors">
            <svg class="@sm:w-[17px] @md/lg:w-[17px] @sm:h-[12px] @md/lg:h-[12px]" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10.999 11L15.999 6L10.999 1" stroke="#012E31" stroke-linecap="round" stroke-linejoin="bevel"/>
              <path d="M16 6L1 6" stroke="#012E31" stroke-linecap="round"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Swiper Slider -->
      <div class="slider-wrapper relative">
        <div class="swiper <?= $slider_id; ?> !overflow-visible">
          <div class="swiper-wrapper">
            <?php foreach ($videos as $index => $video):
              $video_id = 'video-' . uniqid();
              $is_url = $video['sliderVideos_video_isUrl'] ?? false;
              $video_file = $video['sliderVideos_video_video'] ?? null;
              $video_url = $video['sliderVideos_video_url'] ?? '';
              $poster = $video['sliderVideos_video_poster'] ?? null;
              
              // Prepare video data for the ad-ui video component
              $video_args = [
                'isUrl' => $is_url,
                'video' => $video_file,
                'url' => $video_url,
                'poster' => $poster,
                'layout_settings' => [
                  'aspect' => '16/9',
                  'alignment' => 'center',
                  'controls' => false,
                  'autoplay' => false,
                  'loop' => false,
                  'muted' => false,
                  'width' => 100,
                  'fit' => true,
                  'parallax' => false,
                ],
                'isNested' => true,
              ];
            ?>
              <div class="swiper-slide">
                <div class="video-wrapper @sm:rounded-xl @md/lg:rounded-xl overflow-hidden">
                  <?php get_template_part('ad-ui/acf/components/_video/markup', null, $video_args); ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Swiper Initialization -->
      <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
          if (typeof window.Swiper !== 'undefined' && typeof window.SwiperNavigation !== 'undefined') {
            
            const swiper = new window.Swiper('.<?= $slider_id; ?>', {
              modules: [window.SwiperNavigation],
              slidesPerView: 1,
              spaceBetween: 8,
              loop: false,
              grabCursor: true,
              preventInteractionOnTransition: true,
              
              navigation: {
                nextEl: '.<?= $slider_id; ?>-next',
                prevEl: '.<?= $slider_id; ?>-prev',
                disabledClass: 'swiper-button-disabled',
              },
              breakpoints: {
                600: {
                  slidesPerView: 1.6,
                  spaceBetween: 40,
                },
              },
              on: {
                init: function() {
                  updatePagination(this);
                },
                slideChange: function() {
                  updatePagination(this);
                }
              }
            });
            
            function updatePagination(swiperInstance) {
              const currentSlide = swiperInstance.activeIndex + 1;
              const totalSlides = <?= $total_slides; ?>;
              const progressPercentage = (currentSlide / totalSlides) * 100;
              
              // Update numbers
              const currentSlideEl = document.querySelector('.<?= $slider_id; ?>').closest('.slider-videos').querySelector('.current-slide');
              if (currentSlideEl) {
                currentSlideEl.textContent = String(currentSlide).padStart(2, '0');
              }
              
              // Update progress bar
              const progressBar = document.querySelector('.<?= $slider_id; ?>').closest('.slider-videos').querySelector('.slider-progress-bar');
              if (progressBar) {
                progressBar.style.width = progressPercentage + '%';
              }
            }
            
            // Prevent video component events from interfering with swiper navigation
            const swiperContainer = document.querySelector('.<?= $slider_id; ?>');
          
          } else {
            console.error('Swiper is not available. Make sure it\'s loaded in build.js');
          }
        });
      </script>
    <?php endif; ?>
  </div>
</section>
