const path = require("path");
const { twWithADUI } = require("./ad-ui/core/index");

const config = {
  content: [
    path.resolve(__dirname, "./*.{html,php,js}"),
    path.resolve(__dirname, "./templates/*.{html,php,js}"),
    path.resolve(__dirname, "./src/components/**/*.{html,php,js}"),
  ],
  theme: {
    extend: {
      // Custom colors
      fontFamily: {
        safiro: ['Safiro', 'sans-serif'],
      },
      colors: {
        'green-semi-light': '#286169',
      },
    },
  },
  plugins: [
    function({ addBase,addComponents }) {
      addBase({
        ':root': {
          '--color-logo-default-background': '#FFFFFF',
          '--color-logo-hover-background': '#FFFFFF',
          '--logo-border': '0',
          '--logo-border-radius': '12',
        },
        '@screen md': {
          ':root': {
            '--logo-border': '0',
            '--logo-border-radius': '12',
          }
        },
      }),
      addComponents({
        '.label-primary': {
          color: '#286169', // green-semi-light
          // fontFamily: 'Safiro, sans-serif',
          // fontSize: '13px',
          // fontWeight: '600',
          // lineHeight: '18px',
          // letterSpacing: '2.6px',
          // textTransform: 'uppercase',
        },
        '.theme-dark-green .label-primary': {
          color: '#D7DB31', // yellow
        },
      })
    }
  ],
};

module.exports = twWithADUI(config);
