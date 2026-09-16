<?php global $adwp, $adui_tokens; ?>
<?php
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

$size = $args['layout_settings']['size'];

switch ($args['layout_settings']['align']) {
  case 'start':
    $align = 'self-start';
    break;
  case 'center':
    $align = 'self-center';
    break;
  case 'end':
    $align = 'self-end';
    break;
  default:
    $align = 'self-start';
    break;
}


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


$sizeClass = 'md:flex-grid-col-span-6';
if($size == '1/2')  $sizeClass = 'md:flex-grid-col-span-3';
if($size == '1/3')  $sizeClass = 'md:flex-grid-col-span-2';
if($size == '2/3')  $sizeClass = 'md:flex-grid-col-span-4';
if($size == '1')  $align = 'self-start';

?>
<div class="feature-column inline-flexible column flex-grid-col-span-6 <?= $sizeClass; ?>">
  <div class="flex-grid flex-grid-cols-1 <?= $align ?> <?= $gapY ?>">
    <?php $adwp->render_flexible_layout($args['_columnsThirdColumn_content'], [
      'isNested' => true,
      'parentSize' => $size,
      'layout_settings' => [
        'isFullWidth' => false,
      ],
    ]); ?>
  </div>
</div>