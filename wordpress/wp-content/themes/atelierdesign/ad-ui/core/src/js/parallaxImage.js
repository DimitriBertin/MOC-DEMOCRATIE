/**
 * Parallax Animations
 */

// Get Real Offset
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

// Get all classes with .parallax-image
const parallaxImages = document.querySelectorAll('.parallax-image')

// Loop through all parallax images
parallaxImages.forEach((parallaxImage) => {
  ;['DOMContentLoaded', 'load', 'scroll', 'resize', 'touchmove', 'htmx:afterSwap'].forEach(
    (event) => {
      window.addEventListener(event, (e) => {
        // From and To values of transform translate Y percentage
        let fromOffset = -12
        let toOffset = 12
        if (parallaxImage.classList.contains('parallax-image--big')) {
          fromOffset = -30
          toOffset = 30
        }

        // Scale & no-scale
        let scale = 1.1666
        if (parallaxImage.classList.contains('parallax-image--no-scale')) {
          scale = 1.0
        }

        var scrollTop = window.pageYOffset || document.documentElement.scrollTop
        var scrollBottom = scrollTop + document.documentElement.clientHeight
        var parallaxImageOffset = getRealOffset(parallaxImage)
        var parallaxImageHeight = parallaxImage.offsetHeight

        if (
          scrollBottom > parallaxImageOffset.top &&
          scrollTop < parallaxImageOffset.top + parallaxImageHeight
        ) {
          var parallaxImagePercentage =
            ((scrollBottom - parallaxImageOffset.top) /
              (parallaxImageHeight + document.documentElement.clientHeight)) *
            100
          var parallaxImageTranslateY =
            (parallaxImagePercentage * (toOffset - fromOffset)) / 100 + fromOffset
          parallaxImage.style.transform =
            'translateY(' + parallaxImageTranslateY + '%) scale(' + scale + ')'
        } else {
          if (scrollTop > parallaxImageOffset.top + parallaxImageHeight) {
            parallaxImage.style.transform = 'translateY(' + toOffset + '%) scale(' + scale + ')'
          }
          if (scrollBottom < parallaxImageOffset.top) {
            parallaxImage.style.transform = 'translateY(' + fromOffset + '%) scale(' + scale + ')'
          }
        }
      })
    },
  )
})
