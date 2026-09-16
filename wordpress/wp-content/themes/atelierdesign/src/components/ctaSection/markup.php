<?php
/**
 * CTA Section Component
 *
 * A call-to-action section with background image, overlay, and button
 * Usage: get_template_part('src/components/ctaSection/markup', null, $section_data);
 */

global $adwp;

$section = is_array($args) ? $args : [];

// Extract data from section
$title = $section['title'] ?? '';
$subtitle = $section['subtitle'] ?? '';
$backgroundImage = $section['backgroundImage'] ?? null;
$overlayOpacity = isset($section['backgroundOverlayOpacity']) ? (int)$section['backgroundOverlayOpacity'] : 0;
$ctaButton = $section['ctaButton'] ?? null;

?>

<section class="cta-section py-section theme-yellow relative flex items-center justify-center overflow-hidden">
  <!-- Background Image -->
  <?php if (!empty($backgroundImage)): ?>
    <?php echo wp_get_attachment_image($backgroundImage['ID'], 'large', false, ['class' => 'absolute inset-0 w-full h-full object-cover']); ?>
  <?php endif; ?>

  <!-- Background Overlay -->
  <?php if ($overlayOpacity > 0): ?>
    <div class="absolute inset-0 bg-black" style="opacity: <?php echo $overlayOpacity / 100; ?>;"></div>
  <?php endif; ?>

  <!-- Dark Overlay Gradient -->
  <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-transparent"></div>

  <!-- Content Container -->
  <div class="relative z-10 container ">
    <div class="flex items-end justify-end max-lg:flex-col">
      
      <!-- Content Block -->
      <div class="flex flex-col items-start @sm:pb-9 @md:pb-9 @lg:pb-24 @sm:gap-6 @md:gap-6 @lg:gap-6 autoscale">
        
        <!-- Headline -->
        <?php if (!empty($title)): ?>
          <h2 class="heading-2xl text-yellow">
            <?php echo nl2br(esc_html($title)); ?>
          </h2>
        <?php endif; ?>

        <!-- Subtitle -->
        <?php if (!empty($subtitle)): ?>
          <p class="paragraph-lg text-white">
            <?php echo esc_html($subtitle); ?>
          </p>
        <?php endif; ?>
        
      </div>

      <!-- CTA Button -->
      <?php if (!empty($ctaButton)): ?>
        <div class="flex lg:justify-end w-full">
          <a 
            href="<?php echo esc_url($ctaButton['url']); ?>" 
            target="<?php echo esc_attr($ctaButton['target'] ?: '_self'); ?>"
            class="button-primary button-flat autoscale"
          >
            <span class="button-title"><?php echo esc_html($ctaButton['title']); ?></span>
          </a>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>
