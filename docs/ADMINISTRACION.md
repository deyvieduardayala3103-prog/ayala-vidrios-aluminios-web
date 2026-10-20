# Uso del panel

Entra en `/ingresar` con el usuario creado por `php artisan ayala:admin`. No hay registro público de usuarios.

- **Administrador:** gestiona contenido, clientes, solicitudes, mensajes, configuración, ventas opcionales y usuarios.
- **Editor:** gestiona especialidades, servicios, categorías, productos, proyectos y galerías. No accede a datos de clientes, adjuntos privados, configuración, cotizaciones ni usuarios.

El dashboard obtiene cifras de la base de datos; una instalación nueva muestra cero. Los productos y las ventas se activan de forma independiente en Configuración. Registrar una venta no procesa pagos y no cambia automáticamente el estado de una cotización.

## Primera configuración
Carga el archivo original del logo (no se redimensiona ni recodifica), una fotografía principal autorizada y datos reales de contacto. WhatsApp necesita código de país y número completo sin `+`, espacios ni guiones. Ejemplo de formato: solo dígitos; usa el número real de la empresa. Hasta configurarlo no aparece el enlace flotante. No se envía ningún mensaje automáticamente.

Completa historia, misión, visión, valores y textos legales. Nunca cargues testimonios o proyectos ficticios como trabajos reales. La imagen generada provisional está marcada como demostración.

## Servicios y proyectos
Las cinco especialidades se cargan con sus servicios esenciales. La cuarta es **Baldosas**. Se pueden editar descripciones, imágenes y servicios. Mantén slugs estables para preservar enlaces. Si una especialidad tiene servicios, proyectos o solicitudes asociados, la base bloquea su eliminación.

Crea proyectos como borradores, agrega varias fotografías reales y una descripción accesible para cada lote. Para textos alternativos distintos, sube cada imagen en un lote separado. Revisa el proyecto y marca Publicado cuando esté confirmado. Destacado permite mostrarlo en Inicio. Las galerías públicas permiten ampliar imágenes con diálogo y cierre por teclado. Puedes retirar imágenes individuales desde la edición.

## Cotizaciones
La solicitud pública crea un cliente, una cotización, un detalle y la primera entrada del historial en una transacción. No exige registro. Cada solicitud conserva un cliente independiente para preservar los datos proporcionados; no se fusionan personas por coincidencia de teléfono. Revisa duplicados cuando corresponda.

Filtra por cliente, especialidad, estado y fechas. Abre la solicitud para consultar medidas, descripción, contacto y adjuntos privados. Registra un importe **manualmente**, observaciones y nota del cambio. Se añade al historial el estado, importe, usuario y momento de actualización, incluso si el estado no cambió. Las observaciones son internas. No se genera presupuesto definitivo, factura ni correo automático.

Estados disponibles: Pendiente, En revisión, Cotizada, Aprobada, Rechazada, Finalizada. La aplicación permite cambios manuales entre ellos para corregir y retomar solicitudes; no aplica un flujo comercial no aprobado. `app/Contracts/MaterialCalculator.php` reserva el contrato para futuras calculadoras de cantidades sin inventar fórmulas ni precios.

## Contacto, usuarios y ventas
Los mensajes se almacenan para revisión; marca la fecha de lectura desde su ficha. Las cuentas nuevas exigen contraseña robusta. No puedes desactivar tu propia cuenta ni cambiar tu propio rol. Conserva siempre un administrador activo. Para revocar acceso, desactiva al usuario; el middleware comprueba su estado en cada petición. Evita eliminar usuarios que necesites identificar en el historial; al eliminarlos, la referencia del historial se conserva con responsable vacío.

Al activar ventas se permite registrar cliente, cotización opcional, importe, fecha y observaciones. La cotización debe pertenecer al mismo cliente. Es un registro manual interno y no sustituye un sistema contable.

## Datos y seguridad
No hay botones de eliminación para cotizaciones, mensajes o ventas; conserva su trazabilidad y acuerda una política de retención. Los borrados de contenido piden confirmación y las claves foráneas protegen dependencias. Para ejercer derechos sobre datos o aplicar retención, un administrador técnico deberá ejecutar un procedimiento controlado con respaldo y auditoría; esta versión no incluye exportación o eliminación masiva de datos personales.
