<?php

/**
 * Determine if an array is associative (object-like)
 * @param mixed $item
 * @return bool
 */
function adui_is_assoc_array($item)
{
  if (!is_array($item)) return false;
  return array_keys($item) !== range(0, count($item) - 1);
}

/**
 * Determine if an array is indexed (list-like)
 * @param mixed $item
 * @return bool
 */
function adui_is_indexed_array($item)
{
  if (!is_array($item)) return false;
  return array_keys($item) === range(0, count($item) - 1);
}

/**
 * Deep merges properties from source into target with special handling:
 * - Associative arrays are merged recursively
 * - Indexed arrays are concatenated
 * - Primitive values from source override target values
 *
 * @param mixed $target
 * @param mixed $source
 * @return mixed
 */
function deepMerge($target, $source)
{
  if (adui_is_assoc_array($target) && adui_is_assoc_array($source)) {
    $output = $target;
    foreach ($source as $key => $value) {
      if (adui_is_assoc_array($value) && array_key_exists($key, $target) && adui_is_assoc_array($target[$key])) {
        $output[$key] = deepMerge($target[$key], $value);
      } elseif (adui_is_indexed_array($value) && array_key_exists($key, $target) && adui_is_indexed_array($target[$key])) {
        $output[$key] = array_merge($target[$key], $value);
      } else {
        $output[$key] = $value;
      }
    }
    return $output;
  }

  // If not arrays or different shapes, source overrides
  return $source;
}

/**
 * Deep merges properties from source into target with different handling than deepMerge:
 * - Associative arrays are merged recursively like in deepMerge
 * - Other values from source override target values if defined
 * - Indexed arrays are completely replaced rather than concatenated
 *
 * @param mixed $target
 * @param mixed $source
 * @return mixed
 */
function deepMergeReplace($target, $source)
{
  if (adui_is_assoc_array($target) && adui_is_assoc_array($source)) {
    $output = $target;
    foreach ($source as $key => $sourceValue) {
      $targetValue = array_key_exists($key, $output) ? $output[$key] : null;

      if (adui_is_assoc_array($sourceValue) && adui_is_assoc_array($targetValue)) {
        // Merge nested associative arrays using deepMerge
        $output[$key] = deepMerge($targetValue, $sourceValue);
      } elseif ($sourceValue !== null) {
        // Replace arrays and primitives
        $output[$key] = $sourceValue;
      }
    }
    return $output;
  }

  return $source !== null ? $source : $target;
}

