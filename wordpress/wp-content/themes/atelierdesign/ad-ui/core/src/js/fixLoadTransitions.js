/**
 * @fileoverview Module to prevent flash of unstyled content (FOUC) and premature transitions
 * by removing the 'no-transition' class from the document body after the page is fully loaded.
 *
 * This is commonly used to disable CSS transitions during page load to prevent jarring
 * animation effects before all resources are ready.
 *
 * @author Gyozo G
 * @version 1.0.0
 */

/**
 * Handles the removal of the 'no-transition' class from the document body
 * when the window load event is fired. This allows CSS transitions to
 * activate only after the page is fully loaded, preventing visual glitches.
 *
 * @event window#load
 * @listens window#load
 *
 * @example
 * // CSS should include:
 * // .no-transition, .no-transition * {
 * //   transition: none !important;
 * // }
 * // HTML should include:
 * // <body class="no-transition">
 */
window.addEventListener('load', function () {
  document.body.classList.remove('no-transition')
})
