---
name: wordpress-accessibility-seo
description: Mejora la semántica HTML, accesibilidad y fundamentos técnicos de SEO en un theme WordPress. Úsala al crear secciones, menús, formularios, botones, encabezados, enlaces o metadatos visuales.
---

# Accesibilidad y fundamentos técnicos de SEO

## Estructura semántica

- Usa elementos HTML según su propósito: `header`, `nav`, `main`, `section`, `article`, `aside` y `footer` cuando correspondan.
- Evita sustituir elementos interactivos por `div` o `span` si un `button`, `a`, `input` u otro elemento nativo resuelve el caso.
- Una página debe tener una estructura de encabezados comprensible. No elijas `h1`–`h6` solo por su tamaño visual; usa CSS para el aspecto.
- Evita duplicar `id` en cards, secciones o contenido repetido.
- Los enlaces describen su destino y los botones describen su acción. No uses `href="#"` como sustituto de un botón funcional.

## Teclado y enfoque

- Todo comportamiento operable con mouse debe tener una forma equivalente de usarse con teclado.
- Conserva un indicador visible de foco (`:focus-visible` o el patrón del proyecto).
- No elimines el outline sin reemplazarlo por un indicador claro.
- Para menús, diálogos y sliders, revisa orden de tabulación, apertura/cierre con teclado y retorno del foco cuando aplique.
- No agregues roles ARIA redundantes cuando la semántica HTML nativa ya comunica la función.
- Si una interacción requiere ARIA, usa roles, estados y atributos coherentes con el comportamiento real; ARIA no hace accesible un componente por sí sola.

## Imágenes y medios

- Para imágenes informativas, redacta un texto alternativo conciso que comunique su propósito sin repetir innecesariamente el pie de imagen.
- Para imágenes decorativas, usa un alt vacío si eso es lo correcto.
- No añadas palabras clave artificiales al `alt`.
- Asegura que los controles multimedia tengan etiquetas entendibles y que los videos con información hablada consideren subtítulos apropiados.

## Formularios

- Cada campo debe tener etiqueta visible o una alternativa accesible apropiada.
- No uses placeholders como única etiqueta.
- Asocia mensajes de error con el campo y no comuniques estados únicamente mediante color.
- Usa los tipos de entrada adecuados y conserva la navegación con teclado.
- No elimines protección, validación o comprobaciones del lado del servidor al mejorar la interfaz.

## Contraste y movimiento

- Comprueba contraste, legibilidad, zoom y reflujo en pantallas pequeñas.
- No uses solo color para mostrar errores, estado activo o información importante.
- Respeta `prefers-reduced-motion` para animaciones no esenciales y evita parpadeos o movimientos innecesarios.
- No fijes alturas o tamaños de texto que corten el contenido al aumentar el zoom.

## SEO técnico básico

- Usa títulos de página y encabezados coherentes con el contenido y la plantilla.
- Conserva funciones de WordPress y plugins SEO ya existentes; no generes títulos o metadescripciones duplicados.
- Usa enlaces rastreables y texto de enlace descriptivo.
- Evita ocultar contenido relevante solo para manipular buscadores.
- No crees canonical, robots, schema, Open Graph ni metadatos duplicados si el sitio ya delega esa responsabilidad a WordPress o a un plugin.
- No prometas posiciones en buscadores ni resultados SEO derivados de una modificación aislada.

## Lista de comprobación

- ¿La estructura se entiende sin CSS?
- ¿Se puede recorrer con teclado y se ve el foco?
- ¿Los botones, enlaces, imágenes y campos tienen nombres o etiquetas adecuados?
- ¿El contenido funciona con zoom y en móvil?
- ¿Se conservaron los metadatos y responsabilidades SEO existentes?
