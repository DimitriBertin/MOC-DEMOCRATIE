const { vw } = require('../utils/vw')

const content = {
  '.mx-content': {
    marginLeft: vw('var(--content-padding-x)', 'sm'),
    marginRight: vw('var(--content-padding-x)', 'sm'),
    '@screen md': {
      marginLeft: vw('var(--content-padding-x)', 'lg'),
      marginRight: vw('var(--content-padding-x)', 'lg'),
    },
  },
  '.-mx-content': {
    marginLeft: vw('calc(var(--content-padding-x) * -1)', 'sm'),
    marginRight: vw('calc(var(--content-padding-x) * -1)', 'sm'),
    '@screen md': {
      marginLeft: vw('calc(var(--content-padding-x) * -1)', 'lg'),
      marginRight: vw('calc(var(--content-padding-x) * -1)', 'lg'),
    },
  },
  '.ml-content': {
    marginLeft: vw('var(--content-padding-x)', 'sm'),
    '@screen md': {
      marginLeft: vw('var(--content-padding-x)', 'lg'),
    },
  },
  '.-ml-content': {
    marginLeft: vw('calc(var(--content-padding-x) * -1)', 'sm'),
    '@screen md': {
      marginLeft: vw('calc(var(--content-padding-x) * -1)', 'lg'),
    },
  },
  '.mr-content': {
    marginRight: vw('var(--content-padding-x)', 'sm'),
    '@screen md': {
      marginRight: vw('var(--content-padding-x)', 'lg'),
    },
  },
  '.-mr-content': {
    marginRight: vw('calc(var(--content-padding-x) * -1)', 'sm'),
    '@screen md': {
      marginRight: vw('calc(var(--content-padding-x) * -1)', 'lg'),
    },
  },
  '.px-content': {
    paddingLeft: vw('var(--content-padding-x)', 'sm'),
    paddingRight: vw('var(--content-padding-x)', 'sm'),
    '@screen md': {
      paddingLeft: vw('var(--content-padding-x)', 'lg'),
      paddingRight: vw('var(--content-padding-x)', 'lg'),
    },
  },
  '.pl-content': {
    paddingLeft: vw('var(--content-padding-x)', 'sm'),
    '@screen md': {
      paddingLeft: vw('var(--content-padding-x)', 'lg'),
    },
  },
  '.pr-content': {
    paddingRight: vw('var(--content-padding-x)', 'sm'),
    '@screen md': {
      paddingRight: vw('var(--content-padding-x)', 'lg'),
    },
  },
}

module.exports = { content }
