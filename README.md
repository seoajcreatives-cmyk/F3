# 📋 MANUAL DE TRABAJO — Sitio Web F3 Construction

> **¿Para quién es este manual?** Para cualquier persona del equipo que necesite editar el sitio. No necesitas saber programar. Si puedes editar un documento de Word y manejar archivos en tu computador, puedes seguir este manual.

---

## ¿Cómo funciona este sitio?

El sitio de F3 Construction está construido con una tecnología llamada **Astro**, que genera páginas web muy rápidas con excelentes puntajes en Google. No es un WordPress.

El sitio tiene dos lugares importantes:

**GitHub** es donde vive el código fuente — es el archivo original y la fuente de verdad del proyecto. Todos los cambios grandes pasan por aquí.

**Hostinger** es donde vive el sitio publicado — es lo que ven los clientes cuando entran a f3constructionny.com.

---

## Los dos ambientes del sitio

El sitio tiene dos versiones:

**Prueba (TEST):** f3constructionny.com/test/ — aquí se ven los cambios antes de publicarlos. Es como un borrador. Nadie afuera del equipo lo usa.

**Producción (LIVE):** f3constructionny.com — este es el sitio real que ven los clientes.

Regla de oro: siempre revisa en TEST antes de publicar en producción cuando uses el Flujo B.

---

## ¿Dónde está cada archivo del sitio?

| Necesito editar... | Busco en... |
|---|---|
| Textos SEO de páginas de servicios (títulos, meta descriptions, párrafos, FAQs) | `src/data/services.json` |
| Páginas individuales (inicio, contacto, galería) | `src/pages/` |
| El menú de navegación o el footer | `src/components/` |
| Los colores o fuentes del sitio | `src/styles/` |
| Imágenes o logos | `public/media/` |

---

## Los dos flujos de trabajo

### Flujo A — Cambios pequeños de SEO (textos, títulos, meta descriptions, FAQs)

Este es el flujo para cambios puntuales de optimización SEO en páginas de servicios. Casi todo el contenido de servicios está en el archivo `src/data/services.json`.

**Paso 1 — Descargar el archivo desde GitHub.**
Entra a github.com/seoajcreatives-cmyk/F3 y navega hasta `src/data/services.json`. Haz clic en el botón de descarga (ícono de flecha hacia abajo) para guardar el archivo en tu computador.

**Paso 2 — Editar en VS Code.**
Abre el archivo descargado en Visual Studio Code. Busca el servicio que quieres editar usando `Ctrl + F` y buscando el slug de la URL (ejemplo: busca `deck-builders-long-island-ny` para editar la página de ese servicio). Edita los campos que necesites:
- `metaTitle` → el título que aparece en Google
- `metaDescription` → la descripción que aparece en Google
- `h1` → el título principal de la página
- `infoParagraphs` → los párrafos de texto de la sección de información
- `faqs` → las preguntas frecuentes
- `canonical` → la URL canónica de la página

**Paso 3 — Subir a Hostinger.**
Una vez editado, sube el archivo modificado a Hostinger reemplazando el archivo anterior. Los cambios aparecen de inmediato en el sitio real.

**Paso 4 — Actualizar GitHub (obligatorio).**
Para mantener GitHub sincronizado con lo que está en Hostinger, también debes guardar el cambio en GitHub. Entra al archivo `src/data/services.json` en GitHub, haz clic en el ícono del lápiz (✏️) para editarlo, reemplaza el contenido con tu versión actualizada, y haz commit directamente a la rama **main** con un mensaje descriptivo. Ejemplo: *"SEO: actualizar meta title y h1 de deck builders long island"*.

> ⚠️ Este paso es fundamental. Si no actualizas GitHub, la próxima vez que alguien haga un cambio grande desde Antigravity el deploy puede sobreescribir lo que subiste a Hostinger y perderás los cambios.

---

### Flujo B — Cambios grandes con Google Antigravity

Usa Antigravity cuando el cambio afecte varias páginas al mismo tiempo, necesites agregar secciones nuevas, reorganizar contenido extenso, o no estás seguro en qué archivo está lo que quieres cambiar.

**Paso 1 — Verificar que el proyecto está conectado en Antigravity.**
Si es la primera vez, conecta el repositorio: https://github.com/seoajcreatives-cmyk/F3 y selecciona la rama **test**.

**Paso 2 — Describir el cambio al agente.**
Sé específico. No digas "mejora el sitio" — di algo como: *"En la página de servicios deck builders, agrega un nuevo FAQ con la pregunta X y la respuesta Y"*.

**Paso 3 — Revisar los cambios.**
Antes de confirmar, revisa lo que el agente modificó y pídele que explique qué archivos tocó.

