const autoscale = {
  '.autoscale': {
    '@apply mm-md:@screen-lg/md mm-md:@scale-lg/md xl:@scale-lg/xl': {},
  },
  '.autoscale-children': {
    '> *': {
      '@apply autoscale': {},
    },
  },
  '.spacing-reset': {
    '@apply mm-md:@screen-lg mm-md:@scale-1 xl:@scale-1': {},
  },
}

module.exports = { autoscale }
