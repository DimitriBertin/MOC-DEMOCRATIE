const { tokens } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')
const { vw } = require('../utils/vw')

const gridComponent = {
  ...Object.keys(tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].grid).reduce(
    (acc, key) => {
      acc[`.grid-${toKebabCase(key)}`] = {
        display: 'grid',
        gridTemplateColumns: `repeat(var(--grid-${toKebabCase(key)}-count), 1fr)`,
        columnGap: vw(`var(--grid-${toKebabCase(key)}-gap)`, 'sm'),
        '@screen md': {
          columnGap: vw(`var(--grid-${toKebabCase(key)}-gap)`, 'lg'),
        },
      }
      return acc
    },
    {},
  ),
}

const gridUtilities = {
  ...Object.keys(tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].grid).reduce(
    (acc, key) => {
      acc[`.grid-cols-${toKebabCase(key)}`] = {
        gridTemplateColumns: `repeat(var(--grid-${toKebabCase(key)}-count), 1fr)`,
      }
      acc[`.gap-grid-${toKebabCase(key)}`] = {
        columnGap: vw(`var(--grid-${toKebabCase(key)}-gap)`, 'sm'),
        '@screen md': {
          columnGap: vw(`var(--grid-${toKebabCase(key)}-gap)`, 'lg'),
        },
      }
      return acc
    },
    {},
  ),
  ...Object.keys(tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].gap).reduce(
    (acc, key) => {
      acc[`.gap-${toKebabCase(key)}`] = {
        columnGap: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        rowGap: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          columnGap: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
          rowGap: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.gap-x-${toKebabCase(key)}`] = {
        columnGap: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          columnGap: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.gap-y-${toKebabCase(key)}`] = {
        rowGap: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          rowGap: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      return acc
    },
    {},
  ),
}
const flexGridGaps = {
  ...Object.keys(tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].gap).reduce(
    (acc, key) => {
      acc[`.flex-grid-gap-${toKebabCase(key)}`] = {
        '--tw-flex-grid-gap-x': vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '--tw-flex-grid-gap-y': vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          '--tw-flex-grid-gap-x': vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
          '--tw-flex-grid-gap-y': vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.flex-grid-gap-x-${toKebabCase(key)}`] = {
        '--tw-flex-grid-gap-x': vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          '--tw-flex-grid-gap-x': vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.flex-grid-gap-y-${toKebabCase(key)}`] = {
        '--tw-flex-grid-gap-y': vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          '--tw-flex-grid-gap-y': vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      return acc
    },
    {},
  ),
}

module.exports = { gridComponent, gridUtilities, flexGridGaps }
