const { isReference, isTailwindReference } = require('./isReference')
const { toKebabCase } = require('./toKebabCase')

/**
 * Extracts the token path from a reference (with or without quotes)
 * @param {string} value - The token reference
 * @returns {string} The clean token path
 */
function extractTokenPath(value) {
  if (typeof value !== 'string') return value

  // Handle quoted references: "{token.path}" -> {token.path}
  if (
    (value.startsWith('"{') && value.endsWith('}"')) ||
    (value.startsWith("'{") && value.endsWith("'}"))
  ) {
    return value.slice(1, -1) // Remove outer quotes
  }

  // Return unquoted references as-is
  return value
}

/**
 * Converts a value (or token reference) to a CSS variable value string.
 *
 * If the value is a token reference (e.g., "{color.primary}"), it will be converted to a CSS variable reference (e.g., var(--color-primary)).
 * Otherwise, the value is returned as a string, optionally wrapped.
 *
 * @param {string} value - The value or token reference to convert.
 * @param {string} [wrap=""] - Optional string to wrap the value (e.g., quotes).
 * @param {string} [prefix=""] - Optional prefix for the CSS variable name.
 * @returns {string} The CSS variable value string.
 */
function referenceToCssVariable(value, wrap = '', prefix = '') {
  if (isReference(value)) {
    if (!isTailwindReference(value)) {
      // Extract the clean token path (remove quotes if present)
      const cleanReference = extractTokenPath(value)

      // Remove the braces from the reference
      const path = cleanReference.slice(1, -1)

      // Convert the path to kebab-case and replace dots with hyphens
      const kebabPath = toKebabCase(path).replace(/\./g, '-')

      return `var(--${prefix}${kebabPath})`
    } else {
      // change it to theme() function
      return `theme(${value.slice(13, -1)})`
    }
  } else {
    return wrap + value + wrap
  }
}

/**
 * Recursively converts all references to CSS variables in an object
 * Properly handles design token structure with $type and $value properties
 *
 * @param {*} obj - The object to convert
 * @param {*} prefix - The prefix for the CSS variable name
 * @returns {Object} The object with all references converted to CSS variables
 */
function allReferencesToCssVariables(obj, prefix = '') {
  if (!obj || typeof obj !== 'object') {
    return obj
  }

  const result = {}

  for (const key in obj) {
    if (obj[key] && typeof obj[key] === 'object') {
      if (obj[key]['$type'] && obj[key]['$value']) {
        // Handle design token with $type and $value structure
        result[key] = {
          $type: obj[key]['$type'],
        }

        if (typeof obj[key]['$value'] === 'object') {
          // Process the $value object recursively
          result[key]['$value'] = {}
          for (const valueKey in obj[key]['$value']) {
            const valueItem = obj[key]['$value'][valueKey]
            if (typeof valueItem === 'string' && isReference(valueItem)) {
              result[key]['$value'][valueKey] = referenceToCssVariable(valueItem, '', '')
            } else {
              result[key]['$value'][valueKey] = valueItem
            }
          }
        } else if (typeof obj[key]['$value'] === 'string' && isReference(obj[key]['$value'])) {
          result[key]['$value'] = referenceToCssVariable(obj[key]['$value'], '', '')
        } else {
          result[key]['$value'] = obj[key]['$value']
        }
      } else {
        // Handle nested objects without token structure
        result[key] = allReferencesToCssVariables(obj[key], prefix)
      }
    } else if (typeof obj[key] === 'string' && isReference(obj[key])) {
      result[key] = referenceToCssVariable(obj[key], '', prefix)
    } else {
      result[key] = obj[key]
    }
  }

  return result
}

module.exports = { referenceToCssVariable, allReferencesToCssVariables }
