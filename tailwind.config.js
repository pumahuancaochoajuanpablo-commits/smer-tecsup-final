import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
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
                    cyan:    '#0CB9D7',   // Azul-cyan Tecsup
                    dark:    '#0C2333',   // Azul oscuro Tecsup
                    gray:    '#333333',   // Gris texto
                    green:   '#2DC000',   // Verde botones acción
                    white:   '#FFFFFF',
                    light:   '#E8F8FC',   // Fondo suave derivado del cyan
                    border:  '#B0E8F3',   // Borde suave
                },
            },
        },
    },

    plugins: [forms],
};