**Paso 4 — Hacer push a la rama test.**
Confirma los cambios. Antigravity los sube automáticamente a GitHub en la rama test. Escribe un mensaje claro en el commit.

**Paso 5 — Esperar el deploy automático.**
GitHub construye y publica el sitio en /test/ en 1 a 3 minutos. Puedes ver el progreso en la pestaña Actions del repositorio.

**Paso 6 — Revisar en el ambiente de prueba.**
Abre f3constructionny.com/test/ y verifica que todo se vea bien. Si no ves los cambios, recarga con `Ctrl + Shift + R`.

**Paso 7 — Pasar a producción.**
Cuando todo esté correcto, el encargado hace un push a la rama **main** y el cambio se publica en el sitio real.

---

## ¿Cómo saber si el deploy funcionó? (solo aplica al Flujo B)

Ve a github.com/seoajcreatives-cmyk/F3/actions y mira el último proceso:

- 🟡 **Amarillo** → el robot está trabajando. Espera antes de hacer más cambios.
- 🟢 **Verde** → deploy exitoso. Revisa el sitio en el navegador.
- 🔴 **Rojo** → algo falló. No toques nada y avisa al encargado técnico con captura del error.

---

## ¿Qué hacer si algo sale mal?

**No veo mis cambios en el sitio** → Si subiste directamente a Hostinger (Flujo A), los cambios deben verse de inmediato. Recarga con `Ctrl + Shift + R`. Si usaste Antigravity (Flujo B) y el sitio está en /test/, espera 3 minutos y recarga.

**El deploy tiene ícono rojo** → No intentes arreglar nada solo. Toma captura del error en Actions y avisa al encargado técnico.

**Cambié algo por error en GitHub** → Puedes revertir el último commit. Entra al historial de commits (pestaña Code → Commits), haz clic en el commit anterior y usa el botón "Revert". El robot publica automáticamente la versión anterior.

**Cambié algo por error en Hostinger** → Vuelve a subir la versión anterior del archivo desde GitHub.

---

## Reglas importantes

- Siempre que edites y subas a Hostinger directamente (Flujo A), también actualiza el mismo cambio en GitHub para mantener todo sincronizado.
- Nunca hagas push mientras el ícono en Actions está amarillo — espera a que termine.
- Antes de usar Antigravity (Flujo B), asegúrate de que GitHub esté actualizado. Si alguien hizo cambios directos en Hostinger sin actualizar GitHub, esos cambios se perderán en el próximo deploy.
- Nunca compartas las credenciales FTP ni los Secrets de GitHub con nadie fuera del equipo técnico.

---

## Links importantes

| Lo que necesito | El link |
|---|---|
| Ver el sitio real | https://f3constructionny.com |
| Ver el ambiente de prueba | https://f3constructionny.com/test/ |
| Ver los deploys en curso | https://github.com/seoajcreatives-cmyk/F3/actions |
| Repositorio en GitHub | https://github.com/seoajcreatives-cmyk/F3 |

---

## Glosario

**Astro** — La tecnología con la que está construido el sitio. Genera páginas web muy rápidas. No necesitas saber usarla directamente.

**GitHub** — Donde se guarda todo el código del sitio. Funciona como un Google Drive para código: guarda el historial de todos los cambios.

**Repositorio** — El proyecto completo dentro de GitHub. En este caso es seoajcreatives-cmyk/F3.

**Rama (branch)** — Una versión paralela del código. `test` es la versión de prueba y `main` es la versión de producción.

**Commit** — Guardar un cambio en GitHub. Cada commit tiene un mensaje que describe qué se cambió.

**Deploy** — El proceso automático que toma el código de GitHub, lo construye y lo publica en Hostinger. Tarda entre 1 y 3 minutos. Solo aplica al Flujo B con Antigravity.

**GitHub Actions** — El robot automático que se encarga del deploy. Se puede ver su estado en la pestaña Actions del repositorio.

**Hostinger** — El servidor web donde vive el sitio publicado. Para cambios pequeños de SEO (Flujo A) se edita directamente aquí, siempre y cuando también se actualice GitHub.

**Google Antigravity** — El editor con agente de IA que el equipo usa para hacer cambios grandes o en múltiples páginas a la vez. Se conecta directamente al repositorio de GitHub.

**VS Code** — Visual Studio Code, el editor de texto donde el equipo edita los archivos localmente antes de subirlos a Hostinger.

**Slug** — El identificador único de cada servicio en el archivo services.json. Es la parte final de la URL. Por ejemplo, en `/services/deck-builders-long-island-ny/` el slug es `deck-builders-long-island-ny`. Sirve para encontrar rápidamente el servicio correcto en el archivo.

**Caché** — Copias temporales que guarda el navegador. Si el navegador muestra una versión vieja del sitio, recarga con `Ctrl + Shift + R`.
