<?php

require_once __DIR__ . '/isReference.php';
require_once __DIR__ . '/toKebabCase.php';

/**
 * Extracts the token path from a reference (with or without quotes)
 * @param string $value
 * @return string
 */
function extractTokenPath($value)
{
  if (!is_string($value)) return $value;

  if ((str_starts_with($value, '"{') && str_ends_with($value, '}"')) || (str_starts_with($value, "'{") && str_ends_with($value, "'}"))) {
    return substr($value, 1, -1);
  }

  return $value;
}

/**
 * Converts a value (or token reference) to a CSS variable value string.
 *
 * @param string $value
 * @param string $wrap
 * @param string $prefix
 * @return string
 */
function referenceToCssVariable($value, $wrap = '', $prefix = '')
{
  if (isReference($value)) {
    if (!isTailwindReference($value)) {
      $cleanReference = extractTokenPath($value);
      $path = substr($cleanReference, 1, -1);
      $kebabPath = str_replace('.', '-', toKebabCase($path));
      return 'var(--' . $prefix . $kebabPath . ')';
    } else {
      return 'theme(' . substr($value, 13, -1) . ')';
    }
  } else {
    return $wrap . $value . $wrap;
  }
}

/**
 * Recursively converts all references to CSS variables in an object
 * Properly handles design token structure with $type and $value properties
 *
 * @param mixed $obj
 * @param string $prefix
 * @return mixed
 */
function allReferencesToCssVariables($obj, $prefix = '')
{
  if (!$obj || (!is_array($obj) && !is_object($obj))) {
    return $obj;
  }

  $result = [];
  foreach ((array)$obj as $key => $value) {
    if (is_array($value) || is_object($value)) {
      $valueArr = (array)$value;
      if (array_key_exists('$type', $valueArr) && array_key_exists('$value', $valueArr)) {
        $result[$key] = ['$type' => $valueArr['$type']];
        if (is_array($valueArr['$value']) || is_object($valueArr['$value'])) {
          $result[$key]['$value'] = [];
          foreach ((array)$valueArr['$value'] as $vKey => $vItem) {
            if (is_string($vItem) && isReference($vItem)) {
              $result[$key]['$value'][$vKey] = referenceToCssVariable($vItem, '', '');
            } else {
              $result[$key]['$value'][$vKey] = $vItem;
            }
          }
        } elseif (is_string($valueArr['$value']) && isReference($valueArr['$value'])) {
          $result[$key]['$value'] = referenceToCssVariable($valueArr['$value'], '', '');
        } else {
          $result[$key]['$value'] = $valueArr['$value'];
        }
      } else {
        $result[$key] = allReferencesToCssVariables($value, $prefix);
      }
    } elseif (is_string($value) && isReference($value)) {
      $result[$key] = referenceToCssVariable($value, '', $prefix);
    } else {
      $result[$key] = $value;
    }
  }

  return $result;
}

