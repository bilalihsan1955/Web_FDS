/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './theme.json',
  ],
  theme: {
    extend: {
      colors: {
        'fds-teal': {
          50: '#e8f5f7',
          100: '#d0ebef',
          200: '#a1d7de',
          300: '#72c3ce',
          400: '#43afbd',
          500: '#0f808a', // Primary
          600: '#0a555c',
          700: '#052b2e',
          800: '#031e20',
          900: '#021517',
        },
        'fds-white': '#ffffff',
        'fds-black': '#000000',
        'fds-gray': {
          50: '#f9fafb',
          100: '#f3f4f6',
          200: '#e5e7eb',
          300: '#d1d5db',
          400: '#9ca3af',
          500: '#6b7280',
          600: '#4b5563',
          700: '#374151',
          800: '#1f2937',
          900: '#111827',
        },
      },
      spacing: {
        '52px': '52px',
        '70vh': '70vh',
      },
      inset: {
        't-[52px]': '52px 0 auto 0',
      },
    },
  },
  plugins: [],
}
