const plugin = require('tailwindcss/plugin')

// Config
const animationDuration = '1s'
const animationTimingFunction = 'cubic-bezier(0.33, 1, 0.68, 1)' // Ease Out Cubic

// Adding a utility function to deep merge the colors object
function flattenColorPalette(colors) {
  const flattened = {}

  for (const [color, value] of Object.entries(colors)) {
    if (typeof value === 'string') {
      flattened[color] = value
    } else {
      for (const [shade, hex] of Object.entries(value)) {
        flattened[`${color}-${shade}`] = hex
      }
    }
  }

  return flattened
}

// Enforcing a color to be in the format of rgb(r, g, b)
const rgb = (value) => {
  if (value.startsWith('rgb')) {
    return {
      r: parseInt(value.match(/rgb(a)?\(([^)]+)\)/)[2].split(',')[0]),
      g: parseInt(value.match(/rgb(a)?\(([^)]+)\)/)[2].split(',')[1]),
      b: parseInt(value.match(/rgb(a)?\(([^)]+)\)/)[2].split(',')[2]),
    }
  }

  let hex = value
  if (!hex.startsWith('#')) {
    hex = `#${hex}`
  }

  if (hex.length === 4) {
    hex = hex.replace(/^#(.)(.)(.)$/, '#$1$1$2$2$3$3')
  }
  const r = parseInt(hex.slice(1, 3), 16)
  const g = parseInt(hex.slice(3, 5), 16)
  const b = parseInt(hex.slice(5, 7), 16)

  // return {r, g, b}
  return { r, g, b }
}

// Extending TailwindcCSS to support grids up to 24 columns
exports.extendGrid24 = {
  gridColumn: {
    'span-24': 'span 24 / span 24',
    'span-23': 'span 23 / span 23',
    'span-22': 'span 22 / span 22',
    'span-21': 'span 21 / span 21',
    'span-20': 'span 20 / span 20',
    'span-19': 'span 19 / span 19',
    'span-18': 'span 18 / span 18',
    'span-17': 'span 17 / span 17',
    'span-16': 'span 16 / span 16',
    'span-15': 'span 15 / span 15',
    'span-14': 'span 14 / span 14',
    'span-13': 'span 13 / span 13',
  },
  gridColumnStart: {
    24: '24',
    23: '23',
    22: '22',
    21: '21',
    20: '20',
    19: '19',
    18: '18',
    17: '17',
    16: '16',
    15: '15',
    14: '14',
    13: '13',
  },
  gridColumnEnd: {
    25: '25',
    24: '24',
    23: '23',
    22: '22',
    21: '21',
    20: '20',
    19: '19',
    18: '18',
    17: '17',
    16: '16',
    15: '15',
    14: '14',
  },
  gridTemplateColumns: {
    24: 'repeat(24, minmax(0, 1fr))',
    23: 'repeat(23, minmax(0, 1fr))',
    22: 'repeat(22, minmax(0, 1fr))',
    21: 'repeat(21, minmax(0, 1fr))',
    20: 'repeat(20, minmax(0, 1fr))',
    19: 'repeat(19, minmax(0, 1fr))',
    18: 'repeat(18, minmax(0, 1fr))',
    17: 'repeat(17, minmax(0, 1fr))',
    16: 'repeat(16, minmax(0, 1fr))',
    15: 'repeat(15, minmax(0, 1fr))',
    14: 'repeat(14, minmax(0, 1fr))',
    13: 'repeat(13, minmax(0, 1fr))',
  },
}

