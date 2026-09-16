const keyNumbers = {
  '.key-numbers': {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
  },
  '.key-numbers-prefix': {
    color: 'var(--color-key-numbers-prefix)',
    '@apply text-key-numbers-prefix': {},
  },
  '.key-numbers-number': {
    color: 'var(--color-key-numbers-number)',
    '@apply text-key-numbers-number': {},
  },
  '.key-numbers-suffix': {
    color: 'var(--color-key-numbers-suffix)',
    '@apply text-key-numbers-suffix': {},
  },
}

module.exports = { keyNumbers }
