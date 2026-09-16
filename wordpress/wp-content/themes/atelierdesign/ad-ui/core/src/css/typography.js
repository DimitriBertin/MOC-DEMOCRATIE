const { tokens } = require('../handleTokens')
const { allReferencesToCssVariables } = require('../utils/referenceToCssVariable')
const { toKebabCase } = require('../utils/toKebabCase')
const { vw } = require('../utils/vw')

const resolvedTypographyTokens = allReferencesToCssVariables(tokens.textStyles)

function processTextStyleObject(object, classPrefix = 'text') {
  const cssClasses = {}

  function isStyleObject(obj) {
    // Check if this object contains style properties (has fontSize, fontFamily, etc.)
    if (!obj || typeof obj !== 'object') return false
    const styleProps = [
      'fontFamily',
      'fontSize',
      'fontWeight',
      'letterSpacing',
      'lineHeight',
      'textTransform',
      'textDecoration',
    ]
    return styleProps.some((prop) => obj.hasOwnProperty(prop))
  }

  function processResponsiveStyles(styleObj) {
    const responsiveStyleObj = {}
    const mdStyles = {}

    for (const [prop, value] of Object.entries(styleObj)) {
      if (prop === 'fontSize' || prop === 'lineHeight' || prop === 'letterSpacing') {
        // Apply vw function with responsive breakpoints
        responsiveStyleObj[prop] = vw(value, 'sm')
        mdStyles[prop] = vw(value, 'lg')
      } else {
        // Keep other properties as-is
        responsiveStyleObj[prop] = value
      }
    }

    // Add @screen md if we have responsive styles
    if (Object.keys(mdStyles).length > 0) {
      responsiveStyleObj['@screen md'] = mdStyles
    }

    return responsiveStyleObj
  }

  function flattenObject(obj, keyPath = []) {
    for (const key in obj) {
      const value = obj[key]
      const currentPath = [...keyPath, key]

      if (isStyleObject(value)) {
        // This is a style object - create CSS class with responsive styles
        const className = `.${classPrefix}-${currentPath.map((k) => toKebabCase(k)).join('-')}`
        cssClasses[className] = processResponsiveStyles(value)
      } else if (value && typeof value === 'object' && !Array.isArray(value)) {
        // This is a nested object - continue recursing
        flattenObject(value, currentPath)
      }
    }
  }

  flattenObject(object)
  return cssClasses
}

const typography = {
  ...processTextStyleObject(resolvedTypographyTokens),
  '.link': {
    color: 'var(--color-typography-link-text)',
    textDecoration: 'underline',
    textDecorationColor: 'var(--color-typography-link-underline)',
    '&:hover': {
      color: 'var(--color-typography-link-text-hover)',
      textDecorationColor: 'var(--color-typography-link-underline-hover)',
    },
  },
}

module.exports = { typography }
