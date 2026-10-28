---
name: wordpress-theme-workflow
description: Analiza, planifica y modifica themes de WordPress con un flujo de trabajo seguro y explicaciones claras. Úsala al iniciar un proyecto, crear secciones, corregir bugs, refactorizar o cambiar archivos de un theme.
---

# Flujo de trabajo para themes WordPress

## Objetivo

Trabaja como desarrollador/a cuidadoso/a de themes WordPress. Prioriza entender el proyecto real antes de editarlo, conservar su arquitectura y explicar el trabajo en español sencillo cuando la persona usuaria lo necesite.

## Regla principal: inspeccionar antes de cambiar

1. Identifica la raíz del workspace y verifica que realmente sea el proyecto esperado.
2. Lee los archivos relevantes antes de recomendar cambios. Busca, según existan: `style.css`, `functions.php`, `index.php`, `header.php`, `footer.php`, archivos de plantillas, `template-parts/`, `inc/`, `assets/`, `theme.json`, `package.json`, `composer.json`, `phpcs.xml*`, `phpstan.neon*` y documentación del proyecto.
3. Determina si el theme es clásico, de bloques o híbrido. No supongas cuál utiliza.
4. Averigua cómo se cargan los estilos y scripts, qué librerías ya existen y si hay un proceso de compilación. Revisa versiones desde los archivos del proyecto cuando estén disponibles.
5. Identifica las convenciones de nombres, prefijos, estructura CSS y patrones existentes.
6. Si faltan datos, separa claramente hechos confirmados, inferencias y dudas. No inventes rutas, versiones, comandos ni APIs propias del proyecto.

## Plan antes de implementar

Para un cambio no trivial, presenta un plan corto que indique:
- causa o problema observado;
- archivos que probablemente deban cambiar y por qué;
- compatibilidad, seguridad, responsive y accesibilidad que deben respetarse;
- comprobaciones que se ejecutarán.

Para tareas pequeñas y claramente especificadas, evita ceremonias innecesarias, pero mantén las verificaciones.

## Implementación

- Haz el cambio mínimo que resuelva la necesidad sin rediseñar ni reescribir archivos no relacionados.
- Sigue los patrones locales si son seguros y coherentes.
- No reemplaces todo `functions.php`, una plantilla completa o una hoja de estilos solo para introducir un cambio localizado.
- No cambies el theme, plugins, base de datos, configuración del servidor ni dependencias sin que sea necesario para la tarea.
- No introduzcas un framework, plugin, paquete, CDN ni librería nueva si las capacidades existentes bastan.
- No borres código cuya función no comprendas. Busca sus referencias y explica la incertidumbre.
- No edites directamente archivos generados si el proyecto los produce desde SCSS, TypeScript u otra fuente. Localiza el origen real y confirma el flujo de compilación.
- No expongas contraseñas, tokens, datos personales ni credenciales halladas en archivos o logs.
- Antes de una operación destructiva o difícil de revertir, pide autorización explícita y confirma que exista una copia recuperable.

## Convenciones WordPress

- Usa APIs, hooks y funciones nativas de WordPress cuando correspondan.
- Respeta la diferencia entre lógica PHP, plantillas, recursos estáticos y funcionalidades que deberían vivir en un plugin en vez de un theme.
- En datos externos o dinámicos, aplica validación/saneamiento y escape de salida según contexto.
- Mantén compatibilidad con las versiones mínimas del proyecto. No uses sintaxis reciente de PHP o APIs recientes de WordPress sin verificar soporte.
- No ocultes errores con `@`, mensajes vacíos, supresión de excepciones o fallbacks silenciosos que dificulten la depuración.

## Verificación y entrega

Después del cambio:
1. Revisa el diff y confirma que solo se tocaron archivos necesarios.
2. Ejecuta las comprobaciones disponibles y apropiadas; por ejemplo, lint de PHP, pruebas existentes, análisis estático o build. No inventes que una prueba pasó si no se ejecutó.
3. Si no puedes abrir el sitio en navegador o no existe entorno de pruebas, dilo con claridad.
4. Considera regresiones en escritorio/móvil, teclado, consola del navegador, errores PHP y rutas afectadas.
5. Resume en español: qué cambió, qué archivos se tocaron, qué se comprobó y qué queda pendiente.

## Modo de explicación para principiantes

Cuando expliques algo técnico:
- empieza por el resultado práctico;
- define cualquier término técnico en una frase;
- da pasos concretos, uno por uno;
- diferencia instrucciones para hacer algo de cambios que ya se realizaron;
- si una acción necesita que la persona la ejecute manualmente, di dónde debe hacerlo.

## Cuándo combinar otras skills

- Código PHP, hooks, formularios o datos: `wordpress-php-security`.
- Plantillas, Loop o estructura: `wordpress-template-architecture`.
- Diseño responsive, Bootstrap o Swiper: `wordpress-bootstrap-swiper-ui`.
- CSS/JS, imágenes o carga de recursos: `wordpress-assets-performance`.
- HTML semántico, accesibilidad o contenido: `wordpress-accessibility-seo`.
- Errores, pruebas y revisión final: `wordpress-debugging-quality`.
