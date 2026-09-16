<?php 
global $adwp, $adui_tokens;

$section_color = $args['section_color'];

// Split $args['color'] based on the '/' and get the theme-* and the bg-layout-* classes from it
$colorParts = explode('/', $args['section_color']);
$themeClass = "theme-" . mb_strtolower($colorParts[0], 'UTF-8');
$layoutClass = "bg-layout-" . mb_strtolower($colorParts[1], 'UTF-8');

$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$gapNames = array_keys($adui_tokens['responsiveSizing'][$responsiveSizingModes[0]]['gap'] ?? []);
$gapNamesArray = array_values($gapNames); // Convert to numeric-indexed array

// Helper function to convert numeric gap values to gap names
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
switch ($args['layout_settings']['align']) {
  case 'start':
    $align = 'items-start';
    $valign = 'justify-start';
    break;
  case 'center':
    $align = 'items-center';
    $valign = 'justify-center';
    break;
  case 'end':
    $align = 'items-end';
    $valign = 'justify-end';
    break;
  default:
    $align = 'items-start';
    $valign = 'justify-start';
    break;
}

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

$gapYName = getGapName($args['layout_settings']['gapY_1-3'], $gapNamesArray);
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

// GAP Y
$gapYName = getGapName($args['layout_settings']['gapY_2-3'], $gapNamesArray);
switch ($gapYName) {
  case 'none':
    $gapY_2 = 'flex-grid-gap-y-none';
    break;
  case 'xs':
    $gapY_2 = 'flex-grid-gap-y-xs';
    break;
  case 'sm':
    $gapY_2 = 'flex-grid-gap-y-sm';
    break;
  case 'md':
    $gapY_2 = 'flex-grid-gap-y-md';
    break;
  case 'lg':
    $gapY_2 = 'flex-grid-gap-y-lg';
    break;
  case 'xl':
    $gapY_2 = 'flex-grid-gap-y-xl';
    break;
  case '2xl':
    $gapY_2 = 'flex-grid-gap-y-2xl';
    break;
  case '3xl':
    $gapY_2 = 'flex-grid-gap-y-3xl';
    break;
  default:
    $gapY_2 = 'flex-grid-gap-y-lg';
    break;
}

$gapXName_2 = getGapName($args['layout_settings']['gapX_2-3'], $gapNamesArray);
switch ($gapXName_2) {
  case 'none':
    $gapX_2 = 'flex-grid-gap-x-none';
    break;
  case 'xs':
    $gapX_2 = 'flex-grid-gap-x-xs';
    break;
  case 'sm':
    $gapX_2 = 'flex-grid-gap-x-sm';
    break;
  case 'md':
    $gapX_2 = 'flex-grid-gap-x-md';
    break;
  case 'lg':
    $gapX_2 = 'flex-grid-gap-x-lg';
    break;
  case 'xl':
    $gapX_2 = 'flex-grid-gap-x-xl';
    break;
  case '2xl':
    $gapX_2 = 'flex-grid-gap-x-2xl';
    break;
  case '3xl':
    $gapX_2 = 'flex-grid-gap-x-3xl';
    break;
  default:
    $gapX_2 = 'flex-grid-gap-x-lg';
    break;
}

switch ($args['layout_settings']['alignX_2-3']) {
  case 'start':

    $alignX_2 = 'justify-start';
    break;
  case 'center':
    $alignX_2 = 'justify-center';
    break;
  case 'end':
    $alignX_2 = 'justify-end';
    break;
  default:
    $alignX_2 = 'justify-start';
    break;
}


$divideX = '';
if (isset($args['layout_settings']['divideX']) && $args['layout_settings']['divideX'] == true) {
  $divideX = 'flex-grid-divide-x';
}

$divideX_2 = '';
if (isset($args['layout_settings']['divideX_2-3']) && $args['layout_settings']['divideX_2-3'] == true) {
  $divideX_2 = 'flex-grid-divide-x';
}


$isSticky = $args['layout_settings']['isSticky'];
$sticky = '';
if ($isSticky) $sticky = 'md:sticky @md/lg:top-12'; 
if($isSticky && $args['layout_settings']['align'] == 'center' ) $sticky = 'md:sticky @md/lg:top-12';
if($isSticky && $args['layout_settings']['align'] == 'end' ) $sticky = 'md:sticky @md/lg:bottom-12';

?>
<section class="py-section columns-third flex-grid md:flex-grid-cols-3 flex-grid-gap-y-lg <?= $gapX; ?>  <?= $divideX; ?> flex-grid-divide-[--color-separator-primary] <?= $layoutClass ?> <?= $themeClass ?> <?= $args['layout_settings']['reversed'] ? 'md:flex-row-reverse' : ''; ?> px-container">
  <div class="flex-grid-col-span-1 flex flex-col <?= $valign ?>">
    <div class="flex-grid flex-grid-cols-1 <?= $gapY; ?> <?= $sticky ?> ">
      <?php
        $adwp->render_flexible_layout($args['columns']['column_1_3'], [
          'isNested' => true,
          'layout_settings' => [
            'isFullWidth' => false,
          ],
        ]);
      ?>
    </div>
  </div>
  <div class="flex-grid-col-span-1 md:flex-grid-col-span-2 overflow-hidden">
    <div class="flex-grid flex-grid-cols-6 flex-wrap <?= $alignX_2 ?> <?= $gapY_2; ?> <?= $gapX_2; ?> <?= $divideX_2; ?> flex-grid-divide-[--color-separator-primary]">
      <?php  
        $adwp->render_flexible_layout($args['columns']['column_2_3'], [
          'isNested' => true,
          'layout_settings' => [
            'isFullWidth' => false,
          ],
        ]);
      ?>
    </div>
  </div>
</section>
