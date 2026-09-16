<?php
$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$alignment = '';
switch ($args['alignment']) {
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

$color = 'icon-' . $args['color'];
$hasBackground = isset($args['hasBackground']) && $args['hasBackground'] == true ? 'icon-has-background' : '';

?>
<?php if (isset($args['icon']['name']) && !empty($args['icon']['name'])): ?>
  <div class="icon-wrapper flex <?= $alignment; ?> <?= $isFullWidth ? '' : 'px-content' ?> autoscale-children aos animate-fadeinup">
    <div class="<?= $color; ?> <?= $hasBackground; ?>">
      <span class="material-symbols-outlined">
        <?= $args['icon']['name'] ?? ''; ?>
      </span>
    </div>
  </div>
<?php endif; ?>