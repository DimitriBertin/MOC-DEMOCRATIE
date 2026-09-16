const { vw } = require('../utils/vw')
const { tokens } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')

const badge = {
  ...Object.keys(tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].badge).reduce(
    (acc, variant) => {
      acc[`.badge-${toKebabCase(variant)}`] = {
        [`@apply text-badge-${toKebabCase(variant)}`]: {},
        display: 'inline-block',
        "--tw-shadow-color": `var(--color-badge-${toKebabCase(variant)}-shadow)`, // prettier-ignore
        '--tw-shadow': 'var(--tw-shadow-colored)',
        backgroundColor: `var(--color-badge-${toKebabCase(variant)}-background)`,
        borderColor: `var(--color-badge-${toKebabCase(variant)}-border)`,
        color: `var(--color-badge-${toKebabCase(variant)}-text)`,
        paddingLeft: vw(`var(--badge-${toKebabCase(variant)}-padding-left)`, 'sm'),
        paddingRight: vw(`var(--badge-${toKebabCase(variant)}-padding-right)`, 'sm'),
        paddingTop: vw(`var(--badge-${toKebabCase(variant)}-padding-top)`, 'sm'),
        paddingBottom: vw(`var(--badge-${toKebabCase(variant)}-padding-bottom)`, 'sm'),
        borderTopLeftRadius: vw(
          `var(--badge-${toKebabCase(variant)}-border-top-left-radius)`,
          'sm',
        ),
        borderTopRightRadius: vw(
          `var(--badge-${toKebabCase(variant)}-border-top-right-radius)`,
          'sm',
        ),
        borderBottomLeftRadius: vw(
          `var(--badge-${toKebabCase(variant)}-border-bottom-left-radius)`,
          'sm',
        ),
        borderBottomRightRadius: vw(
          `var(--badge-${toKebabCase(variant)}-border-bottom-right-radius)`,
          'sm',
        ),
        borderLeftWidth: vw(`var(--badge-${toKebabCase(variant)}-border-left-width)`, 'sm'),
        borderRightWidth: vw(`var(--badge-${toKebabCase(variant)}-border-right-width)`, 'sm'),
        borderTopWidth: vw(`var(--badge-${toKebabCase(variant)}-border-top-width)`, 'sm'),
        borderBottomWidth: vw(`var(--badge-${toKebabCase(variant)}-border-bottom-width)`, 'sm'),
        boxShadow: `${vw(`var(--badge-${toKebabCase(variant)}-shadow-position-x)`, 'sm')} ${vw(`var(--badge-${toKebabCase(variant)}-shadow-position-y)`, 'sm')} ${vw(`var(--badge-${toKebabCase(variant)}-shadow-blur)`, 'sm')} ${vw(`var(--badge-${toKebabCase(variant)}-shadow-spread)`, 'sm')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
        '@screen md': {
          paddingLeft: vw(`var(--badge-${toKebabCase(variant)}-padding-left)`, 'lg'),
          paddingRight: vw(`var(--badge-${toKebabCase(variant)}-padding-right)`, 'lg'),
          paddingTop: vw(`var(--badge-${toKebabCase(variant)}-padding-top)`, 'lg'),
          paddingBottom: vw(`var(--badge-${toKebabCase(variant)}-padding-bottom)`, 'lg'),
          borderTopLeftRadius: vw(
            `var(--badge-${toKebabCase(variant)}-border-top-left-radius)`,
            'lg',
          ),
          borderTopRightRadius: vw(
            `var(--badge-${toKebabCase(variant)}-border-top-right-radius)`,
            'lg',
          ),
          borderBottomLeftRadius: vw(
            `var(--badge-${toKebabCase(variant)}-border-bottom-left-radius)`,
            'lg',
          ),
          borderBottomRightRadius: vw(
            `var(--badge-${toKebabCase(variant)}-border-bottom-right-radius)`,
            'lg',
          ),
          borderLeftWidth: vw(`var(--badge-${toKebabCase(variant)}-border-left-width)`, 'lg'),
          borderRightWidth: vw(`var(--badge-${toKebabCase(variant)}-border-right-width)`, 'lg'),
          borderTopWidth: vw(`var(--badge-${toKebabCase(variant)}-border-top-width)`, 'lg'),
          borderBottomWidth: vw(`var(--badge-${toKebabCase(variant)}-border-bottom-width)`, 'lg'),
          boxShadow: `${vw(`var(--badge-${toKebabCase(variant)}-shadow-position-x)`, 'lg')} ${vw(`var(--badge-${toKebabCase(variant)}-shadow-position-y)`, 'lg')} ${vw(`var(--badge-${toKebabCase(variant)}-shadow-blur)`, 'lg')} ${vw(`var(--badge-${toKebabCase(variant)}-shadow-spread)`, 'lg')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
        },
      }
      return acc
    },
    {},
  ),
}

module.exports = { badge }
