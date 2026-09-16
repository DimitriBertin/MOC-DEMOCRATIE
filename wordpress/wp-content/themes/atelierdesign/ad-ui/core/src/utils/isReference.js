/**
 * Checks if a string is a token reference (a.k.a. a string wrapped in {curly.braces})
 *
 * @param value - The string to check
 * @returns boolean - True if the string is a token reference, false otherwise
 */
function isReference(value) {
  if (typeof value !== 'string') return false

  // Check for unquoted reference: {token.path}
  if (value.startsWith('{') && value.endsWith('}')) {
    return true
  }

  // Check for quoted reference: "{token.path}"
  if (
    (value.startsWith('"{') && value.endsWith('}"')) ||
    (value.startsWith("'{") && value.endsWith("'}"))
  ) {
    return true
  }

  return false
}

function isTailwindReference(value) {
  if (typeof value !== 'string') return false

  // Check for unquoted reference: {token.path}
  if (value.startsWith('{tailwindcss.') && value.endsWith('}')) {
    return true
  }

  // Check for quoted reference: "{token.path}"
  if (
    (value.startsWith('"{tailwindcss.') && value.endsWith('}"')) ||
    (value.startsWith("'{tailwindcss.") && value.endsWith("'}"))
  ) {
    return true
  }

  return false
}

function isConfigReference(value) {
  if (typeof value !== 'string') return false

  // Check for unquoted reference: {token.path}
  if (value.startsWith('{config.') && value.endsWith('}')) {
    return true
  }

  // Check for quoted reference: "{token.path}"
  if (
    (value.startsWith('"{config.') && value.endsWith('}"')) ||
    (value.startsWith("'{config.") && value.endsWith("'}"))
  ) {
    return true
  }

  return false
}

function isCalcReference(value) {
  if (typeof value !== 'string') return false

  // Check for unquoted reference: {token.path}
  if (value.startsWith('{calc.') && value.endsWith('}')) {
    return true
  }

  // Check for quoted reference: "{token.path}"
  if (
    (value.startsWith('"{calc.') && value.endsWith('}"')) ||
    (value.startsWith("'{calc.") && value.endsWith("'}"))
  ) {
    return true
  }

  return false
}

module.exports = {
  isReference,
  isTailwindReference,
  isConfigReference,
  isCalcReference,
}
