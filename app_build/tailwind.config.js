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
                'electric-lime': '#1e5e3a',   // Lush Forest Green (representing nature and adventure)
                'near-black': '#0f1a15',      // Deep Jungle Black
                'warm-cream': '#f4f3ed',      // Warm Cream sand page background
                'parchment-card': '#ffffff',  // Pristine Elevated White
                'stone': '#dfdfd6',           // Soft Stone borders
                'graphite': '#3f4e45',        // Deep Sage Charcoal body text
                'charcoal': '#0b1611',        // Midnight Forest dark sections
                'mint-confirm': '#1dc479',
                'coral-alert': '#eb3131',
            },
            borderRadius: {
                '3xl': '26px',
            }
        },
    },

    plugins: [forms],
};
