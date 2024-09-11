module.exports = {
  content: [
    './*.{html,js}',         // Matches HTML and JS files in the root directory
    './**/*.{html,js}',  // Matches HTML and JS files in the src folder, if any
    '!./node_modules/**/*',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'], // Corrected spelling of 'Montserrat'
      },
    },
  },
  plugins: [],
 
};
