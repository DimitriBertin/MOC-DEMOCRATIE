const { vw } = require('../utils/vw')
const { tokens } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')

const icon = {
  [Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].icon)
    .map((color) => `:where(.icon-${toKebabCase(color)})`)
    .join(', ')]: {
    width: vw('var(--icon-size)', 'sm'),
    height: vw('var(--icon-size)', 'sm'),
    fontSize: vw('var(--icon-size)', 'sm'),
    lineHeight: 1,
    '@screen md': {
      width: vw('var(--icon-size)', 'lg'),
      height: vw('var(--icon-size)', 'lg'),
      fontSize: vw('var(--icon-size)', 'lg'),
    },
  },
  // Enforce the font size for the Material Symbols
  [Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].icon)
    .map(
      (color) =>
        `.icon-${toKebabCase(color)}[class*="material-symbols-"], .icon-${toKebabCase(color)} *[class*="material-symbols-"]`,
    )
    .join(', ')]: {
    fontSize: vw('var(--icon-size)', 'sm'),
    lineHeight: 1,
    '@screen md': {
      fontSize: vw('var(--icon-size)', 'lg'),
      lineHeight: 1,
    },
  },
  [Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].icon)
    .map((color) => `:where(.icon-${toKebabCase(color)}.icon-has-background)`)
    .join(', ')]: {
    display: 'inline-grid',
    placeItems: 'center',
    width: vw('var(--icon-holder-size)', 'sm'),
    height: vw('var(--icon-holder-size)', 'sm'),
    borderRadius: vw('var(--icon-holder-border-radius)', 'sm'),
    borderWidth: vw('var(--icon-holder-border)', 'sm'),
    borderStyle: 'solid',
    boxShadow: `${vw('var(--icon-holder-shadow-x)', 'sm')} ${vw('var(--icon-holder-shadow-y)', 'sm')} ${vw('var(--icon-holder-shadow-blur)', 'sm')} ${vw('var(--icon-holder-shadow-spread)', 'sm')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
    '@screen md': {
      width: vw('var(--icon-holder-size)', 'lg'),
      height: vw('var(--icon-holder-size)', 'lg'),
      borderRadius: vw('var(--icon-holder-border-radius)', 'lg'),
      borderWidth: vw('var(--icon-holder-border)', 'lg'),
      boxShadow: `${vw('var(--icon-holder-shadow-x)', 'lg')} ${vw('var(--icon-holder-shadow-y)', 'lg')} ${vw('var(--icon-holder-shadow-blur)', 'lg')} ${vw('var(--icon-holder-shadow-spread)', 'lg')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
    },
  },
  [Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].icon)
    .map((color) => `:where(.icon-${toKebabCase(color)}.icon-has-background > *)`)
    .join(', ')]: {
    width: vw('var(--icon-size-in-holder)', 'sm'),
    height: vw('var(--icon-size-in-holder)', 'sm'),
    fontSize: vw('var(--icon-size-in-holder)', 'sm'),
    lineHeight: 1,
    '@screen md': {
      width: vw('var(--icon-size-in-holder)', 'lg'),
      height: vw('var(--icon-size-in-holder)', 'lg'),
      fontSize: vw('var(--icon-size-in-holder)', 'lg'),
    },
  },
  // Enforce the font size for the Material Symbols
  [Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].icon)
    .map(
      (color) => `.icon-${toKebabCase(color)}.icon-has-background > *[class*="material-symbols-"]`,
    )
    .join(', ')]: {
    fontSize: vw('var(--icon-size-in-holder)', 'sm'),
    lineHeight: 1,
    '@screen md': {
      fontSize: vw('var(--icon-size-in-holder)', 'lg'),
      lineHeight: 1,
    },
  },
  ...Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].icon).reduce((acc, color) => {
    acc[`:where(.icon-${toKebabCase(color)})`] = {
      color: `var(--color-icon-${toKebabCase(color)}-fill)`,
    }
    return acc
  }, {}),
  ...Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].icon).reduce((acc, color) => {
    acc[`:where(.icon-${toKebabCase(color)}).icon-has-background`] = {
      backgroundColor: `var(--color-icon-${toKebabCase(color)}-holder-background)`,
      borderColor: `var(--color-icon-${toKebabCase(color)}-holder-border)`,
      '--tw-shadow-color': `var(--color-icon-${toKebabCase(color)}-holder-shadow)`,
      '--tw-shadow': 'var(--tw-shadow-colored)',
      color: `var(--color-icon-${toKebabCase(color)}-holder-fill)`,
    }
    return acc
  }, {}),
}

module.exports = { icon }
