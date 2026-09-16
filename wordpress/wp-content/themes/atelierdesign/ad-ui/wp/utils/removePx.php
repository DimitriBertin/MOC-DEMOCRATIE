<?php

/**
 * Removes "px" from the end of a value and keeps it as a unitless string
 * @param mixed $value
 * @return mixed
 */
function removePx($value)
{
  if (is_string($value) && substr($value, -2) === 'px') {
    $numericValue = substr($value, 0, -2);
    return is_numeric($numericValue) ? $numericValue : $value;
  }
  return $value;
}

/**
 * Removes "px" from the end of a value and converts it to an integer
 * @param mixed $value
 * @return mixed
 */
function removePxToNumber($value)
{
  if (is_string($value) && substr($value, -2) === 'px') {
    $numericValue = substr($value, 0, -2);
    if (is_numeric($numericValue)) return intval($numericValue, 10);
  }
  return $value;
}

/**
 * Recursively processes an object to remove "px" suffixes and convert them to unitless strings
 * @param mixed $obj
 * @return mixed
 */
function removeAllPxFromObject($obj)
{
  if ($obj === null) return $obj;
  if (is_array($obj)) {
    $out = [];
    foreach ($obj as $k => $v) {
      $out[$k] = removeAllPxFromObject($v);
    }
    return $out;
  }
  return removePx($obj);
}

/**
 * Recursively processes an object to remove "px" suffixes and convert them to integers
 * @param mixed $obj
 * @return mixed
 */
function removeAllPxFromObjectToNumbers($obj)
{
  if ($obj === null) return $obj;
  if (is_array($obj)) {
    $out = [];
    foreach ($obj as $k => $v) {
      $out[$k] = removeAllPxFromObjectToNumbers($v);
    }
    return $out;
  }
  return removePxToNumber($obj);
}

/**
 * Adds "px" unit to numeric values (useful for CSS custom properties)
 * @param mixed $value
 * @return string|mixed
 */
function addPxUnit($value)
{
  if (is_numeric($value)) {
    return $value . 'px';
  }
  return $value;
}

/**
 * Recursively processes an object to add "px" units to numeric values
 * @param mixed $obj
 * @return mixed
 */
function addPxUnitToObject($obj)
{
  if ($obj === null) return $obj;
  if (is_array($obj)) {
    $out = [];
    foreach ($obj as $k => $v) {
      $out[$k] = addPxUnitToObject($v);
    }
    return $out;
  }
  return addPxUnit($obj);
}
