/**
 * Converts a string to Title Case
 *
 * @param str - The string to convert
 * @returns The Title Case version of the string
 */
const toTitleCase = (str) => {
  // Handle empty string
  if (!str) return ''

  // First normalize the string to handle various input formats
  const normalizedStr = str
    // Insert space before uppercase letters
    .replace(/([A-Z])/g, ' $1')
    // Replace dashes, underscores with spaces
    .replace(/[-_]+/g, ' ')
    // Normalize spaces
    .replace(/\s+/g, ' ')
    // Trim any leading/trailing spaces
    .trim()
    // Convert to lowercase
    .toLowerCase()

  // Convert first letter of each word to uppercase
  return normalizedStr
    .split(' ')
    .map((word) => {
      if (word.length === 0) return word
      return word[0].toUpperCase() + word.slice(1)
    })
    .join(' ')
}

module.exports = { toTitleCase }
