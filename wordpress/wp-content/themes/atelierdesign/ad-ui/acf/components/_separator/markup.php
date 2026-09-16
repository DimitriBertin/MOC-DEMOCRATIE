<?php

global $adwp, $adui_tokens;

// Get the same gap names array from tokens as in fields.php
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

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$color = 'separator-' . $args['layout_settings']['color'];


// Convert numeric gap value to gap name
$marginYName = getGapName($args['layout_settings']['marginY'], $gapNamesArray);

switch ($marginYName) {
  case 'none':
    $marginY = 'my-0';
    break;
  case 'xs':
    $marginY = 'my-xs';
    break;
  case 'sm':
    $marginY = 'my-sm';
    break;
  case 'md':
    $marginY = 'my-md';
    break;
  case 'lg':
    $marginY = 'my-lg';
    break;
  case 'xl':
    $marginY = 'my-xl';
    break;
  case '2xl':
    $marginY = 'my-2xl';
    break;
  case '3xl':
    $marginY = 'my-3xl';
    break;
  default:
    $marginY = 'my-lg';
    break;
}

?>
<div class="separator-wrapper flex items-center <?= $isFullWidth ? '' : 'px-content' ?> aos animate-fadeinup <?= $marginY; ?> first:mt-0 last:mb-0">
  <hr class="<?= $color; ?>" />
</div>