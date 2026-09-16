const vw = (value, screen = 'sm') => {
  return `calc((${value} / var(--tw-screen-${screen})) * var(--tw-screen-max) * var(--tw-scale))`
}

module.exports = { vw }
