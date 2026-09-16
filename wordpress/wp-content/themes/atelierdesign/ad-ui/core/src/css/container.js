const { vw } = require('../utils/vw')

const container = {
  '.container': {
    width: '100%',
    marginLeft: 'auto',
    marginRight: 'auto',
    maxWidth: vw('var(--container-width)', 'sm'),
    '@screen md': {
      maxWidth: vw('var(--container-width)', 'lg'),
    },
  },
  '.w-container': {
    width: vw('var(--container-width)', 'sm'),
    '@screen md': {
      width: vw('var(--container-width)', 'lg'),
    },
  },
  '.max-w-container': {
    maxWidth: vw('var(--container-width)', 'sm'),
    '@screen md': {
      maxWidth: vw('var(--container-width)', 'lg'),
    },
  },
  '.mx-container': {
    marginLeft: vw('var(--container-margin)', 'sm'),
    marginRight: vw('var(--container-margin)', 'sm'),
    '@screen md': {
      marginLeft: vw('var(--container-margin)', 'lg'),
      marginRight: vw('var(--container-margin)', 'lg'),
    },
  },
  '.-mx-container': {
    marginLeft: vw('calc(var(--container-margin) * -1)', 'sm'),
    marginRight: vw('calc(var(--container-margin) * -1)', 'sm'),
    '@screen md': {
      marginLeft: vw('calc(var(--container-margin) * -1)', 'lg'),
      marginRight: vw('calc(var(--container-margin) * -1)', 'lg'),
    },
  },
  '.ml-container': {
    marginLeft: vw('var(--container-margin)', 'sm'),
    '@screen md': {
      marginLeft: vw('var(--container-margin)', 'lg'),
    },
  },
  '.-ml-container': {
    marginLeft: vw('calc(var(--container-margin) * -1)', 'sm'),
    '@screen md': {
      marginLeft: vw('calc(var(--container-margin) * -1)', 'lg'),
    },
  },
  '.mr-container': {
    marginRight: vw('var(--container-margin)', 'sm'),
    '@screen md': {
      marginRight: vw('var(--container-margin)', 'lg'),
    },
  },
  '.-mr-container': {
    marginRight: vw('calc(var(--container-margin) * -1)', 'sm'),
    '@screen md': {
      marginRight: vw('calc(var(--container-margin) * -1)', 'lg'),
    },
  },
  '.px-container': {
    paddingLeft: vw('var(--container-margin)', 'sm'),
    paddingRight: vw('var(--container-margin)', 'sm'),
    '@screen md': {
      paddingLeft: vw('var(--container-margin)', 'lg'),
      paddingRight: vw('var(--container-margin)', 'lg'),
    },
  },
  '.pl-container': {
    paddingLeft: vw('var(--container-margin)', 'sm'),
    '@screen md': {
      paddingLeft: vw('var(--container-margin)', 'lg'),
    },
  },
  '.pr-container': {
    paddingRight: vw('var(--container-margin)', 'sm'),
    '@screen md': {
      paddingRight: vw('var(--container-margin)', 'lg'),
    },
  },
}

module.exports = { container }
