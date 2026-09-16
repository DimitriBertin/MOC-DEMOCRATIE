const { tokens } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')
const { vw } = require('../utils/vw')

const marginUtilities = {
  ...Object.keys(tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].gap).reduce(
    (acc, key) => {
      acc[`.m-${toKebabCase(key)}`] = {
        margin: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          margin: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.-m-${toKebabCase(key)}`] = {
        margin: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'sm'),
        '@screen md': {
          margin: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'lg'),
        },
      }
      acc[`.mx-${toKebabCase(key)}`] = {
        marginInline: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          marginInline: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.-mx-${toKebabCase(key)}`] = {
        marginInline: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'sm'),
        '@screen md': {
          marginInline: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'lg'),
        },
      }
      acc[`.my-${toKebabCase(key)}`] = {
        marginBlock: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          marginBlock: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.-my-${toKebabCase(key)}`] = {
        marginBlock: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'sm'),
        '@screen md': {
          marginBlock: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'lg'),
        },
      }
      acc[`.mt-${toKebabCase(key)}`] = {
        marginTop: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'sm'),
        '@screen md': {
          marginTop: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'lg'),
        },
      }
      acc[`.-mt-${toKebabCase(key)}`] = {
        marginTop: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'sm'),
        '@screen md': {
          marginTop: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'lg'),
        },
      }
      acc[`.mb-${toKebabCase(key)}`] = {
        marginBottom: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          marginBottom: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.-mb-${toKebabCase(key)}`] = {
        marginBottom: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'sm'),
        '@screen md': {
          marginBottom: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'lg'),
        },
      }
      acc[`.ml-${toKebabCase(key)}`] = {
        marginLeft: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          marginLeft: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.-ml-${toKebabCase(key)}`] = {
        marginLeft: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'sm'),
        '@screen md': {
          marginLeft: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'lg'),
        },
      }
      acc[`.mr-${toKebabCase(key)}`] = {
        marginRight: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          marginRight: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.-mr-${toKebabCase(key)}`] = {
        marginRight: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'sm'),
        '@screen md': {
          marginRight: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'lg'),
        },
      }
      return acc
    },
    {},
  ),
}

const paddingUtilities = {
  ...Object.keys(tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].gap).reduce(
    (acc, key) => {
      acc[`.p-${toKebabCase(key)}`] = {
        padding: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          padding: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.px-${toKebabCase(key)}`] = {
        paddingInline: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          paddingInline: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.-px-${toKebabCase(key)}`] = {
        paddingInline: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'sm'),
        '@screen md': {
          paddingInline: vw(`var(--gap-${toKebabCase(key)}) * -1`, 'lg'),
        },
      }
      acc[`.py-${toKebabCase(key)}`] = {
        paddingBlock: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          paddingBlock: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.pt-${toKebabCase(key)}`] = {
        paddingTop: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          paddingTop: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.pb-${toKebabCase(key)}`] = {
        paddingBottom: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          paddingBottom: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.pl-${toKebabCase(key)}`] = {
        paddingLeft: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          paddingLeft: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      acc[`.pr-${toKebabCase(key)}`] = {
        paddingRight: vw(`var(--gap-${toKebabCase(key)})`, 'sm'),
        '@screen md': {
          paddingRight: vw(`var(--gap-${toKebabCase(key)})`, 'lg'),
        },
      }
      return acc
    },
    {},
  ),
}

module.exports = { marginUtilities, paddingUtilities }
