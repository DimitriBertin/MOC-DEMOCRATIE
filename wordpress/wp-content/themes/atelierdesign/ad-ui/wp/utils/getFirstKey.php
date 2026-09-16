<?php

/**
 * Get the first key from an array (for default/base values)
 *
 * @param array|null $obj
 * @return string|int|null
 */
function getFirstKey($obj)
{
  if (!$obj || !is_array($obj)) return null;
  $keys = array_keys($obj);
  return count($keys) > 0 ? $keys[0] : null;
}

