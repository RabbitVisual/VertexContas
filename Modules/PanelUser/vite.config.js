import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: '../../public/build-paneluser',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-paneluser',
            input: [
                'resources/assets/sass/app.scss',
                'resources/assets/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
