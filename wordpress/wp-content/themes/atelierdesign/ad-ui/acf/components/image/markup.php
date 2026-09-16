<?php

global $adwp;

$aspect = 'h-auto';
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

$fit = 'object-cover';

$parallax = $args['layout_settings']['parallax'] == true && $args['layout_settings']['aspect'] != 'auto' ? 'parallax-image' : '';

?>
<section class="poster-image">
  <?php if ($parallax != ''): ?>
    <div class="parallax-image-wrapper w-full">
    <?php endif; ?>
    <img
      class="w-full <?= $aspect; ?> <?= $fit; ?> <?= $parallax; ?>"
      src="<?= $args['image']['url'] ?? ''; ?>"
      srcset="<?= $args['image']['sizes']['thumbnail'] ?? ''; ?> 640w, <?= $args['image']['sizes']['medium'] ?? ''; ?> 1280w, <?= $args['image']['sizes']['large'] ?? ''; ?> 2560w"
      sizes="(max-width: 640px) 640px, (max-width: 1280px) 1280px, 2560px"
      alt="<?= $args['image']['alt'] ?? ''; ?>"
      loading="lazy"
      decoding="async" />
    <?php if ($parallax != ''): ?>
    </div>
  <?php endif; ?>
</section>