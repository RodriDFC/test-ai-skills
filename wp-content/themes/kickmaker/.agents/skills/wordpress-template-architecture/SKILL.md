---
name: wordpress-template-architecture
description: Detecta y modifica correctamente la arquitectura de un theme clásico, de bloques o híbrido de WordPress. Úsala al crear páginas, archivos, cabecera, pie, Loop, template parts o modificar la jerarquía de plantillas.
---

# Arquitectura de plantillas WordPress

## 1. Identifica el tipo de theme

Antes de editar, inspecciona la estructura y evita asumirla:

- **Clásico:** suele utilizar plantillas PHP como `index.php`, `single.php`, `page.php`, `archive.php`, `header.php` y `footer.php`.
- **De bloques:** suele incluir `theme.json`, directorios `templates/` y `parts/` con archivos HTML de bloques.
- **Híbrido:** combina archivos y capacidades de ambos sistemas.

No conviertas un theme clásico a bloques ni uno de bloques a clásico salvo petición explícita y plan de migración.

## 2. Encuentra la plantilla adecuada

Para themes clásicos, aplica la jerarquía de plantillas de WordPress en vez de crear un archivo con un nombre arbitrario. Comprueba la documentación oficial si la jerarquía de un caso concreto no está clara.

- Una entrada individual suele usar `single.php` o una plantilla más específica.
- Una página suele usar `page.php` o una plantilla personalizada reconocida por WordPress.
- Los archivos de categoría/etiqueta/taxonomía suelen seguir la jerarquía de archivo correspondiente.
- `index.php` es la plantilla de respaldo en themes clásicos, no el lugar automático para toda funcionalidad.

Para themes de bloques, revisa `templates/`, `parts/` y `theme.json` y conserva el formato y las convenciones existentes.

## 3. Reutilización

- Reutiliza los template parts y componentes del proyecto cuando existan.
- Para themes clásicos, usa `get_header()`, `get_footer()`, `get_sidebar()` o `get_template_part()` según encaje.
- Evita duplicar bloques grandes de markup en varias plantillas si pueden compartir un componente seguro.
- No crees una abstracción excesiva para una única línea o elemento simple.
- Comprueba rutas y nombres exactos de los template parts.

Ejemplo de inclusión de una parte de plantilla en un theme clásico:

```php
get_template_part( 'template-parts/content', get_post_type() );
```

Antes de aplicar el ejemplo, verifica si el proyecto ya usa ese patrón y si existe el archivo requerido.

## 4. Loop y consultas

- Respeta el Loop principal cuando la página usa la consulta principal de WordPress.
- No crees una nueva `WP_Query` si la consulta principal ya proporciona los datos necesarios.
- Si una consulta secundaria es necesaria, limita campos/elementos cuando proceda y restaura el contexto con `wp_reset_postdata()` al terminar.
- Evita alterar globales como `$post` de manera que afecte a componentes posteriores.
- Para paginación, conserva los parámetros y el comportamiento que requiere WordPress y el diseño existente.

Ejemplo básico para una plantilla clásica:

```php
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        get_template_part( 'template-parts/content', get_post_type() );
    }
} else {
    get_template_part( 'template-parts/content', 'none' );
}
```

Solo uses la parte `content-none` si existe o si decides crearla como parte explícita del cambio.

## 5. Funciones y organización

- Lee `functions.php` antes de cambiarlo. Si el proyecto ya separa código en `inc/`, sigue ese diseño.
- No sustituyas el archivo entero para añadir una función aislada.
- Respeta prefijos y evita nombres globales genéricos.
- Distingue el código propio del theme del código que pertenece a plugins.
- No agregues a un theme funcionalidades que deban sobrevivir al cambio de theme (por ejemplo, lógica crítica de negocio) sin explicar el impacto y considerar un plugin.

## 6. CSS y estructura de contenido

- Sigue las clases y el sistema CSS del proyecto.
- Evita añadir wrappers o clases globales que alteren páginas que no forman parte de la tarea.
- No dupliques IDs HTML en componentes repetidos.
- Para bloques repetidos, usa una estructura semántica y clases consistentes.

## 7. Lista de comprobación

- ¿Se identificó el tipo de theme y la plantilla activa probable?
- ¿El archivo nuevo sigue la jerarquía de WordPress?
- ¿Se reutilizaron template parts existentes sin duplicación innecesaria?
- ¿El Loop, el contexto de la entrada y la paginación siguen funcionando?
- ¿Se evitaron cambios de arquitectura no solicitados?
- ¿Se revisaron portada, archivo, página individual y móvil cuando el cambio afecta a esas vistas?
