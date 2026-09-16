const { vw } = require('../utils/vw')

const sectionComponent = {
  'section, .section': {
    display: 'block',
    width: '100%',
    overflowX: 'clip',
    overflowY: 'visible',
  },
  '.section': {
    paddingTop: vw('var(--section-padding-y)', 'sm'),
    paddingBottom: vw('var(--section-padding-y)', 'sm'),
    '@screen md': {
      paddingTop: vw('var(--section-padding-y)', 'lg'),
      paddingBottom: vw('var(--section-padding-y)', 'lg'),
    },
  },
}

const sectionUtilities = {
  '.py-section': {
    paddingTop: vw('var(--section-padding-y)', 'sm'),
    paddingBottom: vw('var(--section-padding-y)', 'sm'),
    '@screen md': {
      paddingTop: vw('var(--section-padding-y)', 'lg'),
      paddingBottom: vw('var(--section-padding-y)', 'lg'),
    },
  },
  '.pt-section': {
    paddingTop: vw('var(--section-padding-y)', 'sm'),
    '@screen md': {
      paddingTop: vw('var(--section-padding-y)', 'lg'),
    },
  },
  '.pb-section': {
    paddingBottom: vw('var(--section-padding-y)', 'sm'),
    '@screen md': {
      paddingBottom: vw('var(--section-padding-y)', 'lg'),
    },
  },
  '.my-section': {
    marginTop: vw('var(--section-padding-y)', 'sm'),
    marginBottom: vw('var(--section-padding-y)', 'sm'),
    '@screen md': {
      marginTop: vw('var(--section-padding-y)', 'lg'),
      marginBottom: vw('var(--section-padding-y)', 'lg'),
    },
  },
  '.-my-section': {
    marginTop: vw('calc(var(--section-padding-y) * -1)', 'sm'),
    marginBottom: vw('calc(var(--section-padding-y) * -1)', 'sm'),
    '@screen md': {
      marginTop: vw('calc(var(--section-padding-y) * -1)', 'lg'),
      marginBottom: vw('calc(var(--section-padding-y) * -1)', 'lg'),
    },
  },
  '.mt-section': {
    marginTop: vw('var(--section-padding-y)', 'sm'),
    '@screen md': {
      marginTop: vw('var(--section-padding-y)', 'lg'),
    },
  },
  '.-mt-section': {
    marginTop: vw('calc(var(--section-padding-y) * -1)', 'sm'),
    '@screen md': {
      marginTop: vw('calc(var(--section-padding-y) * -1)', 'lg'),
    },
  },
  '.mb-section': {
    marginBottom: vw('var(--section-padding-y)', 'sm'),
    '@screen md': {
      marginBottom: vw('var(--section-padding-y)', 'lg'),
    },
  },
  '.-mb-section': {
    marginBottom: vw('calc(var(--section-padding-y) * -1)', 'sm'),
    '@screen md': {
      marginBottom: vw('calc(var(--section-padding-y) * -1)', 'lg'),
    },
  },
  '.top-section': {
    top: vw('var(--section-padding-y)', 'sm'),
    '@screen md': {
      top: vw('var(--section-padding-y)', 'lg'),
    },
  },
  '.-top-section': {
    top: vw('calc(var(--section-padding-y) * -1)', 'sm'),
    '@screen md': {
      top: vw('calc(var(--section-padding-y) * -1)', 'lg'),
    },
  },
  '.bottom-section': {
    bottom: vw('var(--section-padding-y)', 'sm'),
    '@screen md': {
      bottom: vw('var(--section-padding-y)', 'lg'),
    },
  },
  '.-bottom-section': {
    bottom: vw('calc(var(--section-padding-y) * -1)', 'sm'),
    '@screen md': {
      bottom: vw('calc(var(--section-padding-y) * -1)', 'lg'),
    },
  },
}

module.exports = { sectionComponent, sectionUtilities }
