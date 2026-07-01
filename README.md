# 📋 Manual de Trabajo — Sitio Web F3 Construction

**¿Para quién es este manual?** Para cualquier persona del equipo que necesite editar el sitio. No necesitas ser desarrollador. Si sabes editar un documento y manejar archivos en tu computador, puedes seguir este manual. Para el Flujo A necesitas instalar un par de herramientas gratuitas (se explica más abajo), pero no tendrás que escribir código.

---

## ¿Cómo funciona este sitio?

El sitio de F3 Construction está construido con una tecnología llamada **Astro**, que genera páginas web muy rápidas con excelentes puntajes en Google. **No es un WordPress.**

Hay un detalle clave de Astro que hay que entender, porque de aquí salían casi todas las confusiones del equipo:

> ⚠️ **Astro no publica los archivos que editas directamente.** Cuando editas un archivo (por ejemplo `services.json`), Astro lo *compila* y genera una versión final del sitio en una carpeta llamada `dist/`. Eso compilado es lo que ven los clientes. Por eso **subir un archivo suelto al servidor no cambia nada**: hay que compilar primero.

La buena noticia es que **de eso se encarga un robot automático (GitHub Actions)**. Tú solo editas y guardas en GitHub; el robot compila y publica solo.

### La regla que lo resuelve todo

> 🟢 **GitHub es la única fuente de verdad.**
> Todo cambio entra por GitHub. Nadie edita Hostinger a mano nunca. El robot toma lo que hay en GitHub, lo compila y lo publica.

### Los dos lugares importantes

- **GitHub** — donde vive el código fuente. Es el archivo original y la fuente de verdad del proyecto. Todos los cambios pasan por aquí.
- **Hostinger** — donde vive el sitio publicado. Es lo que ven los clientes en f3constructionny.com. **Se actualiza solo, por el robot. No se toca a mano.**

### Los dos ambientes del sitio

- **Prueba (TEST):** `f3constructionny.com/test/` — aquí se ven los cambios antes de publicarlos. Es el borrador. Nadie afuera del equipo lo usa.
- **Producción (LIVE):** `f3constructionny.com` — el sitio real que ven los clientes.

> 🥇 **Regla de oro:** siempre se revisa en TEST antes de pasar a producción. Aplica a **los dos flujos**, sin excepción.

---

## ¿Dónde está cada archivo del sitio?

| Necesito editar… | Busco en… |
|---|---|
| Textos SEO de páginas de servicios (títulos, meta descriptions, párrafos, FAQs) | `src/data/services.json` |
| Páginas individuales (inicio, contacto, galería) | `src/pages/` |
| El menú de navegación o el footer | `src/components/` |
| Los colores o fuentes del sitio | `src/styles/` |
| Imágenes o logos | `public/media/` |

---

## Antes de empezar (solo para el Flujo A)

Para editar a mano necesitas, **una sola vez**:

1. Una cuenta de GitHub con acceso al repositorio.
2. **GitHub Desktop** instalado (es visual, no necesitas usar la terminal). *Alternativa para técnicos: Git por línea de comandos.*
3. **VS Code** instalado (para editar los archivos).
4. **Clonar el repositorio** (esto reemplaza al viejo "descargar el archivo"). En GitHub Desktop: *File → Clone repository →* pega `https://github.com/seoajcreatives-cmyk/F3` y elige una carpeta en tu computador.

> Clonar deja tu copia local conectada con GitHub. Así, cuando guardes un cambio, se sincroniza solo — sin volver a pegar nada a mano.

El **Flujo B** (con Antigravity) no requiere nada de esto.

---

## Cómo se publica un cambio (esto vale para los DOS flujos)

Sin importar cómo hiciste el cambio, la forma de publicarlo es siempre la misma:

1. **El cambio se sube a la rama `test`.**
2. El robot lo compila y publica en `f3constructionny.com/test/` en 1 a 3 minutos.
3. **Se revisa en `/test/`** que todo esté bien.
4. **El encargado pasa los cambios de `test` a `main`** (producción).
5. El robot vuelve a compilar y publica en el sitio real automáticamente.

