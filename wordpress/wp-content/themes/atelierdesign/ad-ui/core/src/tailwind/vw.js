const plugin = require('tailwindcss/plugin')
const postcss = require('postcss')

function roundToDec(number, decimals = 4) {
  return +(Math.round(number + `e+${decimals}`) + `e-${decimals}`)
}

exports.pluginVw = plugin.withOptions(
  function (options = {}) {
    return function ({ addBase, addVariant, matchVariant, matchUtilities, theme, config }) {
      /**
       * Function to convert a "px" value to a "vw" value
       * @param {int|string} pxValue - The value in pixels
       * @param {int|string} screenSizeWidth - The screen size width in pixels
       * @param {boolean} scaleUtility - If set to true, the function will return the value with the `var(--tw-scale)` CSS variable
       * @returns
       */

      const vw = (pxValue, screenSizeWidth, scaleUtility = false) => {
        const cssVariablePrefix =
          options.cssVariablePrefix !== undefined ? '--' + options.cssVariablePrefix : '--tw-'

        pxValue = pxValue.replace('px', '')
        screenSizeWidth = screenSizeWidth.replace('px', '')
        return scaleUtility
          ? `calc(${roundToDec(
              (parseFloat(pxValue) / parseFloat(screenSizeWidth)) * 100,
              4,
            )}vw * var(${cssVariablePrefix}scale))`
          : `${roundToDec((parseFloat(pxValue) / parseFloat(screenSizeWidth)) * 100, 4)}vw`
      }

      /**
       * Setting up variables for the vw plugin
       */

      // Getting the screens from the theme
      const screens = theme('screens') ?? []
      const screenValues = Object.values(screens)

      // order screens by size
      Object.keys(screens).sort((a, b) => screens[a] - screens[b])

      // Getting the vwScreens from the theme (fallback to "screens" if not provided)
      const vwScreens = theme('vwScreens') ?? screens
      const vwScreenValues = Object.values(vwScreens) ?? screenValues

      // order vwScreens by size
      Object.keys(vwScreens).sort((a, b) => vwScreens[a] - vwScreens[b])

      // Defining the prefix for the CSS variables
      const cssVariablePrefix =
        options.cssVariablePrefix !== undefined ? '--' + options.cssVariablePrefix : '--tw-'

      /**
       * Adding the base CSS variables to the :root for the screen sizes
       * - `screen-max` is the value of the our viewport's full width - The reason why it's a variable is to be able to overwrite with CSS. For example setting it to a maximum fixed value with @media queries when the viewport reaches a certain width.
       * - `screen-current` is the value of our current relating screen size, generated from the "screens" and "vwScreens" values defined in the Tailwind config.
       */

      addBase({
        ':root': {
          [`${cssVariablePrefix}scale`]: options.enableScaleUtilities ? '1' : false,
          [`${cssVariablePrefix}screen-max`]: '100vw',
          [`${cssVariablePrefix}screen-current`]:
            String(vwScreenValues[0]).replace('px', '') ?? String(screens[0]).replace('px', ''),
          // each screen size @media min-width
          ...Object.fromEntries(
            Object.entries(screens).map(([key, value], index) => [
              ...(index > 0
                ? [
                    `@media (min-width: ${value})`,
                    {
                      [`${cssVariablePrefix}screen-current`]:
                        String(vwScreens[key]).replace('px', '') ?? String(value).replace('px', ''),
                    },
                  ]
                : []),
            ]),
          ),
        },
      })

      if (!config('experimental.optimizeUniversalDefaults')) {
        addBase({
          ':root': {
            // each screen size in px
            ...Object.fromEntries(
              Object.entries(screens).map((value, index) => [
                `${cssVariablePrefix}screen-${Object.keys(screens)[index]}`,
                Object.values(vwScreens)[index].replace('px', '') ??
                  String(value).replace('px', ''),
              ]),
            ),
          },
        })
      }

      /**
       * Registering the `@@` static variant to convert "px" values to relative values defined by the `screen-max` and `screen-current` CSS variables
       */

      addVariant('@@', ({ container }) => {
        let styles = []

        container.walkRules((rule) => {
          let hasNotNull = false
          rule.walkDecls((decl) => {
            if (
              decl.value !== 0 &&
              decl.value !== '0' &&
              decl.value !== '0px' &&
              (config('disableSctrictMode')
                ? /^-?(\.\d+|\d+(\.\d+)?)(px)?$/.test(decl.value)
                : /^-?(\.\d+|\d+(\.\d+)?)px$/.test(decl.value))
            ) {
              styles.push({
                value: decl.value.split('px')[0],
                important: decl.important,
                prop: decl.prop,
              })

              decl.remove()
              hasNotNull = true
            }
          })

          if (hasNotNull) {
            styles.forEach((style) => {
              rule.prepend(
                postcss.decl({
                  prop: style.prop,
                  value: `calc((${
                    style.value
                  } / var(${cssVariablePrefix}screen-current)) * var(${cssVariablePrefix}screen-max)${
                    options.enableScaleUtilities ? ' * var(' + cssVariablePrefix + 'scale)' : ''
                  })${style.important ? ' !important' : ''}`,
                }),
              )
            })
          }
        })
      })

      /**
       * Registering the `@` dynamic variant that accepts a breakpoint name (or arbitrary values) to convert "px" values to relative values defined by the modifier and it's corresponding "vwScreens" value
       * - for example: `@:text-[16px]` will result in `font-size: calc((16 / DEFAULT_SCREEN_SIZE) * FULL_VIEWPORT_WIDTH);`
       *                `@lg:text-[16px]` will result in `@media (min-width: LG_SCREEN_SIZE) { font-size: calc((16 / LG_SCREEN_SIZE) * FULL_VIEWPORT_WIDTH); }`
       */

      matchVariant(
        '@',
        (value = '', { modifier, container }) => {
          // Setup breakpoint name
          let breakpointName =
            Object.keys(screens).find((key) => screens[key] === value) ??
            'arbitrary-' + value.replace('px', '').replace('[', '').replace(']', '')

          if (breakpointName == 'DEFAULT') {
            breakpointName = 'default'
          }

          // Setup screen size
          let screenSize = vwScreens[breakpointName] ?? value

          // Setup modifier name
          let modifierName = null

          // if modifier is defined, look for the corresponding screen size
          if (
            modifier !== undefined &&
            modifier !== null &&
            modifier.length > 0 &&
            modifier !== 0
          ) {
            modifier = modifier.replace('[', '').replace(']', '')

            modifierName = screens[modifier]
              ? modifier
              : 'arbitrary-' + modifier.replace('px', '').replace('[', '').replace(']', '')

            if (modifierName == 'DEFAULT') {
              modifierName = 'default'
            }

            // Overwrite screen size with modifier screen size
            screenSize = vwScreens[modifierName] ?? modifier.replace('px', '')
          }

          let styles = []

          container.walkRules((rule) => {
            rule.walkDecls((decl) => {
              // remove ${cssVariablePrefix}w-relative
              if (decl.prop === `${cssVariablePrefix}screen-${breakpointName}`) {
                decl.remove()
              }
              if (decl.prop === `${cssVariablePrefix}screen-${modifierName}`) {
                decl.remove()
              }
            })

            let hasNotNull = false
            rule.walkDecls((decl) => {
              if (
                decl.value !== 0 &&
                decl.value !== '0' &&
                decl.value !== '0px' &&
                (config('disableSctrictMode')
                  ? /^-?(\.\d+|\d+(\.\d+)?)(px)?$/.test(decl.value)
                  : /^-?(\.\d+|\d+(\.\d+)?)px$/.test(decl.value))
              ) {
                styles.push({
                  value: decl.value.split('px')[0],
                  important: decl.important,
                  prop: decl.prop,
                })

                decl.remove()
                hasNotNull = true
              }
            })

            if (hasNotNull) {
              styles.forEach((style) => {
                rule.prepend(
                  postcss.decl({
                    prop: style.prop,
                    value: options.rawRenderToVW
                      ? `${vw(
                          style.value,
                          screenSize,
                          options.enableScaleUtilities,
                        )} /* ${style.value}px */`
                      : `calc((${style.value} / var(${cssVariablePrefix}screen-${
                          modifierName ?? breakpointName
                        })) * var(${cssVariablePrefix}screen-max)${
                          options.enableScaleUtilities
                            ? ' * var(' + cssVariablePrefix + 'scale)'
                            : ''
                        })`,
                    important: style.important,
                  }),
                )
              })

              if (modifierName) {
                if (
                  modifierName.includes('arbitrary') ||
                  config('experimental.optimizeUniversalDefaults')
                ) {
                  rule.append(
                    postcss.decl({
                      prop: `${cssVariablePrefix}screen-${modifierName}`,
                      value: String(screenSize).replace('px', ''),
                    }),
                  )
                }
              } else if (
                breakpointName.includes('arbitrary') ||
                config('experimental.optimizeUniversalDefaults')
              ) {
                rule.append(
                  postcss.decl({
                    prop: `${cssVariablePrefix}screen-${breakpointName}`,
                    value: String(screenSize).replace('px', ''),
                  }),
                )
              }
            }
          })

          // Only add a `@media (min-width: SCREEN_SIZE)` query
          // - if the breakpoint name is not "default" and there is no arbitrary modifier
          if (breakpointName !== 'default' && modifier !== undefined) {
            // - if the screen size is larger than 0
            if (
              value !== 0 &&
              value !== '0' &&
              value !== '0px' &&
              (config('disableSctrictMode')
                ? /^-?(\.\d+|\d+(\.\d+)?)(px)?$/.test(value)
                : /^-?(\.\d+|\d+(\.\d+)?)px$/.test(value))
            ) {
              let parsed = ((numericValue) => (numericValue === null ? null : parseFloat(value)))(
                value.match(/^(\d+\.\d+|\d+|\.\d+)\D+/)?.[1] ?? null,
              )
              return parsed !== null ? `@media (min-width: ${value})` : []
            }
          }
          // otherwise don't return anything, so the `@media` query is not added
        },
        {
          values: theme('screens'),
        },
      )

      // Utility to change the screen size
      matchUtilities(
        {
          '@screen': (value, { modifier }) => {
            // Setup breakpoint name
            let screenName =
              Object.keys(screens).find((key) => screens[key] === value) ??
              'arbitrary-' + value.replace('px', '').replace('[', '').replace(']', '')

            if (screenName == 'DEFAULT') {
              screenName = 'default'
            }

            // modifier
            if (modifier) {
              modifier = modifier.replace('[', '').replace(']', '')
              modifier = vwScreens[modifier]
                ? String(vwScreens[modifier]).replace('px', '')
                : String(modifier).replace('px', '')
            }

            return {
              [`${cssVariablePrefix}screen-${screenName}`]: modifier ?? value.replace('px', ''),
            }
          },
        },
        {
          values: theme('screens'),
          modifiers: 'any',
        },
      )

      // Utility to change the scale
      if (options.enableScaleUtilities) {
        matchUtilities(
          {
            '@scale': (value) => {
              return {
                [`${cssVariablePrefix}scale`]: value,
              }
            },
          },
          {
            values: theme('vwScale'),
          },
        )
      }
    }
  },
  function (options) {
    return {
      cssVariablePrefix: 'tw-', // The prefix for the CSS variables, default is "--tw-", if you want to use nothing, set it to an empty string ("")
      rawRenderToVW: false, // If set to true, the plugin will render the value to "vw" without the `calc()` function
      enableScaleUtilities: false, // If set to true, the plugin will enable the `@scale` utility and append the `{prefix}scale` CSS variable to the `:root` element and to the `@{breakpoint}/{screen}:` and `@@:` variants
      disableSctrictMode: false, // If set to true, the plugin will not check if the value is a valid number with "px" at the end
      theme: {
        vwScale: {
          DEFAULT: '1',
          reset: '1',
          0.1: '0.1',
          0.2: '0.2',
          0.25: '0.25',
          0.33: '0.333333',
          0.4: '0.4',
          0.5: '0.5',
          0.6: '0.6',
          0.66: '0.666667',
          0.75: '0.75',
          0.8: '0.8',
          0.9: '0.9',
          1: '1',
          1.1: '1.1',
          1.2: '1.2',
          1.25: '1.25',
          1.33: '1.333333',
          1.4: '1.4',
          1.5: '1.5',
          1.6: '1.6',
          1.66: '1.666667',
          1.75: '1.75',
          1.8: '1.8',
          1.9: '1.9',
          2: '2',
          2.25: '2.25',
          2.5: '2.5',
          2.75: '2.75',
          3: '3',
          3.5: '3.5',
          4: '4',
          4.5: '4.5',
          5: '5',
          6: '6',
          7: '7',
          8: '8',
          9: '9',
          10: '10',
          '1/5': '0.2',
          '1/4': '0.25',
          '1/3': '0.333333',
          '2/5': '0.4',
          '1/2': '0.5',
          '2/4': '0.5',
          '3/5': '0.6',
          '2/3': '0.666667',
          '3/4': '0.75',
          '4/5': '0.8',
        },
      },
    }
  },
)

