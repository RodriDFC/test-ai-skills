---
name: wordpress-assets-performance
description: Gestiona correctamente CSS, JavaScript, fuentes e imágenes en themes WordPress, con carga mediante APIs nativas y atención al rendimiento. Úsala al añadir recursos, corregir estilos, imágenes lentas o problemas de carga.
---

# Recursos y rendimiento del theme

## Descubre el flujo de assets

Antes de editar, confirma si el theme sirve CSS/JS directamente o usa Sass, Vite, Webpack, Gulp, npm u otro proceso. Revisa `package.json`, lockfiles, carpetas `src/`, `assets/`, `dist/` y documentación antes de asumir comandos.

- Si hay fuentes y archivos compilados, modifica la fuente correcta y sigue el build existente.
- No edites `dist/` o assets generados como única fuente cuando serán sobrescritos.
- No ejecutes instalaciones ni actualizaciones de dependencias innecesarias.

## Encolado WordPress

- Carga estilos y scripts con las funciones de encolado de WordPress en el hook apropiado, normalmente `wp_enqueue_scripts` en el frontend.
- Usa identificadores únicos consistentes con el prefijo del theme.
- Declara las dependencias reales, el lugar de carga apropiado y una versión coherente.
- Utiliza rutas del theme con APIs como `get_theme_file_uri()` o `get_stylesheet_directory_uri()` según si el recurso debe venir del theme padre o del tema activo/hijo.
- No dupliques un recurso que WordPress o el theme ya estén cargando.
- Antes de retirar una dependencia, comprueba qué código la utiliza.
- No apliques `defer`/`async` indiscriminadamente: puedes romper dependencias, orden de ejecución o scripts que esperan el DOM.

Ejemplo de referencia, no para copiar sin revisar el proyecto:

```php
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_assets' );

function mytheme_enqueue_assets() {
    $relative_path = '/assets/css/main.css';
    $file_path     = get_theme_file_path( $relative_path );
    $version       = file_exists( $file_path ) ? (string) filemtime( $file_path ) : wp_get_theme()->get( 'Version' );

    wp_enqueue_style(
        'mytheme-main',
        get_theme_file_uri( $relative_path ),
        array(),
        $version
    );
}
```

Usa el prefijo real del proyecto, el archivo real y un fallback apropiado. Comprueba si el theme ya registra versiones, cache busting o build.

## Imágenes

- Aprovecha el sistema de imágenes de WordPress y sus tamaños derivados cuando sea adecuado.
- No reemplaces imágenes gestionadas por la biblioteca de medios con URLs codificadas si el contenido debe ser editable.
- Evita servir archivos enormes para miniaturas y evita forzar proporciones que deformen la imagen.
- Usa `srcset`/`sizes` y atributos compatibles con el contexto cuando se rendericen imágenes responsive.
- Añade texto alternativo según la función de la imagen; decorativas pueden requerir `alt=""`.
- No apliques carga diferida automáticamente a la imagen principal visible al cargar la página. Prioriza el contenido principal y usa lazy loading en imágenes fuera de pantalla cuando corresponda.
- No precargues muchas imágenes ni subas agresivamente la prioridad de elementos secundarios.

## CSS

- Localiza la regla que causa el problema antes de añadir una nueva.
- Usa selectores específicos del componente, con especificidad razonable.
- Evita `!important` salvo necesidad demostrada y documentada.
- Evita estilos inline para resolver problemas persistentes si el theme ya usa hojas de estilos.
- No dupliques resets, fuentes o librerías CSS.
- Comprueba que el CSS compilado y el CSS fuente no diverjan.

## JavaScript

- No añadas scripts globales para un comportamiento exclusivo de una página sin comprobar el coste y la estructura existente.
- Evita listeners duplicados y selectores globales que afecten a otros componentes.
- Maneja elementos que pueden no existir en todas las plantillas.
- Usa delegación de eventos cuando sea apropiado para contenido dinámico, sin convertirla en una abstracción innecesaria.
- No insertes datos no confiables en `innerHTML`. Prefiere crear nodos y asignar `textContent` o sanitizar según el caso.

## Verificación

- Ejecuta el build solo si el proyecto define ese comando y comprueba su resultado.
- Verifica consola del navegador, solicitudes duplicadas, errores 404 y dependencias que no cargan.
- Compara el antes y después si hay herramientas disponibles; no prometas una mejora de rendimiento sin mediciones.
- Reporta los límites de la comprobación si no tienes acceso al navegador o al entorno real.
