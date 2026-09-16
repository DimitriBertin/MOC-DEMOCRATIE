<?php
global $adwp;

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$alignment = '';
switch ($args['layout_settings']['alignment']) {
  case 'left':
    $alignment = 'text-left items-start';
    break;
  case 'center':
    $alignment = 'text-center items-center';
    break;
  case 'right':
    $alignment = 'text-right items-end';
    break;
}
?>
<?php if (!empty($args['prefix']) || !empty($args['number']) || !empty($args['suffix'])): ?>
  <div class="key-numbers-wrapper <?= $isFullWidth ? '' : 'px-content' ?> autoscale-children aos animate-fadeinup">
    <div class="key-numbers <?= $alignment; ?>">
      <?php if (!empty($args['prefix'])): ?>
        <span class="key-numbers-prefix">
          <?php
          $prefix = $args['prefix'];
          echo (substr($prefix, -1) === ' ') ? substr($prefix, 0, -1) . '&nbsp;' : $prefix;
          ?>
        </span>
      <?php endif; ?>

      <?php if (!empty($args['number'])): ?>
        <span class="key-numbers-number">
          <?php
          $number = $args['number'];
          echo preg_replace('/\s+/', '&nbsp;', $number);
          ?>
        </span>
      <?php endif; ?>

      <?php if (!empty($args['suffix'])): ?>
        <span class="key-numbers-suffix">
          <?php
          $suffix = $args['suffix'];
          echo (substr($suffix, 0, 1) === ' ') ? '&nbsp;' . substr($suffix, 1) : $suffix;
          ?>
        </span>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>