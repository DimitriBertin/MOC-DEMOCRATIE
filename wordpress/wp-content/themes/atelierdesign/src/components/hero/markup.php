<?php
/**
 * Hero Component Markup
 * Supports multiple template types with different layouts and content
 */

// Get hero data with automatic detection
$hero_data = get_hero_data();
$template_type = $hero_data['template_type'];

$is_single = ($template_type == 'single-post' || $template_type == 'single-document' || $template_type == 'single-evenement');


// Extract hero data
$title = $hero_data['title'] ? $hero_data['title'] : get_the_title();
$background_image = $hero_data['background_image'];
$label = $hero_data['label'];
$taxonomy_badges = $hero_data['taxonomy_badges'];
$cta_button = $hero_data['cta_button'];
$text_alignment = $hero_data['text_alignment'];
$image_layout = $hero_data['image_layout'];

// Determine layout classes based on template type
$section_classes = ['hero', 'relative', 'flex', 'items-center'];
$content_classes = ['relative', 'w-full', 'mm-sm:container', 'px-content', 'z-2'];
$image_layout_classes = [];
$title_classes = ['text-display', 'text-yellow', 'autoscale'];
$scroll_classes = [];
$header_classes = [];

// Template-specific configurations
switch ($template_type) {
    case 'homepage':
        $section_classes[] = 'min-h-dvh';
        $section_classes[] = '@sm:pt-[102px] @md/lg:pt-[227px]';
        $section_classes[] = '@sm:pb-[204px]';
        $section_classes[] = '@md/lg:pb-[292px]';
        $content_classes[] = 'text-center';
        $scroll_classes[] = '@sm:bottom-[176px]';
        $scroll_classes[] = '@md/lg:bottom-[230px]';
        break;
        
    case 'page':
        $section_classes[] = 'min-h-[80dvh]';
        $section_classes[] = '@sm:pt-[102px] @md/lg:pt-[227px]';
        $section_classes[] = '@sm:pb-[40px]';
        $section_classes[] = ' @md/lg:pb-[100px]';       
        if ($text_alignment === 'left') {
            $content_classes[] = 'text-left';
        } else {
            $content_classes[] = 'text-center';
            $header_classes[] = 'justify-center';
        }
        break;
        
    case 'single-job':
        $section_classes[] = '@sm:pt-[102px] @md/lg:pt-[227px]';
        $section_classes[] = '@sm:pb-[40px]';
        $section_classes[] = ' @md/lg:pb-[83px]';
        break;
        
    case 'single-campagne':
        $section_classes[] = '@sm:pt-[102px] @md/lg:pt-[227px]';
        $section_classes[] = 'min-h-[80dvh]';
        $section_classes[] = '@sm:pb-[40px]';
        $section_classes[] = ' @md/lg:pb-[100px]';
        break;
    case 'single-post':
    case 'single-evenement':
    case 'single-document':
        $content_classes[] = 'flex';
        $image_layout_classes[] = '@sm:rounded-xl @md/lg:rounded-xl overflow-hidden';
        if ($background_image && $background_image['ID'] !== 1) {
          if ($image_layout === 'content') {
            $image_layout_classes[] = 'md:min-w-[48%] @md/lg:translate-x-[100px]';
            $image_layout_classes[] = 'mm-sm:absolute mm-sm:container mm-sm:top-full @sm:mt-10 @md/lg:mt-0  mm-sm:left-1/2 mm-sm:-translate-x-1/2 mm-sm:aspect-[3/2]';
            $section_classes[] = '@sm:pb-28 @sm:mb-[148px] @sm:pt-[102px] @md/lg:pt-[158px] @md/lg:pb-16 @md/lg:mb-0';
          } else {
            $section_classes[] = '@sm:pt-[102px] @md/lg:pt-[227px]';
            $section_classes[] = '@sm:pb-28 @md/lg:pb-[227px]';
            $section_classes[] = '@sm:mb-[148px] @md/lg:mb-[423px]';
            $content_classes[] = 'flex-wrap';
            $image_layout_classes[] = 'absolute container top-full @sm:mt-10 @md/lg:mt-20 left-1/2 -translate-x-1/2 aspect-[2/1] mm-sm:aspect-[3/2]';
          }
        }
        else {
          $image_layout_classes[] = 'hidden';
          $section_classes[] = ' @sm:pt-[102px] @md/lg:pt-[227px] @sm:pb-[60px] @md/lg:pb-[124px]';

        }
        // $section_classes[] = 'min-h-[80dvh]';
        // $section_classes[] = '@sm:pb-[40px]';
        // $section_classes[] = ' @md/lg:pb-[100px]';
        // if ($image_layout === 'content') {
        //     $section_classes[] = 'py-16';
        //     $section_classes[] = 'lg:py-24';
        //     $content_classes[] = 'max-w-4xl';
        //     $content_classes[] = 'mx-auto';
        // } else {
        //     $section_classes[] = 'min-h-[70vh]';
        //     $section_classes[] = 'flex';
        //     $content_classes[] = 'max-w-7xl';
        //     $content_classes[] = 'mx-auto';
        // }
        // $content_classes[] = 'text-center';
        // $title_classes[] = 'text-center';
        break;
}
?>

