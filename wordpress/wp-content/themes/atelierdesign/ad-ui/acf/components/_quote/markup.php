<?php

global $adwp;

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

switch ($args['layout_settings']['alignment']) {
  case 'left':
    $alignment = 'quote-align-left';
    break;
  case 'center':
    $alignment = 'quote-align-center';
    break;
  case 'right':
    $alignment = 'quote-align-right';
    break;
  default:
    $alignment = 'quote-align-left';
    break;
}

if ($args['layout_settings']['hasBorder'] && $args['layout_settings']['alignment'] !== 'center') {
  $border = 'quote-has-border';
} else {
  $border = '';
}
?>
<?php if (!empty($args['content']) || !empty($args['image']['url']) || !empty($args['labelPrimary']) || !empty($args['labelSecondary'])): ?>
  <div class="quote-wrapper <?= $isFullWidth ? '' : 'px-content' ?> autoscale-children aos animate-fadeinup">
    <blockquote class="quote <?= $border; ?> <?= $alignment; ?>">
      <?php if ($args['layout_settings']['hasIcon']): ?>
        <svg class="quote-icon" viewBox="0 0 34 32" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M23.4031 29C21.0961 29 19.2259 27.1298 19.2259 24.8228V17.5522C19.2259 9.72596 24.9575 5.17805 31.122 3.50298C32.3565 3.16753 33.5 4.15289 33.5 5.43216V6.00626C33.5 6.92835 32.886 7.72449 32.0541 8.12209C29.2232 9.47501 26.415 12.3309 26.415 16.3881H29.1144C31.4214 16.3881 33.2916 18.2583 33.2916 20.5653V24.8228C33.2916 27.1298 31.4214 29 29.1144 29H23.4031ZM4.67721 29C2.3702 29 0.5 27.1298 0.5 24.8228V17.5522C0.5 9.72596 6.23162 5.17805 12.3961 3.50298C13.6306 3.16753 14.7741 4.15289 14.7741 5.43216V6.00626C14.7741 6.92835 14.1601 7.72449 13.3282 8.12209C10.4973 9.47501 7.68915 12.3309 7.68915 16.3881H10.3885C12.6955 16.3881 14.5657 18.2583 14.5657 20.5653V24.8228C14.5657 27.1298 12.6955 29 10.3885 29H4.67721Z" fill="currentColor" />
        </svg>
      <?php endif; ?>
      <?php if (!empty($args['content'])): ?>
        <div class="quote-content">
          <?= $args['content']; ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($args['image']['url']) || !empty($args['labelPrimary']) || !empty($args['labelSecondary'])): ?>
        <cite class="quote-cite">
          <?php if (!empty($args['image']['url'])) : ?>
            <img src="<?= $args['image']['sizes']['thumbnail']; ?>" alt="<?= $args['image']['alt'] ?? ''; ?>" class="quote-avatar" loading="lazy" decoding="async" />
          <?php endif; ?>
          <?php if (!empty($args['labelPrimary']) || !empty($args['labelSecondary'])) : ?>
            <div class="quote-labels">
              <?php if (!empty($args['labelPrimary'])) : ?>
                <div class="quote-primary-label">
                  <?= $args['labelPrimary']; ?>
                </div>
              <?php endif; ?>
              <?php if (!empty($args['labelSecondary'])) : ?>
                <div class="quote-secondary-label">
                  <?= $args['labelSecondary']; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </cite>
      <?php endif; ?>
    </blockquote>
  </div>
<?php endif; ?>