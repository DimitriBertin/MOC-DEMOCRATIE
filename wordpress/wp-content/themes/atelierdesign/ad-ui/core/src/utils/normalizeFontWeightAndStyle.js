const { isReference } = require('./isReference')

/**
 * Normalizes font weight and style properties in a font object or individual string values
 *
 * @param value - Font object or font weight/style string
 * @returns Processed object with normalized font weights and styles, or normalized weight/style object for strings
 */
function normalizeFontWeightAndStyle(value) {
  // If it's an object, recursively process all its properties
  if (typeof value === 'object' && value !== null) {
    const result = {}

    for (const [key, val] of Object.entries(value)) {
      if (typeof val === 'object' && val !== null) {
        // Recursively process nested objects
        result[key] = normalizeFontWeightAndStyle(val)
      } else if (typeof val === 'string' && shouldNormalizeProperty(key)) {
        // Normalize font weight properties and generate corresponding style properties
        const normalized = normalizeFontWeightAndStylePair(val)
        result[key] = normalized.fontWeight

        // Generate corresponding font style property with proper camelCase
        const styleKey = key.replace(/Weight/g, 'Style').replace(/weight/g, 'Style')
        result[styleKey] = normalized.fontStyle
      } else {
        // Keep other properties as-is
        result[key] = val
      }
    }

    return result
  }

  // If it's a string, return the legacy format for backward compatibility
  if (typeof value === 'string') {
    return normalizeFontWeightAndStyleLegacy(value)
  }

  // Default fallback
  return value
}

/**
 * Determines if a property should be normalized based on its key name
 */
function shouldNormalizeProperty(key) {
  const lowerKey = key.toLowerCase()
  return lowerKey.includes('weight')
}

/**
 * Normalizes a font weight string and determines the corresponding font style
 */
function normalizeFontWeightAndStylePair(value) {
  if (typeof value !== 'string') {
    return { fontWeight: value, fontStyle: 'normal' }
  }

  // Check if this is a token reference (e.g., "{font.h1.fontWeight}")
  if (isReference(value)) {
    // This is a token reference, preserve it as is
    return { fontWeight: value, fontStyle: 'normal' }
  }

  // Lowercase for consistent processing
  const lcValue = value.toLowerCase()

  // Default values
  let fontWeight = '400'
  let fontStyle = 'normal'

  // Check for italic or oblique style
  if (lcValue.includes('italic')) {
    fontStyle = 'italic'
  } else if (lcValue.includes('oblique')) {
    fontStyle = 'oblique'
  }

  // Map common weight names to numeric values
  const weightMap = {
    thin: '100',
    hairline: '100',
    extralight: '200',
    ultralight: '200',
    light: '300',
    regular: '400',
    normal: '400',
    book: '400',
    medium: '500',
    semibold: '600',
    demibold: '600',
    bold: '700',
    extrabold: '800',
    ultrabold: '800',
    black: '900',
    heavy: '900',
    extrablack: '950',
    ultrablack: '950',
  }

  // Try to match known weight names
  for (const [name, weight] of Object.entries(weightMap)) {
    // Check for exact match or word boundary to avoid partial matches
    const regex = new RegExp(`\\b${name}\\b`, 'i')
    if (regex.test(lcValue)) {
      fontWeight = weight
      break
    }
  }

  // If it's already a numeric value (e.g., "700"), use that
  if (/^\d+$/.test(value)) {
    fontWeight = value
  } else {
    // Check if the value contains only style information (like "italic" or "oblique") without weight
    const valueWithoutStyle = lcValue.replace(/\s*(italic|oblique)\s*/i, '').trim()

    // If after removing style keywords, nothing is left or it's not a known weight,
    // it means the input was style-only (like "Italic" or "Oblique") - default to regular weight
    if (!valueWithoutStyle || !weightMap[valueWithoutStyle]) {
      // If we found a style but no explicit weight, assume regular weight (400)
      if (fontStyle === 'italic' || fontStyle === 'oblique') {
        fontWeight = '400'
      } else {
        // For other cases where no weight is found, return original value
        fontWeight = value
      }
    }
  }

  return { fontWeight, fontStyle }
}

/**
 * Legacy function for backward compatibility - normalizes a single font weight/style string
 */
function normalizeFontWeightAndStyleLegacy(value) {
  // Default values
  let fontWeight = '400'
  let fontStyle = 'normal'

  if (typeof value !== 'string') {
    return { fontWeight, fontStyle }
  }

  // Check if this is a token reference (e.g., "{font.h1.fontWeight}")
  if (isReference(value)) {
    // This is a token reference, preserve it as is
    return { fontWeight: value, fontStyle: 'normal' }
  }

  // Lowercase for consistent processing
  const lcValue = value.toLowerCase()

  // Check for italic or oblique style
  if (lcValue.includes('italic')) {
    fontStyle = 'italic'
  } else if (lcValue.includes('oblique')) {
    fontStyle = 'oblique'
  }

  // Map common weight names to numeric values
  const weightMap = {
    thin: '100',
    hairline: '100',
    extralight: '200',
    ultralight: '200',
    light: '300',
    regular: '400',
    normal: '400',
    book: '400',
    medium: '500',
    semibold: '600',
    demibold: '600',
    bold: '700',
    extrabold: '800',
    ultrabold: '800',
    black: '900',
    heavy: '900',
    extrablack: '950',
    ultrablack: '950',
  }

  // Try to match known weight names
  for (const [name, weight] of Object.entries(weightMap)) {
    // Check for exact match or word boundary to avoid partial matches
    const regex = new RegExp(`\\b${name}\\b`, 'i')
    if (regex.test(lcValue)) {
      fontWeight = weight
      break
    }
  }

  // If it's a direct numeric value (e.g., "700"), use that
  if (/^\d+$/.test(value)) {
    fontWeight = value
  } else {
    // Check if the value contains only style information (like "italic" or "oblique") without weight
    const valueWithoutStyle = lcValue.replace(/\s*(italic|oblique)\s*/i, '').trim()

    // If after removing style keywords, nothing is left or it's not a known weight,
    // it means the input was style-only (like "Italic" or "Oblique") - keep default weight (400)
    if (!valueWithoutStyle || !weightMap[valueWithoutStyle]) {
      // fontWeight already defaults to "400" which is correct for style-only inputs
    }
  }

  return { fontWeight, fontStyle }
}

module.exports = { normalizeFontWeightAndStyle }
