const COMPONENTS = ['columns', 'advanced-layout', 'feature-columns']

function updateDividers() {
  COMPONENTS.forEach((component) => {
    const items = Array.from(document.querySelectorAll(`.${component} > *`))
    if (!items.length) return

    let currentRowTop = items[0].offsetTop
    let rowStartIndex = 0

    items.forEach((item, index) => {
      item.classList.remove('last-col-in-row', 'first-col-in-row')

      if (item.offsetTop !== currentRowTop) {
        // Close previous row
        items[index - 1].classList.add('last-col-in-row')
        items[rowStartIndex].classList.add('first-col-in-row')

        // Start tracking new row
        currentRowTop = item.offsetTop
        rowStartIndex = index
        item.classList.add('first-col-in-row')
      }

      if (index === items.length - 1) {
        items[rowStartIndex].classList.add('first-col-in-row')
        item.classList.add('last-col-in-row')
      }
    })
  })
}

function updateElementNumbers() {
  COMPONENTS.forEach((component) => {
    const containers = document.querySelectorAll(`.${component}`)
    containers.forEach((container) => {
      const items = Array.from(container.querySelectorAll(':scope > *'))
      if (!items.length) return

      // Remove existing number labels
      items.forEach((item) => {
        item.style.removeProperty('--nth')
      })

      let currentRowTop = items[0].offsetTop
      let indexInRow = 1

      items.forEach((item, i) => {
        // If this item starts a new row (based on offsetTop)
        if (item.offsetTop > currentRowTop) {
          currentRowTop = item.offsetTop
          indexInRow = 1 // reset count for new row
        }

        item.style.setProperty('--nth', indexInRow)

        indexInRow++
      })
    })
  })
}

// Initial runs
updateDividers()
updateElementNumbers()

// Update on resize
;['DOMContentLoaded', 'load', 'resize'].forEach((event) => {
  window.addEventListener(event, updateDividers)
  window.addEventListener(event, updateElementNumbers)
  window.addEventListener(event, ensureObservers)
})

// Watch for dynamic DOM changes
const observer = new MutationObserver(updateElementNumbers)
const observedNodes = new WeakSet()

function ensureObservers() {
  COMPONENTS.forEach((component) => {
    document.querySelectorAll(`.${component}`).forEach((node) => {
      if (!observedNodes.has(node)) {
        observer.observe(node, { childList: true, subtree: true })
        observedNodes.add(node)
      }
    })
  })
}

ensureObservers()
