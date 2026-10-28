---
name: wordpress-php-security
description: Escribe y revisa PHP para themes WordPress aplicando APIs nativas, compatibilidad, validación, saneamiento, escape de salida y controles de acceso. Úsala al editar PHP, formularios, hooks, opciones, metadatos o consultas SQL.
---

# PHP seguro para WordPress

## Antes de escribir código

- Comprueba la versión mínima de PHP y WordPress declarada por el proyecto o por el entorno; no la adivines.
- Sigue la indentación, el prefijo y las convenciones de nombres que ya use el theme.
- Usa APIs de WordPress antes de crear utilidades propias.
- Coloca la lógica en el lugar apropiado: no conviertas el theme en un plugin general ni metas funcionalidades de negocio en plantillas si ya existe una capa para ello.

## Validación, saneamiento y escape

Trata los datos de formularios, URL, usuarios, metadatos, base de datos y APIs externas como datos no confiables.

- **Validar:** comprueba que el valor cumple el formato esperado y rechaza lo que no cumpla.
- **Sanear:** transforma el valor a una forma segura para el uso previsto.
- **Escapar:** prepara el valor al imprimirlo según el contexto final. Escapa lo más tarde posible.

Elige la función según el contexto:

```php
<h2><?php echo esc_html( $title ); ?></h2>
<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
<div class="<?php echo esc_attr( $class_name ); ?>">
    <?php echo esc_html( $description ); ?>
</div>
```

Funciones frecuentes, no intercambiables:
- Texto: `sanitize_text_field()` y `esc_html()`.
- Atributos HTML: `esc_attr()`.
- URL: `esc_url()`.
- Claves/slug: `sanitize_key()` o `sanitize_title()` según el caso.
- Enteros positivos: `absint()` cuando encaje con la regla de negocio.
- HTML limitado intencionalmente: `wp_kses()` o una variante adecuada.
- Email: `sanitize_email()` y validación con una función apropiada.

No apliques `esc_html()` a un valor que deba conservar HTML permitido; tampoco imprimas contenido dinámico crudo porque venga de la base de datos.

## Nonces, capacidades y formularios

Un nonce ayuda a mitigar solicitudes falsificadas, pero **no sustituye la comprobación de permisos**.

Para operaciones que cambian datos:
1. comprueba método y presencia de parámetros esperados;
2. verifica nonce con la API adecuada;
3. comprueba permisos con `current_user_can()` según la acción;
4. deshaz el slashing de entradas de WordPress cuando corresponda;
5. valida y sanea los valores;
6. ejecuta la operación y maneja el error;
7. responde o redirige de forma segura.

Ejemplo orientativo para comprobar una acción administrativa:

```php
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( esc_html__( 'No tienes permiso para realizar esta acción.', 'text-domain' ) );
}

check_admin_referer( 'my_theme_save_settings', 'my_theme_nonce' );
```

Adapta la capacidad, la acción del nonce y los nombres de campos al flujo real. No uses `manage_options` automáticamente si la acción corresponde a un permiso más específico.

## Base de datos

- Evita SQL manual si existe una API de WordPress apropiada.
- Si necesitas `$wpdb`, prepara cada valor variable con `$wpdb->prepare()` y usa placeholders compatibles con la versión soportada.
- No concatenes entradas del usuario dentro de una consulta SQL.
- Escapa también la salida del resultado; una consulta preparada no hace que el contenido sea seguro para HTML.
- No cambies esquema, borres datos ni ejecutes consultas destructivas sin autorización explícita y respaldo verificable.

Ejemplo conceptual:

```php
global $wpdb;
$table_name = $wpdb->prefix . 'example_items';
$item_id    = absint( $item_id );

$result = $wpdb->get_row(
    $wpdb->prepare( "SELECT id, title FROM {$table_name} WHERE id = %d", $item_id )
);
```

En este ejemplo el nombre de tabla debe proceder de una fuente controlada por el código, no de entrada del usuario. La sintaxis SQL y la API usada deben adecuarse al caso real.

## Hooks y callbacks

- Registra acciones/filtros en el punto apropiado, normalmente durante la carga del theme o en una función de inicialización existente.
- Evita registrar la misma acción repetidamente o ejecutar trabajo pesado en cada petición sin necesidad.
- Usa callbacks con nombres únicos coherentes con el prefijo del theme, siguiendo el patrón existente.
- No sobrescribas funciones de WordPress ni uses nombres genéricos que puedan colisionar.
- Antes de retirar un hook, busca otros componentes que dependan de él.

## Carga de recursos

- Usa `wp_enqueue_scripts` y las APIs de encolado; evita insertar CSS/JS global mediante etiquetas directas en `header.php` o `footer.php`.
- Declara dependencias correctas y versiones reales. No asumas que una librería está disponible por estar instalada en otro sitio.
- No uses `wp_localize_script()` como almacén genérico de datos salvo que sea el mecanismo apropiado; para datos generales considera las APIs disponibles y el patrón del proyecto.

## Compatibilidad y calidad

- No uses funciones PHP recientes sin comprobar la versión mínima admitida.
- Evita variables indefinidas, índices sin comprobar, conversiones implícitas dudosas y llamadas a funciones de plugins sin verificar que sean opcionales.
- Usa traducciones de WordPress para texto visible cuando el theme ya está internacionalizado o cuando corresponda (`__()`, `esc_html__()`, `_x()`, entre otras).
- No expongas errores detallados al público en producción ni registres información secreta.
- No ocultes problemas con `@`.

## Lista de revisión

Antes de terminar, confirma:
- ¿Toda salida dinámica está escapada según contexto?
- ¿Cada dato entrante se valida y/o sanea de acuerdo con su uso?
- ¿Las operaciones sensibles tienen nonce cuando corresponde y una comprobación de capacidad?
- ¿Se evitan consultas SQL concatenadas?
- ¿La solución respeta las versiones soportadas?
- ¿Los errores se manejan sin revelar secretos ni silenciar fallos?
