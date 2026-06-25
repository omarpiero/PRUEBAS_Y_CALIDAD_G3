# Desarrollo del Sistema — Iteraciones (Capítulo 8)

> **Referencia al Informe Final:** Cubre el Capítulo 8 (Desarrollo del Sistema). Refleja el estado real del repositorio del proyecto.

El desarrollo del LMS v2.0 se ejecutó utilizando una metodología Iterativa e Incremental (Spec-Anchored SDD), garantizando que en cada iteración se liberara un incremento de software funcional y validado.

## 8.1. Iteración 1: Configuración Inicial del Proyecto (Foundation)
- **Scaffolding y Entorno:** Se inicializó la aplicación utilizando **Laravel 12** sobre PHP 8.2. Se configuraron los parámetros de entorno en `.env` para asegurar conectividad con la base de datos (MySQL vía XAMPP en puerto 3307).
- **Frontend Moderno:** Se sustituyó Webpack por **Vite 7** para obtener reemplazo de módulos en caliente (HMR). Se integró **Tailwind CSS v4** y los componentes base de UI usando **React 19**.
- **Esquema de Datos (Migraciones):** Generación de las tablas primarias: `users`, `courses`, `categories`, `course_modules`. Estas fueron diseñadas estrictamente según el modelo entidad-relación del proyecto.

## 8.2. Iteración 2: Desarrollo de Funcionalidades Básicas (Core Business)
- **Autenticación y Seguridad:** Implementación de registro y login vía `AuthController`. Incorporación del `AdminMiddleware` para aislar y proteger las rutas del panel de control.
- **Gestión Académica (CRUD):** El desarrollo de `Admin\CourseController` y `Admin\CourseModuleController` permitió a los administradores poblar el catálogo de cursos.
- **Carrito de Compras en Memoria:** Se construyó el `CartController`, logrando persistencia temporal en sesión (`session()->put('cart', ...)`) para preparar la plataforma hacia la etapa financiera, incluyendo el modelo inicial de `Coupon` (RF-07).

## 8.3. Iteración 3: Implementación de Módulos Funcionales (Integraciones)
- **Motor Financiero Transaccional:** Se programó el `PaymentController` y el `StripeService`. Se utilizó la API de Stripe para manejar transacciones ACID protegidas por `DB::transaction()`, junto con un Webhook asíncrono para generar las matriculaciones (`Enrollment`) y facturaciones (`Sale`) sin intervenir la sesión bloqueante del usuario.
- **Asistente Cognitivo IA:** Desarrollo del `Api\ChatController`. Se vinculó la API REST de Google Gemini 2.5 Flash, inyectada en el DOM mediante el componente de React `AiChat.jsx` para brindar soporte asíncrono permanente (MP-07).
- **Indicadores de Calidad (Dashboard):** Se consolidó el `DashboardController` para entregar telemetría en tiempo real: contadores de ingresos, estudiantes matriculados y cursos publicados.

## 8.4. Iteraciones Posteriores (Refinamiento y Testing)
- **Blindaje QA:** Despliegue de la suite de 85 pruebas unitarias y de características (`tests/Feature/`) utilizando PHPUnit. Se aplicaron Mocks a la pasarela de Stripe y a Gemini para garantizar que el flujo CI/CD funcionara off-line y de manera idempotente usando la base de datos en memoria (`sqlite`).
- **Sistema de Auditoría Inmutable:** Implementación del patrón Singleton `AuditService` para rastrear toda mutación administrativa.
