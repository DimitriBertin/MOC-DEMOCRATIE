<?php

// Utilities
require_once __DIR__ . '/utils/cleanObjectFromTypes.php';
require_once __DIR__ . '/utils/normalizeFontWeightAndStyle.php';
require_once __DIR__ . '/utils/normalizeCssValue.php';
require_once __DIR__ . '/utils/referenceToCssVariable.php';
require_once __DIR__ . '/utils/removePx.php';
require_once __DIR__ . '/utils/removeFalseValues.php';
require_once __DIR__ . '/utils/isReference.php';

// Shared globals for config/calc so other utils (e.g. vw.php) can access them
global $adui_config, $adui_calc, $adui_tokens, $adui_tailwind;

$adui_config = null;
$adui_calc = null;
$adui_tokens = null;

$baseDir = __DIR__;
// Point to core data directory from the wp module; fallback to local data if not found
$dataDir = realpath($baseDir . '/../core/src/data') ?: ($baseDir . '/data');
$manifestPath = $dataDir . '/manifest.json';

function adui_read_json($path)
{
  if (!file_exists($path)) return null;
  $raw = file_get_contents($path);
  if ($raw === false) return null;
  $json = json_decode($raw, true);
  return $json;
}

$manifest = adui_read_json($manifestPath);
if (!$manifest) {
  throw new Exception('Could not find the manifest file at ' . $manifestPath);
}

// Load config
if (isset($manifest['collections']['config'])) {
  $m = $manifest['collections']['config']['modes'] ?? [];
  if (count($m) === 1) {
    $first = array_values($m)[0] ?? [];
    if (count($first) === 1) {
      $configPath = $first[0];
      if ($configPath) {
        $cfgJson = adui_read_json($dataDir . '/' . $configPath);
        if ($cfgJson && isset($cfgJson['config'])) {
          $adui_config = removeAllPxFromObject(cleanObjectFromTypes($cfgJson['config']));
        }
      }
    }
  }
}

// Load calc
if (isset($manifest['collections']['calc'])) {
  $m = $manifest['collections']['calc']['modes'] ?? [];
  if (count($m) === 1) {
    $first = array_values($m)[0] ?? [];
    if (count($first) === 1) {
      $calcPath = $first[0];
      if ($calcPath) {
        $calcJson = adui_read_json($dataDir . '/' . $calcPath);
        if ($calcJson && isset($calcJson['calc'])) {
          $adui_calc = removeAllPxFromObject(cleanObjectFromTypes($calcJson['calc']));
        }
      }
    }
  }
}

// enabledFeatures
$enabledFeatures = null;
if (isset($manifest['collections']['enabledFeatures'])) {
  $m = $manifest['collections']['enabledFeatures']['modes'] ?? [];
  if (count($m) === 1) {
    $first = array_values($m)[0] ?? [];
    if (count($first) === 1) {
      $enabledFeaturesPath = $first[0];
      if ($enabledFeaturesPath) {
        $json = adui_read_json($dataDir . '/' . $enabledFeaturesPath);
        if ($json) {
          // remove $type and convert $value to boolean based on {true}/{false}
          $enabledFeatures = $json;
          $removeTypeAndValue = function (&$obj) use (&$removeTypeAndValue) {
            if (!is_array($obj)) return;
            foreach ($obj as $key => &$value) {
              if (is_array($value) && !isset($value['$value'])) {
                $removeTypeAndValue($value);
              } elseif (is_array($value) && isset($value['$value'])) {
                $obj[$key] = ($value['$value'] === '{true}') ? true : false;
              }
            }
          };
          $removeTypeAndValue($enabledFeatures);
        }
      }
    }
  }
}

// color primitives
$colorPrimitives = false;
if (isset($manifest['collections']['colorPrimitives'])) {
  $m = $manifest['collections']['colorPrimitives']['modes'] ?? [];
  if (count($m) === 1) {
    $first = array_values($m)[0] ?? [];
    if (count($first) === 1) {
      $path = $first[0];
      if ($path) {
        $colorPrimitives = adui_read_json($dataDir . '/' . $path);
        if (is_array($colorPrimitives) && isset($colorPrimitives['unavailable'])) {
          unset($colorPrimitives['unavailable']);
        }

        $colorPrimitives = cleanObjectFromTypes($colorPrimitives);
      }
    }
  }
}

