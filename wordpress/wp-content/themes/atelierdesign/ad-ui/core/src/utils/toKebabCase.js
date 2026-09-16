/**
 * Converts a string to kebab-case
 *
 * @param str - The string to convert
 * @returns The kebab-case version of the string
 */
const toKebabCase = (str) => {
  // Handle special cases and unusual formatting
  if (!str) return ''

  // Convert camelCase or PascalCase to kebab-case
  return (
    str
      // Handle consecutive uppercase letters followed by lowercase (e.g., "XMLParser" → "XML-Parser")
      .replace(/([A-Z]+)([A-Z][a-z])/g, '$1-$2')
      // Handle lowercase followed by uppercase (e.g., "camelCase" → "camel-Case")
      .replace(/([a-z])([A-Z])/g, '$1-$2')
      // Replace underscores and spaces with hyphens
      .replace(/[_\s]+/g, '-')
      // Convert to lowercase
      .toLowerCase()
      // Remove any duplicate hyphens
      .replace(/-+/g, '-')
      // Remove leading hyphen if present
      .replace(/^-/, '')
  )
}

/**
 * Converts a string to a URL-friendly format (kebab-case with special characters removed and accented characters normalized)
 *
 * @param str - The string to convert
 * @returns The URL-friendly version of the string
 */
const toUrlCase = (str) => {
  if (!str) return ''

  return (
    str
      // Normalize accented characters to their base form
      .normalize('NFD')
      // Remove diacritics (accent marks)
      .replace(/[\u0300-\u036f]/g, '')
      // Handle consecutive uppercase letters followed by lowercase (e.g., "XMLParser" → "XML-Parser")
      .replace(/([A-Z]+)([A-Z][a-z])/g, '$1-$2')
      // Handle lowercase followed by uppercase (e.g., "camelCase" → "camel-Case")
      .replace(/([a-z])([A-Z])/g, '$1-$2')
      // Replace underscores, spaces, and special characters with hyphens
      .replace(/[_\s\W]+/g, '-')
      // Convert to lowercase
      .toLowerCase()
      // Remove any duplicate hyphens
      .replace(/-+/g, '-')
      // Remove leading and trailing hyphens if present
      .replace(/^-|-$/g, '')
  )
}

module.exports = { toKebabCase, toUrlCase }
