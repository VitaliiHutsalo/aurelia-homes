import { defineConfig } from 'vite';
import { resolve } from 'node:path';

export default defineConfig({
  build: {
    emptyOutDir: true,
    manifest: true,
    outDir: 'dist',
    rollupOptions: {
      input: resolve(__dirname, 'src/js/main.js'),
    },
  },
});
