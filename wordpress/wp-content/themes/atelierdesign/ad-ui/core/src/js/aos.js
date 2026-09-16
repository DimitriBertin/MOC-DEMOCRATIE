/**
 * @fileoverview Animate On Scroll (AOS) module providing scroll-based animations
 * and counting animations for elements in the viewport.
 *
 * This module automatically adds animation classes to elements when they come into view,
 * supports count-up animations for numeric values, and handles various viewport events
 * including HTMX after-swap events.
 *
 * @author Gyozo G
 * @version 1.0.0
 */

/**
 * Calculates the real offset position of an element relative to the document,
 * accounting for scroll positions of parent elements.
 *
 * @param {HTMLElement} el - The element to calculate the offset for
 * @returns {{top: number, left: number}} Object containing the top and left offset values
 *
 * @example
 * const element = document.querySelector('.my-element');
 * const offset = getRealOffset(element);
 * console.log(`Element is at ${offset.top}px from top, ${offset.left}px from left`);
 */
function getRealOffset(el) {
  var _x = 0
  var _y = 0

  while (el && !isNaN(el.offsetLeft) && !isNaN(el.offsetTop)) {
    _x += el.offsetLeft - el.scrollLeft
    _y += el.offsetTop - el.scrollTop
    el = el.offsetParent
  }

  return { top: _y, left: _x }
}

/**
 * Creates a smooth counting animation that increments a number from 0 to a target value
 * over a specified duration. Updates the element's innerHTML with the current count.
 *
 * @param {HTMLElement} element - The DOM element to update with the counting value
 * @param {number} targetValue - The final number to count up to
 * @param {number} [duration=1000] - Duration of the animation in milliseconds
 *
 * @example
 * // Count up to 100 over 2 seconds
 * const counter = document.querySelector('.counter');
 * countUpAnimation(counter, 100, 2000);
 *
 * @example
 * // Quick count to 50 (default 1 second)
 * countUpAnimation(document.getElementById('sales'), 50);
 */
function countUpAnimation(element, targetValue, duration = 1000) {
  const fps = 60 // 60 frames per second
  const step = targetValue / (duration / 1000) / fps

  let currentValue = 0
  let interval = setInterval(() => {
    currentValue += step
    element.innerHTML = Math.round(currentValue)

    if (currentValue >= targetValue) {
      element.innerHTML = targetValue
      clearInterval(interval)
    }
  }, 1000 / fps)
}

/**
 * Checks if an element should be excluded from animations based on disable classes.
 *
 * An element is excluded if:
 * - It has the class `.animate-disable`
 * - It has the class `.aos-disable-children`
 * - It's inside a parent with the class `.aos-disable-children`
 *
 * @param {HTMLElement} element - The element to check
 * @returns {boolean} True if the element should be excluded from animations
 *
 * @example
 * const element = document.querySelector('.my-element');
 * if (!shouldExcludeFromAnimation(element)) {
 *   // Run animation
 * }
 */
function shouldExcludeFromAnimation(element) {
  // Check if element itself has disable classes
  if (element.classList.contains('animate-disable')) {
    return true
  }

  // Check if element is inside a parent with .aos-disable-children
  // Note: We check parentElement first to exclude the element itself
  if (element.parentElement?.closest('.aos-disable-children')) {
    return true
  }

  return false
}

/**
 * Main animation controller that handles scroll-based animations.
 *
 * Listens to multiple events (DOMContentLoaded, load, scroll, resize, touchmove, htmx:afterSwap)
 * and checks if elements with animation classes are in the viewport. When elements come into view,
 * it adds the 'animated' class and triggers special animations like count-up if specified.
 *
 * Supported CSS classes:
 * - `.aos` or `.animates-on-scroll`: Elements that should animate when scrolled into view
 * - `.animate-countup`: Elements that should use count-up animation (requires data attributes)
 * - `.animates-once`: Elements that should only animate once (won't re-animate when scrolled out and back in)
 * - `.animate-disable`: Disable animations on this specific element
 * - `.aos-disable-children`: When applied to a parent, disables animations on all child elements
 *
 * Data attributes for count-up animation:
 * - `data-countup-value`: The target number to count to (falls back to element's innerHTML)
 * - `data-countup-duration`: Duration in milliseconds (defaults to 1000ms)
 *
 * @event document#DOMContentLoaded
 * @event window#load
 * @event window#scroll
 * @event window#resize
 * @event window#touchmove
 * @event document#htmx:afterSwap
 *
 * @listens document#DOMContentLoaded
 * @listens window#load
 * @listens window#scroll
 * @listens window#resize
 * @listens window#touchmove
 * @listens document#htmx:afterSwap
 *
 * @example
 * // HTML usage:
 * // <div class="aos fade-in">This will fade in when scrolled into view</div>
 * // <span class="aos animate-countup" data-countup-value="150" data-countup-duration="2000">0</span>
 * // <div class="animates-on-scroll animates-once">This animates only once</div>
 * // <div class="aos animate-disable">This will not animate</div>
 * // <div class="aos-disable-children"><div class="aos">This child will not animate</div></div>
 */
;['DOMContentLoaded', 'load', 'scroll', 'resize', 'touchmove', 'htmx:afterSwap'].forEach(
  (event) => {
    document.addEventListener(event, (e) => {
      var scrollBottom = window.scrollY + window.innerHeight

      var classes = ['aos', 'animates-on-scroll']

      classes.forEach(runDelays)

      /**
       * Processes elements with animation classes and determines if they should be animated
       * based on their position relative to the viewport.
       *
       * @param {string} item - The CSS class name to search for
       * @param {number} index - Array index (unused)
       * @param {string[]} arr - The full array of class names (unused)
       *
       * @inner
       */
      function runDelays(item, index, arr) {
        document.querySelectorAll('.' + item).forEach((e_this) => {
          // Skip elements that should be excluded from animations
          if (shouldExcludeFromAnimation(e_this)) {
            return
          }

          if (window.screenWidth > 767) {
            if (e_this.offsetHeight > 100) {
              var objectOffsetMiddle = getRealOffset(e_this).top + e_this.offsetHeight / 2
            } else {
              var objectOffsetMiddle = getRealOffset(e_this).top + 20
            }
          } else {
            var objectOffsetMiddle = getRealOffset(e_this).top + 20
          }
          if (scrollBottom > objectOffsetMiddle) {
            if (!e_this.classList.contains('animated')) {
              e_this.classList.add('animated')
              if (e_this.classList.contains('animate-countup')) {
                let targetValue = e_this.dataset.countupValue ?? e_this.innerHTML
                let duration = parseFloat(e_this.dataset.countupDuration ?? 1000)
                countUpAnimation(e_this, targetValue, duration)
              }
            }
          }
        })

        document.querySelectorAll('.' + item).forEach((e_this) => {
          // Skip elements that should be excluded from animations
          if (shouldExcludeFromAnimation(e_this)) {
            return
          }

          if (getRealOffset(e_this).top > scrollBottom) {
            if (!e_this.classList.contains('animates-once')) {
              e_this.classList.remove('animated')
            }
          }
        })
      }
    })
  },
)
