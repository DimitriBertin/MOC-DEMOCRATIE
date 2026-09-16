const { deepMerge } = require('./src/index')

function twWithADUI(config) {
  return deepMerge(require('./tailwind.config.js'), config)
}

module.exports = { twWithADUI }
