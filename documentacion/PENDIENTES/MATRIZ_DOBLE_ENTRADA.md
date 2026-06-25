# Matriz de Doble Entrada — JM y JS Alimentos LMS v2.0 (Versión Técnica Extendida)

> Repositorio: https://github.com/ROGERCanchumanyaUC/pruebas-calidad-grupo-03.git  
> Evidencias mapeadas directamente desde el código fuente del LMS (`app/`, `tests/`, `routes/`).

---

## 1. Matriz de Requerimientos Funcionales (RF) vs. Clases de Implementación

Identificación de la ubicación exacta en el código donde la lógica de negocio se ejecuta y valida.

| RF | Descripción Comercial | Implementado Por (Controlador/Módulo) | Archivo Principal Responsable |
|---|---|---|---|
| RF-01 | Catálogo Público de Cursos | `CourseController@index` | `app/Http/Controllers/CourseController.php` |
| RF-02 | Búsqueda y Filtrado de Cursos | `CourseController@search` | `app/Http/Controllers/CourseController.php` |
| RF-03 | Visualización del Detalle del Curso | `CourseController@show` | `app/Http/Controllers/CourseController.php` |
| RF-04 | Registro de Nuevos Estudiantes | `AuthController@register` | `app/Http/Controllers/AuthController.php` |
| RF-05 | Autenticación y Cierre de Sesión | `AuthController@login` / `logout` | `app/Http/Controllers/AuthController.php` |
| RF-06 | Carrito de Compras de Memoria | `CartController@add` / `remove` / `view` | `app/Http/Controllers/CartController.php` |
| RF-07 | Descuentos Lógicos (Cupones) | Modelo `Coupon` / `CartController` | `app/Models/Coupon.php` |
| RF-08 | Pago Seguro con Pasarela | `PaymentController@process` | `app/Services/StripeService.php` |
| RF-09 | Inscripción Asíncrona (Matriculación) | `PaymentController@webhook` | `app/Http/Controllers/PaymentController.php` |
| RF-10 | Facturación (Generación de Recibo) | `Sale` e `Invoice` Generator | `app/Models/Sale.php` |
| RF-11 | Asistente IA (Soporte Estudiantil) | `Api\ChatController@sendMessage` | `app/Http/Controllers/Api/ChatController.php` |
| RF-12 | Panel Admin: CRUD Cursos | `Admin\CourseController` | `app/Http/Controllers/Admin/CourseController.php` |
| RF-13 | Control de Módulos (Reordenamiento) | `Admin\CourseModuleController` | `app/Http/Controllers/Admin/CourseModuleController.php` |
| RF-14 | Gestión de Materiales (PDF/Video) | `Admin\CourseMaterialController` | `app/Http/Controllers/Admin/CourseMaterialController.php` |
| RF-15 | Configuración de Privilegios y Lockouts| `Admin\UserController@toggleAdmin` | `app/Http/Controllers/Admin/UserController.php` |
| RF-16 | Panel de Indicadores (Dashboard) | `Admin\DashboardController@index` | `app/Http/Controllers/Admin/DashboardController.php` |
| RF-17 | Formulario y Bandeja de Contacto | `Admin\ContactsController` | `app/Http/Controllers/Admin/ContactsController.php` |
| RF-18 | Aula Virtual: Progreso del Estudiante | `StudentCourseController@show` | `app/Http/Controllers/StudentCourseController.php` |
| RF-19 | Streaming Privado de Archivos | `StudentCourseController@serveFile` | `app/Http/Controllers/StudentCourseController.php` |
| RF-20 | Motor Generador de Cupones Admin | `Admin\CouponController` | `app/Http/Controllers/Admin/CouponController.php` |
| RF-21 | Panel de Ingresos Históricos | `Admin\SaleController` | `app/Http/Controllers/Admin/SaleController.php` |

---

## 2. Matriz RNF vs. Implementación Arquitectónica Exacta

Cómo las exigencias de Arquitectura y Seguridad No Funcional (RNF) fueron traducidas a librerías y componentes del framework Laravel.

