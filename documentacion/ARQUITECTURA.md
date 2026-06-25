# Arquitectura del Proyecto — JM y JS Alimentos

---

## 1. Estructura de Carpetas y Archivos

El proyecto está construido con **Laravel 12**, que impone una estructura de directorios estándar y bien definida. A continuación se detalla cada carpeta con su responsabilidad dentro del sistema.

```
boceto/
│
├── app/                          ← Núcleo de la aplicación
│   ├── Http/
│   │   ├── Controllers/          ← Lógica de cada pantalla
│   │   │   ├── Controller.php        (clase base abstracta de Laravel)
│   │   │   ├── AuthController.php    (login, registro, logout)
│   │   │   ├── CartController.php    (carrito de compras en sesión)
│   │   │   ├── ContactController.php (formulario de contacto)
│   │   │   ├── EnrollmentController.php (inscripciones directas)
│   │   │   ├── MiCuentaController.php   (panel del estudiante)
│   │   │   ├── PaymentController.php    (proceso de pago)
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php  (estadísticas admin)
│   │   │   │   ├── UserController.php       (gestión de usuarios)
│   │   │   │   └── ContactsController.php   (gestión de mensajes)
│   │   │   └── Api/
│   │   │       └── ChatController.php       (chatbot IA con Gemini)
│   │   └── Middleware/
│   │       └── AdminMiddleware.php   (protección de rutas de admin)
│   ├── Models/
│   │   ├── User.php              ← Modelo de usuarios
│   │   ├── Enrollment.php        ← Modelo de inscripciones
│   │   └── Contact.php           ← Modelo de mensajes de contacto
│   └── Providers/
│       └── AppServiceProvider.php (configuración inicial de la app)
│
├── bootstrap/
│   ├── app.php                   ← Punto de configuración de Laravel 12
│   └── providers.php             ← Lista de service providers
│
├── config/                       ← Configuración del sistema
│   ├── app.php                   (nombre, zona horaria, locale)
│   ├── auth.php                  (guards y providers de autenticación)
│   ├── database.php              (conexión a SQLite)
│   ├── session.php               (sesiones en base de datos)
│   ├── cache.php                 (caché en base de datos)
│   ├── queue.php                 (colas de trabajos)
│   ├── mail.php                  (configuración de correo)
│   └── services.php              (clave y modelo de Gemini API)
│
├── database/
│   ├── migrations/               ← Historial de cambios en la BD
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_05_06_185634_add_is_admin_to_users_table.php
│   │   ├── 2026_05_06_213613_create_contacts_table.php
│   │   ├── 2026_05_06_214855_create_enrollments_table.php
│   │   └── 2026_05_06_214856_add_dni_phone_to_users_table.php
│   ├── factories/
│   │   └── UserFactory.php       (generación de datos de prueba)
│   └── seeders/
│       └── DatabaseSeeder.php    (datos iniciales)
│
├── public/                       ← Único directorio expuesto al navegador
│   ├── index.php                 (punto de entrada HTTP de toda la app)
│   ├── css/
│   │   └── site.css              (hoja de estilos principal — 1,542 líneas)
│   ├── img/                      (imágenes estáticas del sitio)
│   └── build/                    (assets compilados por Vite)
│
├── resources/
│   ├── js/
│   │   ├── app.jsx               (punto de entrada de React)
│   │   └── components/           (componentes React de la interfaz)
│   └── views/                    ← Plantillas Blade (HTML del servidor)
│       ├── layouts/
│       │   ├── app.blade.php     (layout principal: navbar + footer)
│       │   └── admin.blade.php   (layout del panel administrativo)
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   └── inscripcion.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── users.blade.php
│       │   └── contacts.blade.php
│       ├── inicio.blade.php
│       ├── nosotros.blade.php
│       ├── cursos.blade.php
│       ├── contacto.blade.php
│       ├── mi-cuenta.blade.php
│       ├── checkout.blade.php
│       └── pago-exito.blade.php
│
├── routes/
│   ├── web.php                   ← Rutas del sitio web (HTML)
│   ├── api.php                   ← Rutas de la API (JSON)
│   └── console.php               (comandos de Artisan)
│
├── storage/                      ← Archivos generados en tiempo de ejecución
│   ├── app/                      (archivos subidos por usuarios)
│   ├── framework/cache/          (caché del framework)
│   ├── framework/sessions/       (sesiones de usuario)
│   └── logs/                     (registros del sistema)
│
├── tests/
│   ├── Feature/                  (pruebas de integración)
│   └── Unit/                     (pruebas unitarias)
│
├── .env                          ← Variables de entorno (no se sube al repositorio)
├── .env.example                  (plantilla de variables de entorno)
├── artisan                       ← CLI de Laravel
├── composer.json                 (dependencias PHP)
├── package.json                  (dependencias JavaScript)
└── vite.config.js                (configuración del bundler)
```

