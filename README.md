# AYALA VIDRIOS Y ALUMINIOS

Aplicación empresarial Laravel 12, PHP 8.3+, MySQL, Blade, Tailwind CSS 4, Alpine.js y Vite. Proyecto local preparado para Laragon y un futuro despliegue PHP en Hostinger.

## Documentación
- [Arquitectura y entidades](docs/ARQUITECTURA.md)
- [Instalación en Laragon](docs/INSTALACION-LARAGON.md)
- [Administración](docs/ADMINISTRACION.md)
- [Preparación para Hostinger](docs/HOSTINGER.md)
- [Verificación y límites](docs/VERIFICACION.md)

## Funcionalidades
Sitio público responsive en español, cinco especialidades, servicios editables, portafolio con filtros y galería ampliable, solicitudes persistentes de cotización con fotos privadas y consentimiento, historial de gestión, mensajes de contacto, catálogo opcional, clientes, registro opcional de ventas, usuarios por rol y configuración empresarial. Panel protegido sin credenciales predeterminadas ni registros comerciales ficticios.

Los datos empresariales desconocidos quedan vacíos y editables. No se calculan precios ni se envían mensajes de WhatsApp automáticamente. La identidad gráfica original se carga desde Configuración y se conserva sin modificar. El entorno no expuso el archivo binario del logo adjunto al chat; por eso la instalación nueva utiliza texto de marca provisional. La fotografía principal real se puede cargar sin cambios de código. La imagen generada inicial está identificada como demostración.

## Inicio rápido
Desde la raíz, en una instalación nueva y después de configurar MySQL en `.env`:

```text
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm ci
npm run build
php artisan ayala:admin
php artisan serve
```

Antes de `key:generate`, copia `.env.example` a `.env` **solo si no existe**. Consulta la guía Laragon para crear la base. No ejecutes migraciones sobre bases existentes sin inspección y respaldo.

No se desplegó a Hostinger ni a Sites: el runtime de Sites no ejecuta Laravel/PHP. Las dependencias y los recursos compilados se verifican en el entorno de desarrollo indicado en el informe. Los textos legales requieren revisión de la empresa antes de producción; el espacio del Libro de Reclamaciones no es un sistema de reclamaciones habilitado.
