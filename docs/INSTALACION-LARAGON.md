# Instalación en Windows, Visual Studio Code y Laragon

## Requisitos
PHP 8.3 o 8.4; extensiones ctype, curl, dom, fileinfo, filter, gd (JPEG/PNG/WebP), hash, mbstring, openssl, pcre, PDO, pdo_mysql, session, tokenizer, xml, xmlwriter y zip. Composer 2. Node.js 22.12+ (o 24 LTS) y NPM. MySQL 8.0+ o MariaDB compatible. Apache con mod_rewrite o Nginx.

## Preparar una instalación nueva
1. Extrae el proyecto en `C:\laragon\www\ayala-vidrios`. No extraigas sobre otro proyecto. Abre esa carpeta en VS Code.
2. Inicia Apache y MySQL desde Laragon. Abre la terminal de Laragon y entra en la raíz del proyecto (donde está `artisan`):

```powershell
cd C:\laragon\www\ayala-vidrios
php -v
composer install
Copy-Item .env.example .env
```

El comando de copia debe ejecutarse solo si `.env` no existe. No sobreescribas una configuración existente.

3. En HeidiSQL, phpMyAdmin o la consola MySQL de Laragon, crea una base nueva con un nombre disponible:

```sql
CREATE DATABASE ayala_vidrios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

No se requiere importar un SQL manual: las migraciones crean el esquema. Si la base ya existe, inspecciónala y acuerda su uso antes de ejecutar migraciones.

4. Edita `.env` en VS Code. Indica el usuario y contraseña de tu MySQL local; no compartas el archivo:

```dotenv
APP_NAME="AYALA VIDRIOS Y ALUMINIOS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_LOCALE=es
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ayala_vidrios
DB_USERNAME=tu_usuario_local
DB_PASSWORD=tu_clave_local
SESSION_DRIVER=database
CACHE_STORE=database
SESSION_SECURE_COOKIE=false
```

5. Desde `C:\laragon\www\ayala-vidrios`, ejecuta en orden:

```powershell
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm ci
npm run build
php artisan ayala:admin
php artisan serve --host=127.0.0.1 --port=8000
```

`key:generate` crea la clave de una instalación nueva. No regenerarla en una instalación con datos cifrados o sesiones en uso. `migrate --seed` crea tablas y los catálogos esenciales: no crea proyectos, ventas ni solicitudes de ejemplo. `storage:link` enlaza solo el almacenamiento público. En Windows puede requerir modo desarrollador o terminal con permisos de crear enlaces. Nunca enlaces `storage/app/private`.

`ayala:admin` solicita nombre, correo y contraseña oculta (12 caracteres, mayúsculas, minúsculas, número y símbolo). No existe una contraseña predeterminada. Crea otro administrador desde el panel para continuidad operativa. En caso de perder el acceso, un operador con acceso al servidor puede ejecutar nuevamente `ayala:admin` con un correo nuevo.

6. Abre `http://127.0.0.1:8000` y `http://127.0.0.1:8000/ingresar`. Si usas dominio automático de Laragon, configura el DocumentRoot hacia `C:/laragon/www/ayala-vidrios/public` y ajusta `APP_URL`. No publiques la raíz del proyecto.

## Desarrollo
Desde la misma raíz, en otra terminal:

```powershell
npm run dev
```

Mantén `php artisan serve` y Vite activos mientras desarrollas. Para comprobar el resultado compilado, detén Vite y ejecuta `npm run build`. No copies `public/hot` a producción.

## Pruebas
Desde la raíz:

```powershell
php artisan test
```

Por defecto PHPUnit usa SQLite en memoria para evitar tocar la base local. Requiere `pdo_sqlite`. Los datos de las pruebas se destruyen al finalizar; nunca apuntes `RefreshDatabase` a una base con datos que quieras conservar. Para MySQL usa exclusivamente una base de pruebas nueva y dedicada, con `DB_CONNECTION=mysql`, `DB_DATABASE=ayala_testing` y configuración PHPUnit adaptada. Consulta `VERIFICACION.md` para los resultados realmente ejecutados en la entrega.

## Archivos y límites
Configura PHP con `upload_max_filesize=6M`, `post_max_size=60M`, `max_file_uploads=20` y memoria suficiente (256 MB o más). El servidor rechaza imágenes mayores de 5 MB, extensiones no admitidas y galerías que excedan el límite de carga. Las fotografías comerciales se convierten a WebP hasta 1920 px. El logotipo se guarda sin recodificar ni modificar. Las fotografías de cotizaciones permanecen privadas y solo un administrador puede descargarlas.
