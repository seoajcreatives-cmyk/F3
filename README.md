# Manual de Edicion - Sitio Web F3 Construction
> Para quien es este manual: Cualquier persona del equipo que necesite editar el sitio, aunque no sea tecnica. No necesitas saber programar.

---

## Como funciona el sitio

El sitio web de F3 Construction tiene DOS ambientes:

- PRUEBA (TEST): f3constructionny.com/test/ — Ver los cambios antes de publicarlos
- PRODUCCION (LIVE): f3constructionny.com — Lo que ven los clientes reales

Los cambios siempre van primero a TEST, se revisan, y solo cuando estan bien se pasan a produccion.

---

## Las herramientas que usamos

- Google Antigravity: Editor de paginas web con IA — Aqui se hacen todos los cambios visuales
- GitHub: Repositorio de codigo — Guarda todas las versiones del sitio
- GitHub Actions: Sistema automatico — Detecta cambios y publica el sitio solo
- Hostinger: Servidor web — Donde vive el sitio en internet

El flujo completo:
Google Antigravity → guarda en GitHub → GitHub Actions construye → publica en Hostinger

Tu solo necesitas trabajar en Antigravity. El resto pasa automaticamente.

---

## Como hacer cambios en el sitio

### Paso 1 — Abrir Google Antigravity

Accede a tu cuenta de Google Antigravity y abre el proyecto F3 Construction.

### Paso 2 — Verificar la rama correcta

Antes de editar, verifica en que rama estas trabajando:

- Rama `test` → los cambios van a f3constructionny.com/test/ (aqui debes trabajar normalmente)
- Rama `main` → los cambios van directo a produccion (solo cuando estes seguro)

REGLA DE ORO: Siempre edita en la rama `test` primero. Nunca edites directamente en `main` a menos que el equipo lo haya aprobado.

### Paso 3 — Hacer tus cambios

Edita el texto, imagenes o secciones que necesites usando las herramientas de Antigravity normalmente.

### Paso 4 — Guardar en Antigravity

Cuando termines de editar, guarda los cambios. Antigravity los enviara automaticamente a GitHub.

### Paso 5 — Esperar el deploy automatico

GitHub detectara el cambio y comenzara a construir el sitio automaticamente. Este proceso tarda entre 1 y 3 minutos.

Puedes ver el progreso en: https://github.com/seoajcreatives-cmyk/F3/actions

Mientras el proceso corre veras un circulo amarillo. Cuando termina bien, aparece una paloma verde.

### Paso 6 — Revisar en TEST

Abre f3constructionny.com/test/ en tu navegador.

Si no ves los cambios, haz Ctrl + Shift + R (Windows) o Cmd + Shift + R (Mac) para forzar la recarga sin cache.

### Paso 7 — Aprobar y pasar a produccion

Si todo se ve bien en TEST, el encargado del equipo mueve los cambios a la rama `main` en GitHub. Eso dispara un nuevo deploy automatico que publica todo en f3constructionny.com.

---

## Semaforo de estados en GitHub Actions

Cuando entras a la pagina de Actions, cada deploy muestra un icono:

- Circulo amarillo (en progreso) → Esperar, no tocar nada
- Paloma verde → Deploy exitoso → Revisar el sitio en el navegador
- X roja → Deploy fallido → Avisar al encargado tecnico

IMPORTANTE: Nunca hagas otro cambio mientras el icono esta en amarillo. Espera siempre a que termine.

---

## Links importantes

- Sitio de produccion: https://f3constructionny.com
- Sitio de prueba: https://f3constructionny.com/test/
- GitHub Actions (ver deploys): https://github.com/seoajcreatives-cmyk/F3/actions
- Repositorio GitHub: https://github.com/seoajcreatives-cmyk/F3

---

## Cosas que NUNCA debes hacer

- NO subas archivos directamente a Hostinger — todo va por GitHub
- NO edites archivos de configuracion como .github/workflows/, astro.config.mjs, package.json
- NO borres ni muevas estos archivos en Hostinger: .htaccess, .ftp-deploy-sync-state.json
- NO hagas cambios en produccion (main) sin probar antes en TEST (test)
- NO hagas un segundo push mientras hay uno en progreso (icono amarillo)

---

## Que hacer si algo salio mal

- No veo mis cambios en /test/ → Espera 2-3 min, luego Ctrl+Shift+R para recargar
- El deploy tiene X roja → Toma captura de pantalla del error y avisa al encargado tecnico
- El sitio de produccion no abre → Avisar inmediatamente al encargado tecnico
- Borre algo por error en Antigravity → No cierres la pestana, avisa al encargado para revertir desde GitHub

---

## Informacion tecnica (solo referencia)

- Rama `test` usa el usuario FTP u560442877.F3Test (raiz: public_html/test/)
- Rama `main` usa el usuario FTP u560442877.F3Prod (raiz: public_html/)
- El servidor FTP usa la IP directa 82.197.84.251 (no el dominio ftp. que pasa por Cloudflare)
- Las credenciales FTP estan guardadas como Secrets en GitHub — no se deben compartir

---

Manual actualizado: Junio 2026 — F3 Construction Corp