exports.pluginFlexGrid = plugin(
  function ({ addUtilities, matchUtilities, theme }) {
    addUtilities({
      '.flex-grid': {
        '--tw-flex-grid-gap-x': '0px',
        '--tw-flex-grid-gap-y': '0px',
        '--tw-flex-grid-cols': '1',
        display: 'flex',
        flexDirection: 'row',
        flexWrap: 'wrap',
        columnGap: 'calc(var(--tw-flex-grid-gap-x) - 1px)',
        rowGap: 'calc(var(--tw-flex-grid-gap-y) - 1px)',
        overflowX: 'visible',
        overflowY: 'visible',
        '> * ': {
          width:
            'round(down, calc((100% / var(--tw-flex-grid-cols)) - ((var(--tw-flex-grid-cols) - 1) * var(--tw-flex-grid-gap-x)) / var(--tw-flex-grid-cols)), 1px)',
        },
      },
    })

    matchUtilities(
      {
        'flex-grid-col-span': (value) => ({
          width: `calc((100% / (var(--tw-flex-grid-cols) / ${value})) - ((var(--tw-flex-grid-cols) / ${value} - 1) * var(--tw-flex-grid-gap-x)) / (var(--tw-flex-grid-cols) / ${value}))`,
        }),
      },
      {
        values: theme('flexGridColumn'),
      },
    )

    matchUtilities(
      {
        'flex-grid-cols': (value) => ({
          '--tw-flex-grid-cols': value,
        }),
      },
      {
        values: theme('flexGridColumn'),
      },
    )

    matchUtilities(
      {
        'flex-grid-cols': (value) => ({
          '--tw-flex-grid-cols': value,
        }),
      },
      {
        values: theme('flexGridColumn'),
      },
    )

    matchUtilities(
      {
        'flex-grid-gap': (value) => ({
          '--tw-flex-grid-gap-x': value,
          '--tw-flex-grid-gap-y': value,
        }),
      },
      {
        values: theme('spacing'),
      },
    )

    matchUtilities(
      {
        'flex-grid-gap-x': (value) => ({
          '--tw-flex-grid-gap-x': value,
        }),
      },
      {
        values: theme('spacing'),
      },
    )

    matchUtilities(
      {
        'flex-grid-gap-y': (value) => ({
          '--tw-flex-grid-gap-y': value,
        }),
      },
      {
        values: theme('spacing'),
      },
    )

    matchUtilities(
      {
        'flex-grid-divide-x': (value) => ({
          overflowX: 'clip',
          '> *': {
            position: 'relative',
            '&::before': {
              content: 'var(--tw-content)',
              position: 'absolute',
              top: 0,
              width: '0px',
              height: '100%',
              right: 'calc(-1 * (var(--tw-flex-grid-gap-x) / 2))',
              transform: 'translateX(50%)',
              borderLeftWidth: value,
              borderStyle: 'solid',
            },
            '&:last-child::before': {
              display: 'none',
            },
          },
        }),
      },
      { values: theme('divideWidth') },
    )

    matchUtilities(
      {
        'flex-grid-divide-y': (value) => ({
          overflowY: 'clip',
          '> *': {
            position: 'relative',
            '&::after': {
              content: 'var(--tw-content)',
              position: 'absolute',
              left: 0,
              width: '100%',
              height: '0px',
              bottom: 'calc(-1 * (var(--tw-flex-grid-gap-y) / 2))',
              transform: 'translateY(50%)',
              borderTopWidth: value,
              borderStyle: 'solid',
            },
            '&:last-child::after': {
              display: 'none',
            },
          },
        }),
      },
      { values: theme('divideWidth') },
    )

    matchUtilities(
      {
        'flex-grid-divide': (value, { modifier }) => ({
          '& > *::after, & > *::before': {
            borderColor: modifier
              ? `rgba(${rgb(value).r}, ${rgb(value).g}, ${rgb(value).b}, ${modifier})`
              : value,
          },
        }),
        'flex-grid-divide-x': (value, { modifier }) => ({
          '& > *::before': {
            borderColor: modifier
              ? `rgba(${rgb(value).r}, ${rgb(value).g}, ${rgb(value).b}, ${modifier})`
              : value,
          },
        }),
        'flex-grid-divide-y': (value, { modifier }) => ({
          '& > *::after': {
            borderColor: modifier
              ? `rgba(${rgb(value).r}, ${rgb(value).g}, ${rgb(value).b}, ${modifier})`
              : value,
          },
        }),
      },
      {
        values: flattenColorPalette(theme('colors')),
        modifiers: {
          0: '0',
          10: '0.1',
          20: '0.2',
          25: '0.25',
          30: '0.3',
          40: '0.4',
          50: '0.5',
          60: '0.6',
          70: '0.7',
          75: '0.75',
          80: '0.8',
          90: '0.9',
          95: '0.95',
          100: '1',
        },
      },
    )
  },
  {
    theme: {
      flexGridColumn: {
        1: '1',
        2: '2',
        3: '3',
        4: '4',
        5: '5',
        6: '6',
        7: '7',
        8: '8',
        9: '9',
        10: '10',
        11: '11',
        12: '12',
        13: '13',
        14: '14',
        15: '15',
        16: '16',
        17: '17',
        18: '18',
        19: '19',
        20: '20',
        21: '21',
        22: '22',
        23: '23',
        24: '24',
      },
    },
  },
)

