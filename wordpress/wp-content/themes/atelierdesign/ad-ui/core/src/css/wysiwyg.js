const wysiwyg = {
  '.wysiwyg': {
    '> *': {
      marginBlock: 'var(--typography-block-margin-y, 24px)',
    },
    '> *:is(h1, h2, h3, h4, h5, h6)': {
      marginTop: '1.2ch',
      marginBottom: '0.9ch',
    },
    '> *:is(p, ul, ol)': {
      marginBlock: '1.7ch',
    },
    hr: {
      marginBlock: 'calc(var(--typography-block-margin-y, 24px) + var(--separator-margin-y, 12px))',
    },
    '> *:first-child': {
      marginTop: '0',
    },
    '> *:last-child': {
      marginBottom: '0',
    },
    'ul, ol': {
      listStyleType: 'none',
      '@apply text-paragraph-md text-typography-paragraph-primary': {},
      li: {
        '@apply relative @sm:pl-[1.5ch] @md/lg:pl-[2ch]': {},
      },
    },
    'ul li::before': {
      '@apply content-["•"] absolute top-0 left-0 h-[1lh]': {},
    },
    ol: {
      counterReset: 'list',
      'li::before': {
        '@apply absolute top-0 left-0 h-[1lh]': {},
        counterIncrement: 'list',
        content: 'counter(list) "."',
      },
    },
  },
  ':where(.wysiwyg h1, .wysiwyg h2, .wysiwyg h3, .wysiwyg h4, .wysiwyg h5, .wysiwyg h6)': {
    '@apply text-typography-heading-primary': {},
  },
  ':where(.wysiwyg p)': {
    '@apply text-paragraph-md text-typography-paragraph-primary': {},
  },
  ':where(.wysiwyg) a': {
    '@apply link': {},
  },
  ':where(.wysiwyg ul[style*="text-align:center"], .wysiwyg ul[style*="text-align: center"], .wysiwyg ol[style*="text-align:center"], .wysiwyg ol[style*="text-align: center"])':
    {
      position: 'relative',
      textAlign: 'left !important',
      marginLeft: 'auto',
      marginRight: 'auto',
      width: 'auto',
      maxWidth: 'fit-content',
    },
}

module.exports = { wysiwyg }