// tailwindcss
$adui_tailwind = false;
if (isset($manifest['collections']['tailwindcss'])) {
  $m = $manifest['collections']['tailwindcss']['modes'] ?? [];
  if (count($m) === 1) {
    $first = array_values($m)[0] ?? [];
    if (count($first) === 1) {
      $path = $first[0];
      if ($path) {
        $twJson = adui_read_json($dataDir . '/' . $path);
        if ($twJson && isset($twJson['tailwindcss'])) {
          $adui_tailwind = cleanObjectFromTypes($twJson['tailwindcss']);
        }
      }
    }
  }
}

// fonts
$font = false;
if (isset($manifest['collections']['fonts'])) {
  $m = $manifest['collections']['fonts']['modes'] ?? [];
  if (count($m) === 1) {
    $first = array_values($m)[0] ?? [];
    if (count($first) === 1) {
      $fontsPath = $first[0];
      if ($fontsPath) {
        $fontJson = adui_read_json($dataDir . '/' . $fontsPath);
        if ($fontJson && isset($fontJson['font'])) {
          $font = cleanObjectFromTypes($fontJson['font']);
          $font = normalizeFontWeightAndStyle($font);
        }
      }
    }
  }
}

// responsiveSizing
$responsiveSizing = false;
if (isset($manifest['collections']['responsiveSizing'])) {
  $modes = $manifest['collections']['responsiveSizing']['modes'] ?? [];
  if (count($modes) === 2 && isset($modes['sm']) && isset($modes['lg'])) {
    $smPath = array_values($modes['sm'])[0] ?? null;
    $lgPath = array_values($modes['lg'])[0] ?? null;
    if ($smPath && $lgPath) {
      $sm = adui_read_json($dataDir . '/' . $smPath);
      $lg = adui_read_json($dataDir . '/' . $lgPath);
      if ($sm && $lg) {
        $responsiveSizing = [
          'sm' => removeAllPxFromObject(cleanObjectFromTypes($sm)),
          'lg' => removeAllPxFromObject(cleanObjectFromTypes($lg)),
        ];

        // Resolve config/calc references in units
        $resolveUnitsReferences = function ($obj) use (&$resolveUnitsReferences, &$adui_config, &$adui_calc) {
          if (!$obj || !is_array($obj)) return $obj;
          $resolved = [];
          foreach ($obj as $key => $value) {
            if (is_string($value)) {
              if (isConfigReference($value)) {
                $objPath = substr($value, 8, -1);
                $pathSegments = explode('.', $objPath);
                $configValue = $adui_config;
                foreach ($pathSegments as $segment) {
                  if (is_array($configValue) && array_key_exists($segment, $configValue)) {
                    $configValue = $configValue[$segment];
                  } else {
                    $configValue = $value;
                    break;
                  }
                }
                $resolved[$key] = $configValue;
              } elseif (isCalcReference($value)) {
                $objPath = substr($value, 6, -1);
                $pathSegments = explode('.', $objPath);
                $calcValue = $adui_calc;
                foreach ($pathSegments as $segment) {
                  if (is_array($calcValue) && array_key_exists($segment, $calcValue)) {
                    $calcValue = $calcValue[$segment];
                  } else {
                    $calcValue = $value;
                    break;
                  }
                }
                $resolved[$key] = $calcValue;
              } else {
                $resolved[$key] = $value;
              }
            } elseif (is_array($value)) {
              $resolved[$key] = $resolveUnitsReferences($value);
            } else {
              $resolved[$key] = $value;
            }
          }
          return $resolved;
        };

        if (isset($responsiveSizing['sm']['units'])) {
          $responsiveSizing['sm']['units'] = $resolveUnitsReferences($responsiveSizing['sm']['units']);
        }
        if (isset($responsiveSizing['lg']['units'])) {
          $responsiveSizing['lg']['units'] = $resolveUnitsReferences($responsiveSizing['lg']['units']);
        }

        // Resolve all references in responsiveSizing using the resolved units
        $resolveResponsiveSizingReferences = function ($obj, $units) use (&$resolveResponsiveSizingReferences, &$adui_config, &$adui_calc) {
          if (!$obj || !is_array($obj)) return $obj;
          $resolved = [];
          foreach ($obj as $key => $value) {
            if ($key === 'units') {
              $resolved[$key] = $value;
              continue;
            }
            if (is_string($value)) {
              if (substr($value, 0, 1) === '{' && substr($value, -1) === '}') {
                $reference = substr($value, 1, -1);
                $parts = explode('.', $reference);
                if ($parts[0] === 'units' && $units) {
                  $unitValue = $units;
                  for ($i = 1; $i < count($parts); $i++) {
                    $seg = $parts[$i];
                    if (is_array($unitValue) && array_key_exists($seg, $unitValue)) {
                      $unitValue = $unitValue[$seg];
                    } else {
                      $unitValue = $value;
                      break;
                    }
                  }
                  $resolved[$key] = $unitValue;
                } elseif (isConfigReference($value)) {
                  $objPath = substr($value, 8, -1);
                  $pathSegments = explode('.', $objPath);
                  $configValue = $adui_config;
                  foreach ($pathSegments as $segment) {
                    if (is_array($configValue) && array_key_exists($segment, $configValue)) {
                      $configValue = $configValue[$segment];
                    } else {
                      $configValue = $value;
                      break;
                    }
                  }
                  $resolved[$key] = $configValue;
                } elseif (isCalcReference($value)) {
                  $objPath = substr($value, 6, -1);
                  $pathSegments = explode('.', $objPath);
                  $calcValue = $adui_calc;
                  foreach ($pathSegments as $segment) {
                    if (is_array($calcValue) && array_key_exists($segment, $calcValue)) {
                      $calcValue = $calcValue[$segment];
                    } else {
                      $calcValue = $value;
                      break;
                    }
                  }
                  $resolved[$key] = $calcValue;
                } else {
                  $resolved[$key] = $value;
                }
              } else {
                $resolved[$key] = $value;
              }
            } elseif (is_array($value)) {
              $resolved[$key] = $resolveResponsiveSizingReferences($value, $units);
            } else {
              $resolved[$key] = $value;
            }
          }
          return $resolved;
        };

        if (isset($responsiveSizing['sm'])) {
          $responsiveSizing['sm'] = $resolveResponsiveSizingReferences($responsiveSizing['sm'], $responsiveSizing['sm']['units'] ?? null);
        }
        if (isset($responsiveSizing['lg'])) {
          $responsiveSizing['lg'] = $resolveResponsiveSizingReferences($responsiveSizing['lg'], $responsiveSizing['lg']['units'] ?? null);
        }

        // remove units after resolving
        if (isset($responsiveSizing['sm']['units'])) unset($responsiveSizing['sm']['units']);
        if (isset($responsiveSizing['lg']['units'])) unset($responsiveSizing['lg']['units']);
      }
    }
  }
}

