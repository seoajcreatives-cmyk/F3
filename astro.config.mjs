import { defineConfig } from 'astro/config';
import { execSync } from 'child_process';

function getGitBranch() {
  if (process.env.GITHUB_REF_NAME) {
    return process.env.GITHUB_REF_NAME;
  }
  try {
    return execSync('git rev-parse --abbrev-ref HEAD').toString().trim();
  } catch (e) {
    return 'main';
  }
}

const currentBranch = getGitBranch();
const base = currentBranch === 'test' ? '/test' : '/';

// https://astro.build/config
export default defineConfig({
  // Prerenderizado Estático (SSG) por defecto
  output: 'static',
  site: 'https://f3constructionny.com',
  base: base,
  trailingSlash: 'never', // <- AGREGA ESTA LÍNEA COMPLETA
  image: {
    domains: ['f3constructionny.com'],
  },
});