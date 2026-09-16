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

switch ($args['layout_settings']['span']) {
  case '1/12':
    $span = 'md:flex-grid-col-span-1';
    break;
  case '2/12':
    $span = 'md:flex-grid-col-span-2';
    break;
  case '3/12':
    $span = 'md:flex-grid-col-span-3';
    break;
  case '4/12':
    $span = 'md:flex-grid-col-span-4';
    break;
  case '5/12':
    $span = 'md:flex-grid-col-span-5';
    break;
  case '6/12':
    $span = 'md:flex-grid-col-span-6';
    break;
  case '7/12':
    $span = 'md:flex-grid-col-span-7';
    break;
  case '8/12':
    $span = 'md:flex-grid-col-span-8';
    break;
  case '9/12':
    $span = 'md:flex-grid-col-span-9';
    break;
  case '10/12':
    $span = 'md:flex-grid-col-span-10';
    break;
  case '11/12':
    $span = 'md:flex-grid-col-span-11';
    break;
  case '12/12':
    $span = 'md:flex-grid-col-span-12';
    break;
}
?>
<div class="advanced-layout-wrapper inline-flexible flex-grid-col-span-1 aos animate-fadeinup aos-disable-children animate-delay-[calc((var(--nth,1)-1)*150ms)] <?= $span; ?> <?= $wrapperClass; ?>">
  <?php if (!empty($elemClass)) : ?>
    <div class="inline-flexible <?= $elemClass; ?>">
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