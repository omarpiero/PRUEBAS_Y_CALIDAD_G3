# 09. Matriz de doble entrada de cierre

Fecha de cierre: 2026-06-27  
Objetivo: completar la matriz exigida por la asignatura con requerimientos, implementacion, pruebas y evidencia.

## 1. RF vs implementacion vs pruebas

| RF | Descripcion | Implementacion principal | Prueba/evidencia sugerida | Estado |
| --- | --- | --- | --- | --- |
| RF-01 | Catalogo publico de cursos | `app/Http/Controllers/CourseController.php` | `PublicCourseCatalogTest.php` | Implementado |
| RF-02 | Busqueda y filtrado de cursos | `CourseController@index/search` y vistas de catalogo | `PublicCourseCatalogTest.php` | Implementado |
| RF-03 | Detalle publico de curso | `CourseController@show` | `PublicCourseCatalogTest.php` | Implementado |
| RF-04 | Registro de estudiantes | `AuthController@register` | pruebas de autenticacion y flujo publico | Implementado |
| RF-05 | Login/logout | `AuthController@login/logout` | `AdminSecurityAndRolesTest.php` | Implementado |
| RF-06 | Carrito de compras | `CartController` | `PaymentStripeTest.php` | Implementado |
| RF-07 | Cupones | `Coupon`, `CartController`, `Admin/CouponController` | `AdminSalesAndCouponsTest.php` | Implementado |
| RF-08 | Checkout con Stripe | `PaymentController@process`, `StripeService` | `PaymentStripeTest.php` | Implementado |
| RF-09 | Webhook y matricula automatica | `PaymentController@webhook/confirmSale` | `PaymentStripeTest.php` | Implementado |
| RF-10 | Registro de venta/recibo | `Sale`, `SaleItem`, `Admin/SaleController` | `AdminSalesAndCouponsTest.php` | Implementado |
| RF-11 | Chatbot IA | `Api/ChatController`, `GeminiAssistantService`, `AiChat.jsx` | `GeminiAssistantTest.php` | Implementado |
| RF-12 | CRUD administrativo de cursos | `Admin/CourseController` | `AdminCourseCrudTest.php` | Implementado |
| RF-13 | Modulos y reordenamiento | `Admin/CourseModuleController` | `AdminCourseMaterialTest.php` | Implementado |
| RF-14 | Materiales de curso | `Admin/CourseMaterialController`, `CourseMaterial` | `AdminCourseMaterialTest.php` | Implementado |
| RF-15 | Usuarios, roles y permisos | `Admin/UserController`, `Role`, `Permission` | `AdminSecurityAndRolesTest.php` | Implementado |
| RF-16 | Dashboard de indicadores | `Admin/DashboardController` | `AdminDashboardAnalyticsTest.php` | Implementado |
| RF-17 | Contactos | `ContactsController`, `Admin/ContactsController` | revision funcional y captura admin/contactos | Implementado |
| RF-18 | Aula virtual del estudiante | `StudentCourseController@show` | `StudentCourseAccessTest.php` | Implementado |
| RF-19 | Acceso privado a archivos | `StudentCourseController@serveFile` | `StudentCourseAccessTest.php` | Implementado |
| RF-20 | Gestion administrativa de cupones | `Admin/CouponController` | `AdminSalesAndCouponsTest.php` | Implementado |
| RF-21 | Panel de ingresos historicos | `Admin/SaleController` | `AdminSalesAndCouponsTest.php` | Implementado |

## 2. RNF vs evidencia tecnica

| RNF | Atributo ISO/IEC 25010 | Evidencia tecnica | Prueba/evidencia | Estado |
| --- | --- | --- | --- | --- |
| RNF-01 | Eficiencia | Vite, eager loading, consultas Eloquent controladas | capturas y revision de codigo | Implementado |
| RNF-02 | Usabilidad | Blade + Tailwind, pantallas publicas/admin/estudiante | `documentacion/CAPTURAS/` | Implementado |
| RNF-03 | Seguridad | CSRF, hashing, Eloquent bindings, headers | `AdminSecurityAndRolesTest.php` | Implementado |
| RNF-04 | Trazabilidad | `AuditService`, `audit_logs` | `AdminSecurityAndRolesTest.php` | Implementado |
| RNF-05 | Integridad transaccional | `DB::transaction` en pagos | `PaymentStripeTest.php` | Implementado |
| RNF-06 | Disponibilidad administrativa | control contra ultimo admin | `AdminSecurityAndRolesTest.php` | Implementado |
| RNF-07 | Seguridad HTTP/CSP | middleware de headers | `AdminSecurityAndRolesTest.php` | Parcial para produccion estricta |
| RNF-08 | Portabilidad QA | SQLite in-memory en `phpunit.xml` | suite PHPUnit | Implementado |
| RNF-09 | Proteccion anti abuso | throttle en login/chat | `AdminSecurityAndRolesTest.php` | Implementado |
| RNF-10 | Mantenibilidad | docs, tests, matriz, commits semanticos | Git log y docs | Implementado |

## 3. Criterio de aceptacion

La matriz esta lista para anexarse cuando:

- Los 21 RF aparecen en el Word o anexos.
- Cada RF tiene archivo responsable y prueba/evidencia.
- Los 10 RNF se vinculan con ISO/IEC 25010 y con evidencia verificable.
- La URL del repositorio es unica en todo el documento.
