<?php

require_once __DIR__ . '/toKebabCase.php';
require_once __DIR__ . '/isReference.php';
require_once __DIR__ . '/../handleTokens.php';

// Lightweight helpers to resolve dot-paths from arrays/objects
function adui_get_by_path($root, $path)
{
  if (!is_array($root) && !is_object($root)) return null;
  $segments = explode('.', $path);
  $current = $root;
  foreach ($segments as $seg) {
    if (is_array($current) && array_key_exists($seg, $current)) {
      $current = $current[$seg];
    } elseif (is_object($current) && isset($current->{$seg})) {
      $current = $current->{$seg};
    } else {
      return null;
    }
  }
  return $current;
}

// getResolvedValue, $adui_config and $adui_calc come from handleTokens.php

function extractTokenPathFlat($value)
{
  if (!is_string($value)) return $value;
  if ((substr($value, 0, 2) === '"{' && substr($value, -2) === '}"') || (substr($value, 0, 2) === "'{" && substr($value, -2) === "'}")) {
    return substr($value, 1, -1);
  }
  return $value;
}

function convertTokenReference($reference, $variablePrefix = '--')
{
  global $adui_config, $adui_calc;
  // Tailwind reference
  if (isTailwindReference($reference)) {
    $themeFnValue = substr(extractTokenPathFlat($reference), 13, -1);
    return 'theme(' . $themeFnValue . ')';
  }

  // Config reference
  if (isConfigReference($reference)) {
    $configFnValue = substr(extractTokenPathFlat($reference), 8, -1);
    $resolved = getResolvedValue($adui_config, $configFnValue);
    return $resolved !== null ? $resolved : '';
  }

  // Calc reference
  if (isCalcReference($reference)) {
    $calcFnValue = substr(extractTokenPathFlat($reference), 6, -1);
    $resolved = getResolvedValue($adui_calc, $calcFnValue);
    return $resolved !== null ? $resolved : '';
  }

  // Generic token {path.to.token}
  $cleanReference = extractTokenPathFlat($reference);
  $path = substr($cleanReference, 1, -1);
  $kebabPath = str_replace('.', '-', toKebabCase($path));
  // Build variable name using provided variablePrefix
  $vp = preg_replace('/^--/', '', (string)$variablePrefix);
  $suffix = preg_replace('/^primitives-/', '', $kebabPath);
  $name = $vp !== '' ? ('--' . $vp . $suffix) : ('--' . $suffix);
  return 'var(' . $name . ')';
}

/**
 * Recursively flattens a nested token array into a single-level assoc array with concatenated keys.
 * Handles $value leafs and converts token references like "{color.primary}" to CSS variables.
 *
 * @param array $data
 * @param string $prefix
 * @return array
 */
function flattenTokensObject($data, $prefix = '', $varPrefix = null)
{
  $flattened = [];
  $variablePrefix = $varPrefix !== null ? $varPrefix : $prefix;
  if (!is_array($data)) return $flattened;

  foreach ($data as $key => $val) {
    if (is_array($val)) {
      if (array_key_exists('$value', $val)) {
        $value = $val['$value'];
        if (is_string($value) && isReference($value)) {
          $flattened[$prefix . toKebabCase($key)] = convertTokenReference($value, $variablePrefix);
        } else {
          $flattened[$prefix . toKebabCase($key)] = $value;
        }
      } else {
        $flattened = array_merge(
          $flattened,
          flattenTokensObject($val, $prefix . toKebabCase($key) . '-', $variablePrefix)
        );
      }
    } elseif ($val !== null) {
      if (is_string($val) && isReference($val)) {
        $flattened[$prefix . toKebabCase($key)] = convertTokenReference($val, $variablePrefix);
      } else {
        $flattened[$prefix . toKebabCase($key)] = $val;
      }
    }
  }

  return $flattened;
}
