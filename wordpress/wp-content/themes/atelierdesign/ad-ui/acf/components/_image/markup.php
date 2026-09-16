<?php

global $adwp;

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$alignment = '';
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
      $alignment = '';
      break;
  }
}

$aspect = 'h-auto';
if (isset($args['layout_settings']['aspect'])) {
  switch ($args['layout_settings']['aspect']) {
    case 'md:21/9':
      $aspect = 'md:aspect-[21/9]';
      break;
    case '21/9':
      $aspect = 'aspect-[21/9]';
      break;
    case '16/9':
      $aspect = 'aspect-video';
      break;
    case '5/4':
      $aspect = 'aspect-[5/4]';
      break;
    case '4/3':
      $aspect = 'aspect-[4/3]';
      break;
    case '3/2':
      $aspect = 'aspect-[3/2]';
      break;
    case '2/1':
      $aspect = 'aspect-[2/1]';
      break;
    case '1/1':
      $aspect = 'aspect-square';
      break;
    case '4/5':
      $aspect = 'aspect-[4/5]';
      break;
    case '3/4':
      $aspect = 'aspect-[3/4]';
      break;
    case '2/3':
      $aspect = 'aspect-[2/3]';
      break;
    case '1/2':
      $aspect = 'aspect-[1/2]';
      break;
    default:
      $aspect = 'h-auto';
      break;
  }
}

// $fit = isset($args['layout_settings']['fit']) && $args['layout_settings']['fit'] == true && $args['layout_settings']['aspect'] != 'auto' ? 'object-cover' : 'object-contain';
$fit = 'object-cover';

$parallax = isset($args['layout_settings']['parallax']) && $args['layout_settings']['parallax'] == true && $args['layout_settings']['aspect'] != 'auto' && $args['layout_settings']['fit'] == true ? 'parallax-image' : '';

?>
<?php if (isset($args['image']['url']) && !empty($args['image']['url'])): ?>
  <div class="media-wrapper flex <?= $isFullWidth ? '' : 'mx-content' ?> <?= $alignment; ?> aos animate-fadeinup">
    <?php if ($parallax != ''): ?>
      <div
        class="parallax-image-wrapper media w-full <?= $aspect; ?>"
        <?php if (isset($args['layout_settings']['width']) && $args['layout_settings']['width'] != 100) : ?>
        style="width: <?= isset($args['layout_settings']['width']) ? $args['layout_settings']['width'] ?? '100' : '100'; ?>%;"
        <?php endif; ?>>
      <?php endif; ?>
      <img
        class="<?= $parallax == '' ? 'media' : ''; ?> w-full <?= $parallax == '' ? $aspect : ''; ?> <?= $fit; ?> <?= $parallax; ?>"
        <?php if (isset($args['layout_settings']['width']) && $args['layout_settings']['width'] != 100) : ?>
        <?php if ($parallax == ''): ?>style="width: <?= isset($args['layout_settings']['width']) ? $args['layout_settings']['width'] ?? '100' : '100'; ?>%;" <?php endif; ?>
        <?php endif; ?>
        src="<?= isset($args['image']['url']) ? $args['image']['url'] ?? '' : ''; ?>"
        srcset="<?= isset($args['image']['sizes']['thumbnail']) ? $args['image']['sizes']['thumbnail'] ?? '' : ''; ?> 640w, <?= isset($args['image']['sizes']['medium']) ? $args['image']['sizes']['medium'] ?? '' : ''; ?> 1280w, <?= isset($args['image']['sizes']['large']) ? $args['image']['sizes']['large'] ?? '' : ''; ?> 2560w"
        sizes="(max-width: 640px) 640px, (max-width: 1280px) 1280px, 2560px"
        alt="<?= isset($args['image']['alt']) ? $args['image']['alt'] ?? '' : ''; ?>"
        loading="lazy"
        decoding="async" />
      <?php if ($parallax != ''): ?>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>