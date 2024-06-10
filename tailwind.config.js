const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: 'selector',
    safelist: [
        'my-0', 'my-1', 'my-2', 'my-3', 'my-4', 'my-5', 'my-6', 'my-7', 'my-8', 'my-9', 'my-10',
        'mt-0', 'mt-1', 'mt-2', 'mt-3', 'mt-4', 'mt-5', 'mt-6', 'mt-7', 'mt-8', 'mt-9', 'mt-10',
        'mb-0', 'mb-1', 'mb-2', 'mb-3', 'mb-4', 'mb-5', 'mb-6', 'mb-7', 'mb-8', 'mb-9', 'mb-10',
        'm-3'
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'pvox-orange': '#FF7F50',
                'pvox-dark': '#2d3748',
                'pvox-link-dark': '#2d3748',
            }
        },
    },
    plugins: [],
}