---

## 2. Paradigmas Utilizados: MVC, CDD y SDD

Este proyecto combina tres paradigmas complementarios: **MVC** como arquitectura base del backend, **CDD** (Component-Driven Development) para el frontend, y **SDD** (Separation of Domains Design) como principio organizativo entre capas.

---

### 2.1 MVC — Model-View-Controller

**¿Qué es?**
MVC es el patrón arquitectónico central de Laravel. Divide la aplicación en tres capas con responsabilidades distintas e independientes:

| Capa | Responsabilidad | En este proyecto |
|---|---|---|
| **Model** | Representa los datos y las reglas de negocio | `User`, `Enrollment`, `Contact` |
| **View** | Presenta los datos al usuario | Archivos `.blade.php` en `resources/views/` |
| **Controller** | Recibe peticiones, coordina Model y View | Archivos en `app/Http/Controllers/` |

**Flujo de una solicitud HTTP bajo MVC:**

```
Navegador
    │
    ▼
[routes/web.php]              ← El Router recibe la URL y la asigna a un Controller
    │
    ▼
[Controller]                  ← Procesa la lógica, consulta el Model si es necesario
    │
    ▼
[Model / Eloquent ORM]        ← Interactúa con la base de datos SQLite
    │
    ▼
[Controller → View]           ← Pasa los datos al archivo Blade correspondiente
    │
    ▼
[Blade Template → HTML]       ← Se renderiza y se envía al navegador
```

**Ejemplo concreto — Página "Mi Cuenta":**

```
GET /mi-cuenta
    │
    ▼
routes/web.php → Route::get('/mi-cuenta', [MiCuentaController::class, 'index'])
    │
    ▼
MiCuentaController@index()
    ├── $user = auth()->user()
    ├── $enrollments = $user->enrollments()->get()
    └── return view('mi-cuenta', compact('user', 'enrollments'))
    │
    ▼
resources/views/mi-cuenta.blade.php
    └── Renderiza la vista con los datos del usuario y sus inscripciones
```

---

### 2.2 CDD — Component-Driven Development (Desarrollo Basado en Componentes)

**¿Qué es?**
CDD es el enfoque de construir interfaces de usuario dividiéndolas en **componentes reutilizables e independientes**, desde los más pequeños (botones, badges) hasta los más grandes (layouts completos). Cada componente encapsula su estructura, estilo y comportamiento.

En este proyecto se aplica en **dos niveles**:

#### Nivel 1: Blade Layouts (Componentes de servidor)

Las vistas no se escriben desde cero en cada página. En su lugar, todas heredan de un **layout base** que contiene los elementos comunes:

```
layouts/app.blade.php
    ├── <head> (meta, CSS, fuentes, Vite assets)
    ├── <nav> (navbar con logo, links, carrito, avatar de usuario)
    ├── @yield('content')   ← Cada página inyecta su contenido aquí
    └── <footer> + scripts JS
```

Cada página individual extiende este layout y solo define su contenido propio:

```blade
{{-- cursos.blade.php --}}
@extends('layouts.app')

@section('content')
    {{-- Solo el contenido exclusivo de la página de cursos --}}
@endsection
```

Esto significa que la navbar, el footer, los estilos base y el badge del carrito se definen **una sola vez** y se reutilizan en todas las páginas automáticamente.

El panel admin tiene su propio layout base:

```
layouts/admin.blade.php
    ├── Sidebar de navegación admin
    ├── @yield('content')
    └── Scripts del panel
```

#### Nivel 2: Componentes React (Componentes de cliente)

El chatbot de IA está implementado como un **componente React** independiente en `resources/js/components/`. React permite construir UIs reactivas que se actualizan sin recargar la página, manteniendo el principio CDD en el frontend.

```
resources/js/
    ├── app.jsx               ← Monta los componentes React en el DOM
    └── components/
        └── [ChatBot.jsx]     ← Componente del asistente de IA
```

El componente del chatbot:
- Es autocontenido (tiene su propio estado, lógica y presentación)
- Se monta en un `<div>` específico del layout sin afectar el resto
- Se comunica con el backend via `POST /api/chat` de forma asíncrona

#### Beneficios del CDD en este proyecto

