
<?php

/**
 * EXAMPLE OF HOW THE BUTTON COMPONENT'S RENDERED HTML CAN BE OVERWRITTEN
 * ------------------------------------------------------------------------
 * It is a straight copy of the default `/ad-ui/acf/components/_button/markup.php` file, without the `fields.php` file,
 * and you can just modify anything here that you want, for example change the `.buton-icon` to the project's icon.
 */

$style = 'button-' . $args['layout_settings']['style'];
$color = 'button-' . $args['layout_settings']['color'];

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$alignmentClass = 'text-left';
switch ($args['layout_settings']['alignment']) {
  case 'left':
    $alignmentClass = 'text-left';
    break;
  case 'center':
    $alignmentClass = 'text-center';
    break;
  case 'right':
    $alignmentClass = 'text-right';
    break;
}

?>
<div class="button-wrapper <?= $isFullWidth ? '' : 'px-content' ?> <?= $alignmentClass; ?>">
  <?php if (isset($args['link']['url']) && !empty($args['link']['url'])) : ?>
    <a href="<?= $args['link']['url'] ?? '#'; ?>" <?= isset($args['link']['target']) && !empty($args['link']['target']) ? 'target="' . $args['link']['target'] . '"' : ''; ?> class="<?= $style; ?> <?= $color; ?> transition-colors duration-300 ease-out-cubic">
      <span class="button-title">
        <?= $args['link']['title'] ?? ''; ?>
      </span>
    </a>
  <?php endif; ?>
</div>