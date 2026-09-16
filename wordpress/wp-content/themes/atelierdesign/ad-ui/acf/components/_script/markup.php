<?php

global $adwp;

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

?>
<?php if (!empty($args['script'])): ?>
  <div class="script-wrapper <?= $isFullWidth ? '' : 'px-content' ?>">
    <?= $args['script'] ?? ''; ?>
  </div>
<?php endif; ?>