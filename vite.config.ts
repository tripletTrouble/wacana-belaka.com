import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    optimizeDeps: {
        include: [
            '@atlaskit/pragmatic-drag-and-drop',
            '@atlaskit/pragmatic-drag-and-drop-hitbox',
            '@atlaskit/pragmatic-drag-and-drop/combine',
            '@atlaskit/pragmatic-drag-and-drop/element/adapter',
            '@atlaskit/pragmatic-drag-and-drop/element/pointer-outside-of-preview',
            '@atlaskit/pragmatic-drag-and-drop/element/set-custom-native-drag-preview',
            '@atlaskit/pragmatic-drag-and-drop-hitbox/tree-item',
        ],
    },
    ssr: {
        noExternal: [
            '@atlaskit/pragmatic-drag-and-drop',
            '@atlaskit/pragmatic-drag-and-drop-hitbox',
        ],
    },
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
