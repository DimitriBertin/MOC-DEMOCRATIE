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

if (isset($args['layout_settings']['alignSelf']) && $args['layout_settings']['alignSelf'] != 'inherit') {
  if (isset($args['layout_settings']['isSticky']) && $args['layout_settings']['isSticky'] == true) {
    $wrapperClass = 'relative self-stretch flex';
    switch ($args['layout_settings']['alignSelf']) {
      case 'start':
        $elemClass = 'md:sticky @md/lg:top-12 left-0 w-full self-start';
        break;
      case 'center':
        $elemClass = 'md:sticky @md/lg:top-0 left-0 w-full min-h-screen self-start content-center';
        break;
      case 'end':
        $elemClass = 'md:sticky @md/lg:bottom-12 left-0 w-full self-end';
        break;
    }
  }
}

$disableDividerX = isset($args['layout_settings']['disableDividerX']) && $args['layout_settings']['disableDividerX'] == true ? 'md:before:!hidden' : '';
$disableDividerY = isset($args['layout_settings']['disableDividerY']) && $args['layout_settings']['disableDividerY'] == true ? 'max-max-md:after:!hidden' : '';
?>
<div class="column inline-flexible aos animate-fadeinup aos-disable-children <?= $wrapperClass; ?> <?= $disableDividerX; ?> <?= $disableDividerY; ?>">
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