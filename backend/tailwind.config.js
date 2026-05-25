import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/Filament/**/*.php", // If you want to use Tailwind utilities in Filament custom views
        './vendor/filament/**/*.blade.php', // To ensure Filament components are styled
    ],
    theme: {
        extend: {
            fontFamily: {
                'playfair': ['"Playfair Display"', 'serif'],
                'lato': ['Lato', 'sans-serif'],
            },
            colors: {
                // Example custom colors for a jewellery site
                'primary-gold': '#B08D57', // A soft, elegant gold
                'accent-rose': '#D9A6A4', // A dusty rose accent
                'dark-slate': '#323232',  // For text or dark backgrounds
                'soft-cream': '#F5F5F0',  // A gentle off-white background
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'), // Useful for styling forms if you build any on the frontend
        require('@tailwindcss/typography'), // For styling content from Rich Text Editors (like blog posts)
    ],
}