const { tokens } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')

const paragraph = {
  ...Object.keys(
    tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].typography.paragraph,
  ).reduce((acc, size) => {
    acc[`.paragraph-${toKebabCase(size)}`] = {
      [`@apply text-paragraph-${toKebabCase(size)}`]: {},
    }
    return acc
  }, {}),
  ...Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].typography.paragraph).reduce(
    (acc, color) => {
      acc[`.paragraph-${toKebabCase(color)}`] = {
        color: `var(--color-typography-paragraph-${toKebabCase(color)})`,
      }
      return acc
    },
    {},
  ),
}

module.exports = { paragraph }
