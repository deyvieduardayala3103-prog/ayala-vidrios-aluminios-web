# Preparación para Hostinger

No se ha desplegado ni modificado una cuenta Hostinger. Antes de contratar o publicar, confirma que el plan permite PHP 8.3/8.4, extensiones de `INSTALACION-LARAGON.md`, Composer/SSH o carga de vendor, MySQL, enlaces simbólicos y un directorio público separado. MySQL debe aceptar claves foráneas y utf8mb4.

## Preparar una versión
En la raíz del proyecto, en tu máquina o CI:

```text
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
```

No hace falta Node.js en el hosting si subes `public/build`. Mantén los archivos lock. Ejecuta pruebas antes de preparar dependencias sin desarrollo. No uses `composer update` en producción.

## Ubicación segura
Coloca la aplicación fuera del directorio servido, por ejemplo `~/domains/tu-dominio/app-ayala`. Configura el DocumentRoot del dominio hacia `app-ayala/public`. Nunca sirvas `app/`, `vendor/`, `.env`, `database/` o `storage/app/private` desde la web. Si el plan obliga a usar `public_html`, consulta con soporte cómo apuntar el dominio a `public`; una alternativa es copiar **solo el contenido de public/** a `public_html` y ajustar las dos rutas de `index.php` a la aplicación fuera de la raíz pública. Configura también el enlace de almacenamiento en ese directorio público. No muevas toda la aplicación a `public_html`.

## Base de datos y entorno
Crea desde hPanel una base nueva y un usuario con privilegios limitados a esa base. Guarda `.env` fuera del directorio público y configura:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.pe
APP_LOCALE=es
DB_CONNECTION=mysql
DB_HOST=host_indicado_por_hostinger
DB_PORT=3306
DB_DATABASE=base_asignada
DB_USERNAME=usuario_asignado
DB_PASSWORD=clave_secreta
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
LOG_LEVEL=warning
```

Usa una clave `APP_KEY` nueva solo al inicializar la instalación. Conserva esa clave en actualizaciones y respaldos; no la incluyas en Git.

Desde la **raíz privada de la aplicación**, mediante SSH:

```text
php artisan key:generate
php artisan migrate --seed --force
php artisan storage:link
php artisan ayala:admin
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Los comandos de clave y administrador son para la instalación inicial. Antes de migraciones posteriores, realiza respaldo y revisa los cambios. Nunca ejecutes `migrate:fresh`, `db:wipe` ni un rollback sin evaluar la pérdida de datos. Los seeders preservan el contenido editado con `firstOrCreate`.

## Permisos y dominio
El usuario PHP necesita escritura en `storage/` y `bootstrap/cache/`; no concedas 777. Utiliza permisos apropiados al propietario del plan (habitualmente carpetas 755 y archivos 644; `.env` 600 cuando sea viable). El disco privado no debe ser accesible mediante enlaces públicos.

Configura DNS según hPanel y activa SSL/HTTPS. Redirige HTTP a HTTPS en la configuración del dominio; prueba que la URL canónica y los enlaces `Storage::url` usen HTTPS. Si hay proxy inverso, configura proxies confiables exclusivamente según la infraestructura real.

En Apache, las reglas de `public/.htaccess` deben estar activas. En Nginx, configura `try_files $uri $uri/ /index.php?$query_string` y ejecuta PHP solo a través de `index.php`; deniega ejecución de PHP en almacenamiento. El proyecto incluye `storage/app/public/.htaccess` defensivo para Apache. No permite SVG ni archivos ejecutables en cargas.

## Publicación y operación
Completa el teléfono/WhatsApp, correo, logo, fotografías autorizadas, dirección, horarios y contenido institucional desde Configuración. Verifica privacidad y obligaciones del Libro de Reclamaciones con información real. El espacio legal de esta versión es editable; no es un sistema de reclamaciones con constancia.

Ajusta `robots.txt` con `Sitemap: https://tu-dominio.pe/sitemap.xml`. Registra ese sitemap en las herramientas de buscadores. Comprueba formularios, permisos, enlaces WhatsApp y adjuntos con una solicitud controlada. Borra los registros de verificación solo siguiendo tu política de conservación.

Realiza copias de MySQL, `storage/app/public`, `storage/app/private` y `.env` cifradas y fuera de la web. Define retención, limita acceso y prueba una restauración en un entorno separado. No subas respaldos SQL o ZIP al directorio público. Supervisa errores en `storage/logs` sin exponerlos al visitante. Depura y rota logs, no registres datos personales innecesarios.

Para actualizar: respaldo, `php artisan down`, código y dependencias, `php artisan migrate --force`, cachés y `php artisan up`. En un plan compartido, coordina una ventana y verifica capacidad de restauración.
