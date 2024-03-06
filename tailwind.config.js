module.exports = {
  purge: [
    './resources/views/**/*.blade.php',
    './resources/css/**/*.css',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          '100': '#1E569A',

        },
        secondary: {
          '100': '#29A1E5',
          '200': '#6CCBF4',

        },
        dark: {
          '100': '#202020',

        },
        gris: {
          '100': '#979696',

        },
        white: {
          '100': '#ffffff',

        },
        bgprimary: {
          '100': '#143E91',
          '200': '#1B3D75',
        },
        bgsecondary: {
          '100': '#2A84D1',
          '200': '#2A75AD',
        }
      }
    }
  },
  variants: {},
 /* plugins: [
    require('@tailwindcss/ui'),
  ]*/
}