// Extending TailwindcCSS with more easings
// found on https://easings.net/
exports.extendEasings = {
  transitionTimingFunction: {
    'in-sine': 'cubic-bezier(0.12, 0, 0.39, 0)',
    'out-sine': 'cubic-bezier(0.61, 1, 0.88, 1)',
    'in-out-sine': 'cubic-bezier(0.37, 0, 0.63, 1)',
    'in-quad': 'cubic-bezier(0.11, 0, 0.5, 0)',
    'out-quad': 'cubic-bezier(0.5, 1, 0.89, 1)',
    'in-out-quad': 'cubic-bezier(0.45, 0, 0.55, 1)',
    'in-cubic': 'cubic-bezier(0.32, 0, 0.67, 0)',
    'out-cubic': 'cubic-bezier(0.33, 1, 0.68, 1)',
    'in-out-cubic': 'cubic-bezier(0.65, 0, 0.35, 1)',
    'in-quart': 'cubic-bezier(0.5, 0, 0.75, 0)',
    'out-quart': 'cubic-bezier(0.25, 1, 0.5, 1)',
    'in-out-quart': 'cubic-bezier(0.76, 0, 0.24, 1)',
    'in-quint': 'cubic-bezier(0.64, 0, 0.78, 0)',
    'out-quint': 'cubic-bezier(0.22, 1, 0.36, 1)',
    'in-out-quint': 'cubic-bezier(0.83, 0, 0.17, 1)',
    'in-expo': 'cubic-bezier(0.7, 0, 0.84, 0)',
    'out-expo': 'cubic-bezier(0.16, 1, 0.3, 1)',
    'in-out-expo': 'cubic-bezier(0.87, 0, 0.13, 1)',
    'in-circ': 'cubic-bezier(0.55, 0, 1, 0.45)',
    'out-circ': 'cubic-bezier(0, 0.55, 0.45, 1)',
    'in-out-circ': 'cubic-bezier(0.85, 0, 0.15, 1)',
    'in-back': 'cubic-bezier(0.36, 0, 0.66, -0.56)',
    'out-back': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
    'in-out-back': 'cubic-bezier(0.68, -0.6, 0.32, 1.6)',
    // in-elastic, out-elastic, in-out-elastic, in-bounce, out-bounce, in-out-bounce cannot be implemented as cubic-bezier functions
  },
}

// Adding a plugin to override the default disabled: variant
// extending it to not only include &:disabled as a state but also &[disabled] as an attribute
exports.pluginOverrideDisabled = plugin(function ({ addVariant }) {
  addVariant('disabled', ['&:disabled', '&[disabled]'])
})

// Plugin to add .scrollbar-hidden utility
exports.pluginScrollbarHidden = plugin(function ({ addUtilities }) {
  addUtilities({
    '.scrollbar-hidden': {
      'scrollbar-width': 'none',
      '-ms-overflow-style': 'none',
    },
    '.scrollbar-hidden::-webkit-scrollbar': {
      display: 'none',
    },
  })
})

// Adding a plugin to create nth-* modifiers
exports.pluginNth = plugin(function ({ matchVariant }) {
  matchVariant(
    'nth',
    (value) => {
      return `&:nth-child(${value})`
    },
    {
      values: {
        even: '2n',
        odd: '2n+1',
        1: '1',
        2: '2',
        3: '3',
        4: '4',
        5: '5',
        6: '6',
        7: '7',
        8: '8',
        9: '9',
        10: '10',
        11: '11',
        12: '12',
        13: '13',
        14: '14',
        15: '15',
        16: '16',
        17: '17',
        18: '18',
        19: '19',
        20: '20',
        21: '21',
        22: '22',
        23: '23',
        24: '24',
      },
    },
  )
})

