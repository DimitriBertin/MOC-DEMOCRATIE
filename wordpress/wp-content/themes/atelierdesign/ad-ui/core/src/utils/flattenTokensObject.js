const { toKebabCase } = require('./toKebabCase')
const {
  isReference,
  isTailwindReference,
  isConfigReference,
  isCalcReference,
} = require('./isReference')
const { config, calc, getResolvedValue } = require('../handleTokens')

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
 * Recursively flattens a nested token object into a single-level object with concatenated keys.
 *
 * This function is typically used to process design token objects where leaf nodes contain a `$value` property.
 * The resulting object will have keys representing the path to each `$value`, joined by dashes, and values as the corresponding `$value`.
 * Token references (e.g., "{color.primary}") are automatically converted to CSS variable references.
 *
 * @param {Object} data - The nested token object to flatten. Each leaf node should have a `$value` property.
 * @param {string} [prefix=""] - The prefix to prepend to each key (used internally for recursion).
 * @returns {Object} A flattened object with concatenated keys and their corresponding `$value` values.
 *
 * @example
 * const tokens = {
 *   color: {
 *     primary: { $value: '#ff0000' },
 *     secondary: { $value: '#00ff00' },
 *     accent: {
 *       light: { $value: '#cccccc' },
 *       dark: { $value: '#333333' }
 *     }
 *   }
 * };
 *
 * // Returns:
 * // {
 * //   'color-primary': '#ff0000',
 * //   'color-secondary': '#00ff00',
 * //   'color-accent-light': '#cccccc',
 * //   'color-accent-dark': '#333333'
 * // }
 * flattenTokensObject(tokens);
 */
function flattenTokensObject(data, prefix = '', varPrefix = null) {
  const variablePrefix = varPrefix !== null ? varPrefix : prefix
  let flattenedData = {}

  for (const key in data) {
    if (data[key] && typeof data[key] === 'object') {
      if (data[key]['$value']) {
        // Handle original token format with $value
        const value = data[key]['$value']
        // Convert token references to CSS variables using the current prefix context
        if (isReference(value)) {
          flattenedData[`${prefix}${toKebabCase(key)}`] = convertTokenReference(
            value,
            variablePrefix,
          )
        } else {
          flattenedData[`${prefix}${toKebabCase(key)}`] = value
        }
      } else {
        // Handle nested objects (recursively flatten)
        flattenedData = {
          ...flattenedData,
          ...flattenTokensObject(data[key], `${prefix}${toKebabCase(key)}-`, variablePrefix),
        }
      }
    } else if (data[key] !== null && data[key] !== undefined) {
      // Handle plain values (strings, numbers, etc.)
      const value = data[key]
      // Convert token references to CSS variables using the current prefix context
      if (isReference(value)) {
        flattenedData[`${prefix}${toKebabCase(key)}`] = convertTokenReference(value, variablePrefix)
      } else {
        flattenedData[`${prefix}${toKebabCase(key)}`] = value
      }
    }
  }

  return flattenedData
}

/**
 * Converts a token reference to a CSS variable with the appropriate prefix
 * @param {string} reference - The token reference (e.g., "{color.primary}" or '"{color.primary}"')
 * @returns {string} CSS variable reference (e.g., "var(--color-primary)")
 */
function convertTokenReference(reference, variablePrefix = '--') {
  if (isTailwindReference(reference)) {
    const themeFnValue = extractTokenPath(reference).slice(13, -1)
    return `theme(${themeFnValue})`
  }

  if (isConfigReference(reference)) {
    const configFnValue = extractTokenPath(reference).slice(8, -1)
    return getResolvedValue(config, configFnValue)
  }

  if (isCalcReference(reference)) {
    const calcFnValue = extractTokenPath(reference).slice(6, -1)
    return getResolvedValue(calc, calcFnValue)
  }

  // Extract the clean token path (remove quotes if present)
  const cleanReference = extractTokenPath(reference)

  // Remove the braces from the reference
  const path = cleanReference.slice(1, -1)

  // Convert the path to kebab-case and replace dots with hyphens
  const kebabPath = toKebabCase(path).replace(/\./g, '-')

  // Build variable name based on the first segment
  const firstPart = path.split('.')[0]
  const colorGroups = new Set([
    'primitives',
    'foundations',
    'layout',
    'typography',
    'keyNumbers',
    'label',
    'button',
    'accordeon',
    'group',
    'quote',
    'form',
    'separator',
  ])

  // Use provided variablePrefix, stripping leading dashes to avoid duplication
  const vp = variablePrefix.replace(/^--/, '')
  const suffix = kebabPath.replace(/^(primitives-|font-)/, '')
  const name = vp ? `--${vp}${suffix}` : `--${suffix}`
  return `var(${name})`
}

module.exports = { flattenTokensObject }
