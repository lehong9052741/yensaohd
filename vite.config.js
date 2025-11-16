import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/footer.css',
                'resources/js/footer.js',
                'resources/css/product-detail.css',
                'resources/css/checkout.css',
            ],
            refresh: true,
        }),
    ],
});
