# 📋 MANUAL DE TRABAJO — F3 Construction Website

> Proyecto Astro + GitHub + Hostinger
> Versión 2.0 — Para uso interno del equipo

---

## ¿Cómo funciona este proyecto?

Este sitio no es un WordPress. Es un sitio estático construido con **Astro**, un framework que genera HTML/CSS/JS ultra-optimizado para obtener puntajes verdes en Google PageSpeed. El código fuente vive en GitHub y se publica en Hostinger de forma automática a través de un robot.

**La regla más importante:** El código fuente vive en GitHub. Hostinger solo recibe el resultado final compilado. Nunca se edita directamente en Hostinger.

---

## Las dos capas del proyecto

**En GitHub (lo que edita el equipo):**

- `src/pages/` → las páginas del sitio (archivos `.astro`)
- `src/components/` → partes reutilizables: header, footer, secciones
- `src/layouts/` → plantillas base de las páginas
- `src/styles/` → estilos CSS globales
- `src/data/` → textos, servicios y datos del sitio
- `public/` → imágenes, fonts y archivos estáticos

**En Hostinger (solo el resultado compilado — no se toca):**

- `public_html/` → el sitio en producción
- `public_html/test/` → el entorno de prueba

---

## Las dos ramas de trabajo

El repositorio tiene dos ramas que cumplen funciones distintas:

La **rama `test`** es para trabajo en progreso. Todo lo que va aquí se despliega automáticamente en `f3constructionny.com/test/` para que el equipo lo pueda revisar antes de publicar.

La **rama `main`** es producción. Lo que va aquí se publica en `f3constructionny.com/` directamente.

> **Nota:** El comportamiento exacto de cada rama está siendo ajustado por el equipo técnico. Ver la sección de pendientes al final.

---

## Flujo de trabajo: cambios con Google Antigravity

Este es el flujo para cambios medianos o cuando se usa el agente de IA.

**Paso 1 — Editar en Antigravity.** El agente edita los archivos dentro de `src/` o `public/`. Indicar siempre con claridad qué página o sección se quiere cambiar y qué debe decir exactamente.

**Paso 2 — Push desde Antigravity.** Al confirmar los cambios, Antigravity sube el código a GitHub. En el mensaje de commit escribir qué se hizo, por ejemplo: `Actualizar número de teléfono en header`.

**Paso 3 — El robot trabaja solo.** GitHub Actions detecta el push y automáticamente compila el proyecto con Astro y sube el resultado a Hostinger. Esto tarda entre 1 y 3 minutos. Se puede ver el progreso en GitHub → pestaña **Actions**.

**Paso 4 — Verificar en /test/.** Abrir `f3constructionny.com/test/` y revisar que todo se vea bien.

**Paso 5 — Aprobar para producción.** Si está bien, se hace push a `main` para que el robot despliegue a producción.

---

## Flujo de trabajo: cambios pequeños desde GitHub directamente

Para correcciones rápidas de texto, datos o pequeños ajustes sin necesidad de usar Antigravity.

**Paso 1 — Ir al archivo en GitHub.** Navegar a [github.com/seoajcreatives-cmyk/F3](https://github.com/seoajcreatives-cmyk/F3), luego a `src/pages/` y abrir el archivo de la página que se quiere editar.

**Paso 2 — Clic en el lápiz (editar).** Botón con ícono de lápiz en la esquina superior derecha del archivo.

**Paso 3 — Hacer el cambio.** Editar el texto directamente en el editor del navegador.

**Paso 4 — Commit.** Clic en **"Commit changes"**. Aparece un diálogo con dos opciones:
- `Commit directly to the main branch` → va a producción
- `Create a new branch` → escribir `test` para enviar al entorno de prueba

Elegir según si el cambio es seguro o necesita revisión previa.

**Paso 5 — El robot despliega solo.** Igual que con Antigravity, GitHub Actions compila y publica automáticamente.

---

## ¿Qué NO hay que hacer nunca?

**Nunca subir archivos manualmente a `public_html/` en Hostinger.** El robot es el único que debe escribir ahí. Subir archivos manualmente puede generar conflictos con el sistema de sincronización y causar que el próximo deploy sobreescriba o borre lo que se subió a mano.

**Nunca editar o borrar estos archivos en Hostinger:**
- `.ftp-deploy-sync-state-v2.json` — registro interno del robot
- `.ftp-deploy-sync-state.json` — versión anterior del mismo registro
- `.htaccess` — configuración del servidor
- `DO_NOT_UPLOAD_HERE` — archivo centinela del sistema

**Nunca hacer push mientras hay un deploy en curso.** Esperar a que el círculo en GitHub Actions pase de amarillo a verde antes de enviar más cambios.

---

## ¿Cómo saber si el deploy funcionó?

Ir a [github.com/seoajcreatives-cmyk/F3/actions](https://github.com/seoajcreatives-cmyk/F3/actions) y revisar el último workflow run:

- 🟡 Círculo amarillo → en progreso, esperar
- 🟢 Círculo verde → éxito, el sitio está actualizado
- 🔴 Círculo rojo → error, revisar el log para saber qué paso falló

---

## ¿Qué hacer si algo sale mal?

Si el sitio en producción tiene un error visible y no se puede esperar el proceso normal, la única acción de emergencia válida es **revertir el último commit** en GitHub haciendo clic en el commit anterior en el historial y usando "Revert". Eso activa el robot automáticamente y vuelve al estado anterior. No se debe editar nada en Hostinger directamente.

---

## Dónde está cada cosa

| Necesito... | Lo encuentro en... |
|---|---|
| Editar una página | GitHub → `src/pages/` |
| Editar el header o footer | GitHub → `src/components/` |
| Agregar una imagen | GitHub → `public/media/` |
| Ver si el deploy está corriendo | GitHub → pestaña **Actions** |
| Ver cambios en prueba | `f3constructionny.com/test/` |
| Ver el sitio en producción | `f3constructionny.com/` |
| Acceder al historial de cambios | GitHub → pestaña **Commits** |
