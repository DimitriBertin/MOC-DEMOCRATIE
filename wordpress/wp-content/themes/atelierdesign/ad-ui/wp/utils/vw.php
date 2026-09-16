<?php

/**
 * Convert a value to a viewport width calc() based on configured screen sizes.
 *
 * @param string|int|float $value Numeric CSS value (with or without unit). Recommended: px number.
 * @param string $screen 'sm' or 'lg'
 * @return string CSS calc() expression
 */
function vw($value, $screen = 'sm')
{
  $screenWidth = 1280;
  $config = isset($GLOBALS['adui_config']) && is_array($GLOBALS['adui_config']) ? $GLOBALS['adui_config'] : [];
  if (isset($config['screenSizes']) && is_array($config['screenSizes'])) {
    if ($screen === 'sm' && isset($config['screenSizes']['sm'])) {
      $screenWidth = $config['screenSizes']['sm'];
    } elseif ($screen === 'lg' && isset($config['screenSizes']['lg'])) {
      $screenWidth = $config['screenSizes']['lg'];
    }
  }
  return 'calc(' . $value . ' / ' . $screenWidth . ' * 100vw)';
}

