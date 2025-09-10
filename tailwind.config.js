import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                ethiopic: ['Noto Sans Ethiopic', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                cream: {
                    DEFAULT: 'rgb(var(--color-cream))',
                },
                eggplant: {
                    DEFAULT: 'rgb(var(--color-eggplant))',
                },
                'plum-gray': {
                    DEFAULT: 'rgb(var(--color-plum-gray))',
                },
                gold: {
                    DEFAULT: 'rgb(var(--color-gold))',
                },
            },
        },
    },

    plugins: [forms, typography],
};
