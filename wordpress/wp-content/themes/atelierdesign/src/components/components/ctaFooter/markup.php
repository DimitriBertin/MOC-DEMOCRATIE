<?php
/**
 * CTA Footer Component
 *
 * A footer CTA section with 1-2 call-to-action cards
 * Usage: get_template_part('src/components/ctaFooter/markup', null, $section_data);
 */

global $adwp;

$section = is_array($args) ? $args : [];

// Extract data from section
$items = $section['ctaFooter_items'] ?? [];
$itemCount = count($items);
$single = $itemCount === 1 ? true : false;

// Define background colors for each item (alternating yellow and orange)
$backgrounds = ['theme-yellow', 'theme-orange'];

?>

<section class="cta-footer py-section theme-white bg-layout-main">
  <div class="container">
    
    <?php if (!empty($items)): ?>
      <div class="mm-sm:flex-col flex justify-between @sm:gap-4 @md/lg:gap-4">
            <?php foreach ($items as $index => $item): 
              $bgColor = $backgrounds[$index % 2];
            ?>
              <div class="flex-1 @sm:rounded-xl @md/lg:rounded-xl @sm:p-[50px] @md/lg:py-[100px] @md/lg:px-[70px] flex flex-col justify-center items-center gap-8 relative overflow-hidden <?php echo esc_attr($bgColor); ?> bg-layout-main">
                <div class="flex items-center @sm:gap-8 @md/lg:gap-8 w-full relative z-10 <?php echo $single ? 'flex-col justify-center md:flex-row md:justify-between' : 'flex-col justify-center'; ?>">
                  <div class="flex flex-col @sm:gap-4 @md/lg:gap-4 autoscale <?php echo $single ? 'text-left' : 'text-center'; ?>">
                    <?php if (!empty($item['title'])): ?>
                      <h3 class="heading-xl">
                        <?php echo esc_html($item['title']); ?>
                      </h3>
                    <?php endif; ?>
                    <?php if (!empty($item['text'])): ?>
                      <p class="paragraph-md">
                        <?php echo esc_html($item['text']); ?>
                      </p>
                    <?php endif; ?>
                  </div>

                  <?php if (!empty($item['link'])): ?>
                    <a 
                      href="<?php echo esc_url($item['link']['url']); ?>" 
                      target="<?php echo esc_attr($item['link']['target'] ?: '_self'); ?>"
                      class="button-flat button-primary autoscale"
                    >
                      <span class="button-title"><?php echo esc_html($item['link']['title']); ?></span>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center py-8">
        <p>No CTA items available.</p>
      </div>
    <?php endif; ?>

  </div>
</section>
