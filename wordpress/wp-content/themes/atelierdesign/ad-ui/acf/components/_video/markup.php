<?php

global $adwp;

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$alignment = '';
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

$parallax = $args['layout_settings']['parallax'] == true && $args['layout_settings']['aspect'] != 'auto' && $args['isUrl'] == false && $args['layout_settings']['autoplay'] == true && $args['layout_settings']['controls'] == false ? 'parallax-image' : '';

$hasPoster = isset($args['poster']['url']) && !empty($args['poster']['url']);

?>
<?php if (($args['isUrl'] && !empty($args['url'])) || (!$args['isUrl'] && !empty($args['video']['url']))): ?>
  <div class="media-wrapper flex <?= $isFullWidth ? '' : 'mx-content' ?> <?= $alignment; ?> aos animate-fadeinup">
    <?php if (!$args['isUrl']): ?>
      <div
        class="media w-full <?= $aspect; ?> object-cover <?= $parallax !== '' ? 'parallax-image-wrapper' : ''; ?>"
        style="width: <?= isset($args['layout_settings']['width']) ? $args['layout_settings']['width'] ?? '100' : '100'; ?>%;">
        <video
          class="w-full h-full object-cover object-center <?= $parallax; ?>"
          playsinline
          <?= ($hasPoster || $args['layout_settings']['controls'] || !$args['layout_settings']['autoplay']) ? 'controls' : ''; ?>
          <?= $args['layout_settings']['loop'] ? 'loop' : ''; ?>
          <?= (!$hasPoster && $args['layout_settings']['autoplay']) ? 'autoplay' : ''; ?>
          <?= (!$hasPoster && ($args['layout_settings']['autoplay'] || $args['layout_settings']['muted'])) ? 'muted' : ''; ?>
          <source src="<?= $args['video']['url'] ?? ''; ?>" type="<?= $args['video']['mime_type'] ?? ''; ?>">
        </video>
        <?php if ($hasPoster): ?>
          <img class="media-poster" src="<?= $args['poster']['sizes']['large'] ?? ''; ?>" alt="<?= $args['poster']['alt'] ?? ''; ?>" loading="lazy" decoding="async" />
          <div class="media-overlay"></div>
          <div class="media-icon autoscale">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960">
              <path d="M320-200v-560l440 280-440 280Z" />
            </svg>
          </div>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <div class="media w-full <?= $aspect; ?>">
        <?php
        if (strpos($args['url'], 'vimeo') !== false) {
          $embedUrl = vimeoEmbedFromUrl($args['url']);
          echo '<iframe loading="lazy" class="w-full h-full" src="' . $embedUrl . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write"></iframe>';
        } elseif (strpos($args['url'], 'youtube') !== false) {
          $embedUrl = youtubeEmbedFromUrl($args['url']);
          echo '<iframe loading="lazy" class="w-full h-full" src="' . $embedUrl . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
        } else {
          echo '<div class="paragraph-md paragraph-primary">Invalid Video URL. Please provide a valid YouTube or Vimeo URL.</div>';
        }
        ?>
        <?php if (
          (strpos($args['url'], 'vimeo') !== false || strpos($args['url'], 'youtube') !== false) &&
          $hasPoster
        ): ?>
          <img class="media-poster" src="<?= $args['poster']['sizes']['large'] ?? ''; ?>" alt="<?= $args['poster']['alt'] ?? ''; ?>" loading="lazy" decoding="async" />
          <div class="media-overlay"></div>
          <div class="media-icon autoscale">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 -960 960 960">
              <path d="M320-200v-560l440 280-440 280Z" />
            </svg>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>