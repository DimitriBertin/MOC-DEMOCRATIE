const { vw } = require('../utils/vw')

const quote = {
  '.quote': {
    display: 'flex',
    flexDirection: 'column',
    rowGap: vw('var(--quote-gap-y)', 'sm'),
    paddingBlock: vw('var(--quote-padding-y)', 'sm'),
    '@screen md': {
      rowGap: vw('var(--quote-gap-y)', 'lg'),
      paddingBlock: vw('var(--quote-padding-y)', 'lg'),
    },
  },
  '.quote-icon': {
    display: 'inline-block',
    objectPosition: 'center',
    flexShrink: '0',
    flexGrow: '0',
    overflow: 'visible',
    objectFit: 'contain',
    color: 'var(--color-quote-icon)',
    width: vw('var(--quote-icon-size)', 'sm'),
    height: vw('var(--quote-icon-size)', 'sm'),
    '@screen md': {
      width: vw('var(--quote-icon-size)', 'lg'),
      height: vw('var(--quote-icon-size)', 'lg'),
    },
  },
  '.quote-content': {
    color: 'var(--color-quote-content)',
    '@apply text-quote-content': {},
  },
  ':where(.quote-cite)': {
    display: 'flex',
    justifyContent: 'flex-start',
    alignItems: 'center',
    fontStyle: 'inherit',
    gap: vw('var(--quote-avatar-margin-right)', 'sm'),
    '@screen md': {
      gap: vw('var(--quote-avatar-margin-right)', 'lg'),
    },
  },
  ':where(.quote.quote-align-right .quote-cite)': {
    flexDirection: 'row-reverse',
    justifyContent: 'flex-end',
  },
  '.quote-avatar': {
    display: 'inline-block',
    alignSelf: 'flex-start',
    objectPosition: 'center',
    flexShrink: '0',
    flexGrow: '0',
    overflow: 'hidden',
    objectFit: 'cover',
    width: vw('var(--quote-avatar-size)', 'sm'),
    height: vw('var(--quote-avatar-size)', 'sm'),
    borderRadius: vw('var(--quote-avatar-border-radius)', 'sm'),
    '@screen md': {
      width: vw('var(--quote-avatar-size)', 'lg'),
      height: vw('var(--quote-avatar-size)', 'lg'),
      borderRadius: vw('var(--quote-avatar-border-radius)', 'lg'),
    },
  },
  ':where(.quote-labels)': {
    display: 'flex',
    flexDirection: 'column',
    gap: vw('var(--quote-labels-gap-y)', 'sm'),
    '@screen md': {
      gap: vw('var(--quote-labels-gap-y)', 'lg'),
    },
  },
  ':where(.quote.quote-align-center .quote-cite:not(:has(.quote-avatar)) .quote-labels)': {
    alignItems: 'center',
    textAlign: 'center',
  },
  ':where(.quote.quote-align-right .quote-labels)': {
    alignItems: 'flex-end',
    textAlign: 'right',
  },
  '.quote-primary-label': {
    color: 'var(--color-quote-primary-label)',
    '@apply text-quote-primary-label': {},
  },
  '.quote-secondary-label': {
    color: 'var(--color-quote-secondary-label)',
    '@apply text-quote-secondary-label': {},
  },
  '.quote-align-left': {
    alignItems: 'flex-start',
    '.quote-content': {
      textAlign: 'left',
    },
  },
  '.quote-align-center': {
    alignItems: 'center',
    '.quote-content': {
      textAlign: 'center',
    },
  },
  '.quote-align-right': {
    alignItems: 'flex-end',
    '.quote-content': {
      textAlign: 'right',
    },
  },
  ':where(.quote.quote-has-border.quote-align-left)': {
    borderLeftStyle: 'solid',
    borderLeftColor: 'var(--color-quote-border)',
    borderLeftWidth: vw('var(--quote-border-width)', 'sm'),
    paddingLeft: vw('var(--quote-padding-x)', 'sm'),
    '@screen md': {
      borderLeftWidth: vw('var(--quote-border-width)', 'lg'),
      paddingLeft: vw('var(--quote-padding-x)', 'lg'),
    },
  },
  ':where(.quote.quote-has-border.quote-align-right)': {
    borderRightStyle: 'solid',
    borderRightColor: 'var(--color-quote-border)',
    borderRightWidth: vw('var(--quote-border-width)', 'sm'),
    paddingRight: vw('var(--quote-padding-x)', 'sm'),
    '@screen md': {
      borderRightWidth: vw('var(--quote-border-width)', 'lg'),
      paddingRight: vw('var(--quote-padding-x)', 'lg'),
    },
  },
}

module.exports = { quote }
