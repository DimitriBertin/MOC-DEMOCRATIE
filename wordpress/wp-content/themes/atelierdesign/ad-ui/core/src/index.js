const { tokens } = require('./handleTokens')
const { cleanObjectFromTypes } = require('./utils/cleanObjectFromTypes')
const { deepMerge, deepMergeReplace, isObject, isArray } = require('./utils/deepMerge')
const { flattenTokensObject } = require('./utils/flattenTokensObject')
const { getFirstKey } = require('./utils/getFirstKey')
const {
  isReference,
  isTailwindReference,
  isConfigReference,
  isCalcReference,
} = require('./utils/isReference')
const { normalizeCssValue, allCssValuesNormalized } = require('./utils/normalizeCssValue')
const { normalizeFontWeightAndStyle } = require('./utils/normalizeFontWeightAndStyle')
const {
  referenceToCssVariable,
  allReferencesToCssVariables,
} = require('./utils/referenceToCssVariable')
const { removeFalseValues } = require('./utils/removeFalseValues')
const {
  removePx,
  removeAllPxFromObject,
  removePxToNumber,
  removeAllPxFromObjectToNumbers,
  addPxUnit,
  addPxUnitToObject,
} = require('./utils/removePx')
const { toKebabCase, toUrlCase } = require('./utils/toKebabCase')
const { vw } = require('./utils/vw')

module.exports = {
  tokens,
  cleanObjectFromTypes,
  deepMerge,
  deepMergeReplace,
  isObject,
  isArray,
  flattenTokensObject,
  getFirstKey,
  isReference,
  isTailwindReference,
  isConfigReference,
  isCalcReference,
  normalizeCssValue,
  allCssValuesNormalized,
  normalizeFontWeightAndStyle,
  referenceToCssVariable,
  allReferencesToCssVariables,
  removeFalseValues,
  removePx,
  removeAllPxFromObject,
  removePxToNumber,
  removeAllPxFromObjectToNumbers,
  addPxUnit,
  addPxUnitToObject,
  toKebabCase,
  toUrlCase,
  vw,
}
