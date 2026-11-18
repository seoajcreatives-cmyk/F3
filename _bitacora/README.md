# 📋 Bitácora del Proyecto

Este directorio almacena el historial técnico de cambios y contexto del proyecto para el equipo y los asistentes de Inteligencia Artificial (Antigravity, Claude, Cursor, Copilot, etc.).

## 🔒 Seguridad e Invisibilidad
* Este directorio está **completamente bloqueado** para visitantes web y motores de búsqueda (Googlebot, Bingbot, etc.) mediante el archivo [`.htaccess`](.htaccess).
* Aunque se despliegue en servidores Apache, LiteSpeed, Hostinger, GoDaddy o Netfirms, cualquier petición HTTP externa responde `404 Not Found`.

## 📂 Archivos
1. **`CONTEXTO.md`**: Resumen ejecutivo del proyecto, stack técnico, reglas críticas y estado actual. Es lo primero que lee cualquier desarrollador o IA antes de tocar código.
2. **`bitacora.jsonl`**: Histórico estructurado de cambios en formato *JSON Lines* (1 línea JSON por tarea). Optimizado para lectura ultra rápida y bajo consumo de tokens.

## ✍️ Formato para Registrar Tareas en `bitacora.jsonl`
Cada nueva entrada debe agregarse al final del archivo como una línea JSON válida:

```json
{"timestamp":"YYYY-MM-DDTHH:mm:ss-05:00","author":"Nombre del Responsable","task":"Título conciso de la tarea","details":"Descripción técnica detallada","files_affected":["archivo1.ext","archivo2.ext"],"notes_for_next":"Advertencias o contexto para el siguiente cambio"}
```

## 📖 Comandos Rápidos de Consulta (PowerShell / Bash)
* **Leer los últimos 5 cambios:**
  ```powershell
  Get-Content _bitacora/bitacora.jsonl -Tail 5
  ```
* **Filtrar cambios por autor:**
  ```powershell
  Get-Content _bitacora/bitacora.jsonl | ConvertFrom-Json | Where-Object { $_.author -match "Antonia" }
  ```