// textStyles
$textStyles = false;
if (isset($manifest['styles']['text'][0])) {
  $textStylesPath = $manifest['styles']['text'][0];
  if ($textStylesPath) {
    $textJson = adui_read_json($dataDir . '/' . $textStylesPath);
    if ($textJson) {
      $textStyles = removeAllPxFromObject(cleanObjectFromTypes($textJson));
    }
  }
}

// colorSystem
$colorSystem = false;
if (isset($manifest['collections']['colorSystem'])) {
  $modes = $manifest['collections']['colorSystem']['modes'] ?? [];
  if (count($modes) >= 1) {
    $colorSystem = [];
    foreach ($modes as $mode => $entries) {
      $modeName = $mode;
      $modePath = array_values($entries)[0] ?? null;
      if ($modePath) {
        $modeData = adui_read_json($dataDir . '/' . $modePath);
        if ($modeData) {
          $colorSystem[$modeName] = cleanObjectFromTypes($modeData);
        }
      }
    }
    $colorSystem = removeFalseValues($colorSystem);
  }
}

/**
 * Retrieves the actual value of a token by following references in the token path
 * @param mixed $obj
 * @param string $path
 * @param string|null $scope
 * @param bool $resolveTailwindReferences
 * @return mixed
 */
function getResolvedValue($obj, $path, $scope = null, $resolveTailwindReferences = true)
{
  global $adui_config, $adui_calc, $adui_tailwind, $responsiveSizing, $colorSystem, $font, $colorPrimitives;

  // If a scope is provided and exists as a top-level key, use that as our starting point
  $current = $obj;
  if ($scope && is_array($obj) && array_key_exists($scope, $obj)) {
    $current = $obj[$scope];
  }

  $pathArray = explode('.', $path);
  foreach ($pathArray as $p) {
    if (is_array($current) && array_key_exists($p, $current)) {
      $current = $current[$p];
    } else {
      return null;
    }
  }


  if (is_string($current)) {
    $strValue = $current;
    if (isReference($strValue)) {
      $cleanRef = extractTokenPath($strValue);
      $objName = substr($cleanRef, 1, strpos($cleanRef, '.') - 1);
      $objPath = substr($cleanRef, strpos($cleanRef, '.') + 1, -1);
      if ($objName === 'font') {
        return getResolvedValue($font, $objPath, $scope, $resolveTailwindReferences);
      } elseif ($objName === 'primitives') {
        return getResolvedValue($colorPrimitives, 'primitives.' . $objPath, null, $resolveTailwindReferences);
      } elseif ($objName === 'tailwindcss') {
        if ($resolveTailwindReferences) {
          $tailwindPath = explode('.', $objPath);
          $tailwindValue = $adui_tailwind;
          foreach ($tailwindPath as $segment) {
            if (is_array($tailwindValue) && array_key_exists($segment, $tailwindValue)) {
              $tailwindValue = $tailwindValue[$segment];
            } else {
              return null;
            }
          }
          return $tailwindValue;
        } else {
          return "theme('" . $objPath . "')";
        }
      } elseif ($objName === 'config') {
        $configPath = explode('.', $objPath);
        $configValue = $adui_config;
        foreach ($configPath as $segment) {
          if (is_array($configValue) && array_key_exists($segment, $configValue)) {
            $configValue = $configValue[$segment];
          } else {
            return null;
          }
        }
        return $configValue;
      } elseif ($objName === 'calc') {
        $calcPath = explode('.', $objPath);
        $calcValue = $adui_calc;
        foreach ($calcPath as $segment) {
          if (is_array($calcValue) && array_key_exists($segment, $calcValue)) {
            $calcValue = $calcValue[$segment];
          } else {
            return null;
          }
        }
        return $calcValue;
      } else {
        // Try responsiveSizing then colorSystem for other references
        $resolved = getResolvedValue($responsiveSizing, $objName . '.' . $objPath, $scope, $resolveTailwindReferences);
        if ($resolved !== null) return $resolved;
        return getResolvedValue($colorSystem, $objName . '.' . $objPath, $scope, $resolveTailwindReferences);
      }
    }
  }

  // Clean the result: if it's an object with $type and $value, return just the resolved $value
  if (is_array($current) && array_key_exists('$type', $current) && array_key_exists('$value', $current)) {
    $val = $current['$value'];
    if (is_string($val) && isReference($val)) {
      $cleanRef = extractTokenPath($val);
      $objName = substr($cleanRef, 1, strpos($cleanRef, '.') - 1);
      $objPath = substr($cleanRef, strpos($cleanRef, '.') + 1, -1);
      if ($objName === 'font') {
        return getResolvedValue($font, $objPath, $scope, $resolveTailwindReferences);
      } elseif ($objName === 'primitives') {
        return getResolvedValue($colorPrimitives, 'primitives.' . $objPath, null, $resolveTailwindReferences);
      } elseif ($objName === 'tailwindcss') {
        if ($resolveTailwindReferences) {
          $tailwindPath = explode('.', $objPath);
          $tailwindValue = $adui_tailwind;
          foreach ($tailwindPath as $segment) {
            if (is_array($tailwindValue) && array_key_exists($segment, $tailwindValue)) {
              $tailwindValue = $tailwindValue[$segment];
            } else {
              return null;
            }
          }
          return $tailwindValue;
        } else {
          return "theme('" . $objPath . "')";
        }
      } elseif ($objName === 'config') {
        $configPath = explode('.', $objPath);
        $configValue = $adui_config;
        foreach ($configPath as $segment) {
          if (is_array($configValue) && array_key_exists($segment, $configValue)) {
            $configValue = $configValue[$segment];
          } else {
            return null;
          }
        }
        return $configValue;
      } elseif ($objName === 'calc') {
        $calcPath = explode('.', $objPath);
        $calcValue = $adui_calc;
        foreach ($calcPath as $segment) {
          if (is_array($calcValue) && array_key_exists($segment, $calcValue)) {
            $calcValue = $calcValue[$segment];
          } else {
            return null;
          }
        }
        return $calcValue;
      } else {
        $resolved = getResolvedValue($responsiveSizing, $objName . '.' . $objPath, $scope, $resolveTailwindReferences);
        if ($resolved !== null) return $resolved;
        return getResolvedValue($colorSystem, $objName . '.' . $objPath, $scope, $resolveTailwindReferences);
      }
    }
    return $val;
  }

  return $current;
}

