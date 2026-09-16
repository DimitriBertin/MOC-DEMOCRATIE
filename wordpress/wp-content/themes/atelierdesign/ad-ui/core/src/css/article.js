const { tokens } = require('../handleTokens')
const { flattenTokensObject } = require('../utils/flattenTokensObject')
const { cleanObjectFromTypes } = require('../utils/cleanObjectFromTypes')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')

const article = {
  'article, .article': {
    // each section having the same color system and the same layout background next to each other should have no padding between them
    // so we force a padding-top of 0px on the second element
    ...Object.fromEntries(
      Object.keys(
        flattenTokensObject(
          cleanObjectFromTypes(tokens.colorSystem[getFirstKey(tokens.colorSystem)].layout),
        ),
      ).map((key) => [
        `> section:not([class*="theme"]).bg-layout-${toKebabCase(key)}`,
        {
          paddingTop: '0px',
        },
      ]),
    ),
    ...Object.keys(tokens.colorSystem).reduce((acc, key) => {
      Object.keys(tokens.colorSystem[key].layout).forEach((color) => {
        acc[
          `> section.theme-${toKebabCase(key)}.bg-layout-${toKebabCase(color)} + section.theme-${toKebabCase(key)}.bg-layout-${toKebabCase(color)}`
        ] = {
          paddingTop: '0px',
        }
      })
      return acc
    }, {}),
  },
}

module.exports = { article }
