const { vw } = require('../utils/vw')
const { tokens, resolveColorToPrimitive, findThemeByColorValue } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')
const { flattenTokensObject } = require('../utils/flattenTokensObject')

let cardThemes = {}
// for each color in tokens.colorSystem, find the theme that contains the color
for (const color in tokens.colorSystem) {
  // Initialize the color object first
  cardThemes[color] = {}
  for (const variant in tokens.colorSystem[color].card) {
    cardThemes[color][variant] = findThemeByColorValue(
      tokens.colorSystem,
      resolveColorToPrimitive(tokens.colorSystem, `card.${variant}.background`, color),
    )
  }
}

const card = {
  ...Object.keys(tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].card).reduce(
    (acc, variant) => {
      acc[`.card-${toKebabCase(variant)}`] = {
        '--tw-shadow-color': `var(--color-card-${toKebabCase(variant)}-shadow)`,
        '--tw-shadow': 'var(--tw-shadow-colored)',
        backgroundColor: `var(--color-card-${toKebabCase(variant)}-background)`,
        borderColor: `var(--color-card-${toKebabCase(variant)}-border)`,
        paddingInline: vw(`var(--card-${toKebabCase(variant)}-padding-x)`, 'sm'),
        paddingBlock: vw(`var(--card-${toKebabCase(variant)}-padding-y)`, 'sm'),
        borderStyle: 'solid',
        borderWidth: vw(`var(--card-${toKebabCase(variant)}-border-width)`, 'sm'),
        borderRadius: vw(`var(--card-${toKebabCase(variant)}-border-radius)`, 'sm'),
        boxShadow: `${vw(`var(--card-${toKebabCase(variant)}-shadow-x)`, 'sm')} ${vw(`var(--card-${toKebabCase(variant)}-shadow-y)`, 'sm')} ${vw(`var(--card-${toKebabCase(variant)}-shadow-blur)`, 'sm')} 0px var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
        '@screen md': {
          paddingInline: vw(`var(--card-${toKebabCase(variant)}-padding-x)`, 'lg'),
          paddingBlock: vw(`var(--card-${toKebabCase(variant)}-padding-y)`, 'lg'),
          borderWidth: vw(`var(--card-${toKebabCase(variant)}-border-width)`, 'lg'),
          borderRadius: vw(`var(--card-${toKebabCase(variant)}-border-radius)`, 'lg'),
          boxShadow: `${vw(`var(--card-${toKebabCase(variant)}-shadow-x)`, 'lg')} ${vw(`var(--card-${toKebabCase(variant)}-shadow-y)`, 'lg')} ${vw(`var(--card-${toKebabCase(variant)}-shadow-blur)`, 'lg')} 0px var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
        },
      }
      return acc
    },
    {},
  ),
}

module.exports = { card }
