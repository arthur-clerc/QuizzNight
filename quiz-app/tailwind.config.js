/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{js,jsx,ts,tsx}"],
  theme: {
    extend: {
      colors: {
        'indigo-custom': '#4B0082',
      },
    },
  },
  plugins: [],
};
