const columns = {
  '.last-col-in-row::before': {
    display: 'none !important',
  },
  '.full-divider-y': {
    overflowX: 'clip !important',
    '> *.first-col-in-row': {
      '&::after': {
        width: '200vmax',
        left: '50%',
        translate: '-50% 0',
      },
    },
    '> *:not(.first-col-in-row)': {
      '&::after': {
        display: 'none !important',
      },
    },
  },
}

module.exports = { columns }
