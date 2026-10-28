---
name: wordpress-bootstrap-swiper-ui
description: Construye y corrige componentes visuales responsive dentro de themes WordPress, respetando Bootstrap, Swiper y el sistema de clases que ya exista. Úsala para secciones, sliders, carruseles, navegación y diseño móvil.
---

# Interfaz responsive para themes WordPress

## Regla de compatibilidad

Primero inspecciona el markup, CSS, JavaScript y versiones instaladas. Reutiliza Bootstrap, Swiper u otras dependencias **solo si realmente forman parte del proyecto**. No añadas otra copia de una librería ni cambies su versión sin necesidad y autorización.

## Analiza antes de cambiar

1. Localiza el HTML/PHP que renderiza el componente.
2. Localiza los estilos y el JavaScript que lo inicializan.
3. Averigua si los assets se compilan o se sirven directamente.
4. Revisa cómo se comportan componentes similares en el theme.
5. Identifica el contenedor, el sistema de grid, los breakpoints, los espacios y la nomenclatura CSS existentes.

## Bootstrap

- Usa las clases y versión que ya usa el sitio; no asumas que está disponible la clase de una versión distinta.
- Evita anidar `.container` dentro de `.container` salvo que el diseño lo exija.
- Conserva una jerarquía coherente de `.container`, `.row` y columnas cuando el proyecto sigue la grid de Bootstrap.
- Evita utilizar la grid de Bootstrap junto a otro sistema de grid para el mismo componente si genera reglas conflictivas.
- No añadas estilos globales para resolver un problema local. Prefiere una clase con alcance al componente.

## Swiper y sliders

- Confirma la versión de Swiper y usa la API correspondiente a esa versión.
- Conserva la inicialización y dependencias existentes si son compatibles.
- Inicializa cada instancia una sola vez y limita los selectores al componente correcto para que dos sliders no compartan accidentalmente las mismas flechas o paginación.
- Ajusta `slidesPerView`, `spaceBetween`, breakpoints y navegación según las medidas y los requisitos reales del diseño. No inventes valores que el usuario no ha especificado sin señalarlos como propuesta.
- Si hay menos slides que el espacio disponible, define explícitamente si el control debe ocultarse, deshabilitarse o permanecer visible. No confundas un botón deshabilitado con uno oculto.
- Cuando sea compatible con la versión, revisa `watchOverflow` y el estado de bloqueo (`isLocked`) para detectar si hay desplazamiento posible; añade o quita una clase local para ocultar controles solo si ese es el comportamiento requerido.
- Considera contenido dinámico, imágenes que tardan en cargar y cambios de tamaño de ventana. Evita depender de una única medición tomada antes de que el slider calcule sus dimensiones.
- No uses índices fijos para ocultar flechas si lo que realmente determina la necesidad de navegación es el número de slides visibles por breakpoint.

Ejemplo de comportamiento conceptual, que debe adaptarse a la versión instalada:

```js
function updateSliderControls(swiper, root) {
  const controls = root.querySelectorAll('.swiper-button-prev, .swiper-button-next');
  controls.forEach((control) => {
    control.hidden = Boolean(swiper.isLocked);
  });
}
```

Este ejemplo presupone que `root` corresponde a una única instancia y que `swiper.isLocked` está disponible en la versión instalada. Si no se cumple, consulta la API de esa versión y no copies el ejemplo literalmente.

## Responsive y calidad visual

- Diseña primero para pantallas pequeñas y mejora progresivamente para pantallas mayores.
- Prueba al menos móvil, tablet y escritorio; comprueba anchos estrechos y contenido largo.
- Evita alturas fijas en tarjetas con texto variable, salvo que el diseño lo requiera y exista una solución de desbordamiento.
- Evita `overflow: hidden` global para esconder errores de layout.
- Comprueba saltos de diseño, scroll horizontal accidental, imágenes estiradas y controles que se superponen.
- Conserva estados hover, focus-visible, active y disabled.
- Asegura uso por teclado y un nombre accesible para flechas y botones sin texto.
- Respeta `prefers-reduced-motion` si añades animaciones no esenciales.

## Proceso de implementación

1. Explica qué elemento genera el problema y qué archivo lo controla.
2. Implementa el cambio en el origen correcto (plantilla, fuente SCSS/CSS o JS).
3. Mantén el CSS acotado a esa sección.
4. Revisa colisiones entre clases del theme y las de la librería.
5. Prueba el número mínimo, normal y máximo de elementos del componente.
6. Verifica todos los breakpoints solicitados y que no haya errores en consola.

## Entrega

Describe en español qué archivos cambiaste y cómo probar el comportamiento en móvil y escritorio. Si no pudiste abrir un navegador o probar interacciones reales, deja ese límite claro.
