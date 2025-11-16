import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/header.css',
                'resources/css/style-layout.css',
                'resources/css/product.css',
                'resources/css/footer.css',
                'resources/css/product-detail.css',
                'resources/css/checkout.css',
                'resources/css/cart.css',
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/header.js',
                'resources/js/footer.js'
            ],
            refresh: true,
        }),
    ],
});
