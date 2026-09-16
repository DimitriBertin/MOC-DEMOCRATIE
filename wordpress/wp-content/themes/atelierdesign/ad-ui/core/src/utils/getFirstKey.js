/**
 * Get the first key from an object (for default/base values)
 *
 * @param {Record<string, unknown> | object} obj - The object to get the first key from
 * @returns {string | null} The first key from the object, or null if the object is empty
 */
const getFirstKey = (obj) => {
  if (!obj) return null
  const safeObj = obj
  const keys = Object.keys(safeObj)
  return keys.length > 0 ? keys[0] : null
}

module.exports = { getFirstKey }
