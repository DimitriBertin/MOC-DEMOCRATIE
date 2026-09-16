<?php

/**
 * Converts a string to kebab-case
 *
 * @param string $str
 * @return string
 */
function toKebabCase($str)
{
  if (!$str) return '';

  $result = $str;
  // Handle consecutive uppercase letters followed by lowercase (e.g., XMLParser → XML-Parser)
  $result = preg_replace('/([A-Z]+)([A-Z][a-z])/', '$1-$2', $result);
  // Handle lowercase followed by uppercase (e.g., camelCase → camel-Case)
  $result = preg_replace('/([a-z])([A-Z])/', '$1-$2', $result);
  // Replace underscores and spaces with hyphens
  $result = preg_replace('/[_\s]+/', '-', $result);
  // Convert to lowercase
  $result = strtolower($result);
  // Remove duplicate hyphens
  $result = preg_replace('/-+/', '-', $result);
  // Remove leading hyphen
  $result = preg_replace('/^-/', '', $result);

  return $result;
}

/**
 * Converts a string to a URL-friendly format (kebab-case with special characters removed and accented characters normalized)
 *
 * @param string $str
 * @return string
 */
function toUrlCase($str)
{
  if (!$str) return '';

  $result = $str;

  // Normalize accented characters to their base form if possible
  if (function_exists('iconv')) {
    $result = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $result);
  }

  // Handle consecutive uppercase letters followed by lowercase
  $result = preg_replace('/([A-Z]+)([A-Z][a-z])/', '$1-$2', $result);
  // Handle lowercase followed by uppercase
  $result = preg_replace('/([a-z])([A-Z])/', '$1-$2', $result);
  // Replace underscores, spaces, and special characters with hyphens
  $result = preg_replace('/[_\s\W]+/', '-', $result);
  // Convert to lowercase
  $result = strtolower($result);
  // Remove duplicate hyphens
  $result = preg_replace('/-+/', '-', $result);
  // Trim leading/trailing hyphens
  $result = preg_replace('/^-|-$/', '', $result);

  return $result;
}

