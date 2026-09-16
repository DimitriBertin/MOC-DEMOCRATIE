const { extendEasings } = require('../tailwind/utils')
const { vw } = require('../utils/vw')

const swiper = {
  '.swiper': {
    touchAction: 'pan-y',
  },
  '.swiper-wrapper': {
    position: 'relative',
    width: '100%',
    height: '100%',
    zIndex: '1',
    display: 'flex',
    flexDirection: 'row',
    transitionTimingFunction: 'cubic-bezier(0.22, 0.61, 0.36, 1) !important',
  },
  '.swiper-slide': {
    flexShrink: '0',
    height: 'auto', // swiper autoHeight
    position: 'relative',
    transitionProperty: 'transform',
    display: 'block',
    transform: 'translate3d(0px, 0, 0)',
  },
}

const gallery = {
  '.gallery': {
    userSelect: 'none',
    display: 'flex',
    flexDirection: 'column',
    gap: vw('var(--gallery-space-y)', 'sm'),
    '@screen md': {
      gap: vw('var(--gallery-space-y)', 'lg'),
    },
  },
  '.gallery-slide': {
    height: vw('var(--gallery-slide-height)', 'sm'),
    paddingRight: vw('var(--gallery-slides-gap-x)', 'sm'),
    '@screen md': {
      paddingRight: vw('var(--gallery-slides-gap-x)', 'lg'),
      height: vw('var(--gallery-slide-height)', 'lg'),
    },
    '&:last-child': {
      paddingRight: 0,
      '@screen md': {
        paddingRight: 0,
      },
    },
  },
  '.gallery-navigation, .gallery-navigation-buttons': {
    display: 'flex',
    flexDirection: 'row',
    alignItems: 'center',
  },
  '.gallery-navigation': {
    justifyContent: 'space-between',
  },
  '.gallery-navigation-buttons': {
    justifyContent: 'flex-end',
    gap: vw('var(--gallery-buttons-gap-x)', 'sm'),
    '@screen md': {
      gap: vw('var(--gallery-buttons-gap-x)', 'lg'),
    },
  },
  '.gallery-navigation-button': {
    transitionProperty:
      'opacity, background-color, border-color, text-decoration-color, fill, stroke',
    transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
    transitionDuration: '150ms',
  },
  '.gallery-navigation-button:disabled': {
    opacity: 0.5,
    cursor: 'not-allowed',
  },
}

module.exports = { swiper, gallery }