<section class="theme-dark-green bg-layout-main <?php echo implode(' ', $section_classes); ?>">

  <?php if (!$is_single && $background_image && $background_image['ID']): ?>
      <!-- Background Image with Screen Blend Mode -->
      <?php echo wp_get_attachment_image($background_image['ID'], 'large', false, ['class' => 'absolute inset-0 object-cover mix-blend-screen opacity-90 w-full h-full']); ?>
      
      <!-- Gradient Overlays -->
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-[#012E31] opacity-90" aria-hidden="true"></div>
      <div class="absolute top-0 left-0 right-0 h-48 bg-gradient-to-b from-[#012E31] to-transparent opacity-90" aria-hidden="true"></div>
      <?php else: ?>
      <div class="absolute bottom-0 right-0 autoscale">
        <svg class="@sm:w-[309px] @sm:h-[102px] @md/lg:w-[926px] @md/lg:h-[307px]" width="926" height="307" viewBox="0 0 926 307" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path opacity="0.1" d="M762.657 106.601C742.197 106.601 725.849 113.649 713.808 127.745C701.767 141.841 695.697 161.223 695.697 185.794C695.697 210.364 701.767 229.648 713.808 243.548C725.849 257.449 742.197 264.399 762.657 264.399C783.117 264.399 800.151 257.449 812.192 243.548C824.233 229.648 830.303 210.364 830.303 185.794C830.303 161.223 824.233 141.841 812.192 127.745C800.151 113.649 783.607 106.601 762.657 106.601ZM762.657 0C819.632 0 866.034 17.3264 902.06 51.9792C937.987 86.1425 956 130.78 956 185.892C956 241.003 937.987 285.641 902.06 319.804C866.034 353.967 819.632 371 762.657 371C705.683 371 660.357 353.967 623.94 319.804C587.915 285.641 570 241.003 570 185.892C570 130.78 588.013 86.1425 623.94 51.9792C659.868 17.3264 706.172 0 762.657 0Z" fill="#D7DB31"/>
        <path opacity="0.1" d="M414.278 0C457.077 0 490.572 12.447 514.763 37.3409C538.954 62.2348 551 97.2235 551 142.307V364H426.619V171.807C426.619 134.858 412.222 116.433 383.526 116.433C352.969 116.433 337.691 135.152 337.691 172.493V364H213.309V171.807C213.309 134.858 198.717 116.433 169.531 116.433C140.345 116.433 124.381 135.152 124.381 172.493V364H0V7.54658H117.624V32.1465C134.959 10.6828 162.773 0 201.067 0C250.232 0 286.959 16.1712 311.15 48.6117C335.34 16.2693 369.717 0 414.376 0" fill="#D7DB31"/>
        </svg>

      </div>
  <?php endif; ?>
  <!-- Main Content -->
  <div class="<?php echo implode(' ', $content_classes); ?>">
    <div class="<?php if ($image_layout === 'content') { echo '@md/lg:py-16'; } ?>">
      <div class="hero-header md:items-center flex mm-sm:flex-col @sm:gap-6 @md/lg:gap-4  @sm:mb-4 @md/lg:mb-6 <?php echo implode(' ', $header_classes); ?>">
        <?php if ($label): ?>
            <div class="label text-white autoscale"><?php echo esc_html($label); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($taxonomy_badges)): ?>
          <div class="badge-wrapper flex flex-wrap @sm:gap-2 @md/lg:gap-2">
            <?php foreach ($taxonomy_badges as $badge):
            $badge_color = [];
              switch ($badge['type']) {
                  case 'theme':
                      $badge_color[] = 'bg-light-green border-light-green';
                      break;
                  case 'type':
                      $badge_color[] = 'bg-orange border-orange';
                      break;
                  default:
                      $badge_color[] = 'bg-yellow border-yellow';
                      break;
              }
              ?>  
              <span class="badge-surface autoscale <?php echo implode(' ', $badge_color); ?>">
                <?php echo esc_html($badge['name']); ?>
              </span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <h1 class="<?php echo implode(' ', $title_classes); ?>">
        <?php echo nl2br(esc_html($title)); ?>
      </h1>
    
      <?php if ($cta_button && !empty($cta_button['title']) && !empty($cta_button['url'])): ?>
        <div class="@sm:mt-6 @md/lg:mt-6 theme-yellow">
          <a 
            href="<?php echo esc_url($cta_button['url']); ?>"
            <?php if (!empty($cta_button['target'])): ?>target="<?php echo esc_attr($cta_button['target']); ?>" rel="noopener noreferrer"<?php endif; ?>
            class="button-flat button-primary autoscale"
          ><div class="button-title"><?php echo esc_html($cta_button['title']); ?></div>
          </a>
        </div>
      <?php endif; ?>
    </div>
    <?php if ($is_single && $background_image): ?>
      <div class="<?php echo implode(' ', $image_layout_classes); ?>">
        <?php echo wp_get_attachment_image($background_image['ID'], 'large', false, ['class' => 'object-cover w-full h-full']); ?>
      </div>
    <?php endif; ?>
  </div>
  <?php if (!($is_single && $background_image && $background_image['ID'] !== 1 && $image_layout === 'full-width')): ?>
    <div class="container absolute flex justify-end left-1/2 -translate-x-1/2 bottom-0 <?php echo implode(' ', $scroll_classes); ?> <?php if($image_layout === 'content') echo "mm-sm:hidden" ?>">
      <button
        class="scroll-down-btn group autoscale flex items-center justify-center @sm:w-14 @sm:h-14 @md/lg:w-14 @md/lg:h-14 @sm:-mb-7 @md/lg:-mb-7  @sm:rounded-lg @md/lg:rounded-lg transition-all duration-300 bg-yellow shadow-[19px_19px_43px_rgba(0,0,0,0.09)] hover:scale-105"
        aria-label="Scroll down to explore more content"
        onclick="window.scrollTo({ top: window.innerHeight, behavior: 'smooth' });">

        <svg xmlns="http://www.w3.org/2000/svg" class="@sm:w-[15px] @md/lg:w-[15px] @sm:h-[17px] @md/lg:h-[17px]" viewBox="0 0 15 17" fill="none">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M1.31404 4.61685C1.80942 3.96323 2.69291 3.87492 3.28736 4.4196L7.99481 8.73288L12.7023 4.4196C13.2967 3.87492 14.1802 3.96323 14.6756 4.61685C15.171 5.27046 15.0907 6.24187 14.4962 6.78655L8.89178 11.9217C8.37218 12.3978 7.61744 12.3978 7.09785 11.9217L1.49343 6.78655C0.898976 6.24187 0.818659 5.27046 1.31404 4.61685Z" fill="#012E31"/>
        </svg>
      </button>
    </div>
  <?php endif; ?>
</section>