| Problema sin CDD | Solución con CDD |
|---|---|
| Cambiar el logo requiere editar 12 archivos | Se edita solo `layouts/app.blade.php` |
| La navbar tiene bugs distintos en cada página | La navbar es un único bloque de código |
| Agregar una nueva página requiere reescribir el HTML base | Solo se crea el archivo y se extiende el layout |
| El chatbot estaría acoplado al HTML de la página | El componente React es independiente y portátil |

---

### 2.3 SDD — Separation of Domains Design (Separación por Dominios)

**¿Qué es?**
SDD es el principio de organizar el código **por dominio funcional** en lugar de por tipo técnico. Cada dominio agrupa todo lo que le concierne: sus rutas, su controlador, sus vistas y sus datos.

En este proyecto se identifican **cuatro dominios claramente separados**:

```
┌─────────────────────────────────────────────────────┐
│  DOMINIO PÚBLICO                                    │
│  Rutas: /, /nosotros, /cursos, /contacto            │
│  Vistas: inicio, nosotros, cursos, contacto         │
│  Sin autenticación requerida                        │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│  DOMINIO DE AUTENTICACIÓN                           │
│  Rutas: /login, /register                           │
│  Controller: AuthController                         │
│  Vistas: auth/login, auth/register                  │
│  Middleware: guest (solo para no autenticados)      │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│  DOMINIO DEL ESTUDIANTE                             │
│  Rutas: /mi-cuenta, /checkout, /pago, /pago/exito   │
│  Controllers: MiCuentaController, CartController,   │
│               PaymentController, EnrollmentController│
│  Vistas: mi-cuenta, checkout, pago-exito            │
│  Middleware: auth (solo autenticados)               │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│  DOMINIO ADMINISTRATIVO                             │
│  Rutas: /admin, /admin/users, /admin/contacts       │
│  Controllers: Admin\DashboardController,            │
│               Admin\UserController,                 │
│               Admin\ContactsController              │
│  Vistas: admin/dashboard, admin/users, admin/contacts│
│  Middleware: auth + admin                           │
└─────────────────────────────────────────────────────┘
```

La separación de dominios se refleja también en la estructura física del código:

| Carpeta | Dominio |
|---|---|
| `Controllers/Admin/` | Todo lo administrativo agrupado en su propio subdirectorio |
| `Controllers/Api/` | El chatbot de IA separado de la lógica web |
| `views/auth/` | Las pantallas de autenticación en su propia carpeta |
| `views/admin/` | Las vistas del admin separadas de las del usuario |

#### Separación adicional: Web vs. API

El proyecto mantiene dos archivos de rutas separados que representan dos contratos distintos:

```
routes/web.php  → Responde con HTML (para el navegador)
                  Usa sesiones y CSRF tokens
                  Ejemplo: GET /cursos → vista blade

routes/api.php  → Responde con JSON (para el chatbot)
                  Sin sesiones, sin CSRF
                  Ejemplo: POST /api/chat → { "reply": "..." }
```

Esta separación permite que en el futuro se pueda desarrollar una app móvil o una integración externa que consuma la API sin tocar las rutas web.

---

## 3. Cómo se Aplican los Paradigmas en el Proyecto

### 3.1 MVC en la práctica

**Caso: El estudiante paga un curso**

| Capa MVC | Archivo | Acción |
|---|---|---|
| **Router** | `routes/web.php` | `POST /pago` → `PaymentController@process` |
| **Controller** | `PaymentController.php` | Valida datos de tarjeta, itera el carrito de sesión |
| **Model** | `Enrollment.php` | `Enrollment::create([...])` — guarda en la BD |
| **Model** | `User.php` | `auth()->user()` — obtiene el usuario actual |
| **Controller** | `PaymentController.php` | Vacía el carrito, redirige a éxito |
| **View** | `pago-exito.blade.php` | Muestra el mensaje de confirmación |

**Caso: El admin ve los contactos**

| Capa MVC | Archivo | Acción |
|---|---|---|
| **Router** | `routes/web.php` | `GET /admin/contacts` → `ContactsController@index` |
| **Middleware** | `AdminMiddleware.php` | Verifica `is_admin = true`, bloquea si no |
| **Controller** | `Admin/ContactsController.php` | `Contact::orderBy('created_at', 'desc')->get()` |
| **Model** | `Contact.php` | Retorna la colección de mensajes |
| **View** | `admin/contacts.blade.php` | Renderiza la tabla con los mensajes |

---

### 3.2 CDD en la práctica

**Herencia del layout en todas las páginas**

