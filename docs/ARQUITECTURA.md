# Arquitectura — AYALA VIDRIOS Y ALUMINIOS

Monolito Laravel con PHP 8.3+, MySQL 8, Blade, Tailwind CSS, Alpine.js y Vite. Sin carrito ni pagos. El despliegue de PHP se prepara para Hostinger, no para el runtime de Sites (Cloudflare Workers).

## Carpetas
- app/Http/Controllers: sitio público, autenticación y administración.
- app/Http/Requests: validación del servidor.
- app/Models: entidades y relaciones Eloquent.
- app/Services: recepción de solicitudes y almacenamiento seguro.
- app/Policies: autorizaciones.
- database/migrations y seeders: esquema y catálogos esenciales.
- resources/views: layouts, componentes, público y administración.
- resources/css y js: Tailwind y Alpine, compilados con Vite.
- routes: rutas públicas y protegidas.
- tests/Feature: flujos y permisos.
- docs: instalación, operación y despliegue.

## Datos
users pertenece a roles. specialties tiene services y projects; projects tiene project_images. product_categories tiene products. customers tiene quotes y sales. quotes tiene quote_items, quote_attachments y quote_status_histories; cada historial referencia quote_statuses y al usuario responsable cuando corresponde. contact_messages conserva consultas. settings contiene configuración comercial, legal, contacto y activación de módulos.

Importes DECIMAL, medidas DECIMAL, claves foráneas e índices. Adjuntos de clientes en disco privado, servidos únicamente tras autorización. Fotografías comerciales en disco público. No se crean clientes, ventas, solicitudes ni proyectos ficticios.

## Decisiones
Logo recibido visualmente en el chat: falta acceso al archivo binario en el entorno; permitir carga del original en administración. Contacto, dirección, horarios, redes, historia, misión, visión y textos legales permanecen editables sin información inventada. Catálogo y ventas desactivados inicialmente. Solicitudes no calculan precios: un administrador registra el importe. WhatsApp abre un enlace solamente si hay número configurado, después de guardar la solicitud.

## Esquema principal
| Tabla | Datos principales | Integridad y relaciones |
|---|---|---|
| roles | id, name | Nombre único |
| users | name, email, password, role_id, active | Correo único, rol FK; hash de contraseña |
| specialties | name, slug, description, image, active | Slug único; cinco especialidades iniciales |
| services | specialty_id, name, description, active | FK a especialidad |
| product_categories | name | Categorías editables |
| products | product_category_id, name, description, image, price, unit, available | FK categoría; precio DECIMAL opcional |
| projects | specialty_id, name, slug, description, completed_at, featured, published | FK especialidad, slug único, fecha opcional |
| project_images | project_id, path, alt | FK proyecto, borrado en cascada |
| customers | name, phone, email, address | Teléfono indexado, sin registro público de cuenta |
| quote_statuses | name | Catálogo de seis estados |
| quotes | reference, customer_id, specialty_id, quote_status_id, description, zone, amount, observations, consented_at | Referencia UUID única; FKs restrictivas; índice especialidad/fecha |
| quote_items | quote_id, work_type, width, height, unit, quantity | FK cotización, medidas DECIMAL, cantidad entera |
| quote_attachments | quote_id, path, original_name | FK cotización; rutas privadas |
| quote_status_histories | quote_id, quote_status_id, user_id, note, amount | Auditoría con usuario opcional e importe histórico |
| contact_messages | name, phone, email, message, consented_at, read_at | Mensajes independientes de cuentas |
| sales | customer_id, quote_id, amount, sold_at, notes | Cliente requerido, cotización opcional, importe DECIMAL |
| settings | key, value | Clave única, configuración controlada por lista permitida |

Laravel agrega tablas de infraestructura: migraciones, sesiones, caché y cola. Se utiliza cola síncrona inicialmente, sin proceso trabajador obligatorio.

```text
ayala-vidrios-aluminios-web/
├── app/
│   ├── Contracts/MaterialCalculator.php
│   ├── Http/
│   │   ├── Controllers/{PublicController,AuthController,AdminController}.php
│   │   ├── Middleware/{BackofficeAccess,SecurityHeaders}.php
│   │   └── Requests/{QuoteRequest,ContactRequest,AdminRecordRequest}.php
│   ├── Models/
│   ├── Policies/BusinessPolicy.php
│   ├── Providers/AppServiceProvider.php
│   └── Services/{AdminModules,QuoteIntake,PublicImages}.php
├── bootstrap/
├── config/
├── database/{migrations,seeders,factories}/
├── docs/
├── lang/es/
├── public/{build,images}/
├── resources/
│   ├── css/app.css
│   ├── js/app.js
│   └── views/{layouts,components,public,auth,admin,errors}/
├── routes/{web,console}.php
├── storage/app/{public,private}/
├── tests/{Feature,Unit}/
├── .env.example
├── artisan
├── composer.json / composer.lock
└── package.json / package-lock.json
```

`AdminModules` contiene un registro cerrado de modelos, campos, validaciones y permisos para CRUD sencillos. No construye clases a partir de texto del usuario. La gestión de cotizaciones, el historial y la carga de archivos tienen métodos y servicios propios. Los datos públicos se escapan mediante Blade; los textos editables no aceptan HTML arbitrario.
