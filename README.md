# 📋 MANUAL DE TRABAJO — Sitio Web F3 Construction

> **¿Para quién es este manual?**
> Para cualquier persona del equipo que necesite editar el sitio. No necesitas saber programar ni tener experiencia técnica. Si puedes editar un documento de Word, puedes seguir este manual.

---

## ¿Cómo funciona este sitio?

El sitio de F3 Construction **no es un WordPress**. Es un sitio moderno construido con una tecnología llamada Astro, que genera páginas web muy rápidas y con puntajes perfectos en Google.

Piénsalo así: el sitio tiene dos lugares importantes.

**GitHub** es donde vive el código fuente — es como el archivo original de un documento. Aquí el equipo hace todos los cambios.

**Hostinger** es donde vive el sitio publicado — es lo que ven los clientes cuando entran a f3constructionny.com. Hostinger recibe los cambios automáticamente desde GitHub. **Nunca se edita Hostinger directamente.**

El proceso completo es automático:
Editas en GitHub o Antigravity → el robot construye el sitio → lo publica en Hostinger → los clientes ven los cambios.

---

## Los dos ambientes del sitio

El sitio tiene dos versiones que corren al mismo tiempo:

**Prueba (TEST):** f3constructionny.com/test/
Aquí se ven los cambios antes de publicarlos. Es como un borrador. Nadie afuera del equipo lo usa.

**Producción (LIVE):** f3constructionny.com
Este es el sitio real que ven los clientes. Solo se publica aquí cuando todo está revisado y aprobado.

**Regla de oro: nunca hagas cambios directamente en producción sin haber revisado en TEST primero.**

---

## Las dos ramas de trabajo en GitHub

En GitHub el proyecto tiene dos ramas (versiones del código):

**Rama `test`** → todo lo que se sube aquí aparece automáticamente en f3constructionny.com/test/

**Rama `main`** → todo lo que se sube aquí aparece automáticamente en f3constructionny.com (el sitio real)

Siempre se trabaja primero en `test`, se revisa, y cuando está bien se pasa a `main`.

---

## ¿Dónde está cada archivo del sitio?

Estos son los archivos que el equipo puede necesitar editar:

- `src/pages/` → las páginas del sitio. Ejemplo: la página de inicio, la página de contacto, la página de servicios.
- `src/components/` → partes que se repiten en todas las páginas, como el menú de navegación y el pie de página (footer).
- `src/layouts/` → la estructura base de las páginas (no se toca normalmente).
- `src/styles/` → los colores, fuentes y estilos visuales del sitio.
- `src/data/` → textos, listas de servicios y datos que se usan en el sitio.
- `public/` → imágenes, logos y archivos estáticos.

---

## ¿Cuándo usar Google Antigravity y cuándo usar GitHub directamente?

Hay dos formas de hacer cambios. Elegir la correcta depende del tamaño del cambio:

**Usa Google Antigravity cuando:**
- El cambio afecta varias páginas al mismo tiempo
- Necesitas reorganizar secciones o agregar contenido nuevo extenso
- Quieres ayuda del agente de IA para redactar o estructurar
- No estás seguro de en qué archivo está lo que quieres cambiar

Ejemplos para Antigravity:
- Agregar una nueva sección de servicios en toda la web
- Cambiar el diseño del header en todas las páginas
- Redactar y publicar una nueva página completa
- Actualizar los textos de presentación de la empresa en múltiples secciones

**Usa GitHub directamente cuando:**
- El cambio es pequeño y está en un solo archivo
- Sabes exactamente qué texto quieres cambiar y dónde está
- Es una corrección rápida como un número de teléfono, una dirección o un precio

Ejemplos para GitHub directo:
- Cambiar el número de teléfono en el footer
- Corregir un error de ortografía en la página de contacto
- Actualizar el horario de atención
- Cambiar una palabra en el título de una sección

---

## Flujo A — Cambios grandes con Google Antigravity

Sigue estos pasos en orden cada vez que vayas a trabajar con Antigravity.

**Paso 1 — Clonar (conectar) el repositorio en Antigravity.**
Esto solo se hace la primera vez o si el agente no tiene el proyecto cargado. En Antigravity, busca la opción de conectar o clonar repositorio de GitHub e ingresa la dirección del proyecto: `https://github.com/seoajcreatives-cmyk/F3`. Selecciona la rama `test` para trabajar.