exports.extendAnimations = {
  keyframes: {
    fadeIn: {
      '0%': {
        opacity: '0',
      },
      '100%': {
        opacity: '1',
      },
    },
    fadeInZoomOut: {
      '0%': {
        opacity: '0',
        transform: 'scale(1.10)',
      },
      '100%': {
        opacity: '1',
        transform: 'scale(1)',
      },
    },
    fadeInUp: {
      '0%': {
        opacity: '0',
        transform: 'translate3d(0, 5vh, 0)',
      },
      '100%': {
        opacity: '1',
        transform: 'translate3d(0, 0, 0)',
      },
    },
    fadeInDown: {
      '0%': {
        opacity: '0',
        transform: 'translate3d(0, -15px, 0)',
      },
      '100%': {
        opacity: '1',
        transform: 'translate3d(0, 0, 0)',
      },
    },
    fadeInLeft: {
      '0%': {
        opacity: '0',
        transform: 'translate3d(-100%, 0, 0)',
      },
      '100%': {
        opacity: 1,
        transform: 'translate3d(0, 0, 0)',
      },
    },
    fadeInRight: {
      '0%': {
        opacity: '0',
        transform: 'translate3d(100%, 0, 0)',
      },
      '100%': {
        opacity: 1,
        transform: 'translate3d(0, 0, 0)',
      },
    },
    fadeOut: {
      '0%': {
        opacity: '1',
      },
      '100%': {
        opacity: '0',
      },
    },
  },
  animation: {
    fadein: `fadeIn ${animationDuration} ${animationTimingFunction} forwards`,
    fadeinzoomout: `fadeInZoomOut ${animationDuration} ${animationTimingFunction} forwards`,
    fadeinup: `fadeInUp ${animationDuration} ${animationTimingFunction} forwards`,
    fadeindown: `fadeInDown ${animationDuration} ${animationTimingFunction} forwards`,
    fadeinleft: `fadeInLeft ${animationDuration} ${animationTimingFunction} forwards`,
    fadeinright: `fadeInRight ${animationDuration} ${animationTimingFunction} forwards`,
    fadeout: `fadeOut ${animationDuration} ${animationTimingFunction} forwards`,
  },
}

exports.pluginAnimate = plugin(function ({ addUtilities }) {
  addUtilities({
    '.animate-fadein, .animate-fadeinzoomout, .animate-fadeinup, .animate-fadeindown, .animate-fadeinleft, .animate-fadeinright, .animate-fadeout':
      {
        opacity: '0',
      },
    '.animates-once': {
      animationIterationCount: '1',
    },
    '.animate-disable, .animates-on-scroll:not(.animated), .aos:not(.animated)': {
      opacity: '0 !important',
      animation: 'none !important',
    },
    '.aos-disable-children': {
      '.aos:is(.animated), .aos:not(:is(.animated)), .animates-on-scroll:is(.animated), .animates-on-scroll:not(:is(.animated))':
        {
          opacity: '1 !important',
          animation: 'none !important',
        },
    },
  })
})

exports.pluginAnimateStagger = plugin(function ({ theme, matchUtilities }) {
  // Create staggered animation delays for child elements using matchUtilities
  matchUtilities(
    {
      stagger: (value) => {
        const count = parseInt(value)
        if (isNaN(count) || count < 1 || count > 10) return {}

        const styles = {}

        // Add nth-child selectors with calculated delays
        styles[`&:nth-child(${count}n)`] = {
          'animation-delay':
            value === '1' || value === 'DEFAULT'
              ? `0ms`
              : `calc(var(--tw-stagger-delay, 300ms) * ${count - 1})`,
        }

        for (let j = 1; j < count - 1; j++) {
          styles[`&:nth-child(${count}n - ${j})`] = {
            'animation-delay': `calc(var(--tw-stagger-delay, 300ms) * ${count - 1 - j})`,
          }
        }

        styles[`&:nth-child(${count}n - ${count - 1})`] = {
          'animation-delay': '0ms',
        }

        return styles
      },
    },
    {
      values: {
        DEFAULT: '1',
        ...Object.fromEntries(
          Array.from({ length: 24 }, (_, i) => {
            const num = i + 1
            return [num.toString(), num.toString()]
          }),
        ),
      },
    },
  )

  // Add utilities to customize the stagger delay
  matchUtilities(
    {
      'stagger-delay': (value) => ({
        '--tw-stagger-delay': value,
      }),
    },
    {
      values: theme('animate', {
        DEFAULT: '300ms',
        0: '0ms',
        25: '25ms',
        50: '50ms',
        75: '75ms',
        100: '100ms',
        125: '125ms',
        150: '150ms',
        175: '175ms',
        200: '200ms',
        250: '250ms',
        300: '300ms',
        350: '350ms',
        400: '400ms',
        450: '450ms',
        500: '500ms',
        600: '600ms',
        700: '700ms',
        800: '800ms',
        900: '900ms',
        1000: '1000ms',
        1250: '1250ms',
        1500: '1500ms',
        1750: '1750ms',
        2000: '2000ms',
        2500: '2500ms',
        3000: '3000ms',
      }),
    },
  )
})

