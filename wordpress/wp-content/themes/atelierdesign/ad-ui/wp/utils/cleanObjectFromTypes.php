<?php

/**
 * Clean the objects from the [$type] and set the [$value] to the value of the object of that level.
 *
 * @param array $obj The object to clean, having the [$type] and [$value] properties.
 * @return array The cleaned object, without the [$type] and [$value] properties.
 */
function cleanObjectFromTypes($obj)
{
  if (!is_array($obj)) {
    return $obj;
  }

  $cleanObj = [];
  foreach ($obj as $key => $value) {
    if (is_array($value) && array_key_exists('$type', $value)) {
      $cleanObj[$key] = $value['$value'];
    } elseif (is_array($value)) {
      $cleanObj[$key] = cleanObjectFromTypes($value);
    } else {
      $cleanObj[$key] = $value;
    }
  }

  return $cleanObj;
}