Cada archivo de vista comienza con `@extends('layouts.app')`. Esto significa que cuando se actualiza la navbar (por ejemplo, agregar un nuevo link), el cambio se propaga automáticamente a las 12 páginas del sitio.

**Componente React del chatbot**

El chatbot es un componente React montado en el layout principal. Consume el endpoint `POST /api/chat`, que internamente llama a la API de Google Gemini con un *system prompt* específico sobre la empresa. El componente es completamente independiente: se puede desactivar, actualizar o reemplazar sin tocar ninguna otra parte del código.

---

### 3.3 SDD en la práctica

**Middleware como guardianes de dominio**

La separación entre dominios se enforcea a nivel de rutas con middleware:

```php
// routes/web.php

// Dominio público — sin restricciones
Route::get('/', fn() => view('inicio'));

// Dominio de autenticación — solo para visitantes no logueados
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::get('/register', [AuthController::class, 'showRegister']);
});

// Dominio del estudiante — requiere estar logueado
Route::middleware('auth')->group(function () {
    Route::get('/mi-cuenta', [MiCuentaController::class, 'index']);
    Route::post('/pago', [PaymentController::class, 'process']);
});

// Dominio administrativo — requiere ser admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/contacts', [ContactsController::class, 'index']);
});
```

Un usuario normal que intente acceder a `/admin` es bloqueado por `AdminMiddleware` antes de que su solicitud llegue a cualquier controlador.

**Namespacing de controllers por dominio**

Los controladores del dominio administrativo viven en su propio namespace `App\Http\Controllers\Admin\`, lo que evita conflictos de nombres y hace evidente a qué dominio pertenece cada controlador con solo ver la ruta de importación.

---

## 4. Resumen de la Arquitectura

```
┌───────────────────────────────────────────────────────────┐
│                      NAVEGADOR / CLIENTE                  │
│   Blade HTML renderizado  ←→  React components (chatbot)  │
└──────────────────────────┬────────────────────────────────┘
                           │  HTTP Request
┌──────────────────────────▼────────────────────────────────┐
│                      CAPA DE ENRUTAMIENTO                 │
│   routes/web.php (HTML)   +   routes/api.php (JSON)       │
│   Middleware: guest │ auth │ admin                        │
└──────────────────────────┬────────────────────────────────┘
                           │  Dispatch al Controller
┌──────────────────────────▼────────────────────────────────┐
│                   CAPA DE CONTROLADORES (MVC-C)           │
│  Público    │  Auth    │  Estudiante  │  Admin  │  API    │
│  (inicio,   │  (login, │  (mi-cuenta, │  (dash- │  (chat) │
│  cursos...) │  register│  carrito...) │  board) │        │
└──────────────────────────┬────────────────────────────────┘
                           │  Consultas Eloquent ORM
┌──────────────────────────▼────────────────────────────────┐
│                    CAPA DE MODELOS (MVC-M)                │
│          User  │  Enrollment  │  Contact                  │
└──────────────────────────┬────────────────────────────────┘
                           │
┌──────────────────────────▼────────────────────────────────┐
│                BASE DE DATOS SQLite                       │
│  users │ enrollments │ contacts │ sessions │ cache │ jobs │
└───────────────────────────────────────────────────────────┘

Servicios externos:
  Google Gemini API  ←→  Api\ChatController  (chatbot IA)
```

| Paradigma | Alcance en el proyecto | Archivos clave |
|---|---|---|
| **MVC** | Toda la arquitectura backend | `Controllers/`, `Models/`, `views/` |
| **CDD** | Frontend: layouts Blade + componentes React | `layouts/app.blade.php`, `resources/js/components/` |
| **SDD** | Organización por dominios funcionales | `routes/web.php` (grupos con middleware), `Controllers/Admin/`, `Controllers/Api/` |

---

## 5. Convención de Almacenamiento (Storage)

Para garantizar la seguridad de los materiales educativos del LMS, los archivos de cursos (videos, documentos, presentaciones y recursos descargables) no se almacenan en el disco público expuesto mediante enlaces simbólicos.

### 5.1 Estructura de Rutas
Los materiales se guardan en el disco local privado bajo el patrón:
`private/materials/{course_id}/{module_id}/`

### 5.2 Control de Acceso
El acceso a estos materiales se gestionará a través de un controlador específico que validará la inscripción activa del usuario (o el rol de administrador/instructor) antes de iniciar la descarga o transmisión del archivo, impidiendo el acceso directo y no autorizado.

---

*Documentación de arquitectura — JM y JS Alimentos — Mayo 2026*