Cualquiera del equipo puede subir a `test`. **Solo el encargado pasa a `main`.** Esto crea un punto de control antes de que algo llegue a los clientes.

---

## Flujo A — Cambios pequeños de SEO (editar a mano)

Úsalo para cambios puntuales en páginas de servicios: títulos, meta descriptions, H1, párrafos, FAQs. Casi todo ese contenido está en `src/data/services.json`.

**Paso 1 — Actualiza tu copia local.** Abre GitHub Desktop y trae los últimos cambios (*Fetch origin / Pull*). Asegúrate de estar parado en la rama **`test`**. Esto evita pisar el trabajo de otros.

**Paso 2 — Edita en VS Code.** Abre `src/data/services.json`. Busca el servicio con `Ctrl + F` usando el **slug** de la URL (ejemplo: `deck-builders-long-island-ny`). Edita los campos que necesites:
- `metaTitle` → el título que aparece en Google
- `metaDescription` → la descripción que aparece en Google
- `h1` → el título principal de la página
- `infoParagraphs` → los párrafos de la sección de información
- `faqs` → las preguntas frecuentes
- `canonical` → la URL canónica de la página

Guarda el archivo (`Ctrl + S`).

**Paso 3 — Haz el commit y push.** En GitHub Desktop verás el cambio detectado. Escribe un mensaje **descriptivo que incluya tu nombre** para saber quién hizo el cambio, y haz *Commit* → *Push*.

> **Formato del mensaje:** `descripción del cambio - Nombre de la persona`
> Ejemplo: `SEO: actualizar meta title y h1 de deck builders long island - María González`

Al trabajar desde tu copia clonada, el push sincroniza GitHub solo.

**Paso 4 — Revisa en TEST.** El robot publica en `f3constructionny.com/test/` en 1 a 3 minutos. Ábrelo y verifica (si no ves el cambio, recarga con `Ctrl + Shift + R`).

**Paso 5 — Pasa a producción.** Cuando esté correcto, el encargado lleva el cambio de `test` a `main` y se publica solo en el sitio real.

---

## Flujo B — Cambios grandes con Google Antigravity

Úsalo cuando el cambio afecte varias páginas, necesites agregar secciones nuevas, reorganizar contenido extenso, o no sepas en qué archivo está lo que quieres cambiar.

**Paso 1 — Conecta Antigravity con GitHub (una sola vez).** Antigravity y el repositorio son **dos cosas separadas**: Antigravity es el editor con IA; el repositorio es donde vive el código. La primera vez, autoriza a Antigravity para acceder a tu cuenta de GitHub.

**Paso 2 — Abre el repositorio y elige la rama.** Ya conectado, abre el repositorio **F3** dentro de Antigravity y selecciona la rama **`test`**.

**Paso 3 — Describe el cambio al agente.** Sé específico. No digas "mejora el sitio"; di algo como: *"En la página de servicios deck builders, agrega un nuevo FAQ con la pregunta X y la respuesta Y"*.

**Paso 4 — Revisa lo que hizo el agente.** Antes de confirmar, pídele que explique qué archivos tocó y revisa los cambios.

**Paso 5 — Haz push a `test`.** Confirma. Antigravity sube los cambios a GitHub en la rama `test` con un mensaje claro en el commit (incluye tu nombre igual que en el Flujo A).

**Paso 6 — Revisa en TEST.** Abre `f3constructionny.com/test/` en 1 a 3 minutos y verifica que todo se vea bien (`Ctrl + Shift + R` si hace falta).

**Paso 7 — Pasa a producción.** Cuando todo esté correcto, el encargado lleva el cambio de `test` a `main` y se publica en el sitio real.

---

## ¿Cómo saber si el deploy funcionó?

Ve a **github.com/seoajcreatives-cmyk/F3/actions** y mira el último proceso:

- 🟡 **Amarillo** → el robot está trabajando. Espera antes de hacer más cambios.
- 🟢 **Verde** → deploy exitoso. Revisa el sitio en el navegador.
- 🔴 **Rojo** → algo falló. No toques nada y avisa al encargado técnico con captura del error.

