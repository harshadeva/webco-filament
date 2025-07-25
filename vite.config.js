import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@filament': path.resolve(__dirname, 'vendor/filament/filament/resources/css'),
        },
    },
});
