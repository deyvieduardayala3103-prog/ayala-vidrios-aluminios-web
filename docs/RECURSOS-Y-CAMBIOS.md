# Recursos y cambios

## Identidad y fotografías
El logo fue recibido como imagen visible en el chat. El entorno no proporcionó una ruta ni un archivo descargable de ese adjunto. No se redibujó ni se alteró. El panel permite subir el original (JPG, PNG o WebP) y guarda sus bytes sin recodificación. Mientras no se cargue, se muestra una marca tipográfica provisional.

`public/images/architecture-demo.webp` es una imagen arquitectónica generada para este proyecto el 19 de octubre de 2026. Se identifica como demostración y no se incorpora como un proyecto de la empresa. Se optimizó a aproximadamente 157 KB. Reemplázala mediante Configuración con una fotografía autorizada. Los proyectos reales comienzan vacíos.

Los iconos son SVG sencillos incluidos en el componente Blade, sin librería externa. La tipografía utiliza fuentes del sistema, sin solicitudes a proveedores externos.

## Modificaciones al repositorio inicial
La revisión encontró solamente README y .gitignore. Se conservó el repositorio Git y se guardaron copias previas en `work/` (fuera de la entrega). Se añadieron la estructura oficial Laravel, esquema de negocio, modelos y relaciones, controles de acceso, formularios, sitio público, panel, recursos compilables, pruebas y guías. README y .gitignore se actualizaron para el proyecto implementado.

Laravel está bloqueado en Composer; Tailwind, Alpine y Vite están bloqueados en NPM. Por la política de red de este entorno, las URLs de distribución de Composer apuntan a los ZIP oficiales de `codeload.github.com` para los mismos commits de los paquetes. No se desactivó TLS. En instalaciones normales `composer install` usa ese lock. Una futura actualización de Composer puede volver a producir URLs de api.github.com; la red de desarrollo debe permitir los dominios requeridos.

El proyecto conserva la estructura estándar y no necesita Docker en Laragon ni en Hostinger. Los contenedores usados aquí fueron exclusivamente herramientas de validación, sin conectar bases de datos existentes.

## Alcance entregado y ampliaciones
Se implementan las solicitudes y la gestión manual de importes; no se generan PDFs de presupuesto, facturas, correos ni precios calculados. No hay carrito ni pagos. Se reserva un contrato PHP para calculadoras futuras, sin reglas inventadas. El Libro de Reclamaciones es un espacio editable: no hay recepción legal automatizada con constancia. No se ha publicado el proyecto en un servidor externo.
