const path = require('path')
const { adui } = require('./src/tailwind/plugin')
const { deepMerge } = require('./src/index')

const config = {
  content: [
    path.resolve(__dirname, '../acf/components/**/*.php'),
    path.resolve(__dirname, './src/js/**/*.js'),
  ],
  safelist: [
    {
      pattern: /^theme\-(.*)/,
    },
    {
      pattern: /^bg\-layout\-(.*)/,
    },
    {
      pattern: /^heading\-(.*)/,
    },
    {
      pattern: /^paragraph\-(.*)/,
    },
    {
      pattern: /^button\-(.*)/,
    },
    {
      pattern: /^accordion\-(.*)/,
    },
    {
      pattern: /^card\-(.*)/,
    },
    {
      pattern: /^badge\-(.*)/,
    },
    {
      pattern: /^separator\-(.*)/,
    },
    {
      pattern: /^icon\-(.*)/,
    },
    {
      pattern: /(.*)?gap(\-x|\-y)?\-(0|none|xs|sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl|8xl|9xl|10xl)/,
    },
    'autoscale',
  ],
  plugins: [adui],
}

module.exports = deepMerge(require('./src/tailwind/config'), config)