// Adding a plugin to create animate-delay-* and animation-delay-* modifiers
exports.pluginAnimateDelay = plugin(function ({ matchUtilities, theme }) {
  matchUtilities(
    {
      'animate-delay': (value) => ({
        'animation-delay': `${value}`,
      }),
      'animate-duration': (value) => ({
        'animation-duration': `${value}`,
      }),
    },
    {
      values: theme('animate', {
        0: '0ms',
        25: '25ms',
        50: '50ms',
        75: '75ms',
        100: '100ms',
        125: '125ms',
        150: '150ms',
        175: '175ms',
        200: '200ms',
        225: '225ms',
        250: '250ms',
        275: '275ms',
        300: '300ms',
        325: '325ms',
        350: '350ms',
        375: '375ms',
        400: '400ms',
        425: '425ms',
        450: '450ms',
        475: '475ms',
        500: '500ms',
        525: '525ms',
        550: '550ms',
        575: '575ms',
        600: '600ms',
        625: '625ms',
        650: '650ms',
        675: '675ms',
        700: '700ms',
        725: '725ms',
        750: '750ms',
        775: '775ms',
        800: '800ms',
        825: '825ms',
        850: '850ms',
        875: '875ms',
        900: '900ms',
        925: '925ms',
        950: '950ms',
        975: '975ms',
        1000: '1000ms',
        1250: '1250ms',
        1500: '1500ms',
        1750: '1750ms',
        2000: '2000ms',
        2250: '2250ms',
        2500: '2500ms',
        2750: '2750ms',
        3000: '3000ms',
      }),
    },
  )
})

// Adding a plugin to create is-* modifiers for the :is() pseudo-class
exports.pluginIs = plugin(function ({ matchVariant }) {
  matchVariant('is', (value) => {
    return `&:is(${value})`
  })
  matchVariant('group-is', (value, { modifier }) => {
    return modifier
      ? `:merge(.group\\/${modifier}):is(${value}) &`
      : `:merge(.group):is(${value}) &`
  })
  matchVariant('peer-is', (value, { modifier }) => {
    return modifier
      ? `:merge(.peer\\/${modifier}):is(${value}) ~ &`
      : `:merge(.peer):is(${value}) ~ &`
  })
})

// Adding a plugin to create not-* modifiers for the :not() pseudo-class
exports.pluginNot = plugin(function ({ matchVariant }) {
  matchVariant('not', (value) => {
    return `&:not(${value})`
  })
  matchVariant('group-not', (value, { modifier }) => {
    return modifier
      ? `:merge(.group\\/${modifier}):not(${value}) &`
      : `:merge(.group):not(${value}) &`
  })
  matchVariant('peer-not', (value, { modifier }) => {
    return modifier
      ? `:merge(.peer\\/${modifier}):not(${value}) ~ &`
      : `:merge(.peer):not(${value}) ~ &`
  })
})

