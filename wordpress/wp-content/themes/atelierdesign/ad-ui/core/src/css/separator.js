const { vw } = require('../utils/vw')
const { tokens } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')

const separator = {
  ...Object.keys(tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].separator).reduce(
    (acc, variant) => {
      acc[`.separator-${toKebabCase(variant)}`] = {
        width: '100%',
        // marginBlock: vw(`var(--separator-${toKebabCase(variant)}-margin-y)`, 'sm'), // todo: remove later if not used
        borderTopWidth: `calc(var(--separator-${toKebabCase(variant)}-line-width) * 1px)`,
        borderTopStyle: 'solid',
        borderTopColor: `var(--color-separator-${toKebabCase(variant)})`,
        '@screen md': {
          // marginBlock: vw(`var(--separator-${toKebabCase(variant)}-margin-y)`, 'lg'), // todo: remove later if not used
          borderTopWidth: `calc(var(--separator-${toKebabCase(variant)}-line-width) * 1px)`,
        },
      }
      return acc
    },
    {},
  ),
}

module.exports = { separator }
