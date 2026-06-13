import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/forms.css',
                'resources/css/home.css',
                'resources/css/auctions.css',
                'resources/js/app.js',
                'resources/js/auth/login.js',
                'resources/js/auth/register.js',
                'resources/js/auctions/category-picker.js',
                'resources/js/auctions/auction-page.js',
                'resources/css/auction-page.css',
                'resources/css/messages.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
