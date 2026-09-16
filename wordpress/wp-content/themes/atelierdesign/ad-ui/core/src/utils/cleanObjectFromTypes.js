/**
 * Clean the objects from the [$type] and set the [$value] to the value of the object of that level.
 *
 * @param {Record<string, unknown>} obj - The object to clean, having the [$type] and [$value] properties.
 * @returns {Record<string, unknown>} - The cleaned object, without the [$type] and [$value] properties.
 */
function cleanObjectFromTypes(obj) {
  const cleanObj = {}
  for (const [key, value] of Object.entries(obj)) {
    if (typeof value === 'object' && value !== null && '$type' in value) {
      cleanObj[key] = value['$value']
    } else if (typeof value === 'object' && value !== null) {
      cleanObj[key] = cleanObjectFromTypes(value)
    } else {
      cleanObj[key] = value
    }
  }
  return cleanObj
}

module.exports = { cleanObjectFromTypes }
