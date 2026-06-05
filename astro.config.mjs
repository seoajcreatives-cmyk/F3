import { defineConfig } from 'astro/config';

// https://astro.build/config
export default defineConfig({
  // Prerenderizado Estático (SSG) por defecto
  output: 'static',
  site: 'https://f3constructionny.com',
  base: '/test',
  image: {
    domains: ['f3constructionny.com'],
  },
});
