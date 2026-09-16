const { config, tokens } = require('../handleTokens')
const { cleanObjectFromTypes } = require('../utils/cleanObjectFromTypes')
const { flattenTokensObject } = require('../utils/flattenTokensObject')
const { toKebabCase } = require('../utils/toKebabCase')
const { isReference } = require('../utils/isReference')
const { getFirstKey } = require('../utils/getFirstKey')

const {
  extendGrid24,
  extendEasings,
  extendAnimations,
  pluginFlexGrid,
  pluginNth,
  pluginAnimate,
  pluginAnimateStagger,
  pluginAnimateDelay,
  pluginOverrideDisabled,
  pluginScrollbarHidden,
  pluginIs,
  pluginNot,
  pluginMinMax,
  pluginOrientation,
  pluginAutoLineHeight,
} = require('./utils.js')

const { pluginVw, themeResetPixels } = require('./vw.js')

function constuctFontsFromTokens(object) {
  // Recursively find all unique values that contain fontfamily in the key
  const uniqueFontFamilies = new Set()

  function traverse(obj) {
    if (!obj || typeof obj !== 'object') return

    for (const [key, value] of Object.entries(obj)) {
      // Check if key contains 'fontfamily' (case-insensitive)
      if (key.toLowerCase().includes('fontfamily')) {
        if (typeof value === 'string' && !isReference(value)) {
          uniqueFontFamilies.add(value)
        } else if (Array.isArray(value)) {
          // If value is an array, add each string element
          value.forEach((item) => {
            if (typeof item === 'string' && !isReference(item)) {
              uniqueFontFamilies.add(item)
            }
          })
        }
      }

      // Recursively traverse nested objects
      if (typeof value === 'object') {
        traverse(value)
      }
    }
  }

  traverse(object)

  // Convert to object with kebab-case keys
  const fontFamilyObject = {}
  uniqueFontFamilies.forEach((fontFamily) => {
    // Remove quotes from font family name for the key
    const cleanedFontFamily = fontFamily.replace(/^["']|["']$/g, '')
    const kebabKey = toKebabCase(cleanedFontFamily)
    fontFamilyObject[kebabKey] = fontFamily
  })

  return fontFamilyObject
}

module.exports = {
  corePlugins: {
    container: false,
  },
  theme: {
    ...themeResetPixels,
    screens: {
      ...Object.fromEntries(
        Object.keys(config.breakpoints).map((key) => [
          toKebabCase(key),
          `${config.breakpoints[key]}px`,
        ]),
      ),
    },
    vwScreens: {
      ...Object.fromEntries(
        Object.keys(config.screenSizes).map((key) => [
          toKebabCase(key),
          `${config.screenSizes[key]}px`,
        ]),
      ),
    },
    extend: {
      colors: {
        ...Object.fromEntries(
          Object.keys(
            flattenTokensObject(cleanObjectFromTypes(tokens.colorPrimitives.primitives)),
          ).map((key) => {
            const flattenedTokens = flattenTokensObject(
              cleanObjectFromTypes(tokens.colorPrimitives.primitives),
            )
            return [toKebabCase(key), flattenedTokens[key]]
          }),
        ),
        ...Object.fromEntries(
          Object.keys(
            flattenTokensObject(
              cleanObjectFromTypes(tokens.colorSystem[getFirstKey(tokens.colorSystem)].layout),
            ),
          ).map((key) => [`layout-${toKebabCase(key)}`, `var(--color-layout-${toKebabCase(key)})`]),
        ),
        ...Object.fromEntries(
          Object.keys(
            flattenTokensObject(
              cleanObjectFromTypes(tokens.colorSystem[getFirstKey(tokens.colorSystem)].typography),
            ),
          ).map((key) => [
            `typography-${toKebabCase(key)}`,
            `var(--color-typography-${toKebabCase(key)})`,
          ]),
        ),
      },
      fontFamily: {
        system: [
          '-apple-system',
          'BlinkMacSystemFont',
          '"Segoe UI"',
          'Roboto',
          '"Helvetica Neue"',
          'Arial',
          'sans-serif',
        ],
        mono: [
          'ui-monospace',
          'SFMono-Regular',
          'Menlo',
          'Monaco',
          'Consolas',
          '"Liberation Mono"',
          '"Courier New"',
          'monospace',
        ],
        ...constuctFontsFromTokens(cleanObjectFromTypes(tokens.font)),
      },
      ...extendGrid24,
      ...extendEasings,
      ...extendAnimations,
      vwScale: {
        'lg/md': '0.68965517249',
        'lg/xl': '0.80',
      },
    },
  },
  plugins: [
    pluginFlexGrid,
    pluginNth,
    pluginAnimate,
    pluginAnimateStagger,
    pluginAnimateDelay,
    pluginOverrideDisabled,
    pluginScrollbarHidden,
    pluginIs,
    pluginNot,
    pluginMinMax,
    pluginOrientation,
    pluginAutoLineHeight,
    pluginVw({
      cssVariablePrefix: 'tw-',
      rawRenderToVW: false,
      enableScaleUtilities: true,
    }),
  ],
}
