# Auditoria LMS - JM y JS Alimentos

Fecha: 2026-06-07.
Objetivo: auditar el estado real del proyecto y preparar una ruta profesional para evolucionar el prototipo hacia una plataforma LMS administrable.

---

## 1. Resumen Ejecutivo

El proyecto es una aplicacion Laravel con frontend Blade y un componente React para chat. El trabajo previo ya agrego una base LMS importante: cursos, modulos, materiales, ventas, cupones, roles, permisos, logs de auditoria y settings. La base de datos esta migrada en MySQL local y contiene 9 cursos y 34 modulos iniciales.

La brecha principal esta en la capa funcional: las rutas, controladores y vistas todavia no consumen esa base LMS. El catalogo publico de cursos sigue hardcodeado y el panel admin no tiene CRUD de cursos, modulos, materiales, ventas, cupones ni settings.

Conclusion: el proyecto esta en una fase de "modelo de datos preparado", pero no en una fase de "plataforma LMS utilizable". El Kanban fue reestructurado para reflejar esa realidad.

---

## 2. Evidencia Del Entorno

| Elemento | Resultado |
| --- | --- |
| Framework | Laravel `12.61.1` |
| PHP | `8.5.4` |
| Composer | `2.9.5` |
| Base de datos | MySQL, `127.0.0.1:3307`, BD `jm_js_alimentos` |
| Storage publico | Enlazado: `public/storage` |
| Build frontend | OK |
| Tests existentes | OK: 2 tests, 9 assertions |
| Composer audit | 0 vulnerabilities |
| npm audit | 0 vulnerabilities |

Observacion local: PHP muestra `Module "mysqli" is already loaded`. Esto apunta a una extension duplicada en la configuracion PHP/XAMPP; no bloqueo la ejecucion, pero debe corregirse para una entrega limpia.

---

## 3. Dependencias Instaladas

| Archivo | Dependencia | Version | Proposito |
| --- | --- | --- | --- |
| `package.json` | `chart.js` | `^4.5.1` | Graficos del dashboard |
| `package.json` | `sortablejs` | `^1.15.7` | Reordenamiento de modulos/materiales |
| `package.json` | `quill` | `^2.0.2` | Editor rico para materiales de texto |
| `composer.json` | `stripe/stripe-php` | `^20.2` | Preparacion para pagos reales con Stripe |

Decision: no se instalo un paquete externo de roles porque el proyecto ya tiene tablas custom de roles y permisos. Tampoco se instalo media library pesada porque Laravel Storage cubre el manejo inicial de archivos, URLs y videos; si luego se migra a S3/CDN, se puede reevaluar.

---

## 4. Estado De Datos

| Entidad | Cantidad |
| --- | ---: |
| users | 3 |
| roles | 4 |
| permissions | 30 |
| categories | 6 |
| courses | 9 |
| course_modules | 34 |
| course_materials | 0 |
| enrollments | 0 |
| sales | 0 |
| coupons | 0 |
| settings | 13 |

Lectura de producto:

- Hay oferta de cursos inicial.
- Hay estructura modular inicial.
- No hay contenido educativo real cargado.
- No hay estudiantes inscritos.
- No hay ventas ni cupones.
- No hay materiales descargables ni videos en BD.

---

## 5. Estado Funcional Por Modulo

| Modulo esperado | Estado actual | Brecha |
| --- | --- | --- |
| Dashboard LMS | Basico | Solo muestra usuarios/contactos/inscripciones, no KPIs LMS |
| Gestion de cursos | Pendiente | No existen rutas/admin CRUD para cursos |
| Modulos de curso | Pendiente | Hay tabla/modelo, no hay UI/CRUD |
| Materiales educativos | Pendiente | Hay tabla/modelo, no hay UI/upload/player |
| Catalogo publico dinamico | Pendiente | `/cursos` sigue como vista estatica |
| Detalle de curso | Pendiente | No existe `/cursos/{slug}` |
| Estudiantes | Pendiente | No hay admin de estudiantes ni perfil avanzado |
| Ventas | Pendiente | Tabla existe, checkout no la usa |
| Cupones | Pendiente | Tabla existe, no hay CRUD ni aplicacion en checkout |
| Roles y permisos | Parcial | Datos existen, rutas usan `is_admin` legacy |
| Auditoria | Parcial | Tabla existe, no hay servicio ni vista |
| Seguridad | Parcial | Dependencias limpias, faltan rate limits y hardening |
| Stripe | Preparado | SDK instalado, falta configuracion/servicio/webhooks |

