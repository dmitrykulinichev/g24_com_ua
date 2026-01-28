/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./views/**/*.blade.php",
    "./app/Services/MarkdownService.php", // Там є HTML для скріншотів
    "./content/**/*.md" // Якщо в маркдауні є класи
  ],
  theme: {
    extend: {
      colors: {
        primary: '#2563eb', // blue-600
        secondary: '#1e293b', // slate-800
        accent: '#10b981', // emerald-500
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      }
    },
  },
  plugins: [],
}