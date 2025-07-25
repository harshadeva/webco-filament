/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                'webco-primary': '#32475a',
                'webco-secondary': '#f09c37',
            },
        },
    },
    plugins: [],
};
