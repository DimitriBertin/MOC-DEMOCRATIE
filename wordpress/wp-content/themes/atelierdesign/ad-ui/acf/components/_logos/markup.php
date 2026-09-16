<?php

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

if (isset($args['layout_settings']['alignment'])) {
  switch ($args['layout_settings']['alignment']) {
    case 'left':
      $alignment = 'justify-start';
      break;
    case 'center':
      $alignment = 'justify-center';
      break;
    case 'right':
      $alignment = 'justify-end';
      break;
    default:
      $alignment = 'justify-start';
      break;
  }
} else {
  $alignment = 'justify-start';
}

$animate = 'aos animate-fadeinup stagger-delay-150';

?>
<?php if (is_array($args['logos']) && count($args['logos']) > 0 && isset($args['logos'][0]['image']['url']) && !empty($args['logos'][0]['image']['url'])): ?>
  <div class="logos-wrapper <?= $isFullWidth ? '' : 'px-content' ?>">
    <?php if (is_array($args['logos']) && count($args['logos']) > 0) : ?>
      <div class="logos <?= $alignment; ?>">
        <?php foreach ($args['logos'] as $logo) : ?>
          <?php if (isset($logo['link']) && !empty($logo['link']['url'])) : ?>
            <a class="logo group/logo <?= $animate; ?>" href="<?= $logo['link']['url']; ?>" target="<?= $logo['link']['target'] ?? '_self'; ?>" <?= $logo['link']['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
            <?php else: ?>
              <div class="logo group/logo <?= $animate; ?>">
              <?php endif; ?>
              <div class="logo-image-wrapper">
                <div class="logo-control-scale relative transform" style="--tw-scale-x: <?= ($logo['scale'] / 10) + 1 ?? '0'; ?>; --tw-scale-y: <?= ($logo['scale'] / 10) + 1 ?? '0'; ?>; clip-path: inset(0.5px);">
                  <img src="<?= $logo['image']['url'] ?? ''; ?>" alt="<?= $logo['image']['alt'] ?? ''; ?>" class="logo-image" loading="lazy" decoding="async" />
                  <div class="logo-overlay"></div>
                </div>
              </div>
              <?php if (!isset($logo['link']) || empty($logo['link']['url'])) : ?>
              </div>
            <?php else: ?>
            </a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="paragraph-md paragraph-primary">Add logos to display here.</p>
    <?php endif; ?>
  </div>
<?php endif; ?>