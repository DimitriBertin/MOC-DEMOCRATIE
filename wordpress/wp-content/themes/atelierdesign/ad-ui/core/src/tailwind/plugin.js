const plugin = require('tailwindcss/plugin')

const { variables } = require('../css/variables')
const { layout } = require('../css/layout')
const { container } = require('../css/container')
const { gridComponent, gridUtilities } = require('../css/grid')
const { sectionComponent, sectionUtilities } = require('../css/section')
const { feature } = require('../css/feature')
const { columns } = require('../css/columns')
const { marginUtilities, paddingUtilities } = require('../css/margin')
const { article } = require('../css/article')
const { content } = require('../css/content')
const { typography } = require('../css/typography')
const { heading } = require('../css/heading')
const { paragraph } = require('../css/paragraph')
const { keyNumbers } = require('../css/keyNumbers')
const { wysiwyg } = require('../css/wysiwyg')
const { icon } = require('../css/icon')
const { logo } = require('../css/logo')
const { media } = require('../css/media')
const { swiper, gallery } = require('../css/gallery')
const { badge } = require('../css/badge')
const { button } = require('../css/button')
const { accordion } = require('../css/accordion')
const { card } = require('../css/card')
const { quote } = require('../css/quote')
const { separator } = require('../css/separator')
const { form } = require('../css/form')

const { parallaxImage } = require('../css/parallaxImage')
const { flexGridGaps } = require('../css/grid')
const { autoscale } = require('../css/autoscale')

const adui = plugin(({ addBase, addComponents, addUtilities }) => {
  addBase({
    ...variables,
  })

  addComponents({
    ...layout,
    ...container,
    ...gridComponent,
    ...sectionComponent,
    ...feature,
    ...columns,
    ...article,
    ...typography,
    ...heading,
    ...paragraph,
    ...wysiwyg,
    ...keyNumbers,
    ...icon,
    ...logo,
    ...media,
    ...swiper,
    ...gallery,
    ...badge,
    ...button,
    ...accordion,
    ...card,
    ...quote,
    ...separator,
    ...form,
    ...gridUtilities,
    ...sectionUtilities,
    ...marginUtilities,
    ...paddingUtilities,
    ...content,
    ...parallaxImage,
    ...autoscale,
  })

  addUtilities({
    ...flexGridGaps,
  })
})

module.exports = { adui }
