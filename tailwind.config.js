/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            container: {
                center: true,
                padding: '1rem',
            },
            fontFamily: {
                'body-font': ['Roboto Condensed', 'sans-serif'],
            }
        },
    },
    plugins: [],
}

