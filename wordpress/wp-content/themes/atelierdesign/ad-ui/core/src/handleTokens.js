const fs = require('fs')
const path = require('path')

const { cleanObjectFromTypes } = require('./utils/cleanObjectFromTypes')
const { normalizeFontWeightAndStyle } = require('./utils/normalizeFontWeightAndStyle')
const { allCssValuesNormalized } = require('./utils/normalizeCssValue')
const { allReferencesToCssVariables } = require('./utils/referenceToCssVariable')
const { removeAllPxFromObject } = require('./utils/removePx')
const { removeFalseValues } = require('./utils/removeFalseValues')
const { isConfigReference, isCalcReference } = require('./utils/isReference')

// Get the manifest file
const manifestPath = path.resolve(__dirname, './data/manifest.json')
const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'))

// Check if the manifest file exists
if (!manifest) {
  throw new Error('Could not find the manifest file')
}

let config = null
if (manifest.collections.config) {
  if (Object.keys(manifest.collections.config.modes).length == 1) {
    if (Object.values(manifest.collections.config.modes)[0].length == 1) {
      const configPath = Object.values(manifest.collections.config.modes)[0][0]
      if (configPath) {
        config = JSON.parse(
          fs.readFileSync(path.resolve(__dirname, './data/' + configPath), 'utf8'),
        )

        config = removeAllPxFromObject(cleanObjectFromTypes(config.config))
      }
    }
  }
}

let calc = null
if (manifest.collections.calc) {
  if (Object.keys(manifest.collections.calc.modes).length == 1) {
    if (Object.values(manifest.collections.calc.modes)[0].length == 1) {
      const calcPath = Object.values(manifest.collections.calc.modes)[0][0]
      if (calcPath) {
        calc = JSON.parse(fs.readFileSync(path.resolve(__dirname, './data/' + calcPath), 'utf8'))

        calc = removeAllPxFromObject(cleanObjectFromTypes(calc.calc))
      }
    }
  }
}

let enabledFeatures = null
if (manifest.collections.enabledFeatures) {
  if (Object.keys(manifest.collections.enabledFeatures.modes).length == 1) {
    if (Object.values(manifest.collections.enabledFeatures.modes)[0].length == 1) {
      const enabledFeaturesPath = Object.values(manifest.collections.enabledFeatures.modes)[0][0]
      if (enabledFeaturesPath) {
        enabledFeatures = JSON.parse(
          fs.readFileSync(path.resolve(__dirname, './data/' + enabledFeaturesPath), 'utf8'),
        )

        // go through all the enabledFeatures and if there is a '$type' key, remove it
        // if there is a '$value' key set its parents value to it
        // they can be multiple levels deep
        const removeTypeAndValue = (obj) => {
          for (const key in obj) {
            if (obj[key] && typeof obj[key] === 'object' && !obj[key]['$value']) {
              removeTypeAndValue(obj[key])
            } else if (obj[key] && obj[key]['$value']) {
              obj[key] = obj[key]['$value'] == '{true}' ? true : false
            }
          }
        }

        removeTypeAndValue(enabledFeatures)
      }
    }
  }
}

// Check if there are color primitives, and if there is only one mode of them
let colorPrimitives = false
if (manifest.collections.colorPrimitives) {
  if (Object.keys(manifest.collections.colorPrimitives.modes).length == 1) {
    if (Object.values(manifest.collections.colorPrimitives.modes)[0].length == 1) {
      const colorPrimitivesPath = Object.values(manifest.collections.colorPrimitives.modes)[0][0]
      if (colorPrimitivesPath) {
        colorPrimitives = JSON.parse(
          fs.readFileSync(path.resolve(__dirname, './data/' + colorPrimitivesPath), 'utf8'),
        )

        if (colorPrimitives.unavailable) {
          delete colorPrimitives.unavailable
        }
      }
    }
  }
}

