/**
 * Ensures the user's perceived scroll progress remains unchanged while the viewport is resized.
 * The script remembers the last scroll percentage and, after a resize alters layout metrics,
 * restores the scroll position to match that percentage.
 */

/**
 * Tracks the percentage of total document height the viewport has scrolled,
 * and reapplies that percentage after a resize so the visual position stays consistent.
 */
let lastScrollPercent = 0

/**
 * Computes the current scrollable document height.
 *
 * @returns {number} Total scrollable height in pixels; can be 0 when the document fits entirely in the viewport.
 */
const getScrollableHeight = () => document.documentElement.scrollHeight - window.innerHeight

/**
 * Updates the internally stored scroll percentage based on the user's current scroll position.
 *
 * @returns {void}
 */
const updateScrollPercent = () => {
  const docHeight = getScrollableHeight()
  if (docHeight <= 0) {
    lastScrollPercent = 0
    return
  }
  const scrollTop = window.scrollY || window.pageYOffset
  lastScrollPercent = (scrollTop / docHeight) * 100
}

/**
 * Scrolls the window to the provided percentage of the total scrollable height.
 *
 * @param {number} percent Value between 0 and 100 representing the desired scroll position.
 * @returns {void}
 */
const scrollToPercent = (percent) => {
  const docHeight = getScrollableHeight()
  if (docHeight <= 0) {
    return
  }
  const targetScrollTop = (percent / 100) * docHeight
  window.scrollTo({
    top: targetScrollTop,
  })
}

window.addEventListener('scroll', () => {
  updateScrollPercent()
  // console.log(`Scrolled: ${lastScrollPercent.toFixed(2)}%`)
})

window.addEventListener('resize', () => {
  scrollToPercent(lastScrollPercent)
})

window.addEventListener('load', updateScrollPercent)