/**
 * Resolves a color token path to its primitive color value by following all references
 * @param mixed $obj
 * @param string $colorPath
 * @param string|null $mode
 * @return mixed
 */
function resolveColorToPrimitive($obj, $colorPath, $mode = null)
{
  if (!$obj) return null;
  $resolvedValue = getResolvedValue($obj, $colorPath, $mode, true);
  if (!$resolvedValue) return null;
  if (is_string($resolvedValue) && substr($resolvedValue, 0, 1) === '{') {
    return $resolvedValue;
  }
  return $resolvedValue;
}

/**
 * Finds which color theme/mode contains a specific color value in layout tokens
 * @param mixed $obj
 * @param string $colorValue
 * @return string|null
 */
function findThemeByColorValue($obj, $colorValue)
{
  if (!$obj || !$colorValue) return null;
  foreach ($obj as $themeName => $themeData) {
    if (!is_array($themeData)) continue;
    if (isset($themeData['layout']) && is_array($themeData['layout'])) {
      $stack = [['obj' => $themeData['layout'], 'path' => '']];
      while ($stack) {
        $node = array_pop($stack);
        $searchObj = $node['obj'];
        $currentPath = $node['path'];
        foreach ($searchObj as $key => $value) {
          $fullPath = $currentPath ? ($currentPath . '.' . $key) : $key;
          if (is_array($value)) {
            $stack[] = ['obj' => $value, 'path' => $fullPath];
          } else {
            $resolvedColor = resolveColorToPrimitive($obj, 'layout.' . $fullPath, $themeName);
            if ($resolvedColor === $colorValue) {
              return $themeName;
            }
          }
        }
      }
    }
  }
  return null;
}

// Export tokens (normalized CSS values for safer consumption)
global $adui_tokens;
$adui_tokens = [
  'enabledFeatures' => allCssValuesNormalized($enabledFeatures),
  'colorPrimitives' => allCssValuesNormalized($colorPrimitives),
  'font' => allCssValuesNormalized($font),
  'responsiveSizing' => allCssValuesNormalized($responsiveSizing),
  'textStyles' => allCssValuesNormalized($textStyles),
  'colorSystem' => allCssValuesNormalized($colorSystem),
];

// Also return an array for direct include usage (optional)
return [
  'config' => $adui_config,
  'calc' => $adui_calc,
  'tokens' => $adui_tokens,
  'getResolvedValue' => 'getResolvedValue',
  'resolveColorToPrimitive' => 'resolveColorToPrimitive',
  'findThemeByColorValue' => 'findThemeByColorValue',
];
