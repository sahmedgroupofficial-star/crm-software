import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Backend (Admin Panel)
                'resources/css/backEnd/app.css',
                'resources/js/backEnd/app.js',

                // Frontend (Client Portal)
                'resources/css/frontEnd/app.css',
                'resources/js/frontEnd/app.js',
            ],
            refresh: true,
        }),
    ],
})
