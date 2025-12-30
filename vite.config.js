import { defineConfig } from 'vite';
import vue from "@vitejs/plugin-vue";
import laravel from 'laravel-vite-plugin';

export default defineConfig({
	server: {
		host: process.env.APP_HOST
	},
	build: {
        outDir: 'public/build',
        sourcemap: false,
    },
    resolve: {
        alias: {
          // Add an alias for the images directory
          '@theme_1': '/public/front/theme_1',
          '@upload': '/public/storage/uploads/',
        },
    },
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
          scss: {
            additionalData: `
            @import "node_modules/bootstrap/scss/bootstrap";
            `,
          },
        },
    },
});