| RNF | Exigencia | Tecnología/Mecanismo | Archivo(s) Clave | Estado |
|---|---|---|---|:---:|
| RNF-01 | Latencia Baja (< 3s) | Vite Compilación, Eager Loading (`with()`) | `vite.config.js`, `CourseController.php` | ✔ |
| RNF-02 | Responsividad Omnicanal | UI Reactiva Tailwind CSS v4 | `resources/css/app.css` | ✔ |
| RNF-03 | Hardening (OWASP Top 10) | Hashing Bcrypt, Blade Anti-XSS, PDO Bindings | `routes/web.php`, `kernel.php` | ✔ |
| RNF-04 | Trazabilidad Inmutable | Singleton Log Generator y ORM Hooking | `app/Services/AuditService.php` | ✔ |
| RNF-05 | Integridad Financiera | Motor ACID de Base de Datos (`DB::transaction`) | `app/Http/Controllers/PaymentController.php` | ✔ |
| RNF-06 | Prevención Lockout Admin | Bloqueo lógico contra desactivación final | `app/Http/Controllers/Admin/UserController.php` | ✔ |
| RNF-07 | Content Security Policy | Exclusión de `unsafe-inline/eval` en Servidor | Middleware global (HTTP Headers) | ✔ |
| RNF-08 | Portabilidad QA (Tests) | Supresión de libs gráficas (`fake()->image`) | `tests/Feature/AdminCourseMaterialTest.php` | ✔ |
| RNF-09 | Protección Anti-Spam / Bots | Rate Limiting (Throttle) en Login/API | `routes/web.php` (`throttle:5,1`) | ✔ |
| RNF-10 | Recuperación ante Caídas | Sistema de logs robusto (Monolog) | `storage/logs/laravel.log` | ✔ |

---

## 3. Matriz de Pruebas Automatizadas (PHPUnit) vs. Clases Mapeadas

Volumen explícito del despliegue de QA estructurado en la carpeta `tests/Feature/`.

| Dominio QA Testeado | Clase PHP de la Suite de Pruebas | Entorno Base | Aserciones Mapeadas |
|---|---|:---:|:---:|
| Flujo CRUD y Excepciones Módulos | `AdminCourseCrudTest.php` | SQLite In-Memory | > 40 |
| Cargas Binarias (Materiales) | `AdminCourseMaterialTest.php` | SQLite In-Memory | > 35 |
| Tableros y Lógica de Indicadores | `AdminDashboardAnalyticsTest.php` | SQLite In-Memory | > 25 |
| Cálculos Complejos (Ventas/Cupones) | `AdminSalesAndCouponsTest.php` | SQLite In-Memory | > 40 |
| Segregación y Privilegios | `AdminSecurityAndRolesTest.php` | SQLite In-Memory | > 50 |
| Mocking Inteligencia Artificial | `GeminiAssistantTest.php` | SQLite In-Memory | > 15 |
| Desempeño y Producción Lista | `LmsReleaseReadinessTest.php` | SQLite In-Memory | > 10 |
| Integración Pasarela y Webhook | `PaymentStripeTest.php` | SQLite In-Memory | > 55 |
| Autorizaciones Middleware | `PermissionMiddlewareTest.php` | SQLite In-Memory | > 20 |
| Flujo Público de Selección | `PublicCourseCatalogTest.php` | SQLite In-Memory | > 30 |
| Autorización Aula Virtual Estudiante | `StudentCourseAccessTest.php` | SQLite In-Memory | > 40 |
| **Sumario Técnico de Testing** | **11 Clases Feature Centrales** | **Refrescos Múltiples** | **Total Real: 407 Aserciones** |

---

## 4. Matriz de Componentes Tecnológicos en el Ecosistema

| Rol del Stack | Componente Concreto | Uso Específico en el Código |
|---|---|---|
| Enrutamiento y Request HTTP | **Laravel 12 Routing** | `routes/web.php` |
| Interfaz Renderizada por Servidor | **Blade Templating** | `resources/views/` (Vistas modulares y Layouts `app.blade.php`) |
| Componentes Cliente Aislados | **React 19** | `resources/js/components/AiChat.jsx` (Chatbot) |
| Estilado Atómico | **Tailwind CSS v4** | Utilidades insertadas en vistas (Clases responsivas nativas) |
| Iconografía Vectorial | **Phosphor Icons / Heroicons** | SVG incrustados en botones y menús laterales |
| Bundler y Optimización Frontend | **Vite 7** | `vite.config.js` y recarga de módulos en caliente |
| ORM Base de Datos | **Eloquent** | Migraciones en `database/migrations/`, Modelos en `app/Models/` |
| Pasarela de Pagos (SDK) | **Stripe PHP 16.x** | Integrado en `app/Services/StripeService.php` |
| Cliente HTTP IA (LLM) | **Guzzle/Http Facade** | Conexión endpoint REST a `generativelanguage.googleapis.com` |
| Framework de Calidad QA | **PHPUnit 11** | Entorno orquestado por `phpunit.xml` e inyectado con `TestCase.php` |
| Almacenamiento Local Seguro | **Storage Facade (Disk)** | `storage/app/private/` para PDFs y videos restringidos |

---

*Matrices de doble entrada (Versión Técnica Extendida) — JM y JS Alimentos LMS v2.0 — Junio 2026*
