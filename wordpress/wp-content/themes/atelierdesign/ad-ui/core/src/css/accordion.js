const { tokens, resolveColorToPrimitive, findThemeByColorValue } = require('../handleTokens')
const { getFirstKey } = require('../utils/getFirstKey')
const { toKebabCase } = require('../utils/toKebabCase')
const { vw } = require('../utils/vw')
const { extendEasings } = require('../tailwind/utils')

const accordion = {
  ':where(.accordion-flat, .accordion-outline, .accordion-underline, .accordion-none)': {
    display: 'flex',
    flexDirection: 'column',
  },
  ...(typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].accordion.flat ==
  'object'
    ? {
        // Flat
        ':where(.accordion-flat)': {
          paddingInline: vw('var(--accordion-flat-padding-x)', 'sm'),
          paddingBlock: vw('var(--accordion-flat-padding-y)', 'sm'),
          borderStyle: 'solid',
          borderWidth: vw('var(--accordion-flat-border-width)', 'sm'),
          borderRadius: vw('var(--accordion-flat-border-radius)', 'sm'),
          boxShadow: `${vw('var(--accordion-flat-shadow-x)', 'sm')} ${vw('var(--accordion-flat-shadow-y)', 'sm')} ${vw('var(--accordion-flat-shadow-blur)', 'sm')} ${vw('var(--accordion-flat-shadow-spread)', 'sm')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          '@screen md': {
            paddingInline: vw('var(--accordion-flat-padding-x)', 'lg'),
            paddingBlock: vw('var(--accordion-flat-padding-y)', 'lg'),
            borderWidth: vw('var(--accordion-flat-border-width)', 'lg'),
            borderRadius: vw('var(--accordion-flat-border-radius)', 'lg'),
            boxShadow: `${vw('var(--accordion-flat-shadow-x)', 'lg')} ${vw('var(--accordion-flat-shadow-y)', 'lg')} ${vw('var(--accordion-flat-shadow-blur)', 'lg')} ${vw('var(--accordion-flat-shadow-spread)', 'lg')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          },
        },
        ':where(.accordion-flat .accordion-content-wrapper > *:first-child)': {
          paddingTop: vw('var(--accordion-flat-content-gap)', 'sm'),
          '@screen md': {
            paddingTop: vw('var(--accordion-flat-content-gap)', 'lg'),
          },
        },
        ...Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].accordion).reduce(
          (acc, color) => {
            acc[`:where(.accordion-flat.accordion-${toKebabCase(color)})`] = {
              backgroundColor: `var(--color-accordion-${toKebabCase(color)}-flat-background)`,
              borderColor: `var(--color-accordion-${toKebabCase(color)}-flat-border)`,
              transitionProperty:
                'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
              transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
              transitionDuration: '150ms',
              '--tw-shadow-color': `var(--color-accordion-${toKebabCase(color)}-flat-shadow)`,
              '--tw-shadow': 'var(--tw-shadow-colored)',
            }
            acc[`:where(.accordion-flat.accordion-${toKebabCase(color)} .accordion-title)`] = {
              color: `var(--color-accordion-${toKebabCase(color)}-flat-title)`,
              transitionProperty:
                'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
              transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
              transitionDuration: '150ms',
            }
            acc[`:where(.accordion-flat.accordion-${toKebabCase(color)} .accordion-title-icon)`] = {
              color: `var(--color-accordion-${toKebabCase(color)}-flat-icon)`,
            }
            ;((acc[
              `:where(.accordion-flat.accordion-${toKebabCase(color)} .accordion-title:hover, .accordion-flat.accordion-${toKebabCase(color)}.is-active .accordion-title)`
            ] = {
              color: `var(--color-accordion-${toKebabCase(color)}-flat-title-active)`,
            }),
              (acc[
                `:where(.accordion-flat.accordion-${toKebabCase(color)} .accordion-title:hover .accordion-title-icon, .accordion-flat.accordion-${toKebabCase(color)}.is-active .accordion-title-icon)`
              ] = {
                color: `var(--color-accordion-${toKebabCase(color)}-flat-icon-active)`,
              }))

            return acc
          },
          {},
        ),
      }
    : {}),
  ...(typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].accordion.outline ==
  'object'
    ? {
        ':where(.accordion-outline)': {
          paddingInline: vw('var(--accordion-outline-padding-x)', 'sm'),
          paddingBlock: vw('var(--accordion-outline-padding-y)', 'sm'),
          borderStyle: 'solid',
          borderWidth: vw('var(--accordion-outline-border-width)', 'sm'),
          borderRadius: vw('var(--accordion-outline-border-radius)', 'sm'),
          boxShadow: `${vw('var(--accordion-outline-shadow-x)', 'sm')} ${vw('var(--accordion-outline-shadow-y)', 'sm')} ${vw('var(--accordion-outline-shadow-blur)', 'sm')} ${vw('var(--accordion-outline-shadow-spread)', 'sm')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          '@screen md': {
            paddingInline: vw('var(--accordion-outline-padding-x)', 'lg'),
            paddingBlock: vw('var(--accordion-outline-padding-y)', 'lg'),
            borderWidth: vw('var(--accordion-outline-border-width)', 'lg'),
            borderRadius: vw('var(--accordion-outline-border-radius)', 'lg'),
            boxShadow: `${vw('var(--accordion-outline-shadow-x)', 'lg')} ${vw('var(--accordion-outline-shadow-y)', 'lg')} ${vw('var(--accordion-outline-shadow-blur)', 'lg')} ${vw('var(--accordion-outline-shadow-spread)', 'lg')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          },
        },
        ':where(.accordion-outline .accordion-content-wrapper > *:first-child)': {
          paddingTop: vw('var(--accordion-outline-content-gap)', 'sm'),
          '@screen md': {
            paddingTop: vw('var(--accordion-outline-content-gap)', 'lg'),
          },
        },
        ...Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].accordion).reduce(
          (acc, color) => {
            acc[`:where(.accordion-outline.accordion-${toKebabCase(color)})`] = {
              borderColor: `var(--color-accordion-${toKebabCase(color)}-outline-border)`,
              transitionProperty:
                'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
              transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
              transitionDuration: '150ms',
              '--tw-shadow-color': `var(--color-accordion-${toKebabCase(color)}-outline-shadow)`,
              '--tw-shadow': 'var(--tw-shadow-colored)',
            }
            acc[`:where(.accordion-outline.accordion-${toKebabCase(color)} .accordion-title)`] = {
              color: `var(--color-accordion-${toKebabCase(color)}-outline-title)`,
              transitionProperty:
                'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
              transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
              transitionDuration: '150ms',
            }
            acc[
              `:where(.accordion-outline.accordion-${toKebabCase(color)} .accordion-title-icon)`
            ] = {
              color: `var(--color-accordion-${toKebabCase(color)}-outline-icon)`,
            }
            acc[
              `:where(.accordion-outline.accordion-${toKebabCase(color)} .accordion-title:hover, .accordion-outline.accordion-${toKebabCase(color)}.is-active .accordion-title)`
            ] = {
              color: `var(--color-accordion-${toKebabCase(color)}-outline-title-active)`,
            }
            acc[
              `:where(.accordion-outline.accordion-${toKebabCase(color)} .accordion-title:hover .accordion-title-icon, .accordion-outline.accordion-${toKebabCase(color)}.is-active .accordion-title-icon)`
            ] = {
              color: `var(--color-accordion-${toKebabCase(color)}-outline-icon-active)`,
            }

            return acc
          },
          {},
        ),
      }
    : {}),
  ...(typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].accordion.underline ==
  'object'
    ? {
        ':where(.accordion-underline)': {
          paddingInline: vw('var(--accordion-underline-padding-x)', 'sm'),
          paddingBottom: vw('var(--accordion-underline-padding-y)', 'sm'),
          borderStyle: 'solid',
          borderBottomWidth: vw('var(--accordion-underline-border-width)', 'sm'),
          boxShadow: `${vw('var(--accordion-underline-shadow-x)', 'sm')} ${vw('var(--accordion-underline-shadow-y)', 'sm')} ${vw('var(--accordion-underline-shadow-blur)', 'sm')} ${vw('var(--accordion-underline-shadow-spread)', 'sm')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          '@screen md': {
            paddingInline: vw('var(--accordion-underline-padding-x)', 'lg'),
            paddingBottom: vw('var(--accordion-underline-padding-y)', 'lg'),
            borderBottomWidth: vw('var(--accordion-underline-border-width)', 'lg'),
            boxShadow: `${vw('var(--accordion-underline-shadow-x)', 'lg')} ${vw('var(--accordion-underline-shadow-y)', 'lg')} ${vw('var(--accordion-underline-shadow-blur)', 'lg')} ${vw('var(--accordion-underline-shadow-spread)', 'lg')} var(--tw-shadow-color, rgba(0, 0, 0, 0.25))`,
          },
        },
        ':where(.accordion-underline .accordion-content-wrapper > *:first-child)': {
          paddingTop: vw('var(--accordion-underline-content-gap)', 'sm'),
          '@screen md': {
            paddingTop: vw('var(--accordion-underline-content-gap)', 'lg'),
          },
        },
        ...Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].accordion).reduce(
          (acc, color) => {
            acc[`:where(.accordion-underline.accordion-${toKebabCase(color)})`] = {
              borderColor: `var(--color-accordion-${toKebabCase(color)}-underline-border)`,
              transitionProperty:
                'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
              transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
              transitionDuration: '150ms',
              '--tw-shadow-color': `var(--color-accordion-${toKebabCase(color)}-underline-shadow)`,
              '--tw-shadow': 'var(--tw-shadow-colored)',
            }
            acc[`:where(.accordion-underline.accordion-${toKebabCase(color)} .accordion-title)`] = {
              color: `var(--color-accordion-${toKebabCase(color)}-underline-title)`,
              transitionProperty:
                'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
              transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
              transitionDuration: '150ms',
            }
            acc[
              `:where(.accordion-underline.accordion-${toKebabCase(color)} .accordion-title-icon)`
            ] = {
              color: `var(--color-accordion-${toKebabCase(color)}-underline-icon)`,
            }
            acc[
              `:where(.accordion-underline.accordion-${toKebabCase(color)} .accordion-title:hover, .accordion-underline.accordion-${toKebabCase(color)}.is-active .accordion-title)`
            ] = {
              color: `var(--color-accordion-${toKebabCase(color)}-underline-title-active)`,
            }
            acc[
              `:where(.accordion-underline.accordion-${toKebabCase(color)} .accordion-title:hover .accordion-title-icon, .accordion-underline.accordion-${toKebabCase(color)}.is-active .accordion-title-icon)`
            ] = {
              color: `var(--color-accordion-${toKebabCase(color)}-underline-icon-active)`,
            }

            return acc
          },
          {},
        ),
      }
    : {}),
  ...(typeof tokens.responsiveSizing[getFirstKey(tokens.responsiveSizing)].accordion.none ==
  'object'
    ? {
        ':where(.accordion-none .accordion-content-wrapper > *:first-child)': {
          paddingTop: vw('var(--accordion-none-content-gap)', 'sm'),
          '@screen md': {
            paddingTop: vw('var(--accordion-none-content-gap)', 'lg'),
          },
        },
        ...Object.keys(tokens.colorSystem[getFirstKey(tokens.colorSystem)].accordion).reduce(
          (acc, color) => {
            acc[`:where(.accordion-none.accordion-${toKebabCase(color)})`] = {
              transitionProperty:
                'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
              transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
              transitionDuration: '150ms',
            }
            acc[`:where(.accordion-none.accordion-${toKebabCase(color)} .accordion-title)`] = {
              color: `var(--color-accordion-${toKebabCase(color)}-none-title)`,
              transitionProperty:
                'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
              transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
              transitionDuration: '150ms',
            }
            acc[`:where(.accordion-none.accordion-${toKebabCase(color)} .accordion-title-icon)`] = {
              color: `var(--color-accordion-${toKebabCase(color)}-none-icon)`,
            }
            acc[
              `:where(.accordion-none.accordion-${toKebabCase(color)} .accordion-title:hover, .accordion-none.accordion-${toKebabCase(color)}.is-active .accordion-title)`
            ] = {
              color: `var(--color-accordion-${toKebabCase(color)}-none-title-active)`,
            }
            acc[
              `:where(.accordion-none.accordion-${toKebabCase(color)} .accordion-title:hover .accordion-title-icon, .accordion-none.accordion-${toKebabCase(color)}.is-active .accordion-title-icon)`
            ] = {
              color: `var(--color-accordion-${toKebabCase(color)}-none-icon-active)`,
            }
            return acc
          },
          {},
        ),
      }
    : {}),
  '.accordion-title': {
    position: 'relative',
    zIndex: '10',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    cursor: 'pointer',
    userSelect: 'none',
  },
  '.accordion-title-text': {
    display: 'block',
    flexGrow: '1',
    flexShrink: '1',
    '@apply text-accordion-title': {},
  },
  ':where(.accordion-icon)': {
    display: 'inline-block',
    overflow: 'visible',
    objectFit: 'contain',
    objectPosition: 'center',
    flexShrink: '0',
    flexGrow: '0',
    transform: 'rotate(0deg)',
    transitionProperty:
      'transform, color, background-color, border-color, text-decoration-color, fill, stroke',
    transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
    transitionDuration: '150ms',
    width: vw('var(--typography-accordion-icon-size)', 'sm'),
    height: vw('var(--accordion-icon-size)', 'sm'),
    '@screen md': {
      width: vw('var(--typography-accordion-icon-size)', 'lg'),
      height: vw('var(--typography-accordion-icon-size)', 'lg'),
    },
  },
  ':where(.group\\\/accordion:is(.is-active) .accordion-icon)': {
    transform: 'rotate(90deg)',
  },
  ':where(.accordion-content)': {
    display: 'grid',
    gridTemplateRows: '0fr',
    transitionProperty: 'grid-template-rows',
    transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
    transitionDuration: '300ms',
  },
  '.accordion-content': {
    '.aos:is(.animated), .aos:not(:is(.animated)), .animates-on-scroll:is(.animated), .animates-on-scroll:not(:is(.animated))':
      {
        opacity: '1 !important',
        animation: 'none !important',
      },
  },
  ':where(.is-active .accordion-content)': {
    gridTemplateRows: '1fr',
  },
  ':where(.accordion-content-wrapper)': {
    overflow: 'hidden',
  },
}

module.exports = { accordion }
