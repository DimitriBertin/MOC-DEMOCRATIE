/**
 * Simple object check.
 * @param item - The item to check
 * @returns {boolean} True if the item is an object, false otherwise
 */
function isObject(item) {
  return typeof item === 'object' && !Array.isArray(item)
}

/**
 * Check if an item is an array
 * @param item - The item to check
 * @returns {boolean} True if the item is an array, false otherwise
 */
function isArray(item) {
  return Array.isArray(item)
}

/**
 * Deep merges properties from source into target with special handling:
 * - Objects are merged recursively
 * - Arrays are concatenated
 * - Primitive values from source override target values
 *
 * @param target - The target object to merge into
 * @param source - The source object to merge from
 * @returns A new object with merged properties
 */
function deepMerge(target, source) {
  const output = { ...target }
  if (isObject(target) && isObject(source)) {
    Object.keys(source).forEach((key) => {
      if (isObject(source[key])) {
        if (!(key in target)) {
          Object.assign(output, { [key]: source[key] })
        } else {
          output[key] = deepMerge(target[key], source[key])
        }
      } else if (isArray(source[key]) && isArray(target[key])) {
        // Merge arrays by concatenating them
        output[key] = [...target[key], ...source[key]]
      } else {
        Object.assign(output, { [key]: source[key] })
      }
    })
  }

  return output
}

/**
 * Deep merges properties from source into target with different handling than deepMerge:
 * - Objects are merged recursively like in deepMerge
 * - Other values from source override target values if defined
 * - Arrays are completely replaced rather than concatenated
 *
 * @param target - The target object to merge into
 * @param source - The source object to merge from
 * @returns A new object with merged properties
 */
function deepMergeReplace(target, source) {
  const output = {
    ...target,
  }

  if (isObject(target) && isObject(source)) {
    Object.keys(source).forEach((key) => {
      const targetValue = output[key]
      const sourceValue = source[key]

      if (isObject(sourceValue) && isObject(targetValue)) {
        // If both values are objects, recursively merge them
        output[key] = deepMerge(targetValue, sourceValue)
      } else if (sourceValue !== undefined) {
        // Otherwise just override with the source value
        output[key] = sourceValue
      }
    })
  }

  return output
}

module.exports = {
  isObject,
  isArray,
  deepMerge,
  deepMergeReplace,
}
