<?php

/**
 * Normalizes CSS values by determining if they should be wrapped in quotes
 * @param mixed $value
 * @return mixed
 */
function normalizeCssValue($value)
{
  if (!is_string($value)) {
    return $value;
  }

  $trimmedValue = trim($value);
  if ($trimmedValue === '') {
    return $trimmedValue;
  }

  $cssKeywords = [
    // Generic font families
    'serif',
    'sans-serif',
    'monospace',
    'cursive',
    'fantasy',
    'system-ui',
    // Font style
    'normal',
    'italic',
    'oblique',
    // Font weight
    'bold',
    'bolder',
    'lighter',
    // Font variant
    'small-caps',
    // Display
    'block',
    'inline',
    'inline-block',
    'flex',
    'inline-flex',
    'grid',
    'inline-grid',
    'table',
    'table-row',
    'table-cell',
    'list-item',
    'none',
    // Position
    'static',
    'relative',
    'absolute',
    'fixed',
    'sticky',
    // Text align
    'left',
    'right',
    'center',
    'justify',
    // Vertical align
    'baseline',
    'top',
    'middle',
    'bottom',
    'text-top',
    'text-bottom',
    // Float
    'left',
    'right',
    'none',
    // Clear
    'left',
    'right',
    'both',
    'none',
    // Overflow
    'visible',
    'hidden',
    'scroll',
    'auto',
    // White space
    'normal',
    'nowrap',
    'pre',
    'pre-wrap',
    'pre-line',
    // Text transform
    'none',
    'capitalize',
    'uppercase',
    'lowercase',
    // Text decoration
    'none',
    'underline',
    'overline',
    'line-through',
    // Cursor
    'auto',
    'default',
    'pointer',
    'crosshair',
    'move',
    'text',
    'wait',
    'help',
    // Box sizing
    'content-box',
    'border-box',
    // Colors/keywords
    'transparent',
    'currentcolor',
    'inherit',
    'initial',
    'unset',
    'revert',
    // Background repeat
    'repeat',
    'repeat-x',
    'repeat-y',
    'no-repeat',
    // Background size
    'cover',
    'contain',
    // Border styles
    'none',
    'solid',
    'dashed',
    'dotted',
    'double',
    'groove',
    'ridge',
    'inset',
    'outset',
    // List style
    'disc',
    'circle',
    'square',
    'decimal',
  ];

  if (in_array(strtolower($trimmedValue), $cssKeywords, true)) {
    return $trimmedValue;
  }

  // CSS function pattern like calc(), var(), etc.
  if (preg_match('/^[a-zA-Z-]+\(.*\)$/', $trimmedValue)) {
    return $trimmedValue;
  }

  // Number with optional unit
  if (preg_match('/^-?\d*\.?\d+(px|em|rem|%|vh|vw|pt|pc|in|cm|mm|ex|ch|vmin|vmax|fr|deg|rad|turn|s|ms)?$/', $trimmedValue)) {
    return $trimmedValue;
  }

  // Hex color
  if (preg_match('/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$/', $trimmedValue)) {
    return $trimmedValue;
  }

  /* // Already quoted
  if ((substr($trimmedValue, 0, 1) === '"' && substr($trimmedValue, -1) === '"') || (substr($trimmedValue, 0, 1) === "'" && substr($trimmedValue, -1) === "'")) {
    return $trimmedValue;
  }

  // Contains spaces/special chars
  if (preg_match('/[\s,;:()\[\]{}!@#$%^&*+=|\\<>?\/~`]/', $trimmedValue)) {
    return '"' . $trimmedValue . '"';
  } */

  // Default: quote single-word custom values
  // return '"' . $trimmedValue . '"';
  return $trimmedValue;
}

/**
 * Recursively normalizes all CSS values in an object
 * @param mixed $obj
 * @return mixed
 */
function allCssValuesNormalized($obj)
{
  if ($obj === null) return $obj;

  if (is_array($obj)) {
    $out = [];
    foreach ($obj as $k => $v) {
      $out[$k] = allCssValuesNormalized($v);
    }
    return $out;
  }

  if (is_string($obj)) {
    return normalizeCssValue($obj);
  }

  return $obj;
}
