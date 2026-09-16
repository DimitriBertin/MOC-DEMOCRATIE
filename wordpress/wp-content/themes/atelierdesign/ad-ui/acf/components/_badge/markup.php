<?php

global $adwp;

$style = 'badge-' . toKebabCase($args['style']);

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$alignment = '';
switch ($args['layout_settings']['alignment']) {
  case 'left':
    $alignment = 'text-left';
    break;
  case 'center':
    $alignment = 'text-center';
    break;
  case 'right':
    $alignment = 'text-right';
    break;
  default:
    $alignment = '';
}

?>
<?php if (isset($args['text']) && !empty($args['text'])): ?>
  <div class="badge-wrapper <?= $isFullWidth ? '' : 'px-content' ?> <?= $alignment; ?> autoscale-children aos animate-fadeinup">
    <div class="<?= $style; ?>">
      <?= $args['text'] ?? ''; ?>
    </div>
  </div>
<?php endif; ?>