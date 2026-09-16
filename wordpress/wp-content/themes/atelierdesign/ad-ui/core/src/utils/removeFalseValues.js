const { isReference } = require('./isReference')

/**
 * Recursively removes properties from an object where the value is a reference
 * that equals "{false}"
 *
 * @param {any} obj - The object to process
 * @returns {any} - The processed object with false reference values removed
 */
function removeFalseValues(obj) {
  // Handle null, undefined, or primitive values
  if (obj === null || obj === undefined || typeof obj !== 'object') {
    return obj
  }

  // Handle arrays
  if (Array.isArray(obj)) {
    return obj
      .map((item) => removeFalseValues(item))
      .filter((item) => {
        // Remove array items that are {false} references
        if (isReference(item) && item === '{false}') {
          return false
        }
        return true
      })
  }

  // Handle objects
  const result = {}

  for (const [key, value] of Object.entries(obj)) {
    // Skip properties where the value is a {false} reference
    if (isReference(value) && value === '{false}') {
      continue
    }

    // Recursively process nested objects and arrays
    result[key] = removeFalseValues(value)
  }

  return result
}

module.exports = { removeFalseValues }
