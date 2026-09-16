<?php

require_once __DIR__ . '/isReference.php';

/**
 * Normalizes font weight and style properties in a font array or individual string values
 * @param mixed $value
 * @return mixed
 */
function normalizeFontWeightAndStyle($value)
{
  if (is_array($value)) {
    $result = [];
    foreach ($value as $key => $val) {
      if (is_array($val)) {
        $result[$key] = normalizeFontWeightAndStyle($val);
      } elseif (is_string($val) && shouldNormalizeProperty($key)) {
        $normalized = normalizeFontWeightAndStylePair($val);
        $result[$key] = $normalized['fontWeight'];
        $styleKey = str_replace(['Weight', 'weight'], 'Style', $key);
        $result[$styleKey] = $normalized['fontStyle'];
      } else {
        $result[$key] = $val;
      }
    }
    return $result;
  }

  if (is_string($value)) {
    return normalizeFontWeightAndStyleLegacy($value);
  }

  return $value;
}

function shouldNormalizeProperty($key)
{
  $lower = strtolower($key);
  return strpos($lower, 'weight') !== false;
}

function normalizeFontWeightAndStylePair($value)
{
  if (!is_string($value)) {
    return ['fontWeight' => $value, 'fontStyle' => 'normal'];
  }

  if (isReference($value)) {
    return ['fontWeight' => $value, 'fontStyle' => 'normal'];
  }

  $lc = strtolower($value);
  $fontWeight = '400';
  $fontStyle = 'normal';

  if (strpos($lc, 'italic') !== false) {
    $fontStyle = 'italic';
  } elseif (strpos($lc, 'oblique') !== false) {
    $fontStyle = 'oblique';
  }

  $weightMap = [
    'thin' => '100',
    'hairline' => '100',
    'extralight' => '200',
    'ultralight' => '200',
    'light' => '300',
    'regular' => '400',
    'normal' => '400',
    'book' => '400',
    'medium' => '500',
    'semibold' => '600',
    'demibold' => '600',
    'bold' => '700',
    'extrabold' => '800',
    'ultrabold' => '800',
    'black' => '900',
    'heavy' => '900',
    'extrablack' => '950',
    'ultrablack' => '950',
  ];

  foreach ($weightMap as $name => $weight) {
    if (preg_match('/\b' . preg_quote($name, '/') . '\b/i', $lc)) {
      $fontWeight = $weight;
      break;
    }
  }

  if (preg_match('/^\d+$/', $value)) {
    $fontWeight = $value;
  } else {
    $withoutStyle = trim(preg_replace('/\s*(italic|oblique)\s*/i', '', $lc));
    if ($withoutStyle === '' || !array_key_exists($withoutStyle, $weightMap)) {
      if ($fontStyle === 'italic' || $fontStyle === 'oblique') {
        $fontWeight = '400';
      } else {
        $fontWeight = $value;
      }
    }
  }

  return ['fontWeight' => $fontWeight, 'fontStyle' => $fontStyle];
}

function normalizeFontWeightAndStyleLegacy($value)
{
  $fontWeight = '400';
  $fontStyle = 'normal';
  if (!is_string($value)) {
    return ['fontWeight' => $fontWeight, 'fontStyle' => $fontStyle];
  }
  if (isReference($value)) {
    return ['fontWeight' => $value, 'fontStyle' => 'normal'];
  }
  $lc = strtolower($value);
  if (strpos($lc, 'italic') !== false) {
    $fontStyle = 'italic';
  } elseif (strpos($lc, 'oblique') !== false) {
    $fontStyle = 'oblique';
  }
  $weightMap = [
    'thin' => '100',
    'hairline' => '100',
    'extralight' => '200',
    'ultralight' => '200',
    'light' => '300',
    'regular' => '400',
    'normal' => '400',
    'book' => '400',
    'medium' => '500',
    'semibold' => '600',
    'demibold' => '600',
    'bold' => '700',
    'extrabold' => '800',
    'ultrabold' => '800',
    'black' => '900',
    'heavy' => '900',
    'extrablack' => '950',
    'ultrablack' => '950',
  ];
  foreach ($weightMap as $name => $weight) {
    if (preg_match('/\b' . preg_quote($name, '/') . '\b/i', $lc)) {
      $fontWeight = $weight;
      break;
    }
  }
  if (preg_match('/^\d+$/', $value)) {
    $fontWeight = $value;
  }
  return ['fontWeight' => $fontWeight, 'fontStyle' => $fontStyle];
}