---

## ¿Qué hacer si algo sale mal?

**No veo mis cambios en el sitio** → Revisa en Actions que el deploy esté 🟢 verde y que hayan pasado 1 a 3 minutos. Luego recarga con `Ctrl + Shift + R`. Recuerda que los cambios primero aparecen en `/test/`; solo llegan a producción cuando el encargado los pasa a `main`.

**El deploy tiene ícono rojo** → No intentes arreglar nada solo. Toma captura del error en Actions y avisa al encargado técnico.

**Subí algo a la rama equivocada** → Avisa al encargado antes de hacer nada más; se puede reubicar sin perder el trabajo.

**Cambié algo por error en GitHub** → Se puede revertir el último commit. Entra al historial (*Code → Commits*), abre el commit y usa **"Revert"**. El robot publica automáticamente la versión anterior.

> ❌ **Nunca edites archivos directamente en Hostinger.** El próximo deploy los sobreescribe con lo que hay en GitHub. Si algo hay que corregir en el sitio, se corrige en GitHub.

---

## Reglas importantes

1. **GitHub es la única fuente de verdad.** Todo cambio entra por GitHub; nadie edita Hostinger a mano.
2. **Todo pasa primero por `test` y se revisa antes de `main`.** Sin excepciones.
3. **Cada commit lleva mensaje descriptivo + nombre de quien lo hizo.** Ejemplo: `SEO: nuevo FAQ en deck builders - Juan Pérez`.
4. **Solo el encargado pasa cambios a `main`** (producción).
5. **Nunca hagas push mientras el ícono de Actions está 🟡 amarillo** — espera a que termine.
6. **Nunca compartas las credenciales FTP ni los Secrets de GitHub** con nadie fuera del equipo técnico.

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

**Astro** — La tecnología con la que está construido el sitio. Genera páginas web muy rápidas. Compila los archivos fuente y produce el sitio final en la carpeta `dist/`.

**GitHub** — Donde se guarda todo el código del sitio. Funciona como un Drive para código: guarda el historial de todos los cambios. Es la fuente de verdad del proyecto.

**Repositorio** — El proyecto completo dentro de GitHub. En este caso, `seoajcreatives-cmyk/F3`.

**Clonar** — Descargar el proyecto completo a tu computador dejándolo conectado con GitHub, para que tus cambios se sincronicen solos. Se hace una sola vez.

**Rama (branch)** — Una versión paralela del código. `test` es la versión de prueba y `main` es la de producción.

**Commit** — Guardar un cambio en GitHub. Cada commit lleva un mensaje que describe qué se cambió y el nombre de quien lo hizo.

**Push** — Enviar tus commits desde tu computador a GitHub.

**Deploy** — El proceso automático que toma el código de GitHub, lo compila (genera el `dist`) y lo publica. Tarda de 1 a 3 minutos. Aplica a los dos flujos.

**`dist`** — La carpeta con el sitio ya compilado que Astro genera y que el robot publica en el servidor. **No se crea ni se sube a mano**; lo hace el robot.

**GitHub Actions** — El robot automático que compila y publica. Su estado se ve en la pestaña *Actions* del repositorio.

**Hostinger** — El servidor donde vive el sitio publicado. Se actualiza solo, por el deploy. **No se edita a mano.**

**Google Antigravity** — El editor con agente de IA que el equipo usa para cambios grandes o en múltiples páginas. Se conecta al repositorio de GitHub, pero es una herramienta aparte del repositorio.

**GitHub Desktop** — Programa visual para clonar, hacer commit y push sin usar la terminal.

**VS Code** — Visual Studio Code, el editor donde se editan los archivos localmente.

**Slug** — El identificador único de cada servicio en `services.json`. Es la parte final de la URL. En `/services/deck-builders-long-island-ny/`, el slug es `deck-builders-long-island-ny`. Sirve para encontrar rápido el servicio correcto.

**Caché** — Copias temporales que guarda el navegador. Si ves una versión vieja del sitio, recarga con `Ctrl + Shift + R`.