// Check if there are fonts, and if there is only one mode of them
let font = false
if (manifest.collections.fonts) {
  if (Object.keys(manifest.collections.fonts.modes).length == 1) {
    if (Object.values(manifest.collections.fonts.modes)[0].length == 1) {
      const fontsPath = Object.values(manifest.collections.fonts.modes)[0][0]
      if (fontsPath) {
        font = JSON.parse(fs.readFileSync(path.resolve(__dirname, './data/' + fontsPath), 'utf8'))

        font = cleanObjectFromTypes(font.font)
        font = normalizeFontWeightAndStyle(font)
      }
    }
  }
}

// Check if there are responsiveSizing properties
let responsiveSizing = false
if (manifest.collections.responsiveSizing) {
  // Check if there are sm & lg modes
  if (Object.keys(manifest.collections.responsiveSizing.modes).length == 2) {
    const smPath = Object.values(manifest.collections.responsiveSizing.modes.sm)[0]
    const lgPath = Object.values(manifest.collections.responsiveSizing.modes.lg)[0]
    if (smPath && lgPath) {
      const sm = JSON.parse(fs.readFileSync(path.resolve(__dirname, './data/' + smPath), 'utf8'))
      const lg = JSON.parse(fs.readFileSync(path.resolve(__dirname, './data/' + lgPath), 'utf8'))

      responsiveSizing = {
        sm: removeAllPxFromObject(cleanObjectFromTypes(sm)),
        lg: removeAllPxFromObject(cleanObjectFromTypes(lg)),
      }

      // immediately rasterize all units from calc and config values

      /**
       * Recursively resolves config and calc references in an object
       * @param {Object} obj - The object to process
       * @returns {Object} - The object with resolved references
       */
      function resolveUnitsReferences(obj) {
        if (!obj || typeof obj !== 'object') return obj

        const resolved = {}
        for (const [key, value] of Object.entries(obj)) {
          if (typeof value === 'string') {
            if (isConfigReference(value)) {
              const objPath = value.substring(8, value.length - 1) // Remove {config. and }
              const configPath = objPath.split('.')
              let configValue = config
              for (const segment of configPath) {
                if (configValue && typeof configValue === 'object' && segment in configValue) {
                  configValue = configValue[segment]
                } else {
                  configValue = value // Return original if can't resolve
                  break
                }
              }
              resolved[key] = configValue
            } else if (isCalcReference(value)) {
              const objPath = value.substring(6, value.length - 1) // Remove {calc. and }
              const calcPath = objPath.split('.')
              let calcValue = calc
              for (const segment of calcPath) {
                if (calcValue && typeof calcValue === 'object' && segment in calcValue) {
                  calcValue = calcValue[segment]
                } else {
                  calcValue = value // Return original if can't resolve
                  break
                }
              }
              resolved[key] = calcValue
            } else {
              resolved[key] = value
            }
          } else if (typeof value === 'object') {
            resolved[key] = resolveUnitsReferences(value) // Recursive for nested objects
          } else {
            resolved[key] = value
          }
        }
        return resolved
      }

      // Resolve references in units before deleting them
      if (responsiveSizing.sm && responsiveSizing.sm.units) {
        responsiveSizing.sm.units = resolveUnitsReferences(responsiveSizing.sm.units)
      }
      if (responsiveSizing.lg && responsiveSizing.lg.units) {
        responsiveSizing.lg.units = resolveUnitsReferences(responsiveSizing.lg.units)
      }

      /**
       * Resolves all references in the responsiveSizing object using the resolved units
       * @param {Object} obj - The responsive sizing object for a breakpoint
       * @param {Object} units - The resolved units object for this breakpoint
       * @returns {Object} - The object with all references resolved
       */
      function resolveResponsiveSizingReferences(obj, units) {
        if (!obj || typeof obj !== 'object') return obj

        const resolved = {}
        for (const [key, value] of Object.entries(obj)) {
          if (key === 'units') {
            // Skip units object, we'll handle it separately
            resolved[key] = value
            continue
          }

          if (typeof value === 'string') {
            if (value.startsWith('{') && value.endsWith('}')) {
              const reference = value.substring(1, value.length - 1)
              const parts = reference.split('.')

              if (parts[0] === 'units' && units) {
                // Resolve reference to units
                let unitValue = units
                for (let i = 1; i < parts.length; i++) {
                  if (unitValue && typeof unitValue === 'object' && parts[i] in unitValue) {
                    unitValue = unitValue[parts[i]]
                  } else {
                    unitValue = value // Return original if can't resolve
                    break
                  }
                }
                resolved[key] = unitValue
              } else if (isConfigReference(value)) {
                // Resolve config reference
                const objPath = value.substring(8, value.length - 1)
                const configPath = objPath.split('.')
                let configValue = config
                for (const segment of configPath) {
                  if (configValue && typeof configValue === 'object' && segment in configValue) {
                    configValue = configValue[segment]
                  } else {
                    configValue = value
                    break
                  }
                }
                resolved[key] = configValue
              } else if (isCalcReference(value)) {
                // Resolve calc reference
                const objPath = value.substring(6, value.length - 1)
                const calcPath = objPath.split('.')
                let calcValue = calc
                for (const segment of calcPath) {
                  if (calcValue && typeof calcValue === 'object' && segment in calcValue) {
                    calcValue = calcValue[segment]
                  } else {
                    calcValue = value
                    break
                  }
                }
                resolved[key] = calcValue
              } else {
                resolved[key] = value
              }
            } else {
              resolved[key] = value
            }
          } else if (typeof value === 'object') {
            resolved[key] = resolveResponsiveSizingReferences(value, units)
          } else {
            resolved[key] = value
          }
        }
        return resolved
      }

      // Resolve all references in responsiveSizing using the resolved units
      if (responsiveSizing.sm) {
        responsiveSizing.sm = resolveResponsiveSizingReferences(
          responsiveSizing.sm,
          responsiveSizing.sm.units,
        )
      }
      if (responsiveSizing.lg) {
        responsiveSizing.lg = resolveResponsiveSizingReferences(
          responsiveSizing.lg,
          responsiveSizing.lg.units,
        )
      }

      // Now remove the units after all references have been resolved
      delete responsiveSizing.sm.units
      delete responsiveSizing.lg.units
    }
  }
}

