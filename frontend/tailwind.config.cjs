/** @type {import('tailwindcss').Config} */
module.exports = {
  // Garanta que o content está apontando para os arquivos certos
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: { DEFAULT: '#009688', light: '#b2dfdb', dark: '#00796b' },
        background: '#f8f9fa',
        surface: '#ffffff',
      }
    },
  },
  plugins: [],
}