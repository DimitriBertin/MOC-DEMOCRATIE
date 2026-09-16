<?php

require_once __DIR__ . '/isReference.php';

/**
 * Recursively removes items where the value is a reference equal to "{false}"
 * @param mixed $obj
 * @return mixed
 */
function removeFalseValues($obj)
{
  if ($obj === null || (!is_array($obj) && !is_object($obj))) {
    return $obj;
  }

  if (is_array($obj) && array_keys($obj) === range(0, count($obj) - 1)) {
    $out = [];
    foreach ($obj as $item) {
      $processed = removeFalseValues($item);
      if (is_string($processed) && isReference($processed) && $processed === '{false}') {
        continue;
      }
      $out[] = $processed;
    }
    return $out;
  }

  $result = [];
  foreach ((array)$obj as $key => $value) {
    if (is_string($value) && isReference($value) && $value === '{false}') {
      continue;
    }
    $result[$key] = removeFalseValues($value);
  }
  return $result;
}