// Check if there are text styles
let textStyles = false
if (manifest.styles.text[0]) {
  const textStylesPath = manifest.styles.text[0]
  if (textStylesPath) {
    textStyles = JSON.parse(
      fs.readFileSync(path.resolve(__dirname, './data/' + textStylesPath), 'utf8'),
    )

    textStyles = removeAllPxFromObject(cleanObjectFromTypes(textStyles))
  }
}

// Check if there are tailwindcss properties
let tailwind = false
if (manifest.collections.tailwindcss) {
  if (Object.keys(manifest.collections.tailwindcss.modes).length == 1) {
    if (Object.values(manifest.collections.tailwindcss.modes)[0].length == 1) {
      const tailwindPath = Object.values(manifest.collections.tailwindcss.modes)[0][0]
      if (tailwindPath) {
        tailwind = JSON.parse(
          fs.readFileSync(path.resolve(__dirname, './data/' + tailwindPath), 'utf8'),
        )

        tailwind = cleanObjectFromTypes(tailwind.tailwindcss)
      }
    }
  }
}

// Check if there are colorSystem properties
let colorSystem = false
if (manifest.collections.colorSystem) {
  // Check if there are at least 1 mode
  if (Object.keys(manifest.collections.colorSystem.modes).length >= 1) {
    colorSystem = {}
    Object.keys(manifest.collections.colorSystem.modes).forEach((mode) => {
      let modeName = mode
      const modePath = Object.values(manifest.collections.colorSystem.modes[mode])[0]

      if (modePath) {
        let modeData = JSON.parse(
          fs.readFileSync(path.resolve(__dirname, './data/' + modePath), 'utf8'),
        )

        colorSystem[modeName] = cleanObjectFromTypes(modeData)
      }
    })
    colorSystem = removeFalseValues(colorSystem)
  }
}

