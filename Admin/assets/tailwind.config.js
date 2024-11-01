const defaultTheme = require('tailwindcss/defaultTheme');

// Get icons from https://heroicons.com/

module.exports = {
  important: '#wpradio-page',
  purge: ['../partials/*.php'],
  theme: {
    extend: {
      colors: {
        // Add your custom colors
        orange: {
          100: '#fffaf0',
          200: '#feebc8',
          300: '#fbd38d',
          400: '#f6ad55',
          500: '#ed8936',
          600: '#dd6b20',
          700: '#c05621',
          800: '#9c4221',
          900: '#9c4221'
        },
        // primary: {
        //   500: '#667eea',
        // }
      },
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
      },
    },
    fontSize: {
      xs: '0.75rem',
      sm: '0.875rem',
      base: '1rem',
      lg: '1.125rem',
      xl: '1.25rem',
      '2xl': '1.5rem',
      '3xl': '1.875rem',
      '4xl': '2.25rem',
      '5xl': '3rem',
      '6xl': '4rem',
    },
    // colors: {
    //   transparent: 'transparent',
    //   current: 'currentColor',

    //   black: '#000',
    //   white: '#fff',

    //   gray: {
    //     50: '#f9fafb',
    //     100: '#F7FAFC',
    //     200: '#EDF2F7',
    //     300: '#E2E8F0',
    //     400: '#CBD5E0',
    //     500: '#A0AEC0',
    //     600: '#718096',
    //     700: '#4A5568',
    //     800: '#2D3748',
    //     900: '#1A202C',
    //   },
    //   red: {
    //     50: '#fef2f2',
    //     100: '#FFF5F5',
    //     200: '#FED7D7',
    //     300: '#FEB2B2',
    //     400: '#FC8181',
    //     500: '#F56565',
    //     600: '#E53E3E',
    //     700: '#C53030',
    //     800: '#9B2C2C',
    //     900: '#742A2A',
    //   },
    //   orange: {
    //     100: '#fffaf0',
    //     200: '#feebc8',
    //     300: '#fbd38d',
    //     400: '#f6ad55',
    //     500: '#ed8936',
    //     600: '#dd6b20',
    //     700: '#c05621',
    //     800: '#9c4221',
    //     900: '#9c4221'
    //   },
    //   yellow: {
    //     50: '#fffbeb',
    //     100: '#FFFFF0',
    //     200: '#FEFCBF',
    //     300: '#FAF089',
    //     400: '#F6E05E',
    //     500: '#ECC94B',
    //     600: '#D69E2E',
    //     700: '#B7791F',
    //     800: '#975A16',
    //     900: '#744210',
    //   },
    //   green: {
    //     50: '#ecfdf5',
    //     100: '#F0FFF4',
    //     200: '#C6F6D5',
    //     300: '#9ae6b4',
    //     400: '#68d391',
    //     500: '#48bb78',
    //     600: '#38a169',
    //     700: '#2f855a',
    //     800: '#276749',
    //     900: '#22543d',
    //   },
    //   teal: {
    //     100: '#e6fffa',
    //     200: '#b2f5ea',
    //     300: '#81e6d9',
    //     400: '#4fd1c5',
    //     500: '#38b2ac',
    //     600: '#319795',
    //     700: '#2c7a7b',
    //     800: '#285e61',
    //     900: '#234e52',
    //   },
    //   blue: {
    //     50: '#eff6ff',
    //     100: '#ebf8ff',
    //     200: '#bee3f8',
    //     300: '#90cdf4',
    //     400: '#63b3ed',
    //     500: '#4299e1',
    //     600: '#3182ce',
    //     700: '#2b6cb0',
    //     800: '#2c5282',
    //     900: '#2a4365',
    //   },
    //   indigo: {
    //     100: '#ebf4ff',
    //     200: '#c3dafe',
    //     300: '#a3bffa',
    //     400: '#7f9cf5',
    //     500: '#667eea',
    //     600: '#5a67d8',
    //     700: '#4c51bf',
    //     800: '#434190',
    //     900: '#3c366b',
    //   },
    //   purple: {
    //     100: '#faf5ff',
    //     200: '#e9d8fd',
    //     300: '#d6bcfa',
    //     400: '#b794f4',
    //     500: '#9f7aea',
    //     600: '#805ad5',
    //     700: '#6b46c1',
    //     800: '#553c9a',
    //     900: '#44337a',
    //   },
    //   pink: {
    //     100: '#fff5f7',
    //     200: '#fed7e2',
    //     300: '#fbb6ce',
    //     400: '#f687b3',
    //     500: '#ed64a6',
    //     600: '#d53f8c',
    //     700: '#b83280',
    //     800: '#97266d',
    //     900: '#702459',
    //   },
    // }
  },
  variants: {
    extend: {
      backgroundColor: ['focus', 'active'],
    },
  },
  plugins: [
    // require('@tailwindcss/typography'), // enable it if you need
    // require('@tailwindcss/aspect-ratio'), // enable it if you need
    require('@tailwindcss/forms'),
    require('tailwind-content-placeholder')({
      placeholders: {
        paragraph: {
          height: 4, // the height of the container in em
          rows: [
            // This class will have 4 rows:
            [100], // A 100% width row
            [100], // Another 100% width row
            [40], // A 40% width row
            [], // And an empty row, to create separation
          ],
        },
        line: {
          rows: [
            [100], // One row only, with 70% width
          ],
        },
        square: {
          height: 2, // As this is a sample list, we'll make it a 4 lines placeholder
          rows: [
            [40, 60], // A line with a 20% width segment to simulate the bullet and a 80% width line of content
          ],
        },

        // Create a "bullet list" like content placeholder
        list: {
          height: 4, // As this is a sample list, we'll make it a 4 lines placeholder
          rows: [
            [20, 80], // A line with a 20% width segment to simulate the bullet and a 80% width line of content
            [20, 60], // A line with a 20% width segment to simulate the bullet and a 60% width line of content
            [20, 75], // A line with a 20% width segment to simulate the bullet and a 75% width line of content
            [20, 70], // A line with a 20% width segment to simulate the bullet and a 70% width line of content
          ],
        },
      },
    }),
  ],
};
