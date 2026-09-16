<?php

global $adwp, $adui_tokens;

// Split $args['color'] based on the '/' and get the theme-* and the bg-layout-* classes from it
$colorParts = explode('/', $args['color']);
$themeClass = "theme-" . mb_strtolower($colorParts[0], 'UTF-8');
$layoutClass = "bg-layout-" . mb_strtolower($colorParts[1], 'UTF-8');

$imageWrapperClass = '';
$imageClass = '';

// Determine image aspect ratio, fit and parallax attributes
if ($args['layout_settings']['contained']) {
  switch ($args['layout_settings']['image']['aspect']) {
    case 'md:21/9':
      $imageWrapperClass .= ' md:aspect-[21/9]';
      break;
    case '21/9':
      $imageWrapperClass .= ' aspect-[21/9]';
      break;
    case '16/9':
      $imageWrapperClass .= ' aspect-video';
      break;
    case '5/4':
      $imageWrapperClass .= ' aspect-[5/4]';
      break;
    case '4/3':
      $imageWrapperClass .= ' aspect-[4/3]';
      break;
    case '3/2':
      $imageimageWrapperClassClass .= ' aspect-[3/2]';
      break;
    case '2/1':
      $imageWrapperClass .= ' aspect-[2/1]';
      break;
    case '1/1':
      $imageWrapperClass .= ' aspect-square';
      break;
    case '4/5':
      $imageWrapperClass .= ' aspect-[4/5]';
      break;
    case '3/4':
      $imageWrapperClass .= ' aspect-[3/4]';
      break;
    case '2/3':
      $imageWrapperClass .= ' aspect-[2/3]';
      break;
    case '1/2':
      $imageWrapperClass .= ' aspect-[1/2]';
      break;
    default:
      $imageWrapperClass .= ' h-auto';
      break;
  }

  $imageClass .= ' h-full object-cover';
  if ($args['layout_settings']['image']['parallax']) {
    $imageWrapperClass .= ' parallax-image-wrapper';
    $imageClass .= ' parallax-image';
  }
} else {
  $imageWrapperClass = ' parallax-image-wrapper';
  $imageClass .= ' h-full object-cover parallax-image';
}

// Columns settings

// Get the same gap names array from tokens as in fields.php
$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$gapNames = array_keys($adui_tokens['responsiveSizing'][$responsiveSizingModes[0]]['gap'] ?? []);
$gapNamesArray = array_values($gapNames); // Convert to numeric-indexed array

// Helper function to convert numeric gap values to gap names
$align = '';
$alignContentSelf = '';
if (!function_exists('getGapName')) {
  function getGapName($gapValue, $gapNamesArray)
  {
    // If it's already a string (for backward compatibility), return as is
    if (is_string($gapValue) && in_array($gapValue, $gapNamesArray)) {
      return $gapValue;
    }
    // If it's numeric, convert using array index
    if (is_numeric($gapValue) && isset($gapNamesArray[(int)$gapValue])) {
      return $gapNamesArray[(int)$gapValue];
    }
    // Fallback to first available gap or 'sm' if available
    if (in_array('sm', $gapNamesArray)) {
      return 'sm';
    }
    return $gapNamesArray[0] ?? 'none';
  }
}

$width = 'flex-grid-cols-1 md:flex-grid-cols-2';
// switch ($args['layout_settings']['width']) {
//   case '1/2':
//     $width = 'flex-grid-cols-1 md:flex-grid-cols-2';
//     break;
//   case '1/3':
//     $width = 'flex-grid-cols-1 md:flex-grid-cols-3';
//     break;
//   case '1/3-2/3':
//     if (isset($args['layout_settings']['invertedThirds']) && $args['layout_settings']['invertedThirds'] == true) {
//       // 2/3 first, 1/3 second
//       $width = 'flex-grid-cols-1 md:flex-grid-cols-3 md:nth-[2n-1]:*:flex-grid-col-span-2';
//       if (isset($args['layout_settings']['matrixLayout']) && $args['layout_settings']['matrixLayout'] == true) {
//         // 2/3, 1/3, 1/3, 2/3
//         $width = 'flex-grid-cols-1 md:flex-grid-cols-3 md:nth-[4n]:*:flex-grid-col-span-2 md:nth-[4n-3]:*:flex-grid-col-span-2';
//       }
//     } else {
//       // 1/3 first, 2/3 second
//       $width = 'flex-grid-cols-1 md:flex-grid-cols-3 md:nth-[2n]:*:flex-grid-col-span-2';
//       if (isset($args['layout_settings']['matrixLayout']) && $args['layout_settings']['matrixLayout'] == true) {
//         // 2/3, 1/3, 1/3, 2/3
//         $width = 'flex-grid-cols-1 md:flex-grid-cols-3 md:nth-[4n-1]:*:flex-grid-col-span-2 md:nth-[4n-2]:*:flex-grid-col-span-2';
//       }
//     }
//     break;
//   case '1/4':
//     $width = 'flex-grid-cols-1 md:flex-grid-cols-4';
//     break;
//   default:
//     $width = 'flex-grid-cols-1 md:flex-grid-cols-2';
//     break;
// }

