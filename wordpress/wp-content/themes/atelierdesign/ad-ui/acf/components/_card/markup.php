<?php
global $adwp;

$style = 'card-' . toKebabCase($args['style']);
$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

?>
<?php if (isset($args['content']) && is_array($args['content']) && count($args['content']) > 0): ?>
  <div class="card-wrapper <?= $isFullWidth ? '' : 'md:px-content' ?> aos animate-fadeinup">
    <div class="<?= $style; ?> <?= $isFullWidth ? 'md:px-content' : '' ?>">
      <div class="card-content inline-flexible aos-disable-children autoscale-children">
        <?php $adwp->render_flexible_layout($args['content'], [
          'parentBlock' => '_card',
          'isNested' => true,
        ]);
        ?>
      </div>
    </div>
  </div>
<?php endif; ?>