<?php if (isset($args['items']) && is_array($args['items']) && count($args['items']) > 0): ?>
  <div class="gallery aos animate-fadeinup">
    <div class="gallery-swiper swiper">
      <div class="gallery-items swiper-wrapper">
        <?php foreach ($args['items'] as $item): ?>
          <div class="gallery-slide swiper-slide">
            <?php if (strpos($item['mime_type'], 'image') !== false): ?>
              <img
                src="<?= $item['sizes']['medium']; ?>"
                srcset="<?= $item['sizes']['thumbnail']; ?> 640w, <?= $item['sizes']['medium']; ?> 1024w, <?= $item['sizes']['large']; ?> 2560w"
                sizes="100vw"
                alt="<?= $item['alt'] ?? ''; ?>"
                class="media h-full"
                loading="lazy"
                decoding="async"
                style="aspect-ratio: <?= $item['width']; ?> / <?= $item['height']; ?>;" />
            <?php else: ?>
              <video
                class="media aspect-video"
                src="<?= $item['url']; ?>"
                class="media h-full"
                controls
                playsinline
                muted
                loop
                autoplay
                loading="lazy"
                decoding="async">
              </video>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php if (isset($args['items']) && is_array($args['items']) && count($args['items']) > 1): ?>
      <div class="gallery-navigation autoscale-children">
        <div class="gallery-pagination swiper-pagination text-badge-normal paragraph-primary"></div>
        <div class="gallery-navigation-buttons">
          <button type="button" role="button" aria-label="Previous" class="gallery-navigation-button swiper-button-prev button-primary button-outline rounded-full">
            <span class="button-icon material-symbols-outlined">
              chevron_left
            </span>
          </button>
          <button type="button" role="button" aria-label="Next" class="gallery-navigation-button swiper-button-next button-primary button-outline rounded-full">
            <span class="button-icon material-symbols-outlined">
              chevron_right
            </span>
          </button>
        </div>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>