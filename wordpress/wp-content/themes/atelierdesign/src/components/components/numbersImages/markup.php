<?php
/**
 * Numbers & Images Component
 * 
 * A flexible component for displaying statistics with images in a grid layout
 * Usage: get_template_part('src/components/numbersImages/markup', null, $section_data);
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

// Handle field names
$label = $section['label'] ?? '';
$title = $section['title'] ?? '';
$group = $section['group'] ?? [];

// Extract group items
$image1 = $group['image1'] ?? null;
$title1 = $group['title1'] ?? '';
$text1 = $group['text1'] ?? '';

$image2 = $group['image2'] ?? null;
$title2 = $group['title2'] ?? '';
$text2 = $group['text2'] ?? '';

$image3 = $group['image3'] ?? null;
$title3 = $group['title3'] ?? '';
$text3 = $group['text3'] ?? '';

$title4 = $group['title4'] ?? '';
$text4 = $group['text4'] ?? '';

?>

<section class="numbers-images py-section <?= $themeClass; ?> <?= $layoutClass; ?>">
  <div class="container mm-sm:flex mm-sm:flex-col grid grid-cols-3 grid-rows-3 @sm:gap-3 @md/lg:gap-3 auto-rows-fr">
    
    <!-- Row 1: Header section spanning 2 columns -->
    <div class="col-span-2 row-span-1 flex flex-col justify-center @sm:mb-6 @sm:gap-4 @md/lg:gap-4 autoscale">
      <?php if (!empty($label)): ?>
        <div class="label label-primary">
          <?php echo esc_html($label); ?>
        </div>
      <?php endif; ?>        
      <?php if (!empty($title)): ?>
        <h2 class="heading-2xl heading-primary md:w-2/3">
          <?php echo esc_html($title); ?>
        </h2>
      <?php endif; ?>
    </div>
    
    <!-- Row 1: Header image spanning 1 column -->
    <?php if (!empty($image1)): ?>
      <div class="col-span-1 row-span-1 aspect-square">
        <?php echo wp_get_attachment_image(
          $image1['ID'], 
          'full', 
          false, 
          [
            'class' => 'w-full h-full object-cover @sm:rounded-[10px] @md/lg:rounded-[10px]',
            'alt' => $image1['alt'] ?: $title
          ]
        ); ?>
      </div>
    <?php endif; ?>

    <!-- Row 2: First stat card -->
    <?php if (!empty($title1) || !empty($text1)): ?>
      <div class="numbers-images-color-block col-span-1 row-span-1 autoscale md:aspect-square @sm:p-[30px] @md/lg:p-[30px] @sm:rounded-[10px] @md/lg:rounded-[10px] @sm:gap-5 @md/lg:gap-5 bg-orange flex flex-col justify-end">
        <?php if (!empty($title1)): ?>
          <h3 class="heading-xl">
            <?php echo esc_html($title1); ?>
          </h3>
        <?php endif; ?>
        <?php if (!empty($text1)): ?>
          <p class="paragraph-md">
            <?php echo esc_html($text1); ?>
          </p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Row 2: Second image -->
    <?php if (!empty($image2)): ?>
      <div class="col-span-1 row-span-1 aspect-square">
        <?php echo wp_get_attachment_image(
          $image2['ID'], 
          'medium', 
          false, 
          [
            'class' => 'w-full h-full object-cover @sm:rounded-[10px] @md/lg:rounded-[10px]',
            'alt' => $image2['alt']
          ]
        ); ?>
      </div>
    <?php endif; ?>

    <!-- Row 2: Second stat card -->
    <?php if (!empty($title2) || !empty($text2)): ?>
      <div class="numbers-images-color-block-alt col-span-1 row-span-1 autoscale md:aspect-square @sm:p-[30px] @md/lg:p-[30px] @sm:rounded-[10px] @md/lg:rounded-[10px] @sm:gap-5 @md/lg:gap-5 bg-orange flex flex-col justify-end">
        <?php if (!empty($title2)): ?>
          <h3 class="heading-xl">
            <?php echo esc_html($title2); ?>
          </h3>
        <?php endif; ?>
        <?php if (!empty($text2)): ?>
          <p class="paragraph-md">
            <?php echo esc_html($text2); ?>
          </p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Row 3: Third image -->
    <?php if (!empty($image3)): ?>
      <div class="col-span-1 row-span-1 aspect-square">
        <?php echo wp_get_attachment_image(
          $image3['ID'], 
          'medium', 
          false, 
          [
            'class' => 'w-full h-full object-cover @sm:rounded-[10px] @md/lg:rounded-[10px]',
            'alt' => $image3['alt']
          ]
        ); ?>
      </div>
    <?php endif; ?>

    <!-- Row 3: Third stat card -->
    <?php if (!empty($title3) || !empty($text3)): ?>
      <div class="numbers-images-color-block-alt col-span-1 row-span-1 autoscale md:aspect-square @sm:p-[30px] @md/lg:p-[30px] @sm:rounded-[10px] @md/lg:rounded-[10px] @sm:gap-5 @md/lg:gap-5 bg-orange flex flex-col justify-end">
        <?php if (!empty($title3)): ?>
          <h3 class="heading-xl">
            <?php echo esc_html($title3); ?>
          </h3>
        <?php endif; ?>
        <?php if (!empty($text3)): ?>
          <p class="paragraph-md">
            <?php echo esc_html($text3); ?>
          </p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Row 3: Fourth stat card -->
    <?php if (!empty($title4) || !empty($text4)): ?>
      <div class="numbers-images-color-block col-span-1 row-span-1 autoscale md:aspect-square @sm:p-[30px] @md/lg:p-[30px] @sm:rounded-[10px] @md/lg:rounded-[10px] @sm:gap-5 @md/lg:gap-5 bg-orange flex flex-col justify-end">
        <?php if (!empty($title4)): ?>
          <h3 class="heading-xl">
            <?php echo esc_html($title4); ?>
          </h3>
        <?php endif; ?>
        <?php if (!empty($text4)): ?>
          <p class="paragraph-md">
            <?php echo esc_html($text4); ?>
          </p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>
</section>
