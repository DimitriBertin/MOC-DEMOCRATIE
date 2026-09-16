/**
 * Removes "px" from the end of a value and keeps it as a unitless string
 * @param {string|number} value - The value to process
 * @returns {string|number|*} - The numeric string value without "px", or the original value if no "px" suffix
 */
function removePx(value) {
  if (typeof value === 'string' && value.endsWith('px')) {
    const numericValue = value.slice(0, -2)
    const parsed = parseFloat(numericValue)
    // Return as string to prevent CSS-in-JS from auto-adding px
    return isNaN(parsed) ? value : numericValue
  }
  return value
}

/**
 * Removes "px" from the end of a value and converts it to an integer
 * @param {string|number} value - The value to process
 * @returns {number|*} - The numeric value without "px", or the original value if no "px" suffix
 */
function removePxToNumber(value) {
  if (typeof value === 'string' && value.endsWith('px')) {
    const numericValue = value.slice(0, -2)
    const parsed = parseInt(numericValue, 10)
    return isNaN(parsed) ? value : parsed
  }
  return value
}

/**
 * Recursively processes an object to remove "px" suffixes and convert them to unitless strings
 * @param {*} obj - The object, array, or value to process
 * @returns {*} - The processed object with px values converted to unitless strings
 */
function removeAllPxFromObject(obj) {
  // Handle null or undefined
  if (obj === null || obj === undefined) {
    return obj
  }

  // Handle arrays
  if (Array.isArray(obj)) {
    return obj.map((item) => removeAllPxFromObject(item))
  }

  // Handle objects
  if (typeof obj === 'object' && obj.constructor === Object) {
    const result = {}
    for (const [key, value] of Object.entries(obj)) {
      result[key] = removeAllPxFromObject(value)
    }
    return result
  }

  // Handle primitive values (strings, numbers, etc.)
  return removePx(obj)
}

/**
 * Recursively processes an object to remove "px" suffixes and convert them to integers
 * @param {*} obj - The object, array, or value to process
 * @returns {*} - The processed object with px values converted to integers
 */
function removeAllPxFromObjectToNumbers(obj) {
  // Handle null or undefined
  if (obj === null || obj === undefined) {
    return obj
  }

  // Handle arrays
  if (Array.isArray(obj)) {
    return obj.map((item) => removeAllPxFromObjectToNumbers(item))
  }

  // Handle objects
  if (typeof obj === 'object' && obj.constructor === Object) {
    const result = {}
    for (const [key, value] of Object.entries(obj)) {
      result[key] = removeAllPxFromObjectToNumbers(value)
    }
    return result
  }

  // Handle primitive values (strings, numbers, etc.)
  return removePxToNumber(obj)
}

/**
 * Adds "px" unit to numeric values (useful for CSS custom properties)
 * @param {string|number} value - The value to process
 * @returns {string} - The value with "px" unit added if it was numeric
 */
function addPxUnit(value) {
  if (
    typeof value === 'number' ||
    (typeof value === 'string' && !isNaN(parseFloat(value)) && isFinite(value))
  ) {
    return `${value}px`
  }
  return value
}

/**
 * Recursively processes an object to add "px" units to numeric values
 * @param {*} obj - The object, array, or value to process
 * @returns {*} - The processed object with numeric values converted to px units
 */
function addPxUnitToObject(obj) {
  // Handle null or undefined
  if (obj === null || obj === undefined) {
    return obj
  }

  // Handle arrays
  if (Array.isArray(obj)) {
    return obj.map((item) => addPxUnitToObject(item))
  }

  // Handle objects
  if (typeof obj === 'object' && obj.constructor === Object) {
    const result = {}
    for (const [key, value] of Object.entries(obj)) {
      result[key] = addPxUnitToObject(value)
    }
    return result
  }

  // Handle primitive values (strings, numbers, etc.)
  return addPxUnit(obj)
}

// Export the functions
module.exports = {
  removePx,
  removeAllPxFromObject,
  removePxToNumber,
  removeAllPxFromObjectToNumbers,
  addPxUnit,
  addPxUnitToObject,
}
