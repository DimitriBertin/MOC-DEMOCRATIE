<?php global $adwp; ?>
<?php
$wrapperClass = '';
$elemClass = '';

if ($args['parentDivideX'] == true) {
  $wrapperClass = 'relative self-stretch flex';
  switch ($args['parentAlignment']) {
    case 'start':
      $elemClass = '@md/lg:top-0 left-0 w-full self-start';
      break;
    case 'center':
      $elemClass = '@md/lg:top-0 left-0 w-full self-center';
      break;
    case 'end':
      $elemClass = '@md/lg:bottom-0 left-0 w-full self-end';
      break;
  }
}

if (isset($args['layout_settings']['alignSelf']) && $args['layout_settings']['alignSelf'] != 'inherit' && (!isset($args['layout_settings']['isSticky']) || $args['layout_settings']['isSticky'] == false)) {
  $wrapperClass = 'relative self-stretch flex';
  switch ($args['layout_settings']['alignSelf']) {
    case 'start':
      $elemClass = '@md/lg:top-0 left-0 w-full self-start';
      break;
    case 'center':
      $elemClass = '@md/lg:top-0 left-0 w-full self-center';
      break;
    case 'end':
      $elemClass = '@md/lg:bottom-0 left-0 w-full self-end';
      break;
  }
}

$size = isset($args['layout_settings']['isRow']) && $args['layout_settings']['isRow'] == true ? 'md:flex-grid-col-span-2' : 'md:flex-grid-col-span-1';

$disableDividerX = isset($args['layout_settings']['disableDividerX']) && $args['layout_settings']['disableDividerX'] == true ? 'md:before:!hidden' : '';
$disableDividerY = isset($args['layout_settings']['disableDividerY']) && $args['layout_settings']['disableDividerY'] == true ? 'max-md:after:!hidden' : '';

?>
<?php if (isset($args['layout_settings']['isRow']) && $args['layout_settings']['isRow'] == true): ?>
  <div class="!sr-only spacer">&nbsp;</div>
<?php endif; ?>
<div class="feature-column column inline-flexible <?= $wrapperClass; ?> <?= $size; ?> <?= $disableDividerX; ?> <?= $disableDividerY; ?> aos animate-fadeinup aos-disable-children">
  <?php if (!empty($elemClass)) : ?>
    <div class="inline-flexible <?= $elemClass; ?> spacing-reset">
    <?php endif; ?>
    <?php $adwp->render_flexible_layout($args['content'], [
      'isNested' => true,
      'layout_settings' => [
        'isFullWidth' => false,
      ],
    ]); ?>
    <?php if (!empty($elemClass)) : ?>
    </div>
  <?php endif; ?>
</div>