/**
 * Utility to convert default TailwindCSS theme values to pixels
 * - Description:   Its usage is necessary to be able to convert base TailwindCSS values defined in "em" or "rem" to "px" values so that they can be used with the `@@` and `@` variants
 * - Usage:         1. First you need to `@import { themeResetPixels } from "tailwind-vw"` in your `tailwind.config.js` file
 *                  2. Then add the `...themeResetPixels` with the spread syntax to be the first item the `theme` object
 */

exports.themeResetPixels = {
  fontSize: {
    xs: '12px',
    sm: '14px',
    base: '16px',
    lg: '18px',
    xl: '20px',
    '2xl': '24px',
    '3xl': '30px',
    '4xl': '36px',
    '5xl': '48px',
    '6xl': '60px',
    '7xl': '72px',
  },
  letterSpacing: {
    tighter: '-0.8px',
    tight: '-0.4px',
    normal: '0',
    wide: '0.4px',
    wider: '0.8px',
    widest: '1.6px',
  },
  spacing: {
    px: '1px',
    0: '0px',
    0.5: '2px',
    1: '4px',
    1.5: '6px',
    2: '8px',
    2.5: '10px',
    3: '12px',
    3.5: '14px',
    4: '16px',
    5: '20px',
    6: '24px',
    7: '28px',
    8: '32px',
    9: '36px',
    10: '40px',
    11: '44px',
    12: '48px',
    14: '56px',
    16: '64px',
    20: '80px',
    24: '96px',
    28: '112px',
    32: '128px',
    36: '144px',
    40: '160px',
    44: '176px',
    48: '192px',
    52: '208px',
    56: '224px',
    60: '240px',
    64: '256px',
    72: '288px',
    80: '320px',
    96: '384px',
  },
  borderRadius: {
    none: '0',
    sm: '2px',
    DEFAULT: '4px',
    md: '6px',
    lg: '8px',
    xl: '12px',
    '2xl': '16px',
    '3xl': '24px',
    full: '9999px',
  },
  lineHeight: {
    3: '12px',
    4: '16px',
    5: '20px',
    6: '24px',
    7: '28px',
    8: '32px',
    9: '36px',
    10: '40px',
    none: '1',
    tight: '1.25',
    snug: '1.375',
    normal: '1.5',
    relaxed: '1.625',
    loose: '2',
  },
}