> Si el proyecto ya está cargado en Antigravity, puedes saltar este paso. Verifica que estás en la rama `test` antes de continuar.

**Paso 2 — Describir el cambio al agente.**
Dile al agente exactamente qué quieres cambiar y en qué página o sección. Sé específico. No digas "mejora el sitio" — di algo como: "En la página de servicios, agrega una nueva tarjeta llamada Remodelaciones Comerciales con este texto: ..."

**Paso 3 — Revisar los cambios antes de guardar.**
Antes de confirmar, revisa lo que el agente modificó. Puedes pedirle que te muestre una vista previa o que explique qué archivos tocó.

**Paso 4 — Hacer push a la rama `test`.**
Cuando estés conforme, confirma los cambios. Antigravity los sube automáticamente a GitHub en la rama `test`. En el mensaje del commit escribe brevemente qué hiciste. Ejemplo: `Agregar tarjeta de Remodelaciones Comerciales en página de servicios`.

**Paso 5 — Esperar el deploy automático.**
GitHub detecta el push y en 1 a 3 minutos construye y publica el sitio en /test/. Puedes ver el progreso en la pestaña Actions de GitHub (círculo amarillo = en progreso, círculo verde = listo).

**Paso 6 — Revisar en el ambiente de prueba.**
Abre f3constructionny.com/test/ en el navegador. Si no ves los cambios, presiona Ctrl + Shift + R (Windows) o Cmd + Shift + R (Mac) para recargar sin caché.

**Paso 7 — Pasar a producción.**
Si todo se ve bien, el encargado del equipo hace un nuevo push a la rama `main` en GitHub. Eso activa otro deploy automático y publica los cambios en el sitio real: f3constructionny.com.

---

## Flujo B — Cambios pequeños directo en GitHub

No necesitas Antigravity para cambios rápidos. Puedes editar directamente desde el navegador.

**Paso 1 — Ir al repositorio en GitHub.**
Entra a github.com/seoajcreatives-cmyk/F3. Asegúrate de estar en la rama `test` (se ve en el menú desplegable a la izquierda, debajo del nombre del repositorio).

**Paso 2 — Navegar hasta el archivo.**
En el panel de archivos de la izquierda, abre la carpeta `src/pages/` y haz clic en el archivo de la página que quieres editar.

Ejemplo: si quieres cambiar el número de teléfono del footer, busca en `src/components/` el archivo del footer.

**Paso 3 — Abrir el editor.**
Haz clic en el ícono del lápiz (✏️) en la esquina superior derecha del archivo. Esto abre el editor directamente en el navegador.

**Paso 4 — Hacer el cambio.**
Busca el texto que quieres modificar (puedes usar Ctrl + F para buscar dentro del archivo) y edítalo directamente.

Ejemplo: si el teléfono dice `(631) 555-0000` y quieres cambiarlo a `(631) 123-4567`, simplemente borra el número viejo y escribe el nuevo.

**Paso 5 — Guardar con Commit.**
Haz clic en el botón verde **"Commit changes"** (arriba a la derecha). Aparece un cuadro con dos opciones:

- **"Commit directly to the `test` branch"** → guarda el cambio en la rama de prueba (recomendado para la mayoría de los cambios)
- **"Commit directly to the `main` branch"** → guarda directo en producción (solo si el cambio es urgente y estás completamente seguro)

Escribe un mensaje breve describiendo qué cambiaste. Ejemplo: `Actualizar número de teléfono en footer`. Luego haz clic en **"Commit changes"**.

**Paso 6 — Verificar en /test/.**
En 1 a 3 minutos el cambio aparece en f3constructionny.com/test/. Revisa que todo se vea bien.

**Paso 7 — Pasar a producción.**
Si está correcto, avisa al encargado para que haga el push a `main` y el cambio se publique en el sitio real.

---

## ¿Cómo saber si el deploy funcionó?

Ve a github.com/seoajcreatives-cmyk/F3/actions y mira el último proceso:

- 🟡 **Amarillo** → el robot está trabajando. No hagas más cambios todavía.
- 🟢 **Verde** → el deploy fue exitoso. Revisa el sitio en el navegador.
- 🔴 **Rojo** → algo falló. No toques nada y avisa al encargado técnico con una captura de pantalla del error.

---

## ¿Qué hacer si algo sale mal?

