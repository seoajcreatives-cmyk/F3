import { defineConfig } from 'astro/config';

// El base path se decide por la variable de entorno DEPLOY_ENV, que solo se
// define en el workflow de despliegue de la rama `test`:
//   - DEPLOY_ENV=test  -> el sitio se sirve desde /test  (base '/test')
//   - sin definir      -> producción y local usan la raíz (base '/')
// No dependemos del nombre de la rama de git (poco fiable en CI por el
// checkout en detached HEAD y porque en local la rama activa suele ser `test`).
const base = process.env.DEPLOY_ENV === 'test' ? '/test' : '/';

// https://astro.build/config
export default defineConfig({
  // Prerenderizado Estático (SSG) por defecto
  output: 'static',
  site: 'https://f3constructionny.com',
  base,
  trailingSlash: 'never',
  image: {
    domains: ['f3constructionny.com'],
  },
});
