# Verificación de la entrega

Fecha: 19 de octubre de 2026. Entorno de comprobación: Linux, PHP 8.3.35 con GD/WebP y PDO MySQL, Laravel 12.70.0, MySQL 8.4 en un contenedor aislado. No se accedió a ninguna base de datos existente del usuario.

## Resultados ejecutados
| Comprobación | Resultado |
|---|---|
| Suite completa en SQLite en memoria | 19 pruebas aprobadas, 193 aserciones, 3.04 s |
| Suite completa en MySQL 8.4 | 19 pruebas aprobadas, 193 aserciones, 7.66 s |
| `npm run build` | Correcto, Vite 7.3.7 |
| `composer validate --strict` | Correcto |
| `composer check-platform-reqs` | Correcto en PHP 8.3.35 |
| `php artisan migrate --seed --force` | Correcto sobre una base SQLite nueva de verificación |
| Migraciones sobre MySQL | Correctas durante la suite funcional (RefreshDatabase) |
| `php artisan view:cache` | Correcto |
| `php artisan route:cache` | Correcto |
| `php artisan config:cache` | Correcto; cachés temporales limpiadas al terminar |

Las 19 pruebas incluyen 18 pruebas funcionales y una prueba unitaria básica del esqueleto Laravel. Se comprueba explícitamente el driver pedido para confirmar que la ejecución MySQL no utiliza SQLite por accidente.

## Cobertura funcional comprobada
- Páginas públicas, cinco especialidades y estados vacíos sin registros comerciales ficticios.
- Enlaces de navegación del inicio y rutas de especialidades.
- Inicio y cierre de sesión; rechazo de credenciales inválidas y limitación de intentos.
- Rutas privadas, diferencias entre administrador/editor y bloqueo de cuentas inactivas.
- Renderizado de panel, listados, formularios y configuración.
- CRUD de servicios y protección por claves foráneas.
- CRUD de proyectos, borradores/publicación, imágenes optimizadas, galería y limpieza de archivos al eliminar.
- Recepción de cotizaciones con cliente, detalle, consentimiento, fotografías privadas e historial.
- Validaciones de campos, medidas, tipos de trabajo por especialidad y rechazo de archivos ejecutables.
- Descarga de adjuntos autorizada únicamente al administrador.
- Gestión manual de estados e importes y registro del responsable en el historial.
- Mensajes de contacto y fecha de lectura.
- Activación del catálogo, categorías/productos, registro de clientes y ventas manuales.
- Conservación byte por byte del logotipo cargado.
- Configuración/validación de WhatsApp y visibilidad condicional de enlaces.
- CSRF: una petición sin token se rechaza con 419 fuera del modo de pruebas.
- Límites de envío en formularios públicos.
- Contraseñas robustas y protección de la propia cuenta administrativa.

## Incidencias corregidas durante desarrollo
Se corrigió la configuración de timestamps de roles y estados. Se detectó y corrigió un salto de línea de formato en validaciones de teléfono y WhatsApp. La suite final se repitió y pasó en ambos motores. La dependencia inicial de distribuciones api.github.com se resolvió usando archivos oficiales codeload y un lock compatible con PHP 8.3.

## Límites de verificación y pendientes de publicación
No hubo una herramienta de navegador disponible. No se ejecutaron capturas ni pruebas visuales en 320/360/430/768/1024/1440 px, Lighthouse, auditoría WCAG completa o pruebas de interacción JavaScript en navegador. El código implementa breakpoints Mobile First, menú Alpine, foco visible, reducción de movimiento y tablas con desplazamiento interno; su comprobación visual sigue pendiente en un navegador real.

No se ejecutó en Windows/Laragon ni en Hostinger. Sus procedimientos están documentados y deben comprobarse en esos entornos, incluidos enlaces simbólicos, HTTPS, permisos, límites PHP y DNS. No se verificó un envío real por WhatsApp: solo se construyen enlaces; el visitante debe confirmar el mensaje. No se probó una cuenta administrativa real de la empresa ni se creó una contraseña predeterminada.

El logo se recibió visualmente, pero no estuvo disponible como archivo binario en el entorno. Carga el original desde Configuración. Completa datos reales de contacto, fotografías, historia, misión, visión y valores. Los textos legales deben revisarse antes de producción. El espacio del Libro de Reclamaciones es informativo/editable; no tiene recepción automatizada con constancia legal.

## Distribución
El ZIP incluye código fuente, migraciones, seeders, pruebas, documentación, imagen generada de demostración y recursos frontend compilados. Excluye `.env`, credenciales, bases de datos, logs, archivos privados, datos de prueba, Git, `vendor` y `node_modules`. Ejecuta `composer install` para instalar PHP; usa `npm ci` y `npm run build` si necesitas recompilar los recursos.
