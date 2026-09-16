const parallaxImage = {
  '.parallax-image': {
    aspectRatio: 'inherit',
    width: '100%',
    height: '100%',
    borderRadius: '0',
    transform: 'translateY(-15%) scale(1.15)',
    objectFit: 'cover',
    objectPosition: 'center',
  },
  '.parallax-image-wrapper': {
    overflow: 'hidden',
    pointerEvents: 'none',
  },
}

module.exports = { parallaxImage }
