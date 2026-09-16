const { vw } = require('../utils/vw')

// separator
// const xsElemMarginY = '48'

// icon, button, badge
const smElemMarginY = '24'

// wysiwyg, logos-wrapper, quote, key-numbers
const mdElemMarginY = '24'

// accordion, card, media, google-map, group
const lgElemMarginY = '24'

// columns, (advanced) layout
const xlElemMarginY = '24'

const layout = {
  html: {
    overflowX: 'clip',
    overflowY: 'auto',
  },
  body: {
    position: 'relative',
    minHeight: '100dvh',
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'space-between',
  },
  '.no-transition, .no-transition *': {
    transition: 'none !important',
  },
  'header, footer, main, section': {
    width: '100%',
  },
  main: {
    flexGrow: 1,
  },
  // ':where(.inline-flexible > .separator-wrapper)': {
  //   marginBlock: vw(xsElemMarginY, 'sm'),
  //   '@screen md': {
  //     marginBlock: vw(xsElemMarginY, 'md'),
  //   },
  // },
  ':where(.inline-flexible > .icon-wrapper, .inline-flexible > .button-wrapper, .inline-flexible > .badge-wrapper)':
    {
      marginBlock: vw(smElemMarginY, 'sm'),
      '@screen md': {
        marginBlock: vw(smElemMarginY, 'md'),
      },
    },
  ':where(.inline-flexible > .button-wrapper + .button-wrapper)': {
    marginTop: vw(`${smElemMarginY * 0.75} * -1`, 'sm'),
    '@screen md': {
      marginTop: vw(`${smElemMarginY * 0.75} * -1`, 'md'),
    },
  },
  ':where(.inline-flexible > .wysiwyg, .inline-flexible > .logos-wrapper, .inline-flexible > .quote-wrapper, .inline-flexible > .key-numbers-wrapper)':
    {
      marginBlock: vw(mdElemMarginY, 'sm'),
      '@screen md': {
        marginBlock: vw(mdElemMarginY, 'md'),
      },
    },
  ':where(.inline-flexible > .accordion-wrapper, .inline-flexible > .card-wrapper, .inline-flexible > .media-wrapper, .inline-flexible > .google-map-wrapper, .inline-flexible > .group-wrapper)':
    {
      marginBlock: vw(lgElemMarginY, 'sm'),
      '@screen md': {
        marginBlock: vw(lgElemMarginY, 'md'),
      },
    },
  ':where(.inline-flexible > .columns, .inline-flexible > .advanced-layout)': {
    marginBlock: vw(xlElemMarginY, 'sm'),
    '@screen md': {
      marginBlock: vw(xlElemMarginY, 'md'),
    },
  },
  ':where(.inline-flexible > .accordion-wrapper.px-content:has(.accordion-flat) + .accordion-wrapper.px-content:has(.accordion-flat)), :where(.inline-flexible > .accordion-wrapper.px-content:has(.accordion-outline) + .accordion-wrapper.px-content:has(.accordion-outline))':
    {
      marginTop: vw(`${lgElemMarginY * 0.75} * -1`, 'sm'),
      '@screen md': {
        marginTop: vw(`${lgElemMarginY * 0.75} * -1`, 'md'),
      },
    },
  ':where(.inline-flexible > .accordion-wrapper.px-content:has(.accordion-underline) + .accordion-wrapper.px-content:has(.accordion-underline))':
    {
      marginTop: vw(`${lgElemMarginY / 2} * -1`, 'sm'),
      '@screen md': {
        marginTop: vw(`${lgElemMarginY / 2} * -1`, 'md'),
      },
    },
  ':where(.inline-flexible > .accordion-wrapper.px-content:has(.accordion-none) + .accordion-wrapper.px-content:has(.accordion-none))':
    {
      marginTop: vw(`${lgElemMarginY / 2} * -1`, 'sm'),
      '@screen md': {
        marginTop: vw(`${lgElemMarginY / 2} * -1`, 'md'),
      },
    },
  ':where(.inline-flexible > .accordion-wrapper:not(.px-content):has(.accordion-flat) + .accordion-wrapper:not(.px-content):has(.accordion-flat)), :where(.inline-flexible > .accordion-wrapper:not(.px-content):has(.accordion-outline) + .accordion-wrapper:not(.px-content):has(.accordion-outline))':
    {
      marginTop: vw(`${lgElemMarginY * 0.75} * -1`, 'sm'),
      '@screen md': {
        marginTop: vw(`${lgElemMarginY * 0.75} * -1`, 'md'),
      },
    },
  ':where(.inline-flexible > .accordion-wrapper:not(.px-content):has(.accordion-underline) + .accordion-wrapper:not(.px-content):has(.accordion-underline))':
    {
      marginTop: vw(`${lgElemMarginY / 2} * -1`, 'sm'),
      '@screen md': {
        marginTop: vw(`${lgElemMarginY / 2} * -1`, 'md'),
      },
    },
  ':where(.inline-flexible > .accordion-wrapper:not(.px-content):has(.accordion-none) + .accordion-wrapper:not(.px-content):has(.accordion-none))':
    {
      marginTop: vw(`${lgElemMarginY / 2} * -1`, 'sm'),
      '@screen md': {
        marginTop: vw(`${lgElemMarginY / 2} * -1`, 'md'),
      },
    },
  ':where(.inline-flexible > .card-wrapper.md\:px-content + .card-wrapper.md\:px-content)': {
    marginTop: vw(`${lgElemMarginY * 0.75} * -1`, 'sm'),
    '@screen md': {
      marginTop: vw(`${lgElemMarginY * 0.75} * -1`, 'md'),
    },
  },
  ':where(.inline-flexible > .card-wrapper:not(.md\:px-content) + .card-wrapper:not(.md\:px-content))':
    {
      marginTop: vw(`${lgElemMarginY * 0.75} * -1`, 'sm'),
      '@screen md': {
        marginTop: vw(`${lgElemMarginY * 0.75} * -1`, 'md'),
      },
    },
  ':where(.inline-flexible > .media-wrapper.px-content + .media-wrapper.px-content)': {
    marginTop: vw(`${lgElemMarginY / 2} * -1`, 'sm'),
    '@screen md': {
      marginTop: vw(`${lgElemMarginY / 2} * -1`, 'md'),
    },
  },
  ':where(.inline-flexible > .media-wrapper:not(.px-content) + .media-wrapper:not(.px-content))': {
    marginTop: vw(`${lgElemMarginY / 2} * -1`, 'sm'),
    '@screen md': {
      marginTop: vw(`${lgElemMarginY / 2} * -1`, 'md'),
    },
  },
  '.inline-flexible > *:first-child': {
    marginTop: '0px',
    '@screen md': {
      marginTop: '0px',
    },
  },
  '.inline-flexible > *:last-child': {
    marginBottom: '0px',
    '@screen md': {
      marginBottom: '0px',
    },
  },
}

module.exports = { layout }