switch ($args['layout_settings']['justify']) {
  case 'start':
    $justify = 'justify-start';
    break;
  case 'center':
    $justify = 'justify-center';
    break;
  case 'end':
    $justify = 'justify-end';
    break;
  case 'between':
    $justify = 'justify-between';
    break;
  default:
    $justify = 'justify-start';
    break;
}

switch ($args['layout_settings']['align']) {
  case 'start':
    $align = 'items-start';
    $alignContentSelf = 'md:self-start';
    break;
  case 'center':
    $align = 'items-center';
    $alignContentSelf = 'md:self-center';
    break;
  case 'end':
    $align = 'items-end';
    $alignContentSelf = 'md:self-end';
    break;
  default:
    $align = 'items-start';
    $alignContentSelf = 'md:self-start';
    break;
}

if (!$args['layout_settings']['contained']) {
  $align = 'items-center';
  $alignContentSelf = 'md:self-center';
}

// Convert numeric gap value to gap name
$gapXName = getGapName($args['layout_settings']['gapX'], $gapNamesArray);

switch ($gapXName) {
  case 'none':
    $gapX = 'flex-grid-gap-x-none';
    break;
  case 'xs':
    $gapX = 'flex-grid-gap-x-xs';
    break;
  case 'sm':
    $gapX = 'flex-grid-gap-x-sm';
    break;
  case 'md':
    $gapX = 'flex-grid-gap-x-md';
    break;
  case 'lg':
    $gapX = 'flex-grid-gap-x-lg';
    break;
  case 'xl':
    $gapX = 'flex-grid-gap-x-xl';
    break;
  case '2xl':
    $gapX = 'flex-grid-gap-x-2xl';
    break;
  case '3xl':
    $gapX = 'flex-grid-gap-x-3xl';
    break;
  default:
    $gapX = 'flex-grid-gap-x-sm';
    break;
}

// Convert numeric gap value to gap name
// $mobileGapXName = getGapName($args['layout_settings']['mobileGapX'], $gapNamesArray);
$mobileGapX = '';
// if (isset($args['layout_settings']['overrideMobileGapX']) && $args['layout_settings']['overrideMobileGapX'] == true) {
//   switch ($mobileGapXName) {
//     case 'none':
//       $mobileGapX = 'max-md:flex-grid-gap-x-none';
//       break;
//     case 'xs':
//       $mobileGapX = 'max-md:flex-grid-gap-x-xs';
//       break;
//     case 'sm':
//       $mobileGapX = 'max-md:flex-grid-gap-x-sm';
//       break;
//     case 'md':
//       $mobileGapX = 'max-md:flex-grid-gap-x-md';
//       break;
//     case 'lg':
//       $mobileGapX = 'max-md:flex-grid-gap-x-lg';
//       break;
//     case 'xl':
//       $mobileGapX = 'max-md:flex-grid-gap-x-xl';
//       break;
//     case '2xl':
//       $mobileGapX = 'max-md:flex-grid-gap-x-2xl';
//       break;
//     case '3xl':
//       $mobileGapX = 'max-md:flex-grid-gap-x-3xl';
//       break;
//     default:
//       $mobileGapX = 'max-md:flex-grid-gap-x-sm';
//       break;
//   }
// } else {
//   $mobileGapX = '';
// }

// Convert numeric gap value to gap name
$gapYName = getGapName($args['layout_settings']['gapY'], $gapNamesArray);

switch ($gapYName) {
  case 'none':
    $gapY = 'flex-grid-gap-y-none';
    break;
  case 'xs':
    $gapY = 'flex-grid-gap-y-xs';
    break;
  case 'sm':
    $gapY = 'flex-grid-gap-y-sm';
    break;
  case 'md':
    $gapY = 'flex-grid-gap-y-md';
    break;
  case 'lg':
    $gapY = 'flex-grid-gap-y-lg';
    break;
  case 'xl':
    $gapY = 'flex-grid-gap-y-xl';
    break;
  case '2xl':
    $gapY = 'flex-grid-gap-y-2xl';
    break;
  case '3xl':
    $gapY = 'flex-grid-gap-y-3xl';
    break;
  default:
    $gapY = 'flex-grid-gap-y-lg';
    break;
}