/**
 * Retrieves the actual value of a token by following references in the token path
 *
 * @param obj - The token object to search within
 * @param path - Dot notation path to the desired token (e.g., 'font.h1.fontFamily')
 * @param scope - Optional scope for responsive or themed tokens (e.g., 'lg', 'dark')
 * @param resolveTailwindReferences - Whether to resolve TailwindCSS theme references to their actual values instead of returning with theme() (default: false)
 *
 * @returns The resolved value of the token, or undefined if not found
 *
 * @example
 * // Get a font token value
 * const headerFont = getResolvedValue(font, 'h1.fontFamily');
 *
 * // Get a responsive value for a specific breakpoint
 * const mobilePadding = getResolvedValue(responsiveSizing, 'container.padding', 'sm');
 * const buttonPrimaryBg = getResolvedValue(colorSystem, 'button.primary.background', 'dark');
 */
function getResolvedValue(obj, path, scope, resolveTailwindReferences = true) {
  // If a scope is provided and exists as a top-level key, use that as our starting point
  let current = obj
  if (scope && typeof obj === 'object' && obj !== null && scope in obj) {
    current = obj[scope]
  }

  const pathArray = path.split('.')
  for (const p of pathArray) {
    if (typeof current === 'object' && current !== null && p in current) {
      current = current[p]
    } else {
      return undefined
    }
  }

  // Recursively resolve references until we get a non-reference value
  while (typeof current === 'string' && current.startsWith('{') && current.endsWith('}')) {
    // - if value is between { curly braces } it is a reference to another token
    const strValue = current
    const objName = strValue.substring(1, strValue.indexOf('.'))
    const objPath = strValue.substring(strValue.indexOf('.') + 1, strValue.length - 1)

    let resolvedValue
    if (objName === 'font') {
      resolvedValue = getResolvedValue(font, objPath, scope, resolveTailwindReferences)
    } else if (objName === 'primitives') {
      resolvedValue = getResolvedValue(
        colorPrimitives,
        `primitives.${objPath}`,
        undefined,
        resolveTailwindReferences,
      )
    } else if (objName === 'tailwindcss') {
      if (resolveTailwindReferences) {
        const tailwindPath = objPath.split('.')
        let tailwindValue = tailwind
        for (const segment of tailwindPath) {
          if (tailwindValue && typeof tailwindValue === 'object' && segment in tailwindValue) {
            tailwindValue = tailwindValue[segment]
          } else {
            resolvedValue = undefined
            break
          }
        }
        if (tailwindValue !== undefined) {
          resolvedValue = tailwindValue
        }
      } else {
        resolvedValue = `theme('${objPath}')`
      }
    } else if (objName === 'config') {
      const configPath = objPath.split('.')
      let configValue = config
      for (const segment of configPath) {
        if (configValue && typeof configValue === 'object' && segment in configValue) {
          configValue = configValue[segment]
        } else {
          resolvedValue = undefined
          break
        }
      }
      if (configValue !== undefined) {
        resolvedValue = configValue
      }
    } else if (objName === 'calc') {
      const calcPath = objPath.split('.')
      let calcValue = calc
      for (const segment of calcPath) {
        if (calcValue && typeof calcValue === 'object' && segment in calcValue) {
          calcValue = calcValue[segment]
        } else {
          resolvedValue = undefined
          break
        }
      }
      if (calcValue !== undefined) {
        resolvedValue = calcValue
      }
    } else {
      // For other references like {foundations.surface-primary-bg}, resolve within the current scope first
      // Then try responsive sizing and colorSystem
      resolvedValue = getResolvedValue(
        obj,
        `${objName}.${objPath}`,
        scope,
        resolveTailwindReferences,
      )
      if (resolvedValue === undefined) {
        // Try responsiveSizing and colorSystem as fallbacks
        resolvedValue = getResolvedValue(
          responsiveSizing,
          `${objName}.${objPath}`,
          scope,
          resolveTailwindReferences,
        )
        if (resolvedValue === undefined) {
          resolvedValue = getResolvedValue(
            colorSystem,
            `${objName}.${objPath}`,
            scope,
            resolveTailwindReferences,
          )
        }
      }
    }

    if (resolvedValue === undefined) {
      return undefined
    }

    current = resolvedValue
  }

  if (!current) {
    console.warn(
      `\x1b[33m[WARNING]:\x1b[0m getResolvedValue: '${path}' not found in the given object ` +
        (scope ? `with scope '${scope}'` : ''),
    )
  }

  // Clean the result: if it's an object with $type and $value, return just the $value
  if (
    typeof current === 'object' &&
    current !== null &&
    '$type' in current &&
    '$value' in current
  ) {
    return current['$value']
  }

  return current
}

