/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');
module.exports = {
  content: [
    "./assets/**/*.{vue,js,ts,jsx,tsx}",
    "./templates/**/*.{html,twig}",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    extend: {
        fontFamily: {
            sans: ['Inter-var', ...defaultTheme.fontFamily.sans],
        },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}
