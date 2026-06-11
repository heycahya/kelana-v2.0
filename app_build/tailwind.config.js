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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'electric-lime': '#beff50',
                'near-black': '#14140f',
                'warm-cream': '#f5f5eb',
                'parchment-card': '#fafaf5',
                'stone': '#d2d2c8',
                'graphite': '#6e6e64',
                'charcoal': '#333333',
                'mint-confirm': '#a3e635',
                'coral-alert': '#fca5a5',
            },
            borderRadius: {
                '3xl': '26px',
            }
        },
    },

    plugins: [forms],
};
