<?php

/**
 * Converts a string to Title Case
 *
 * @param string $str The string to convert
 * @return string The Title Case version of the string
 */
function toTitleCase($str)
{
  if (!$str) return '';

  // Normalize input: insert space before uppercase, replace dashes/underscores with spaces,
  // collapse multiple spaces, trim, and lowercase
  $normalized = $str;
  $normalized = preg_replace('/([A-Z])/', ' $1', $normalized);
  $normalized = preg_replace('/[-_]+/', ' ', $normalized);
  $normalized = preg_replace('/\s+/', ' ', $normalized);
  $normalized = trim($normalized);
  $normalized = strtolower($normalized);

  // Capitalize first letter of each word
  return ucwords($normalized);
}
