import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                // Watch for PHP files
                'app/**/*.php',
                'routes/**/*.php',
                'config/**/*.php',

                // Watch for Blade templates
                'resources/views/**/*.blade.php',

                // Watch for CSS files
                'resources/css/**/*.css',
                'public/css/**/*.css',

                // Watch for JavaScript files
                'resources/js/**/*.js',
                'public/js/**/*.js',

                // Watch for other asset files
                'public/assets/**/*',
                'resources/sass/**/*.scss',

                // Watch for configuration files
                '.env',
                'composer.json',
                'package.json',
            ],
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
            port: 5173,
        },
        watch: {
            usePolling: true,
            interval: 100,
        },
    },
    build: {
        outDir: 'public/build',
        manifest: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['bootstrap', 'chart.js', 'sweetalert2'],
                    datatables: ['datatables.net', 'datatables.net-bs5'],
                },
            },
        },
    },
});
