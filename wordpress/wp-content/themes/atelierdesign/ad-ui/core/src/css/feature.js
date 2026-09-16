const feature = {
  // todo: migrate all feature styles to this file from being tailwind classes in the acf/components/feature/markup.php file
  '.feature': {
    display: 'flex',
  },
  ':where(.feature:not(:is(.feature-contained)))': {
    minHeight: '50vh',
  },
}

module.exports = { feature }