// Adding a plugin to create minmax-* utilities to set `@media (min-width: *) and (max-width: *)` rules together
exports.pluginMinMax = plugin(function ({ matchVariant, theme }) {
  const minMaxClasses = ['minmax', 'mm']

  minMaxClasses.forEach((className) => {
    matchVariant(
      className,
      (value, { modifier }) => {
        // Get screens from the theme
        const screens = theme('screens')

        // order screens by size
        Object.keys(screens).sort((a, b) => screens[a] - screens[b])

        // setup breakpoint name
        let breakpointName = Object.keys(screens).find((key) => screens[key] === value) ?? false

        let breakpoint = screens[breakpointName] ?? value
        breakpoint = parseFloat(breakpoint)

        // get index of the current breakpoint
        let breakpointIndex = Object.keys(screens).indexOf(breakpointName)

        // Get next breakpoint in the list
        let nextBreakpointName = Object.keys(screens)[breakpointIndex + 1] ?? false

        let nextBreakpoint = screens[nextBreakpointName] ?? false
        nextBreakpoint = nextBreakpoint ? parseFloat(nextBreakpoint) - 1 : false

        // if there is a modifier, use it as the max-width
        if (modifier) {
          modifier = modifier.replace('[', '').replace(']', '')
          nextBreakpointName = modifier
          nextBreakpoint = screens[nextBreakpointName]
            ? parseFloat(screens[nextBreakpointName]) - 1
            : parseFloat(modifier)
        }

        // construct the media query
        if (parseFloat(breakpoint) === 0) {
          if (nextBreakpoint) {
            return `@media (max-width: ${nextBreakpoint}px)`
          } else {
            return
          }
        } else {
          if (nextBreakpoint) {
            return `@media (min-width: ${breakpoint}px) and (max-width: ${nextBreakpoint}px)`
          } else {
            return `@media (min-width: ${breakpoint}px)`
          }
        }
      },
      {
        values: theme('screens', {
          sm: '640px',
          md: '768px',
          lg: '1024px',
          xl: '1280px',
          '2xl': '1536px',
        }),
      },
    )
  })
})

exports.pluginOrientation = plugin(function ({ addVariant, matchVariant }) {
  addVariant('portrait', '@media (orientation: portrait)')
  addVariant('vertical', '@media (orientation: portrait)')
  addVariant('orientation-y', '@media (orientation: portrait)')
  addVariant('landscape', '@media (orientation: landscape)')
  addVariant('horizontal', '@media (orientation: landscape)')
  addVariant('orientation-x', '@media (orientation: landscape)')
  matchVariant(
    'screen-min-aspect',
    (value) => {
      return `@media (min-aspect-ratio: ${value})`
    },
    {
      values: {
        square: '1/1',
        video: '16/9',
      },
    },
  )
  matchVariant(
    'screen-max-aspect',
    (value) => {
      return `@media (max-aspect-ratio: ${value})`
    },
    {
      values: {
        square: '1/1',
        video: '16/9',
      },
    },
  )
})

exports.pluginAutoLineHeight = plugin.withOptions(
  function (options = {}) {
    return function ({ matchUtilities, theme }) {
      // 1 / cosh(x) = sech(x)
      const sech = (value) => {
        return 1 / Math.cosh(value)
      }

      let minLineHeight = options.minLineHeight
      let maxLineHeight = options.maxLineHeight
      let power = options.power
      let smallestText = options.smallestText

      const round = (n) => {
        return Math.round(n * 1000) / 1000
      }

      matchUtilities(
        {
          autotext: (value) => ({
            fontSize: `${parseFloat(value)}px`,
            lineHeight: options.lineHeightScale
              ? `calc(${
                  parseFloat(value) > smallestText
                    ? round(
                        sech((parseFloat(value) - smallestText) / (10 * power)) *
                          (maxLineHeight - minLineHeight) +
                          minLineHeight,
                      )
                    : maxLineHeight
                } * var(--lh-scale, 1))`
              : parseFloat(value) > smallestText
                ? round(
                    sech((parseFloat(value) - smallestText) / (10 * power)) *
                      (maxLineHeight - minLineHeight) +
                      minLineHeight,
                  )
                : maxLineHeight,
          }),
        },
        { values: theme('fontSize') },
      )
    }
  },
  function (options) {
    return {
      minLineHeight: 1,
      maxLineHeight: 2,
      power: 2.5,
      smallestText: 14,
      lineHeightScale: false,
    }
  },
)
