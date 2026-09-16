const { vw } = require('../utils/vw')
const { extendEasings } = require('../tailwind/utils')

const form = {
  '.form': {
    display: 'flex',
    flexDirection: 'column',
  },
  '.form-row': {
    display: 'grid',
    gridAutoColumns: 'minmax(0, 1fr)',
    gridAutoFlow: 'column',
    gap: vw('24', 'sm'),
    '@screen md': {
      gap: vw('24', 'lg'),
    },
  },
  '.form-group': {
    position: 'relative',
    display: 'flex',
    flexDirection: 'column',
    gap: vw('var(--form-form-group-gap-y)', 'sm'),
    paddingBottom: vw('var(--form-row-space-y)', 'sm'),
    '@screen md': {
      gap: vw('var(--form-form-group-gap-y)', 'lg'),
      paddingBottom: vw('var(--form-row-space-y)', 'lg'),
    },
    '.form-group-label': {
      color: 'var(--color-form-row-label)',
      '@apply text-form-label': {},
    },
    '&:has(:required)': {
      '.form-group-label': {
        '&::after': {
          '--tw-content': "'*'",
          content: 'var(--tw-content)',
          color: 'var(--color-form-row-label-required)',
        },
      },
    },
    '.invalid-message': {
      color: 'var(--color-form-row-invalid-message)',
      display: 'none',
      position: 'absolute',
      bottom: 0,
      right: 0,
      '@apply text-paragraph-sm': {},
    },
    "input[type='text'], input[type='password'], input[type='email'], input[type='tel'], input[type='url'], input[type='search'], input[type='number'], input[type='date'], input[type='time'], input[type='datetime-local'], input[type='month'], input[type='week'], select, textarea":
      {
        width: '100%',
        paddingInline: vw('var(--form-input-block-padding-x)', 'sm'),
        paddingBlock: vw('var(--form-input-block-padding-y)', 'sm'),
        color: 'var(--color-form-input-block-default-text)',
        backgroundColor: 'var(--color-form-input-block-default-background)',
        borderWidth: vw('var(--form-input-block-border-width)', 'sm'),
        borderStyle: 'solid',
        borderColor: 'var(--color-form-input-block-default-border)',
        borderRadius: vw('var(--form-input-block-border-radius)', 'sm'),
        boxShadow: `${vw('var(--form-input-block-default-shadow-x)', 'sm')} ${vw('var(--form-input-block-default-shadow-y)', 'sm')} ${vw('var(--form-input-block-default-shadow-blur)', 'sm')} ${vw('var(--form-input-block-default-shadow-spread)', 'sm')} var(--color-form-input-block-default-shadow)`,
        transitionProperty:
          'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
        transitionTimingFunction: 'cubic-bezier(0.215, 0.61, 0.355, 1)',
        transitionDuration: '150ms',
        '@apply text-form-block': {},
        '&:focus': {
          boxShadow: `${vw('var(--form-input-block-focus-shadow-x)', 'sm')} ${vw('var(--form-input-block-focus-shadow-y)', 'sm')} ${vw('var(--form-input-block-focus-shadow-blur)', 'sm')} ${vw('var(--form-input-block-focus-shadow-spread)', 'sm')} var(--color-form-input-block-focus-shadow)`,
          color: 'var(--color-form-input-block-focus-text)',
          backgroundColor: 'var(--color-form-input-block-focus-background)',
          borderColor: 'var(--color-form-input-block-focus-border)',
        },
        '@screen md': {
          paddingInline: vw('var(--form-input-block-padding-x)', 'lg'),
          paddingBlock: vw('var(--form-input-block-padding-y)', 'lg'),
          borderWidth: vw('var(--form-input-block-border-width)', 'lg'),
          borderRadius: vw('var(--form-input-block-border-radius)', 'lg'),
          boxShadow: `${vw('var(--form-input-block-default-shadow-x)', 'lg')} ${vw('var(--form-input-block-default-shadow-y)', 'lg')} ${vw('var(--form-input-block-default-shadow-blur)', 'lg')} ${vw('var(--form-input-block-default-shadow-spread)', 'lg')} var(--color-form-input-block-default-shadow)`,
          '&:focus': {
            boxShadow: `${vw('var(--form-input-block-focus-shadow-x)', 'lg')} ${vw('var(--form-input-block-focus-shadow-y)', 'lg')} ${vw('var(--form-input-block-focus-shadow-blur)', 'lg')} ${vw('var(--form-input-block-focus-shadow-spread)', 'lg')} var(--color-form-input-block-focus-shadow)`,
          },
        },
        // "&:focus::placeholder, &:focus::-webkit-input-placeholder, &:focus::-moz-placeholder, &:focus::-ms-input-placeholder":
        '&::placeholder': {
          color: 'var(--color-form-input-block-default-placeholder)',
        },
        '&:focus::placeholder': {
          color: 'transparent',
          // -- THIS IS DISABLED BECAUSE WE ACTUALLY WANT TO HIDE THE PLACEHOLDER ON FOCUS
          // color: "var(--color-form-input-block-focus-placeholder)",
        },
        '&:disabled': {
          opacity: 'calc(var(--color-form-input-block-disabled-opacity) / 100)',
        },
      },
    '.form-select': {
      position: 'relative',
      select: {
        appearance: 'none',
        paddingRight: vw(
          '(var(--form-input-block-padding-x) + var(--form-input-block-icon-size))',
          'sm',
        ),
        '@screen md': {
          paddingRight: vw(
            'calc(var(--form-input-block-padding-x) + var(--form-input-block-icon-size))',
            'lg',
          ),
        },
        "&:not(:has(option[value='']:not(:checked)))": {
          color: 'var(--color-form-input-block-default-placeholder)',
        },
      },
      '.form-select-icon': {
        color: 'var(--color-form-input-block-default-icon)',
        pointerEvents: 'none',
        objectFit: 'contain',
        objectPosition: 'center',
        overflow: 'visible',
        position: 'absolute',
        top: '50%',
        transform: 'translateY(-50%)',
        right: vw('var(--form-input-block-padding-x)', 'sm'),
        width: vw('var(--form-input-block-icon-size)', 'sm'),
        height: vw('var(--form-input-block-icon-size)', 'sm'),
        '@screen md': {
          right: vw('var(--form-input-block-padding-x)', 'lg'),
          width: vw('var(--form-input-block-icon-size)', 'lg'),
          height: vw('var(--form-input-block-icon-size)', 'lg'),
        },
      },
      '&:has(:focus)': {
        select: {
          "&:not(:has(option[value='']:not(:checked)))": {
            color: 'var(--color-form-input-block-focus-placeholder)',
          },
        },
        '.form-select-icon': {
          color: 'var(--color-form-input-block-focus-icon)',
        },
      },
      '&:has(select:disabled)': {
        '.form-select-icon': {
          opacity: 'calc(var(--color-form-input-block-disabled-opacity) / 100)',
        },
      },
    },
    textarea: {
      resize: 'none',
      height: vw('328', 'sm'),
      '@screen md': {
        height: vw('328', 'lg'),
      },
    },
    '.form-inline-input-group': {
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'flex-start',
      justifyContent: 'flex-start',
      gap: vw('12', 'sm'),
      '@screen md': {
        gap: vw('12', 'lg'),
      },
    },
    '.form-radio, .form-checkbox': {
      cursor: 'pointer',
      display: 'flex',
      alignItems: 'flex-start',
      justifyContent: 'flex-start',
      gap: vw('12', 'sm'),
      '@screen md': {
        gap: vw('12', 'lg'),
      },
      "input[type='radio'], input[type='checkbox']": {
        width: vw('var(--form-input-inline-input-size)', 'sm'),
        height: vw('var(--form-input-inline-input-size)', 'sm'),
        '@screen md': {
          width: vw('var(--form-input-inline-input-size)', 'lg'),
          height: vw('var(--form-input-inline-input-size)', 'lg'),
        },
      },
      '&-input': {
        position: 'relative',
        display: 'inline-grid',
        placeItems: 'center',
        height: vw('var(--form-input-inline-input-size)', 'sm'),
        '@screen md': {
          height: vw('var(--form-input-inline-input-size)', 'lg'),
        },
        '&::before': {
          pointerEvents: 'none',
          content: "''",
          display: 'block',
          position: 'absolute',
          top: '50%',
          left: '50%',
          transform: 'translate(-50%, -50%)',
          borderStyle: 'solid',
          borderColor: 'var(--color-form-input-inline-default-border)',
          backgroundColor: 'var(--color-form-input-inline-default-background)',
          transitionProperty:
            'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
          transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
          transitionDuration: '150ms',
          borderWidth: vw('var(--form-input-inline-border-width)', 'sm'),
          width: vw('var(--form-input-inline-input-size)', 'sm'),
          height: vw('var(--form-input-inline-input-size)', 'sm'),
          '@screen md': {
            borderWidth: vw('var(--form-input-inline-border-width)', 'lg'),
            width: vw('var(--form-input-inline-input-size)', 'lg'),
            height: vw('var(--form-input-inline-input-size)', 'lg'),
          },
        },
        '&::after': {
          pointerEvents: 'none',
          content: "''",
          display: 'block',
          position: 'absolute',
          top: '50%',
          left: '50%',
          transform: 'translate(-50%, -50%)',
          transitionProperty:
            'color, background-color, border-color, text-decoration-color, fill, stroke, box-shadow',
          transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
          transitionDuration: '150ms',
          width: vw('var(--form-input-inline-input-size)', 'sm'),
          height: vw('var(--form-input-inline-input-size)', 'sm'),
          '@screen md': {
            width: vw('var(--form-input-inline-input-size)', 'lg'),
            height: vw('var(--form-input-inline-input-size)', 'lg'),
          },
        },
        input: {
          opacity: 0,
        },
      },
      '&-label': {
        color: 'var(--color-form-input-inline-default-text)',
        '@apply text-form-inline': {},
      },
      '&-input:has(:checked) .form-radio-label, &-input:has(:checked) .form-checkbox-label': {
        color: 'var(--color-form-input-inline-checked-text)',
      },
      '*': {
        cursor: 'pointer',
      },
      '&:has(:disabled)': {
        cursor: 'not-allowed',
        opacity: 'calc(var(--color-form-input-inline-disabled-opacity) / 100)',
        '*': {
          cursor: 'not-allowed',
        },
      },
    },
    '.form-radio': {
      '&-input::before': {
        borderRadius: '9999px',
      },
      '&-input::after': {
        background: 'transparent',
        borderRadius: '9999px',
        transformOrigin: 'center',
        transform: `translate(-50%, -50%) scale(calc(var(--form-input-inline-marker-size) / var(--form-input-inline-input-size)))`,
      },
      '&-input:has(:checked)': {
        '&::before': {
          borderColor: 'var(--color-form-input-inline-checked-border)',
          backgroundColor: 'var(--color-form-input-inline-checked-background)',
        },
        '&::after': {
          backgroundColor: 'var(--color-form-input-inline-checked-marker)',
        },
      },
    },
    '.form-checkbox': {
      '&-input::before': {
        borderRadius: vw('var(--form-input-inline-checkbox-border-radius)', 'sm'),
        '@screen md': {
          borderRadius: vw('var(--form-input-inline-checkbox-border-radius)', 'lg'),
        },
      },
      '&-input::after': {
        backgroundColor: 'transparent',
        maskImage: `url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'%3E%3Cpath fill='currentColor' d='M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z'/%3E%3C/svg%3E")`,
        maskRepeat: 'no-repeat',
        maskPosition: 'center',
        maskSize: 'contain',
        transformOrigin: 'center',
        transform: `translate(-50%, -50%) scale(calc(var(--form-input-inline-check-size) / var(--form-input-inline-input-size)))`,
      },
      '&-input:has(:checked)': {
        '&::before': {
          borderColor: 'var(--color-form-input-inline-checked-border)',
          backgroundColor: 'var(--color-form-input-inline-checked-background)',
        },
        '&::after': {
          backgroundColor: 'var(--color-form-input-inline-checked-check)',
        },
      },
    },
  },
  '.form-style-compact, .form-style-underline': {
    '.form-group': {
      "&:has(input[type='text'], input[type='password'], input[type='email'], input[type='tel'], input[type='url'], input[type='search'], input[type='number'], input[type='date'], input[type='time'], input[type='datetime-local'], input[type='month'], input[type='week'], select, textarea)":
        {
          '.form-group-label': {
            opacity: 0,
            transitionProperty: 'opacity',
            transitionTimingFunction: extendEasings.transitionTimingFunction['out-cubic'],
            transitionDuration: '150ms',
          },
        },
      "&:has(input[type='text']:focus, input[type='password']:focus, input[type='email']:focus, input[type='tel']:focus, input[type='url']:focus, input[type='search']:focus, input[type='number']:focus, input[type='date']:focus, input[type='time']:focus, input[type='datetime-local']:focus, input[type='month']:focus, input[type='week']:focus, select:focus, textarea:focus)":
        {
          '.form-group-label': {
            opacity: 1,
          },
        },
      "&:has(input[type='text']:not(:placeholder-shown), input[type='password']:not(:placeholder-shown), input[type='email']:not(:placeholder-shown), input[type='tel']:not(:placeholder-shown), input[type='url']:not(:placeholder-shown), input[type='search']:not(:placeholder-shown), input[type='number']:not(:placeholder-shown), input[type='date']:not(:placeholder-shown), input[type='time']:not(:placeholder-shown), input[type='datetime-local']:not(:placeholder-shown), input[type='month']:not(:placeholder-shown), input[type='week']:not(:placeholder-shown), textarea:not(:placeholder-shown)), &:has(option[value='']:not(:checked))":
        {
          '.form-group-label': {
            opacity: 1,
          },
        },
    },
  },
  '.form-style-underline': {
    "input[type='text'], input[type='password'], input[type='email'], input[type='tel'], input[type='url'], input[type='search'], input[type='number'], input[type='date'], input[type='time'], input[type='datetime-local'], input[type='month'], input[type='week'], select, textarea":
      {
        paddingInline: 0,
        paddingTop: 0,
        borderLeftWidth: 0,
        borderRightWidth: 0,
        borderTopWidth: 0,
        borderRadius: 0,
      },
    textarea: {
      height: 'auto',
      resize: 'none',
    },
    '.form-select': {
      select: {
        paddingRight: vw('var(--form-input-block-icon-size)', 'sm'),
      },
      '.form-select-icon': {
        right: 0,
        transform:
          'translateY(calc(var(--form-input-block-padding-y)/var(--tw-screen-sm)*var(--tw-screen-max)*var(--tw-scale) * -1))',
        '@screen md': {
          transform:
            'translateY(calc(var(--form-input-block-padding-y)/var(--tw-screen-lg)*var(--tw-screen-max)*var(--tw-scale) * -1))',
        },
      },
    },
  },
  '.form-group:has(:user-invalid) .invalid-message': {
    display: 'block',
  },
  '.form-group:has(:user-invalid)': {
    "input[type='text'], input[type='password'], input[type='email'], input[type='tel'], input[type='url'], input[type='search'], input[type='number'], input[type='date'], input[type='time'], input[type='datetime-local'], input[type='month'], input[type='week'], select, textarea":
      {
        borderColor: 'var(--color-form-input-block-invalid-border)',
        backgroundColor: 'var(--color-form-input-block-invalid-background)',
        color: 'var(--color-form-input-block-invalid-text)',
        boxShadow: `${vw('var(--form-input-block-default-shadow-x)', 'sm')} ${vw('var(--form-input-block-default-shadow-y)', 'sm')} ${vw('var(--form-input-block-default-shadow-blur)', 'sm')} ${vw('var(--form-input-block-default-shadow-spread)', 'sm')} var(--color-form-input-block-invalid-shadow)`,
        '&:focus': {
          boxShadow: `${vw('var(--form-input-block-focus-shadow-x)', 'sm')} ${vw('var(--form-input-block-focus-shadow-y)', 'sm')} ${vw('var(--form-input-block-focus-shadow-blur)', 'sm')} ${vw('var(--form-input-block-focus-shadow-spread)', 'sm')} var(--color-form-input-block-invalid-shadow)`,
        },
        '@screen md': {
          boxShadow: `${vw('var(--form-input-block-default-shadow-x)', 'lg')} ${vw('var(--form-input-block-default-shadow-y)', 'lg')} ${vw('var(--form-input-block-default-shadow-blur)', 'lg')} ${vw('var(--form-input-block-default-shadow-spread)', 'lg')} var(--color-form-input-block-invalid-shadow)`,
          '&:focus': {
            boxShadow: `${vw('var(--form-input-block-focus-shadow-x)', 'lg')} ${vw('var(--form-input-block-focus-shadow-y)', 'lg')} ${vw('var(--form-input-block-focus-shadow-blur)', 'lg')} ${vw('var(--form-input-block-focus-shadow-spread)', 'lg')} var(--color-form-input-block-invalid-shadow)`,
          },
        },
        '&::placeholder': {
          color: 'var(--color-form-input-block-invalid-placeholder)',
        },
        '&:focus::placeholder': {
          color: 'transparent',
        },
      },
    '.form-select': {
      select: {
        "&:not(:has(option[value='']:not(:checked)))": {
          color: 'var(--color-form-input-block-invalid-placeholder)',
        },
      },
      '.form-select-icon': {
        color: 'var(--color-form-input-block-invalid-icon)',
      },
    },
  },
  '.form-group .form-radio, .form-group .form-checkbox': {
    '&-input:has(:user-invalid)': {
      '&::before': {
        borderColor: 'var(--color-form-input-inline-invalid-border)',
        backgroundColor: 'var(--color-form-input-inline-invalid-background)',
      },
      '&::after': {
        backgroundColor: 'var(--color-form-input-inline-invalid-marker)',
      },
    },
  },
  '.form-group .form-radio:has(:user-invalid) .form-radio-label, .form-group .form-checkbox:has(:user-invalid) .form-checkbox-label':
    {
      color: 'var(--color-form-input-inline-invalid-text)',
    },
}

module.exports = { form }
