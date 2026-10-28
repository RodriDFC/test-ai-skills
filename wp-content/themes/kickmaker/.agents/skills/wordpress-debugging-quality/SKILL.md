---
name: wordpress-debugging-quality
description: Diagnostica problemas de themes WordPress de forma reproducible, ejecuta comprobaciones disponibles y revisa regresiones antes de entregar cambios. Úsala para errores PHP, bugs visuales, consola, rendimiento o revisión de código.
---

# Diagnóstico, pruebas y revisión de calidad

## Diagnosticar antes de corregir

1. Obtén el resultado esperado y el comportamiento observado; si ya están descritos, no vuelvas a preguntar lo mismo.
2. Busca el componente, plantilla, hook o asset responsable.
3. Reproduce el problema si el entorno está disponible.
4. Revisa errores y logs pertinentes ocultando secretos y datos personales.
5. Formula una hipótesis verificable y busca la causa raíz, no solo un parche visual.
6. Cambia una cantidad pequeña de código para poder atribuir el resultado.

No afirmes que has reproducido un error si solo has leído el código. Separa hechos de hipótesis.

## Herramientas

Utiliza únicamente las herramientas presentes o instaladas en el proyecto. Primero revisa README, `package.json`, `composer.json`, scripts, configuración y documentación.

Ejemplos de comprobaciones posibles, no garantizadas:

```bash
php -l ruta/al/archivo.php
```

```bash
composer test
npm run test
npm run build
```

Ejecuta esos comandos solo si el entorno dispone de los programas y el proyecto define los comandos relevantes. No crees scripts de prueba ficticios, no instales paquetes sin autorización y no declares un resultado exitoso si el comando falló o no se ejecutó.

Si hay PHP_CodeSniffer/WordPress Coding Standards, PHPUnit, pruebas de integración o herramientas de análisis ya configuradas, prefiérelas. No instales todo ese ecosistema solo para una edición pequeña.

## Entorno seguro

- Prefiere desarrollo o staging antes que producción.
- No realices operaciones destructivas de base de datos ni cambios en producción por iniciativa propia.
- No desactives seguridad, plugins o validaciones para “hacer pasar” una prueba sin autorización y explicación del riesgo.
- Nunca muestres credenciales encontradas en logs, archivos de configuración o variables de entorno.
- No actives `WP_DEBUG_DISPLAY` para visitantes de un sitio de producción. Si sugieres activar diagnóstico, explica el contexto y cómo evitar exponer detalles al público.

## Revisión después del cambio

Comprueba lo que aplique:

- Sintaxis PHP y errores fatales.
- Warnings, notices y logs pertinentes.
- Errores de JavaScript y solicitudes de red fallidas.
- Plantillas afectadas en escritorio y móvil.
- Estados sin contenido, contenido largo, usuarios no autenticados y permisos insuficientes si la funcionalidad los involucra.
- Formularios, enlaces, navegación, paginación y controles interactivos.
- Accesibilidad por teclado y foco.
- Conflictos con otras secciones, librerías o páginas que compartan clases/hooks.
- Diff final para detectar cambios accidentales, secretos, archivos generados o cambios de formato masivos.

## Si no puedes probar en navegador

Indica exactamente qué revisaste en código y qué hace falta probar manualmente. No uses frases como “funciona perfectamente” sin evidencia.

## Formato de entrega

Al terminar, informa brevemente:
1. **Causa:** qué originaba el problema o qué supuesto se validó.
2. **Cambios:** archivos afectados y cambio realizado.
3. **Pruebas:** comandos o comprobaciones ejecutados y resultados reales.
4. **Pendiente:** cualquier prueba manual o incertidumbre restante.
