import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            // 1. Mendaftarkan Font Profesional kita
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            // 2. Membuat Warna Standar (Misal kita sebut "brand")
            colors: {
                brand: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    400: '#60a5fa', // Warna hover pinggiran
                    500: '#3b82f6', // Biru utama AI kita
                    600: '#2563eb', // Biru gelap untuk hover
                    900: '#0f172a', // Warna gelap tombol
                }
            }
        },
    },
    plugins: [],
};