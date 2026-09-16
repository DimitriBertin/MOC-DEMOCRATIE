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

$parallax = $args['layout_settings']['parallax'] == true && $args['layout_settings']['aspect'] != 'auto' ? 'parallax-image' : '';

?>
<section class="poster-video">
  <div class="w-full <?= $aspect; ?> <?= $parallax !== '' ? 'parallax-image-wrapper' : ''; ?>">
    <video
      class="object-cover object-center <?= $parallax; ?>"
      playsinline
      <?php /*$args['layout_settings']['controls'] ? 'controls' : ''; ?>
      <?= $args['layout_settings']['loop'] ? 'loop' : ''; ?>
      <?= $args['layout_settings']['autoplay'] ? 'autoplay muted' : ''; ?>
      <?= $args['layout_settings']['muted'] ? 'muted' : ''; */ ?>
      loop autoplay muted>
      <source src="<?= $args['video']['url'] ?? ''; ?>" type="<?= $args['video']['mime_type'] ?? ''; ?>">
    </video>
  </div>
</section>