---

## 6. Archivos Clave Revisados

| Archivo | Hallazgo |
| --- | --- |
| `routes/web.php` | Admin solo expone dashboard, users y contacts; `/cursos` es `Route::view` |
| `routes/api.php` | Solo expone `/api/chat` |
| `app/Models/Course.php` | Modelo con relaciones, scopes y precio efectivo |
| `app/Models/CourseMaterial.php` | Soporta video URL/upload, documentos, presentaciones, texto y recursos |
| `app/Models/User.php` | Tiene helpers de roles, pero UI/rutas aun dependen de admin legacy |
| `database/migrations/2026_06_07_220200_create_courses_table.php` | Tabla de cursos bien encaminada |
| `database/migrations/2026_06_07_220400_create_course_materials_table.php` | Tabla preparada para videos, archivos y texto |
| `database/migrations/2026_06_07_220600_create_sales_and_coupons_tables.php` | Ventas, sale_items y cupones preparados |
| `database/seeders/CourseSeeder.php` | 9 cursos y 34 modulos seed, sin materiales |
| `documentacion/KANBAN.md` | Reescrito para evitar mojibake y reflejar avance real |

---

## 7. Riesgos Tecnicos

1. La carpeta auditada no tiene `.git`, por lo que no hay trazabilidad local de cambios.
2. El warning de `mysqli` duplicado ensucia comandos y puede confundir en presentaciones.
3. La documentacion anterior mezcla estado antiguo SQLite con estado actual MySQL.
4. Los modelos nuevos existen, pero faltan controladores, rutas, vistas y pruebas.
5. Los materiales subidos requeriran control de acceso; no deben servirse como archivos publicos si son parte de cursos pagados.
6. El checkout actual debe dejar de operar con nombres de cursos en sesion y pasar a `course_id`.
7. El panel admin necesita migrar de `is_admin` a permisos reales sin romper el admin existente.
8. La cobertura de pruebas es muy baja para una plataforma con pagos y contenido privado.

---

## 8. Decisiones Recomendadas

- Mantener Laravel MVC + Blade para avanzar rapido y con bajo consumo de recursos.
- Usar Laravel Storage para archivos locales en MVP.
- Usar rutas protegidas para descargar materiales privados, no links publicos directos para todo.
- Usar Chart.js solo en dashboard, maximo 3 graficos.
- Usar SortableJS para reordenamiento simple sin framework extra.
- Usar Quill con sanitizacion de HTML antes de mostrar contenido al estudiante.
- Mantener roles custom por ahora y reforzarlos con middleware/policies.
- Preparar Stripe con SDK y servicio, pero no activar pagos reales hasta que existan ventas y acceso por inscripcion.

---

## 9. Verificacion Ejecutada

Comandos ejecutados y resultado:

```bash
php artisan about
php artisan migrate:status
php artisan storage:link
npm install chart.js sortablejs quill --no-audit --no-fund
npm install quill@2.0.2 --no-audit --no-fund
composer require stripe/stripe-php --no-interaction
composer update laravel/framework symfony/http-foundation symfony/http-kernel symfony/mailer symfony/mime symfony/routing symfony/yaml symfony/polyfill-intl-idn --with-all-dependencies --no-interaction
composer audit
npm audit --audit-level=moderate
npm run build
php artisan test
```

Resultado final:

- Composer audit: 0 vulnerabilities.
- npm audit: 0 vulnerabilities.
- Vite build: OK.
- Laravel tests: OK.
- Storage publico: enlazado.

---

## 10. Proxima Accion Recomendada

Empezar por Sprint 1 del Kanban actualizado:

1. Normalizar contratos de datos y permisos.
2. Crear middleware de permisos.
3. Crear FormRequests para cursos, modulos y materiales.
4. Definir `config/lms.php` para uploads, extensiones y limites.
5. Crear servicios pequenos para publicacion de cursos y embeds de video.

Despues de eso, avanzar a catalogo dinamico y CRUD admin de cursos. Ese orden reduce retrabajo y evita construir pantallas sobre reglas todavia borrosas.
