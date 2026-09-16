import Swiper from 'swiper'
import { Navigation, Pagination, FreeMode } from 'swiper/modules'

// get value of --gallery-slides-gap-x CSS variable
const slidesGapX = getComputedStyle(document.documentElement).getPropertyValue(
  '--gallery-slides-gap-x',
)

document.querySelectorAll('.gallery').forEach((gallery) => {
  const swiper = new Swiper('.gallery-swiper', {
    modules: [Navigation, Pagination, FreeMode],

    slidesPerView: 'auto',
    loop: false,
    spaceBetween: 0,

    speed: 500,
    snap: {
      enabled: true,
      snapOnRelease: true,
      snapTo: 'nearest',
      threshold: 5,
      momentum: true,
    },

    grabCursor: true,
    autoHeight: true,
    preloadImages: false,

    createElements: false,

    keyboard: {
      enabled: true,
      onlyInViewport: true,
    },

    mousewheel: {
      forceToAxis: true,
      releaseOnEdges: true,
    },

    navigation: {
      nextEl: gallery.querySelector('.swiper-button-next'),
      prevEl: gallery.querySelector('.swiper-button-prev'),
      addIcons: false,
    },

    pagination: {
      el: gallery.querySelector('.gallery-pagination'),
      type: 'fraction',
      formatFractionCurrent: function (number) {
        return ('0' + number).slice(-2)
      },
      formatFractionTotal: function (number) {
        return ('0' + number).slice(-2)
      },
      renderFraction: function (currentClass, totalClass) {
        return (
          '<span class="' +
          currentClass +
          '"></span>' +
          '/' +
          '<span class="' +
          totalClass +
          '"></span>'
        )
      },
    },

    freeMode: true,

    breakpoints: {
      600: {
        freeMode: false,
      },
    },
  })
})
