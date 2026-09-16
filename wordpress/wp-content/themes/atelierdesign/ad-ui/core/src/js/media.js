;(function () {
  function hideElements(elements) {
    elements.forEach(function (el) {
      if (!el) return
      if (el.style.display !== 'none') {
        el.style.display = 'none'
      }
      el.setAttribute('aria-hidden', 'true')
    })
  }

  function playVideo(videoEl, onPlayed) {
    if (!videoEl) return
    try {
      var playPromise = videoEl.play()
      if (playPromise && typeof playPromise.then === 'function') {
        playPromise.catch(function () {})
      }
    } catch (err) {}
    if (typeof onPlayed === 'function') {
      onPlayed()
    }
  }

  function updateIframeSource(iframeEl, hasPoster, onUpdated) {
    if (!iframeEl) return
    var originalSrc = iframeEl.dataset.adMediaSrc || iframeEl.getAttribute('src') || ''
    if (!originalSrc) {
      if (typeof onUpdated === 'function') onUpdated()
      return
    }

    if (!iframeEl.dataset.adMediaSrc) {
      iframeEl.dataset.adMediaSrc = originalSrc
    }

    try {
      var url = new URL(iframeEl.dataset.adMediaSrc, window.location.origin)
      url.searchParams.set('autoplay', '1')
      url.searchParams.set('playsinline', '1')
      if (hasPoster) {
        url.searchParams.delete('muted')
      } else {
        url.searchParams.set('muted', '1')
      }
      iframeEl.setAttribute('src', url.toString())
    } catch (err) {
      var baseSrc = iframeEl.dataset.adMediaSrc
      var hasQuery = baseSrc.indexOf('?') !== -1
      var params = ['autoplay=1', 'playsinline=1']
      if (!hasPoster) {
        params.push('muted=1')
      }
      var joinedParams = params.join('&')
      var newSrc = baseSrc
      if (hasQuery) {
        var sep = baseSrc.endsWith('&') || baseSrc.endsWith('?') ? '' : '&'
        newSrc = baseSrc + sep + joinedParams
      } else {
        newSrc = baseSrc + '?' + joinedParams
      }
      iframeEl.setAttribute('src', newSrc)
    }

    if (typeof onUpdated === 'function') {
      onUpdated()
    }
  }

  function bindMediaOverlay(mediaEl) {
    if (!mediaEl || mediaEl.dataset.adMediaOverlayBound === 'true') return

    var overlay = mediaEl.querySelector('.media-overlay')
    var poster = mediaEl.querySelector('.media-poster')
    var icon = mediaEl.querySelector('.media-icon')
    var mediaNode = mediaEl.querySelector('video, iframe')

    if (!mediaNode) return
    if (!overlay && !poster && !icon) return

    var hasPoster = !!poster
    var hideTargets = [overlay, poster, icon]

    function hideOverlay() {
      hideElements(hideTargets)
    }

    function handleInteraction(event) {
      event.preventDefault()
      event.stopPropagation()

      if (mediaNode.tagName && mediaNode.tagName.toLowerCase() === 'video') {
        playVideo(mediaNode, hideOverlay)
        return
      }

      updateIframeSource(mediaNode, hasPoster, hideOverlay)
    }

    ;[overlay, poster, icon].forEach(function (interactiveEl) {
      if (!interactiveEl) return
      interactiveEl.addEventListener('click', handleInteraction)
      interactiveEl.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
          handleInteraction(event)
        }
      })
      interactiveEl.setAttribute('tabindex', '0')
      interactiveEl.setAttribute('role', 'button')
      interactiveEl.setAttribute('aria-label', 'Play media')
    })

    if (mediaNode.tagName && mediaNode.tagName.toLowerCase() === 'video') {
      mediaNode.addEventListener('play', hideOverlay, { once: true })
    }

    mediaEl.dataset.adMediaOverlayBound = 'true'
  }

  function initAllMedia() {
    var mediaElements = document.querySelectorAll('.media')
    mediaElements.forEach(bindMediaOverlay)
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAllMedia)
  } else {
    initAllMedia()
  }

  window.addEventListener('load', initAllMedia)

  if (window.htmx && window.htmx.on) {
    window.htmx.on('htmx:afterSwap', initAllMedia)
    window.htmx.on('htmx:afterSettle', initAllMedia)
  } else {
    document.addEventListener('htmx:afterSwap', initAllMedia)
    document.addEventListener('htmx:afterSettle', initAllMedia)
  }
})()
