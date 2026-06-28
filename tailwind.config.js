import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                tecsup: {
                    cyan: '#0CB9D7',
                    dark: '#0C2333',
                    gray: '#333333',
                    green: '#2DC000',
                    white: '#FFFFFF',
                    light: '#E8F8FC',
                    border: '#B0E8F3',
                },
            },
        },
    },

    plugins: [forms],
};
