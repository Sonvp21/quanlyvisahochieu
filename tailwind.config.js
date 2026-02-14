import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        // Background colors
        'bg-green-100',
        'bg-green-500',
        'bg-yellow-100',
        'bg-yellow-400',
        'bg-yellow-500',
        'bg-red-100',
        'bg-red-500',
        'bg-gray-100',
        'bg-gray-400',
        'bg-blue-100',
        'bg-blue-200',
        'bg-orange-500',
        'bg-orange-400',
        'bg-orange-300',
        'bg-orange-600',
        'bg-orange-700',
        'bg-orange-900',


        // Text colors
        'text-green-800',
        'text-green-600',
        'text-yellow-800',
        'text-yellow-600',
        'text-red-800',
        'text-red-600',
        'text-gray-800',
        'text-blue-600',

        // Border colors
        'border-green-500',
        'border-yellow-500',
        'border-red-500',

        // Background colors for alerts
        'bg-green-50',
        'bg-yellow-50',
        'bg-red-50',

        // Text colors for alerts
        'text-green-700',
        'text-yellow-700',
        'text-red-700',

        // Gradient colors - FROM
        'from-blue-400',
        'from-blue-500',
        'from-blue-600',
        'from-blue-700',
        'from-purple-400',
        'from-purple-500',
        'from-purple-600',
        'from-green-400',
        'from-green-500',
        'from-green-600',
        'from-red-400',
        'from-red-500',
        'from-red-600',
        'from-orange-400',
        'from-orange-500',
        'from-orange-600',
        'from-indigo-400',
        'from-indigo-500',
        'from-indigo-600',
        'from-teal-400',
        'from-teal-500',
        'from-teal-600',

        // Gradient colors - TO
        'to-blue-500',
        'to-blue-600',
        'to-blue-700',
        'to-blue-800',
        'to-purple-500',
        'to-purple-600',
        'to-purple-700',
        'to-green-500',
        'to-green-600',
        'to-green-700',
        'to-red-500',
        'to-red-600',
        'to-red-700',
        'to-orange-500',
        'to-orange-600',
        'to-orange-700',
        'to-indigo-500',
        'to-indigo-600',
        'to-indigo-700',
        'to-teal-500',
        'to-teal-600',
        'to-teal-700',

        // Gradient directions
        'bg-gradient-to-r',
        'bg-gradient-to-l',
        'bg-gradient-to-t',
        'bg-gradient-to-b',
        'bg-gradient-to-br',
        'bg-gradient-to-bl',
        'bg-gradient-to-tr',
        'bg-gradient-to-tl',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
