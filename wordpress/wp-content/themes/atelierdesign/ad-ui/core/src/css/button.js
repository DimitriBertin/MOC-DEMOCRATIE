const { tokens } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')
const { vw } = require('../utils/vw')

const button = {
  ...(typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].button.flat == 'object'
    ? {
        '.button-flat': {
          cursor: 'pointer',
          display: 'inline-flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          paddingInline: vw(`var(--button-flat-padding-x)`, 'sm'),
          paddingBlock: vw(`var(--button-flat-padding-y)`, 'sm'),
          gap: vw(`var(--button-flat-gap)`, 'sm'),
          borderStyle: 'solid',
          borderWidth: vw(`var(--button-flat-border-width)`, 'sm'),
          borderRadius: vw(`var(--button-flat-border-radius)`, 'sm'),
          boxShadow: `${vw('var(--button-flat-shadow-x)', 'sm')} ${vw('var(--button-flat-shadow-y)', 'sm')} ${vw('var(--button-flat-shadow-blur)', 'sm')} ${vw('var(--button-flat-shadow-spread)', 'sm')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          '@screen md': {
            paddingInline: vw(`var(--button-flat-padding-x)`, 'lg'),
            paddingBlock: vw(`var(--button-flat-padding-y)`, 'lg'),
            gap: vw(`var(--button-flat-gap)`, 'lg'),
            borderWidth: vw(`var(--button-flat-border-width)`, 'lg'),
            borderRadius: vw(`var(--button-flat-border-radius)`, 'lg'),
            boxShadow: `${vw('var(--button-flat-shadow-x)', 'lg')} ${vw('var(--button-flat-shadow-y)', 'lg')} ${vw('var(--button-flat-shadow-blur)', 'lg')} ${vw('var(--button-flat-shadow-spread)', 'lg')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          },
        },
        ':where(.button-flat .button-icon, .button-flat .button-icon > *)': {
          width: vw(`var(--button-flat-icon-size)`, 'sm'),
          height: vw(`var(--button-flat-icon-size)`, 'sm'),
          fontSize: vw(`var(--button-flat-icon-size)`, 'sm'),
          lineHeight: 1,
          '@screen md': {
            width: vw(`var(--button-flat-icon-size)`, 'lg'),
            height: vw(`var(--button-flat-icon-size)`, 'lg'),
            fontSize: vw(`var(--button-flat-icon-size)`, 'lg'),
          },
        },
        ':where(.button-flat) .button-icon': {
          fontSize: vw(`var(--button-flat-icon-size)`, 'sm'),
          '@screen md': {
            fontSize: vw(`var(--button-flat-icon-size)`, 'lg'),
          },
        },
      }
    : {}),
  ...(typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].button.outline ==
  'object'
    ? {
        '.button-outline': {
          cursor: 'pointer',
          display: 'inline-flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          paddingInline: vw(`var(--button-outline-padding-x)`, 'sm'),
          paddingBlock: vw(`var(--button-outline-padding-y)`, 'sm'),
          gap: vw(`var(--button-outline-gap)`, 'sm'),
          borderStyle: 'solid',
          borderWidth: vw(`var(--button-outline-border-width)`, 'sm'),
          borderRadius: vw(`var(--button-outline-border-radius)`, 'sm'),
          boxShadow: `${vw('var(--button-outline-shadow-x)', 'sm')} ${vw('var(--button-outline-shadow-y)', 'sm')} ${vw('var(--button-outline-shadow-blur)', 'sm')} ${vw('var(--button-outline-shadow-spread)', 'sm')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          '@screen md': {
            paddingInline: vw(`var(--button-outline-padding-x)`, 'lg'),
            paddingBlock: vw(`var(--button-outline-padding-y)`, 'lg'),
            gap: vw(`var(--button-outline-gap)`, 'lg'),
            borderWidth: vw(`var(--button-outline-border-width)`, 'lg'),
            borderRadius: vw(`var(--button-outline-border-radius)`, 'lg'),
            boxShadow: `${vw('var(--button-outline-shadow-x)', 'lg')} ${vw('var(--button-outline-shadow-y)', 'lg')} ${vw('var(--button-outline-shadow-blur)', 'lg')} ${vw('var(--button-outline-shadow-spread)', 'lg')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          },
        },
        ':where(.button-outline .button-icon, .button-outline .button-icon > *)': {
          width: vw(`var(--button-outline-icon-size)`, 'sm'),
          height: vw(`var(--button-outline-icon-size)`, 'sm'),
          fontSize: vw(`var(--button-outline-icon-size)`, 'sm'),
          lineHeight: 1,
          '@screen md': {
            width: vw(`var(--button-outline-icon-size)`, 'lg'),
            height: vw(`var(--button-outline-icon-size)`, 'lg'),
            fontSize: vw(`var(--button-outline-icon-size)`, 'lg'),
          },
        },
        ':where(.button-outline) .button-icon': {
          fontSize: vw(`var(--button-outline-icon-size)`, 'sm'),
          '@screen md': {
            fontSize: vw(`var(--button-outline-icon-size)`, 'lg'),
          },
        },
      }
    : {}),
  ...(typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].button.underline ==
  'object'
    ? {
        '.button-underline': {
          cursor: 'pointer',
          display: 'inline-flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          paddingInline: vw(`var(--button-underline-padding-x)`, 'sm'),
          paddingBlock: vw(`var(--button-underline-padding-y)`, 'sm'),
          gap: vw(`var(--button-underline-gap)`, 'sm'),
          borderStyle: 'solid',
          borderBottomWidth: vw(`var(--button-underline-border-width)`, 'sm'),
          boxShadow: `${vw('var(--button-underline-shadow-x)', 'sm')} ${vw('var(--button-underline-shadow-y)', 'sm')} ${vw('var(--button-underline-shadow-blur)', 'sm')} ${vw('var(--button-underline-shadow-spread)', 'sm')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          '@screen md': {
            paddingInline: vw(`var(--button-underline-padding-x)`, 'lg'),
            paddingBlock: vw(`var(--button-underline-padding-y)`, 'lg'),
            gap: vw(`var(--button-underline-gap)`, 'lg'),
            borderBottomWidth: vw(`var(--button-underline-border-width)`, 'lg'),
            boxShadow: `${vw('var(--button-underline-shadow-x)', 'lg')} ${vw('var(--button-underline-shadow-y)', 'lg')} ${vw('var(--button-underline-shadow-blur)', 'lg')} ${vw('var(--button-underline-shadow-spread)', 'lg')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          },
        },
        ':where(.button-underline .button-icon, .button-underline .button-icon > *)': {
          width: vw(`var(--button-underline-icon-size)`, 'sm'),
          height: vw(`var(--button-underline-icon-size)`, 'sm'),
          fontSize: vw(`var(--button-underline-icon-size)`, 'sm'),
          lineHeight: 1,
          '@screen md': {
            width: vw(`var(--button-underline-icon-size)`, 'lg'),
            height: vw(`var(--button-underline-icon-size)`, 'lg'),
            fontSize: vw(`var(--button-underline-icon-size)`, 'lg'),
          },
        },
        ':where(.button-underline) .button-icon': {
          fontSize: vw(`var(--button-underline-icon-size)`, 'sm'),
          '@screen md': {
            fontSize: vw(`var(--button-underline-icon-size)`, 'lg'),
          },
        },
      }
    : {}),
  ...(typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].button.none == 'object'
    ? {
        '.button-none': {
          cursor: 'pointer',
          display: 'inline-flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          gap: vw(`var(--button-none-gap)`, 'sm'),
          '@screen md': {
            gap: vw(`var(--button-none-gap)`, 'lg'),
          },
        },
        ':where(.button-none .button-icon, .button-none .button-icon > *)': {
          width: vw(`var(--button-none-icon-size)`, 'sm'),
          height: vw(`var(--button-none-icon-size)`, 'sm'),
          fontSize: vw(`var(--button-none-icon-size)`, 'sm'),
          lineHeight: 1,
          '@screen md': {
            width: vw(`var(--button-none-icon-size)`, 'lg'),
            height: vw(`var(--button-none-icon-size)`, 'lg'),
            fontSize: vw(`var(--button-none-icon-size)`, 'lg'),
          },
        },
        ':where(.button-none) .button-icon': {
          fontSize: vw(`var(--button-none-icon-size)`, 'sm'),
          '@screen md': {
            fontSize: vw(`var(--button-none-icon-size)`, 'lg'),
          },
        },
      }
    : {}),
  ...Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].button).reduce(
    (acc, color) => {
      if (
        typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].button.flat == 'object'
      ) {
        acc[`body :where(.button-flat.button-${toKebabCase(color)})`] = {
          '--tw-shadow-color': `var(--color-button-${toKebabCase(color)}-flat-normal-shadow)`,
          '--tw-shadow': 'var(--tw-shadow-colored)',
          color: `var(--color-button-${toKebabCase(color)}-flat-normal-text)`,
          borderColor: `var(--color-button-${toKebabCase(color)}-flat-normal-border)`,
          backgroundColor: `var(--color-button-${toKebabCase(color)}-flat-normal-background)`,
        }
        acc[`body :where(.button-flat.button-${toKebabCase(color)}:hover)`] = {
          '--tw-shadow-color': `var(--color-button-${toKebabCase(color)}-flat-normal-shadow)`,
          '--tw-shadow': 'var(--tw-shadow-colored)',
          color: `var(--color-button-${toKebabCase(color)}-flat-hover-text)`,
          borderColor: `var(--color-button-${toKebabCase(color)}-flat-hover-border)`,
          backgroundColor: `var(--color-button-${toKebabCase(color)}-flat-hover-background)`,
        }
        acc[`:where(.button-flat.button-${toKebabCase(color)}) .button-icon`] = {
          color: `var(--color-button-${toKebabCase(color)}-flat-normal-icon)`,
          transition: 'inherit',
        }
        acc[`:where(.button-flat.button-${toKebabCase(color)}):hover .button-icon`] = {
          color: `var(--color-button-${toKebabCase(color)}-flat-hover-icon)`,
        }
      }

      if (
        typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].button.outline ==
        'object'
      ) {
        acc[`body :where(.button-outline.button-${toKebabCase(color)})`] = {
          '--tw-shadow-color': `var(--color-button-${toKebabCase(color)}-outline-normal-shadow)`,
          '--tw-shadow': 'var(--tw-shadow-colored)',
          color: `var(--color-button-${toKebabCase(color)}-outline-normal-text)`,
          borderColor: `var(--color-button-${toKebabCase(color)}-outline-normal-border)`,
          backgroundColor: `var(--color-button-${toKebabCase(color)}-outline-normal-background)`,
        }
        acc[`body :where(.button-outline.button-${toKebabCase(color)}:hover)`] = {
          '--tw-shadow-color': `var(--color-button-${toKebabCase(color)}-outline-normal-shadow)`,
          '--tw-shadow': 'var(--tw-shadow-colored)',
          color: `var(--color-button-${toKebabCase(color)}-outline-hover-text)`,
          borderColor: `var(--color-button-${toKebabCase(color)}-outline-hover-border)`,
          backgroundColor: `var(--color-button-${toKebabCase(color)}-outline-hover-background)`,
        }
        acc[`:where(.button-outline.button-${toKebabCase(color)}) .button-icon`] = {
          color: `var(--color-button-${toKebabCase(color)}-outline-normal-icon)`,
          transition: 'inherit',
        }
        acc[`:where(.button-outline.button-${toKebabCase(color)}):hover .button-icon`] = {
          color: `var(--color-button-${toKebabCase(color)}-outline-hover-icon)`,
        }
      }

      if (
        typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].button.underline ==
        'object'
      ) {
        acc[`body :where(.button-underline.button-${toKebabCase(color)})`] = {
          '--tw-shadow-color': `var(--color-button-${toKebabCase(color)}-underline-normal-shadow)`,
          '--tw-shadow': 'var(--tw-shadow-colored)',
          color: `var(--color-button-${toKebabCase(color)}-underline-normal-text)`,
          borderColor: `var(--color-button-${toKebabCase(color)}-underline-normal-border)`,
        }
        acc[`body :where(.button-underline.button-${toKebabCase(color)}:hover)`] = {
          '--tw-shadow-color': `var(--color-button-${toKebabCase(color)}-underline-normal-shadow)`,
          '--tw-shadow': 'var(--tw-shadow-colored)',
          color: `var(--color-button-${toKebabCase(color)}-underline-hover-text)`,
          borderColor: `var(--color-button-${toKebabCase(color)}-underline-hover-border)`,
        }
        acc[`:where(.button-underline.button-${toKebabCase(color)}) .button-icon`] = {
          color: `var(--color-button-${toKebabCase(color)}-underline-normal-icon)`,
          transition: 'inherit',
        }
        acc[`:where(.button-underline.button-${toKebabCase(color)}):hover .button-icon`] = {
          color: `var(--color-button-${toKebabCase(color)}-underline-hover-icon)`,
        }
      }

      if (
        typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].button.none == 'object'
      ) {
        acc[`body :where(.button-none.button-${toKebabCase(color)})`] = {
          color: `var(--color-button-${toKebabCase(color)}-none-normal-text)`,
        }
        acc[`body :where(.button-none.button-${toKebabCase(color)}:hover)`] = {
          color: `var(--color-button-${toKebabCase(color)}-none-hover-text)`,
        }
        acc[`:where(.button-underline.button-${toKebabCase(color)}) .button-icon`] = {
          color: `var(--color-button-${toKebabCase(color)}-none-normal-icon)`,
          transition: 'inherit',
        }
        acc[`:where(.button-none.button-${toKebabCase(color)}):hover .button-icon`] = {
          color: `var(--color-button-${toKebabCase(color)}-none-hover-icon)`,
        }
      }

      return acc
    },
    {},
  ),
  '.button-title': {
    '@apply text-button-title': {},
  },
}

module.exports = { button }
