const { tokens } = require('../handleTokens')
const { flattenTokensObject } = require('../utils/flattenTokensObject')
const { toKebabCase } = require('../utils/toKebabCase')
const { getFirstKey } = require('../utils/getFirstKey')
const { getResolvedValue, findThemeByColorValue } = require('../handleTokens')

/**
 * Accordion Content Color Theme Handling
 */

// Get all accordion colors
const colorSystemModes = Object.keys(tokens.colorSystem)
const accordionColors = Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].accordion)
let accordionColorThemes = {}

// Go through all color system modes
for (const mode of colorSystemModes) {
  accordionColorThemes[mode] ??= {} // ensure object exists

  // Go through all colors, and find all flat background colors
  for (const color of accordionColors) {
    const bgColorPrimitive = getResolvedValue(
      tokens.colorSystem,
      `accordion.${color}.flat.background`,
      mode,
    )
    accordionColorThemes[mode][color] = findThemeByColorValue(tokens.colorSystem, bgColorPrimitive)
  }
}

/**
 * Card Content Color Theme Handling
 */

// Get all card styles
const cardStyles = Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].card)
let cardColorThemes = {}

// Go through all color system modes
for (const mode of colorSystemModes) {
  cardColorThemes[mode] ??= {} // ensure object exists

  // Go through all card styles, and find all background colors
  for (const style of cardStyles) {
    const bgColorPrimitive = getResolvedValue(tokens.colorSystem, `card.${style}.background`, mode)
    // Only add to themes if it's not transparent (skip tailwindcss.colors.transparent)
    if (bgColorPrimitive && bgColorPrimitive !== '#00000000') {
      cardColorThemes[mode][style] = findThemeByColorValue(tokens.colorSystem, bgColorPrimitive)
    }
  }
}

// Generate CSS selectors from accordionColorThemes and cardColorThemes
// Logic: [value] => .theme-[mode] .accordion-[color] .accordion-content
// Logic: [value] => .theme-[mode] .card-[style]
const cssSelectors = {}

// Add accordion selectors
for (const mode of Object.keys(accordionColorThemes)) {
  for (const color of Object.keys(accordionColorThemes[mode])) {
    const themeValue = accordionColorThemes[mode][color]
    const selector = `.theme-${toKebabCase(mode)} .accordion-flat.accordion-${color} .accordion-content`

    if (!cssSelectors[themeValue]) {
      cssSelectors[themeValue] = []
    }
    cssSelectors[themeValue].push(selector)
  }
}

// Add card selectors
for (const mode of Object.keys(cardColorThemes)) {
  for (const style of Object.keys(cardColorThemes[mode])) {
    const themeValue = cardColorThemes[mode][style]
    const selector = `.theme-${toKebabCase(mode)} .card-${toKebabCase(style)} .card-content`

    if (!cssSelectors[themeValue]) {
      cssSelectors[themeValue] = []
    }
    cssSelectors[themeValue].push(selector)
  }
}

/**
 * CSS for variables
 */
const variables = {
  ':root': {
    ...flattenTokensObject(tokens.font, '--font-'),
    ...flattenTokensObject(tokens.responsiveSizing.sm, '--'),
    ...flattenTokensObject(tokens.colorPrimitives.primitives, '--color-'),
    ...flattenTokensObject(tokens.colorSystem[getFirstKey(tokens.colorSystem)], '--color-'),
    '@screen md': {
      ...flattenTokensObject(tokens.responsiveSizing.lg, '--'),
    },
  },
  // Original theme classes with accordion content selectors
  ...Object.keys(tokens.colorSystem).reduce((acc, mode) => {
    // Get accordion selectors that should use this mode's colors
    const additionalSelectors = []
    for (const [themeValue, selectors] of Object.entries(cssSelectors)) {
      if (themeValue === mode) {
        additionalSelectors.push(...selectors)
      }
    }

    // Create the combined selector: base theme + additional accordion selectors
    const baseSelector = `.theme-${toKebabCase(mode)}`
    const combinedSelector =
      additionalSelectors.length > 0
        ? [baseSelector, ...additionalSelectors].join(', ')
        : baseSelector

    acc[combinedSelector] = {
      ...flattenTokensObject(tokens.colorSystem[mode], '--color-'),
    }
    return acc
  }, {}),
}

module.exports = { variables }
