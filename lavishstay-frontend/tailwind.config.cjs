/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // Enables dark: prefix
  content: [
    './resources/views/**/*.blade.php', // Scans Blade templates
    './resources/js/**/*.js', // Adjust if you have JS components
  ],
  theme: {
    extend: {
      colors: {
        emerald: {
          100: '#d1fae5',
          400: '#34d399',
          600: '#059669',
        },
        violet: {
          100: '#ede9fe',
          400: '#a78bfa',
          600: '#7c3aed',
        },
        amber: {
          100: '#fef3c7',
          400: '#f59e0b',
          600: '#d97706',
        },
      },
    },
  },
  plugins: [],
};