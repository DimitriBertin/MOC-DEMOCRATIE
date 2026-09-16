const { vw } = require('../utils/vw')

const logo = {
  // full section
  ':where(.logos)': {
    '@apply flex-grid @sm:flex-grid-gap-sm @md/lg:flex-grid-gap-sm': {},
    '@apply flex-grid-cols-2 md:flex-grid-cols-7 lg:flex-grid-cols-7 xl:flex-grid-cols-8': {},
  },
  // section with px content
  ':where(.px-content .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-5': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-5': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-6': {},
    },
  },
  // feature section
  ':where(.feature .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-3': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-3': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-4': {},
    },
  },
  // 1/2 columns
  ':where(.columns.md\\\:flex-grid-cols-2 .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-3': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-4': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-4': {},
    },
  },
  // 1/2 columns with px content
  ':where(.columns.mx-content.md\\\:flex-grid-cols-2 .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-2': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-3': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-3': {},
    },
  },
  // columns 1/3
  ':where(.columns.md\\\:flex-grid-cols-3 .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-2': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-3': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-3': {},
    },
  },
  // columns 1/3 with px content
  ':where(.columns.mx-content.md\\\:flex-grid-cols-3 .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-1': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-2': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-2': {},
    },
  },
  ':where(.columns.md\\\:flex-grid-cols-4 .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-1': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-2': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-2': {},
    },
  },
  // layout 1/4
  ':where(.advanced-layout-wrapper.md\\\:flex-grid-col-span-3 .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-1': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-2': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-2': {},
    },
  },
  // layout 1/3
  ':where(.advanced-layout-wrapper.md\\\:flex-grid-col-span-4 .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-2': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-3': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-3': {},
    },
  },
  // layout 1/2
  ':where(.advanced-layout-wrapper.md\\\:flex-grid-col-span-6 .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-3': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-4': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-4': {},
    },
  },
  // layout 2/3
  ':where(.advanced-layout-wrapper.md\\\:flex-grid-col-span-8 .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-4': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-5': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-5': {},
    },
  },
  // layout 3/4
  ':where(.advanced-layout-wrapper.md\\\:flex-grid-col-span-9 .logos)': {
    '@apply flex-grid-cols-2': {},
    '@screen md': {
      '@apply flex-grid-cols-5': {},
    },
    '@screen lg': {
      '@apply flex-grid-cols-6': {},
    },
    '@screen xl': {
      '@apply flex-grid-cols-6': {},
    },
  },
  // layout 1/1
  ':where(.advanced-layout-wrapper.md\\\:flex-grid-col-span-12 .logos)': {
    // same as full section
  },
  ':where(.logo)': {
    position: 'relative',
    display: 'inline-block',
    overflow: 'hidden',
    borderRadius: vw('var(--logo-border-radius)', 'sm'),
    borderStyle: 'solid',
    borderWidth: vw('var(--logo-border)', 'sm'),
    borderColor: 'var(--color-logo-default-border)',
    backgroundColor: 'var(--color-logo-default-background)',
    transitionProperty:
      'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
    transitionTimingFunction: 'linear',
    transitionDuration: '150ms',
    '@screen md': {
      maxWidth: vw('192', 'lg'),
      borderRadius: vw('var(--logo-border-radius)', 'lg'),
      borderWidth: vw('var(--logo-border)', 'lg'),
    },
  },
  ':where(.logo:is(a):hover)': {
    borderColor: 'var(--color-logo-hover-border)',
    backgroundColor: 'var(--color-logo-hover-background)',
  },
  ':where(.logo-image-wrapper)': {
    padding: '15%',
    overflow: 'hidden',
    position: 'relative',
    zIndex: 2,
    mixBlendMode: 'multiply',
  },
  ':where(.logo-inverted:not(:is(a):hover) .logo-image-wrapper)': {
    mixBlendMode: 'screen',
  },
  ':where(.logo-inverted:not(:is(a)) .logo-image-wrapper)': {
    mixBlendMode: 'screen',
  },
  ':where(.logo-image)': {
    padding: '1px',
    width: '100%',
    aspectRatio: '1/1',
    objectFit: 'contain',
    backgroundColor: 'white',
    filter: 'grayscale(1) brightness(0.75) contrast(2)',
    // -- todo: fix transition on Safari
    // transitionProperty: 'filter',
    // transitionTimingFunction: 'cubic-bezier(0.215, 0.61, 0.355, 1)',
    // transitionDuration: '150ms',
  },
  ':where(.logo.logo-inverted .logo-image)': {
    filter: 'grayscale(1) brightness(0.75) contrast(2) invert(1)',
  },
  ':where(.logo:is(a):hover .logo-image)': {
    filter: 'grayscale(0) brightness(1) contrast(1)',
  },
  ':where(.logo.logo-inverted:is(a):hover .logo-image)': {
    // filter: 'grayscale(1) brightness(1) contrast(1) invert(1)',
    filter: 'grayscale(0) brightness(1) contrast(1)',
  },
  ':where(.logo-overlay)': {
    position: 'absolute',
    inset: 0,
    backgroundColor: 'var(--color-logo-default-fill)',
    mixBlendMode: 'screen',
    pointerEvents: 'none',
    opacity: 1,
    // -- todo: fix transition on Safari
    // transitionProperty: 'opacity',
    // transitionTimingFunction: 'linear',
    // transitionDuration: '150ms',
  },
  ':where(.logo.logo-inverted .logo-overlay)': {
    display: 'none',
  },
  ':where(.logo:is(a):hover .logo-overlay)': {
    opacity: 0,
  },
}

module.exports = { logo }
