<?php
$section = is_array($args) ? $args : [];
$cards = isset($section['cards']) && is_array($section['cards']) ? $section['cards'] : [];
?>
<section class="services-cards">
  <div class="container @sm:mt-[-120px] @md/lg:mt-[-172px] z-2 relative">
    <div class="grid grid-cols-1 md:grid-cols-3 @sm:gap-4 @md/lg:gap-4">
      <?php foreach ($cards as $card): ?>
        <div class="theme-light-green-70 bg-layout-main @sm:rounded-xl @md/lg:rounded-xl @sm:p-7 @sm:pb-12 @md/lg:p-10 flex flex-col h-full">
          <div class="autoscale">
            <?php if (!empty($card['badge'])): ?>
                <div class="badge-wrapper flex justify-end @sm:mb-7 @md/lg:mb-10">
                  <div class="badge-surface">
                    <?php echo esc_html($card['badge']); ?>
                  </div>
                </div>
            <?php endif; ?>

            <div class="card-content flex flex-col flex-grow">
              <?php if (!empty($card['title'])): ?>
                <h3 class="heading-md heading-primary @sm:mb-4 @md/lg:mb-4">
                  <?php echo esc_html($card['title']); ?>
                </h3>
              <?php endif; ?>

              <?php if (!empty($card['description'])): ?>
                <p class="paragraph-sm paragraph-primary @sm:mb-8 @md/lg:mb-8 flex-grow">
                  <?php echo wp_kses_post(nl2br($card['description'])); ?>
                </p>
              <?php endif; ?>

              <div class="card-actions flex flex-col @sm:gap-3 @md/lg:gap-3">
                <?php if (!empty($card['primary_link'])): $l = $card['primary_link']; ?>
                  <a href="<?php echo esc_url($l['url']); ?>" target="<?php echo esc_attr($l['target'] ?: '_self'); ?>" class="button-primary group button-title text-dark-green flex items-center gap-2">
                    <span class="font-semibold"><?php echo esc_html($l['title']); ?></span>
                    <div class="link-arrows flex items-center @sm:-space-x-1 @md/lg:-space-x-1 group-hover:opacity-80 group-hover:translate-x-2 transition-all">
                      <svg class="@sm:w-2.5 @md/lg:w-2.5 @sm:h-2.5 @md/lg:h-2.5 flex-shrink-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.40017 9.59312C2.01527 9.25255 1.96327 8.64515 2.28402 8.23646L4.82401 5.00009L2.28402 1.76371C1.96327 1.35503 2.01527 0.74763 2.40017 0.407056C2.78507 0.0664816 3.35712 0.1217 3.67787 0.530389L6.70183 4.38342C6.98219 4.74064 6.98219 5.25953 6.70183 5.61675L3.67787 9.46979C3.35712 9.87847 2.78507 9.93369 2.40017 9.59312Z" fill="currentColor" />
                      </svg>
                      <svg class="@sm:w-2.5 @md/lg:w-2.5 @sm:h-2.5 @md/lg:h-2.5 flex-shrink-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.61599 9.59312C2.23109 9.25255 2.17909 8.64515 2.49984 8.23646L5.03983 5.00009L2.49984 1.76371C2.17909 1.35503 2.23109 0.74763 2.61599 0.407056C3.00089 0.0664816 3.57294 0.1217 3.89369 0.530389L6.91765 4.38342C7.19801 4.74064 7.19801 5.25953 6.91765 5.61675L3.89369 9.46979C3.57294 9.87847 3.00089 9.93369 2.61599 9.59312Z" fill="currentColor" />
                      </svg>
                    </div>
                  </a>
                <?php endif; ?>

                <?php if (!empty($card['secondary_link'])): $l2 = $card['secondary_link']; ?>
                  <div class="card-divider h-px bg-dark-green opacity-20 @sm:my-1 @md/lg:my-1"></div>
                  <a href="<?php echo esc_url($l2['url']); ?>" target="<?php echo esc_attr($l2['target'] ?: '_self'); ?>" class="button-primary group button-title text-dark-green flex items-center @sm:gap-2 @md:gap-2 @lg:gap-2">
                    <span class="font-semibold"><?php echo esc_html($l2['title']); ?></span>
                    <div class="link-arrows flex items-center @sm:-space-x-1 @md/lg:-space-x-1 group-hover:opacity-80 group-hover:translate-x-2 transition-all">
                      <svg class="@sm:w-2.5 @md/lg:w-2.5 @sm:h-2.5 @md/lg:h-2.5 flex-shrink-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.40017 9.59312C2.01527 9.25255 1.96327 8.64515 2.28402 8.23646L4.82401 5.00009L2.28402 1.76371C1.96327 1.35503 2.01527 0.74763 2.40017 0.407056C2.78507 0.0664816 3.35712 0.1217 3.67787 0.530389L6.70183 4.38342C6.98219 4.74064 6.98219 5.25953 6.70183 5.61675L3.67787 9.46979C3.35712 9.87847 2.78507 9.93369 2.40017 9.59312Z" fill="currentColor" />
                      </svg>
                      <svg class="@sm:w-2.5 @md/lg:w-2.5 @sm:h-2.5 @md/lg:h-2.5 flex-shrink-0" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.61599 9.59312C2.23109 9.25255 2.17909 8.64515 2.49984 8.23646L5.03983 5.00009L2.49984 1.76371C2.17909 1.35503 2.23109 0.74763 2.61599 0.407056C3.00089 0.0664816 3.57294 0.1217 3.89369 0.530389L6.91765 4.38342C7.19801 4.74064 7.19801 5.25953 6.91765 5.61675L3.89369 9.46979C3.57294 9.87847 3.00089 9.93369 2.61599 9.59312Z" fill="currentColor" />
                      </svg>
                    </div>
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