$mobileGapYName = getGapName($args['layout_settings']['mobileGapY'], $gapNamesArray);
$mobileGapY = '';
if (isset($args['layout_settings']['overrideMobileGapY']) && $args['layout_settings']['overrideMobileGapY'] == true) {
  switch ($mobileGapYName) {
    case 'none':
      $mobileGapY = 'max-md:flex-grid-gap-y-none';
      break;
    case 'xs':
      $mobileGapY = 'max-md:flex-grid-gap-y-xs';
      break;
    case 'sm':
      $mobileGapY = 'max-md:flex-grid-gap-y-sm';
      break;
    case 'md':
      $mobileGapY = 'max-md:flex-grid-gap-y-md';
      break;
    case 'lg':
      $mobileGapY = 'max-md:flex-grid-gap-y-lg';
      break;
    case 'xl':
      $mobileGapY = 'max-md:flex-grid-gap-y-xl';
      break;
    case '2xl':
      $mobileGapY = 'max-md:flex-grid-gap-y-2xl';
      break;
    case '3xl':
      $mobileGapY = 'max-md:flex-grid-gap-y-3xl';
      break;
    default:
      $mobileGapY = 'max-md:flex-grid-gap-y-sm';
      break;
  }
} else {
  $mobileGapY = '';
}

$divideX = '';
$overflowX = '';
$overflowY = '';
if (isset($args['layout_settings']['divideX']) && $args['layout_settings']['divideX'] == true) {
  $divideX = 'flex-grid-divide-x';
  $overflowX = 'overflow-x-hidden';
} else {
  $overflowX = 'overflow-x-visible';
}

$divideY = '';
if (isset($args['layout_settings']['divideY']) && $args['layout_settings']['divideY'] == true) {
  $divideY = 'flex-grid-divide-y';
  $overflowY = 'overflow-y-hidden';
  if (isset($args['layout_settings']['fullWidthDivideY']) && $args['layout_settings']['fullWidthDivideY'] == true) {
    $divideY .= ' full-divider-y';
  }
} else {
  $overflowY = 'overflow-y-visible';
}

// Render the feature section
?>
<section class="feature <?= $themeClass; ?> <?= $layoutClass; ?> <?= $args['layout_settings']['contained'] ? 'feature-contained py-section' : ''; ?>">
  <div class="w-full flex flex-col md:flex-row md:gap-x-xl <?= $args['reversed'] ? 'md:flex-row-reverse' : ''; ?> <?= $args['layout_settings']['contained'] ? 'px-container max-md:gap-y-lg' : ''; ?>">
    <div id="featureImage" class="<?= !$args['layout_settings']['contained'] ? 'relative w-full md:w-1/2 grow-0 shrink-0 max-md:aspect-[4/3]' : 'media md:flex-1 aos animate-fadeinup'; ?> <?= $imageWrapperClass; ?>">
      <img
        src="<?= $args['image']['url'] ?? ''; ?>"
        srcset="<?= $args['image']['sizes']['thumbnail'] ?? ''; ?> 640w, 
              <?= $args['image']['sizes']['medium'] ?? ''; ?> 1280w, 
              <?= $args['image']['sizes']['large'] ?? ''; ?> 2560w"
        sizes="(max-width: 550px) 100vw, 50vw"
        alt="<?= $args['image']['alt'] ?? ''; ?>"
        class="w-full <?= !$args['layout_settings']['contained'] ? 'absolute inset-0' : ''; ?> <?= $imageClass; ?>"
        loading="lazy"
        decoding="async" />
    </div>
    <div id="featureContent" class="inline-flexible w-auto grow shrink <?= !$args['layout_settings']['contained'] ? 'md:self-center' : $alignContentSelf; ?> <?= !$args['layout_settings']['contained'] ? 'py-section max-md:px-container max-md:pt-lg' : ''; ?> <?= !$args['layout_settings']['contained'] ? ($args['reversed'] ? 'md:pl-container' : 'md:pr-container') : ''; ?> <?= $args['layout_settings']['contained'] ? 'md:flex-1' : ''; ?>">
      <div class="feature-columns columns flex-grid <?= $overflowX; ?> <?= $overflowY; ?> <?= $width; ?> <?= $gapX; ?> <?= $gapY; ?> <?= $mobileGapX; ?> <?= $mobileGapY; ?> <?= $justify; ?> <?= $align; ?> <?= $divideX; ?> <?= $divideY; ?> flex-grid-divide-[--color-separator-primary] md:*:stagger-2 md:*:stagger-delay-150 spacing-reset">
        <?php $adwp->render_flexible_layout($args['feature'], [
          // 'width' => $args['layout_settings']['width'],
          'width' => '1/2',
          'parentAlignment' => $args['layout_settings']['align'],
          'parentDivideX' => $args['layout_settings']['divideX'],
          'parentColor' => $args['color'],
          'isNested' => true,
        ]); ?>
      </div>
    </div>
  </div>
</section>