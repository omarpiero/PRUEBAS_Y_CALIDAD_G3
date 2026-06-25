# Plataforma LMS - JM y JS Alimentos

Branch: `feat_LMS_v2.0`

Este repositorio contiene la evolucion del prototipo de JM y JS Alimentos hacia una plataforma LMS funcional para cursos de calidad, inocuidad alimentaria, BPM, HACCP e ISO. La aplicacion esta construida con Laravel 12, MySQL/MariaDB, Blade, React y Vite.

## Estado Actual

La rama `feat_LMS_v2.0` deja implementado el MVP LMS de punta a punta:

- Catalogo publico dinamico con filtros.
- Carrito seguro basado en `course_id`.
- Checkout Stripe real con ventas, items de venta, cupones, webhooks y matriculas.
- Panel admin para cursos, modulos, materiales, estudiantes, ventas, cupones, usuarios, roles, settings y auditoria.
- Aula del estudiante con acceso privado a materiales y progreso por leccion.
- Dashboard ejecutivo con KPIs y graficos.
- Chatbot IA con Gemini mediante `/api/chat`.
- Politica de privacidad, terminos y paquete documental para preparacion ante INDECOPI.
- Seguridad base: roles/permisos, rate limiting, sanitizacion de HTML y headers de seguridad.
- Factories, seed demo, manual admin y checklist de deploy.

## Stack

- Backend: Laravel 12, PHP 8.2+
- Base de datos: MySQL/MariaDB
- Frontend: Blade, React, Vite
- UI/Interaccion: Quill, SortableJS, Chart.js
- Pagos: Stripe Checkout con confirmacion por retorno seguro y webhook
- IA: Google Gemini API
- Testing: PHPUnit Feature/Unit tests

## Sprints Implementados

### Sprint 1 - Fundamentos LMS

- Modelos y migraciones base para roles, permisos, categorias, cursos, modulos, materiales, ventas, cupones, settings y auditoria.
- Middleware `role` y `permission`.
- Servicio de publicacion de cursos.
- Configuracion de subidas por tipo de archivo.

### Sprint 2 - Catalogo Publico Y Carrito

- Catalogo `/cursos` con cursos publicados desde BD.
- Detalle publico por slug.
- Filtros por nivel, categoria, precio y busqueda.
- Carrito que valida cursos publicados y resuelve precios en servidor.

### Sprint 3 - CRUD Admin De Cursos

- CRUD administrativo de cursos.
- Publicar/despublicar con validacion de contenido minimo.
- Duplicacion profunda de curso, modulos, materiales y archivos.
- Auditoria de acciones criticas.

### Sprint 4 - Constructor De Modulos Y Materiales

- CRUD de modulos y materiales.
- Reordenamiento con SortableJS.
- Soporte para videos YouTube/Vimeo/subidos, documentos, presentaciones, texto enriquecido y recursos descargables.
- Sanitizacion de contenido Quill.
- Limpieza de archivos reemplazados o eliminados.

### Sprint 5 - Aula Del Estudiante

- Aula privada por curso matriculado.
- Control de acceso por estado de matricula.
- Descarga/streaming seguro de archivos privados.
- Progreso por material.
- Cambio automatico a `completado` al llegar al 100%.

### Sprint 6 - Ventas, Cupones Y Gestion Escolar

- Checkout Stripe que crea `sales` y `sale_items` en estado pendiente, y activa `enrollments` al confirmar el pago.
- Cupones con vigencia, limite de uso y estado activo.
- Panel de estudiantes con suspension, reactivacion y reinicio de progreso.
- Panel de ventas con listado y detalle.

### Sprint 7 - Dashboard Ejecutivo

- KPIs de cursos, usuarios, estudiantes, instructores, ventas, ingresos, ticket promedio y finalizacion.
- Graficos de ventas e inscripciones mensuales.
- Top cursos vendidos.
- Cache de metricas administrativas.

### Sprint 8 - Roles, Settings, Auditoria Y Seguridad

- Roles reales: `admin`, `instructor`, `soporte`, `estudiante`.
- Rutas admin protegidas con permisos especificos.
- Admin legacy compatible con `is_admin`.
- Instructor limitado a cursos propios.
- Settings editables con helper `setting()` y cache.
- Auditoria filtrable.
- Rate limiting en login y chatbot.
- Sanitizacion basica del input del chatbot.
- Headers de seguridad.

### Sprint 9 - QA, Documentacion Y Release

- Factories LMS para categoria, curso, modulo, material, cupon, venta, item de venta y matricula.
- Seed demo idempotente.
- Manual admin.
- Checklist de deploy.
- Pruebas ampliadas de seguridad, roles, permisos, checkout, aula, dashboard y release readiness.
- Validacion de `route:cache`, `config:cache` y `view:cache`.

## Instalacion Local

### 1. Clonar y entrar a la rama

```bash
git clone https://github.com/ROGERCanchumanyaUC/pruebas-calidad-grupo-03.git
cd pruebas-calidad-grupo-03
git checkout feat_LMS_v2.0
```

### 2. Instalar dependencias

```bash
composer install
npm ci
```

### 3. Configurar entorno

```bash
cp .env.example .env
php artisan key:generate
```

Ejemplo local con XAMPP/MySQL:

```env
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=jm_js_alimentos
DB_USERNAME=root
DB_PASSWORD=
FILESYSTEM_DISK=public
```

Para habilitar el asistente IA:

```env
GEMINI_API_KEY=tu_clave_de_google_ai_studio
GEMINI_MODEL=gemini-2.5-flash
GEMINI_CA_BUNDLE=storage/certs/cacert.pem
GEMINI_VERIFY_SSL=true
```

Para habilitar Stripe Checkout:

```env
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
STRIPE_CURRENCY=pen
```

No subas `.env` al repositorio.

### 4. Migrar y cargar demo

```bash
php artisan migrate --seed
php artisan storage:link
```

Para reiniciar completamente una base local de desarrollo:

```bash
php artisan migrate:fresh --seed
```

No uses `migrate:fresh` en produccion.

### 5. Levantar la aplicacion

```bash
php artisan serve
npm run dev
```

Credenciales demo:

- Admin: `72682019@continental.edu.pe` / `password`
- Estudiante: `test@example.com` / `password`

## Comandos De Calidad

```bash
php artisan test
npm run build
composer audit
npm audit --audit-level=moderate
```

Comandos de cache validados para produccion:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Para limpiar cache local:

```bash
php artisan optimize:clear
```

## Documentacion

- Kanban profesional: `documentacion/KANBAN.md`
- Auditoria tecnica: `documentacion/AUDITORIA_LMS_2026_06_07.md`
- Manual admin: `documentacion/MANUAL_ADMIN_LMS.md`
- Checklist deploy: `documentacion/CHECKLIST_DEPLOY_LMS.md`
- Expediente INDECOPI: `documentacion/INDECOPI/README_EXPEDIENTE_INDECOPI.md`
- Documentacion general: `documentacion/DOCUMENTACION_GENERAL.md`
- Arquitectura: `documentacion/ARQUITECTURA.md`

## Notas De Seguridad

- Las claves Gemini, Stripe, correo y BD deben vivir en `.env`.
- Los materiales privados se sirven por controlador, no como archivos publicos directos.
- Las rutas admin usan permisos especificos y el rol `admin` conserva bypass total.
- Stripe Checkout procesa los datos de tarjeta fuera de la plataforma; este servidor no almacena numero de tarjeta, CVC ni expiracion.
- El warning local `Module "mysqli" is already loaded` corresponde a configuracion PHP/XAMPP duplicada y no bloquea la app.