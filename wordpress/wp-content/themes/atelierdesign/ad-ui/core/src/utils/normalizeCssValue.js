/**
 * Normalizes CSS values by determining if they should be wrapped in quotes
 * @param {string} value - The CSS value to normalize
 * @returns {string} - The normalized value, with or without quotes
 */
function normalizeCssValue(value) {
  if (typeof value !== 'string') {
    return value
  }

  // Trim whitespace
  const trimmedValue = value.trim()

  // If empty, return as-is
  if (!trimmedValue) {
    return trimmedValue
  }

  // Common CSS keywords that don't need quotes
  const cssKeywords = new Set([
    // Generic font families
    'serif',
    'sans-serif',
    'monospace',
    'cursive',
    'fantasy',
    'system-ui',

    // Font style values
    'normal',
    'italic',
    'oblique',

    // Font weight values
    'bold',
    'bolder',
    'lighter',

    // Font variant values
    'small-caps',

    // Display values
    'block',
    'inline',
    'inline-block',
    'flex',
    'inline-flex',
    'grid',
    'inline-grid',
    'table',
    'table-row',
    'table-cell',
    'list-item',
    'none',

    // Position values
    'static',
    'relative',
    'absolute',
    'fixed',
    'sticky',

    // Text align values
    'left',
    'right',
    'center',
    'justify',

    // Vertical align values
    'baseline',
    'top',
    'middle',
    'bottom',
    'text-top',
    'text-bottom',

    // Float values
    'left',
    'right',
    'none',

    // Clear values
    'left',
    'right',
    'both',
    'none',

    // Overflow values
    'visible',
    'hidden',
    'scroll',
    'auto',

    // White space values
    'normal',
    'nowrap',
    'pre',
    'pre-wrap',
    'pre-line',

    // Text transform values
    'none',
    'capitalize',
    'uppercase',
    'lowercase',

    // Text decoration values
    'none',
    'underline',
    'overline',
    'line-through',

    // Cursor values
    'auto',
    'default',
    'pointer',
    'crosshair',
    'move',
    'text',
    'wait',
    'help',

    // Box sizing values
    'content-box',
    'border-box',

    // Common color keywords
    'transparent',
    'currentColor',
    'inherit',
    'initial',
    'unset',
    'revert',

    // Background repeat values
    'repeat',
    'repeat-x',
    'repeat-y',
    'no-repeat',

    // Background size values
    'auto',
    'cover',
    'contain',

    // Border style values
    'none',
    'solid',
    'dashed',
    'dotted',
    'double',
    'groove',
    'ridge',
    'inset',
    'outset',

    // List style type values
    'disc',
    'circle',
    'square',
    'decimal',
    'none',

    // CSS units (these typically come with numbers, but can be standalone)
    'auto',
    'inherit',
    'initial',
    'unset',
    'revert',
  ])

  // Check if it's a CSS keyword (case-insensitive)
  if (cssKeywords.has(trimmedValue.toLowerCase())) {
    return trimmedValue
  }

  // Check if it's a CSS function (e.g., calc(), var(), etc.)
  if (/^[a-zA-Z-]+\(.*\)$/.test(trimmedValue)) {
    return trimmedValue
  }

  // Check if it's a number with or without units
  if (
    /^-?\d*\.?\d+(px|em|rem|%|vh|vw|pt|pc|in|cm|mm|ex|ch|vmin|vmax|fr|deg|rad|turn|s|ms)?$/.test(
      trimmedValue,
    )
  ) {
    return trimmedValue
  }

  // Check if it's a hex color
  if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$/.test(trimmedValue)) {
    return trimmedValue
  }

  // Check if it's a token reference (should not be quoted)
  if (trimmedValue.startsWith('{') && trimmedValue.endsWith('}')) {
    return trimmedValue
  }

  // Check if it's already quoted
  if (
    (trimmedValue.startsWith('"') && trimmedValue.endsWith('"')) ||
    (trimmedValue.startsWith("'") && trimmedValue.endsWith("'"))
  ) {
    return trimmedValue
  }

  // Check if it contains spaces or special characters that require quotes
  // Custom font names with spaces, special characters, etc. need quotes
  if (/[\s,;:()[\]{}!@#$%^&*+=|\\<>?/~`]/.test(trimmedValue)) {
    return `"${trimmedValue}"`
  }

  // Single word custom values (like custom font names without spaces)
  // If it's not a recognized CSS keyword and doesn't contain special chars,
  // it's likely a custom font name or custom value that should be quoted
  // For font families, if it's not a generic family, it should be quoted
  return `"${trimmedValue}"`
}

/**
 * Recursively normalizes all CSS values in an object
 *
 * @param {*} obj - The object to normalize
 * @returns {Object} The object with all CSS values normalized
 */
function allCssValuesNormalized(obj) {
  // Handle null or undefined
  if (obj === null || obj === undefined) {
    return obj
  }

  // Handle arrays
  if (Array.isArray(obj)) {
    return obj.map((item) => allCssValuesNormalized(item))
  }

  // Handle objects
  if (typeof obj === 'object') {
    const normalizedObj = {}
    for (const key in obj) {
      if (obj.hasOwnProperty(key)) {
        normalizedObj[key] = allCssValuesNormalized(obj[key])
      }
    }
    return normalizedObj
  }

  // Handle strings - apply normalization
  if (typeof obj === 'string') {
    return normalizeCssValue(obj)
  }

  // Return other types as-is (numbers, booleans, etc.)
  return obj
}

module.exports = {
  normalizeCssValue,
  allCssValuesNormalized,
}