**No veo mis cambios en /test/** → Espera 3 minutos y recarga con Ctrl + Shift + R. Si sigue sin aparecer, revisa Actions para ver si el deploy está en rojo.

**El deploy tiene ícono rojo** → No intentes arreglar nada solo. Toma captura del error en Actions y avisa al encargado técnico.

**Cambié algo por error y el sitio se ve mal** → La solución es revertir el último commit. En GitHub, entra al historial de commits (pestaña Code → Commits), haz clic en el commit anterior y usa el botón "Revert". Eso deshace el cambio automáticamente sin tocar nada en Hostinger.

**El sitio de producción no carga** → Avisa inmediatamente al encargado técnico. No edites nada en Hostinger.

---

## Cosas que NUNCA se deben hacer

- **Nunca subir archivos directamente a Hostinger** — todo va por GitHub. Si subes algo a mano, el robot lo puede sobreescribir o generar conflictos.
- **Nunca borrar o editar estos archivos en Hostinger:** `.htaccess`, `.ftp-deploy-sync-state.json`, `.ftp-deploy-sync-state-v2.json`, `DO_NOT_UPLOAD_HERE`
- **Nunca hacer un push mientras el ícono en Actions está amarillo** — espera a que termine.
- **Nunca editar directamente en `main`** sin haber revisado primero en `test`, a menos que sea una corrección urgente muy pequeña.
- **Nunca compartir las credenciales FTP o los Secrets de GitHub** con nadie fuera del equipo técnico.

---

## Links importantes

| Lo que necesito | El link |
|---|---|
| Ver el sitio real | https://f3constructionny.com |
| Ver el ambiente de prueba | https://f3constructionny.com/test/ |
| Ver los deploys en curso | https://github.com/seoajcreatives-cmyk/F3/actions |
| Repositorio en GitHub | https://github.com/seoajcreatives-cmyk/F3 |

---

## Dónde está cada cosa en GitHub

| Necesito editar... | Busco en... |
|---|---|
| Una página completa del sitio | `src/pages/` |
| El menú de navegación o el footer | `src/components/` |
| Los colores o fuentes del sitio | `src/styles/` |
| Una imagen o logo | `public/media/` |
| Textos de servicios u otros datos | `src/data/` |

---

## Glosario — Palabras que debes conocer

**Astro** — La tecnología con la que está construido el sitio. Genera páginas web muy rápidas. No necesitas saber usarla directamente.

**GitHub** — Es el lugar donde se guarda todo el código del sitio. Funciona como un Google Drive para código: guarda el historial de todos los cambios que se han hecho.

**Repositorio** — El proyecto completo dentro de GitHub. En este caso es `seoajcreatives-cmyk/F3`.

**Rama (branch)** — Una versión paralela del código. `test` es la versión de prueba y `main` es la versión de producción. Los cambios siempre van primero a `test`.

**Commit** — Guardar un cambio en GitHub. Cada commit tiene un mensaje que describe qué se cambió. Es como el historial de cambios de un documento.

**Push** — Subir los commits al repositorio en GitHub. Cuando haces push, el robot de deploy se activa automáticamente.

**Deploy** — El proceso automático que toma el código de GitHub, lo construye y lo publica en Hostinger. Tarda entre 1 y 3 minutos.

**GitHub Actions** — El robot automático que se encarga del deploy. Se puede ver su estado en la pestaña Actions del repositorio.

**Hostinger** — El servidor web donde vive el sitio publicado. El equipo no edita aquí directamente — solo el robot puede escribir en Hostinger.

**FTP** — El protocolo que usa el robot para subir los archivos a Hostinger. Es invisible para el equipo, solo lo configura el área técnica.

**Google Antigravity** — El editor con agente de IA que el equipo usa para hacer cambios grandes o en múltiples páginas a la vez. Se conecta directamente al repositorio de GitHub.

**Clonar el repositorio** — Conectar Antigravity al proyecto de GitHub para poder editar los archivos. Se hace una sola vez o cuando el agente no tiene el proyecto cargado.

**Caché** — Copias temporales que guarda el navegador para cargar las páginas más rápido. A veces el navegador muestra una versión vieja del sitio porque tiene el caché guardado. Se limpia con Ctrl + Shift + R.

**Revertir (Revert)** — Deshacer un commit. Si un cambio rompió algo, se puede revertir desde GitHub y el robot publica automáticamente la versión anterior.