/**
 * Resolves a color token path to its primitive color value by following all references
 *
 * @param {Object} obj - The object to search within
 * @param {string} colorPath - Dot notation path to the color token (e.g., 'accordion.primary.flat.background')
 * @param {string} [mode] - Optional color mode/theme (e.g., 'dark', 'light')
 * @returns {string|undefined} The resolved primitive color value (hex, rgb, etc.) or undefined if not found
 *
 * @example
 * // Resolve a color token to its primitive value
 * const bgColor = resolveColorToPrimitive('accordion.primary.flat.background', 'dark');
 * // Returns something like "#000000" or "rgb(0, 0, 0)"
 */
function resolveColorToPrimitive(obj, colorPath, mode) {
  if (!obj) {
    // console.warn('[WARNING]: colorSystem is not available')
    return undefined
  }

  // Get the color token value using getResolvedValue (now returns clean values)
  const resolvedValue = getResolvedValue(obj, colorPath, mode, true)

  if (!resolvedValue) {
    // console.warn(
    //   `[WARNING]: Color token '${colorPath}' not found` + (mode ? ` in mode '${mode}'` : ''),
    // )
    return undefined
  }

  // If it's still a reference (shouldn't happen with the fixed getResolvedValue), log warning
  if (typeof resolvedValue === 'string' && resolvedValue.startsWith('{')) {
    // console.warn(`[WARNING]: Unresolved reference '${resolvedValue}' for color path '${colorPath}'`)
    return resolvedValue
  }

  // getResolvedValue now returns clean values, so we can return directly
  return resolvedValue
}

/**
 * Finds which color theme/mode contains a specific color value in layout tokens
 *
 * @param {Object} obj - The object to search within
 * @param {string} colorValue - The primitive color value to search for (hex, rgb, etc.)
 * @returns {string} The theme/mode name that contains the color, or error message if not found
 *
 * @example
 * // Find which theme contains a specific color
 * const primaryColor = resolveColorToPrimitive('accordion.primary.flat.background', 'dark');
 * const themeName = findThemeByColorValue(primaryColor);
 * // Returns 'dark' or 'light' etc.
 */
function findThemeByColorValue(obj, colorValue) {
  if (!obj || !colorValue) {
    return 'Error: Invalid input parameters'
  }

  // Loop through all color system modes
  for (const [themeName, themeData] of Object.entries(obj)) {
    if (!themeData || typeof themeData !== 'object') {
      continue
    }

    // Check if this theme has layout tokens
    if (themeData.layout && typeof themeData.layout === 'object') {
      // Recursively search through all layout tokens
      const searchInObject = (searchObj, currentPath = '') => {
        for (const [key, value] of Object.entries(searchObj)) {
          const fullPath = currentPath ? `${currentPath}.${key}` : key

          if (typeof value === 'object' && value !== null) {
            // If it's an object, search recursively
            const result = searchInObject(value, fullPath)
            if (result) return result
          } else {
            // If it's a value, resolve it and compare
            const resolvedColor = resolveColorToPrimitive(obj, `layout.${fullPath}`, themeName)
            if (resolvedColor === colorValue) {
              return themeName
            }
          }
        }
        return null
      }

      const foundTheme = searchInObject(themeData.layout)
      if (foundTheme) {
        return foundTheme
      }
    }
  }

  // return 'Color not found in any theme layout'
  return undefined
}

// Export the tokens object
const tokens = {
  enabledFeatures: allCssValuesNormalized(enabledFeatures),
  colorPrimitives: allCssValuesNormalized(colorPrimitives),
  font: allCssValuesNormalized(font),
  responsiveSizing: allCssValuesNormalized(responsiveSizing),
  textStyles: allCssValuesNormalized(textStyles),
  colorSystem: allCssValuesNormalized(colorSystem),
}

module.exports = {
  config,
  calc,
  tailwind,
  tokens,
  getResolvedValue,
  resolveColorToPrimitive,
  findThemeByColorValue,
}
