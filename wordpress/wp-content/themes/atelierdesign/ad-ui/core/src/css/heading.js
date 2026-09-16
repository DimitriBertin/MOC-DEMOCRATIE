const { tokens } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')

const heading = {
  ...Object.keys(
    tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].typography.heading,
  ).reduce((acc, size) => {
    acc[`.heading-${toKebabCase(size)}`] = {
      [`@apply text-heading-${toKebabCase(size)}`]: {},
    }
    return acc
  }, {}),
  ...Object.keys(
    tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].typography.heading,
  ).reduce((acc, size) => {
    acc[`:where(.heading-${toKebabCase(size)}:is(mark), .heading-${toKebabCase(size)} mark)`] = {
      [`@apply text-heading-${toKebabCase(size)}-mark`]: {},
    }
    return acc
  }, {}),
  ...Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].typography.heading).reduce(
    (acc, color) => {
      acc[`.heading-${toKebabCase(color)}`] = {
        color: `var(--color-typography-heading-${toKebabCase(color)})`,
      }
      return acc
    },
    {},
  ),
}

module.exports = { heading }
