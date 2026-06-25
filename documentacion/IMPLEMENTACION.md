# Implementación — JM y JS Alimentos
## Plataforma de Capacitación en Línea

---

## 1. Especificaciones Técnicas

### 1.1 Stack tecnológico

| Capa | Tecnología | Versión |
|---|---|---|
| **Lenguaje backend** | PHP | ^8.2 |
| **Framework backend** | Laravel | ^12.0 |
| **Base de datos** | SQLite | 3.x |
| **Lenguaje frontend** | JavaScript (ESM) | ES2022+ |
| **Framework frontend** | React | ^19.2.5 |
| **Bundler** | Vite | ^7.0.7 |
| **CSS framework** | Tailwind CSS | ^4.0.0 |
| **Servidor local** | XAMPP (Apache + PHP) | — |
| **Gestor de paquetes PHP** | Composer | ^2.x |
| **Gestor de paquetes JS** | npm | ^10.x |
| **Motor de plantillas** | Blade (Laravel) | built-in |
| **ORM** | Eloquent (Laravel) | built-in |
| **IA externa** | Google Gemini API | gemini-2.5-flash |

---

### 1.2 Arquitectura del servidor

```
Navegador del usuario
        │
        │  HTTP (puerto 80 en XAMPP / 8000 en artisan serve)
        ▼
   Apache / php artisan serve
        │
        ▼
   public/index.php          ← Único punto de entrada HTTP
        │
        ▼
   bootstrap/app.php         ← Configura rutas, middleware, excepciones
        │
        ├── routes/web.php   ← Rutas que devuelven HTML
        └── routes/api.php   ← Rutas que devuelven JSON
        │
        ▼
   Controladores → Modelos → SQLite (database/database.sqlite)
        │
        ▼
   Vistas Blade → HTML renderizado → Navegador
```

---

### 1.3 Configuración del entorno de producción local

El sistema opera en un entorno local sobre **XAMPP**. Las variables de entorno se definen en el archivo `.env` (nunca en el código fuente):

```env
APP_NAME="JM y JS Alimentos"
APP_ENV=local
APP_KEY=base64:...          # generado con php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=es
APP_FALLBACK_LOCALE=es

BCRYPT_ROUNDS=12            # hashing seguro de contraseñas

DB_CONNECTION=sqlite        # base de datos en archivo local
# DB_DATABASE se resuelve automáticamente a database/database.sqlite

SESSION_DRIVER=database     # sesiones guardadas en BD
SESSION_LIFETIME=120        # minutos antes de expirar
SESSION_ENCRYPT=false

QUEUE_CONNECTION=database   # colas en BD (sin workers externos)
CACHE_STORE=database        # caché en BD

LOG_CHANNEL=stack
LOG_LEVEL=debug

MAIL_MAILER=log             # correos escritos al log (no se envían)

# Integración con Google Gemini (chatbot IA)
GEMINI_API_KEY=             # clave obtenida en Google AI Studio
GEMINI_MODEL=gemini-2.5-flash
```

---

### 1.4 Configuración del entorno de pruebas

Definida en `phpunit.xml`, sobreescribe las variables de `.env` solo durante los tests:

| Variable | Valor en tests | Propósito |
|---|---|---|
| `APP_ENV` | `testing` | Activa guards del framework para tests |
| `DB_CONNECTION` | `sqlite` | Igual que producción local |
| `DB_DATABASE` | `:memory:` | BD en RAM, se destruye al finalizar |
| `BCRYPT_ROUNDS` | `4` | Hashing rápido (seguridad no importa en tests) |
| `CACHE_STORE` | `array` | Caché en memoria, sin persistencia |
| `SESSION_DRIVER` | `array` | Sesiones en memoria, sin persistencia |
| `QUEUE_CONNECTION` | `sync` | Trabajos ejecutados inmediatamente |
| `MAIL_MAILER` | `array` | Correos capturados, no enviados |

---

### 1.5 Estructura de la base de datos

**Motor:** SQLite 3 — archivo `database/database.sqlite`

```sql
-- Tabla de usuarios (base de Laravel + campos personalizados)
CREATE TABLE users (
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    name              TEXT NOT NULL,
    email             TEXT NOT NULL UNIQUE,
    email_verified_at DATETIME,
    password          TEXT NOT NULL,           -- Hash bcrypt (12 rondas)
    is_admin          INTEGER NOT NULL DEFAULT 0,  -- BOOLEAN: 0/1
    dni               TEXT,                    -- Nullable
    phone             TEXT,                    -- Nullable
    remember_token    TEXT,
    created_at        DATETIME,
    updated_at        DATETIME
);

-- Tabla de inscripciones
CREATE TABLE enrollments (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id     INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    course_name TEXT NOT NULL,
    level       TEXT NOT NULL,
    price       REAL NOT NULL,                 -- DECIMAL(8,2)
    status      TEXT NOT NULL DEFAULT 'pendiente',  -- pendiente|pagado|completado
    created_at  DATETIME,
    updated_at  DATETIME
);

-- Tabla de mensajes de contacto
CREATE TABLE contacts (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre     TEXT NOT NULL,
    correo     TEXT NOT NULL,
    tema       TEXT NOT NULL,
    curso      TEXT,                           -- Nullable
    mensaje    TEXT NOT NULL,
    leido      INTEGER NOT NULL DEFAULT 0,     -- BOOLEAN: 0/1
    created_at DATETIME,
    updated_at DATETIME
);

-- Tabla de sesiones (driver: database)
CREATE TABLE sessions (
    id            TEXT PRIMARY KEY,
    user_id       INTEGER,
    ip_address    TEXT,
    user_agent    TEXT,
    payload       TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);

-- Tablas del sistema (caché y colas)
CREATE TABLE cache (...);
CREATE TABLE cache_locks (...);
CREATE TABLE jobs (...);
CREATE TABLE job_batches (...);
CREATE TABLE failed_jobs (...);
```

---

### 1.6 Configuración de Vite

```js
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.jsx'],  // Punto de entrada React
            refresh: true,                     // Hot reload en desarrollo
        }),
    ],
    server: {
        host: 'localhost',
        port: 5173,                            // Puerto del servidor de desarrollo
    },
});
```

El bundle compilado se deposita en `public/build/` y es referenciado por `@vite(...)` en los layouts Blade.

---

### 1.7 Registro de middleware personalizado

En `bootstrap/app.php` se registra el alias `'admin'` para `AdminMiddleware`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```

Esto permite usar `middleware('admin')` en cualquier ruta sin importar la clase completa.

---

## 2. Requisitos

### 2.1 Requisitos de software (entorno local / desarrollo)

| Software | Versión mínima | Propósito |
|---|---|---|
| **PHP** | 8.2 | Ejecución del backend Laravel |
| **Composer** | 2.x | Gestión de dependencias PHP |
| **Node.js** | 22.x LTS o superior | Ejecucion de Vite, npm y scripts de desarrollo |
| **npm** | 9.x | Gestión de dependencias JavaScript |
| **XAMPP** (o equivalente) | 8.2+ | Servidor Apache + PHP integrado |
| **SQLite** | 3.x | Incluido en PHP; no requiere instalación separada |
| **Git** | 2.x | Control de versiones |

> **Nota:** La extensión `pdo_sqlite` de PHP debe estar habilitada. En XAMPP viene activada por defecto.

---

### 2.2 Requisitos de hardware (mínimos para desarrollo)

| Componente | Mínimo | Recomendado |
|---|---|---|
| **CPU** | 2 núcleos, 1.6 GHz | 4 núcleos, 2.5 GHz |
| **RAM** | 4 GB | 8 GB |
| **Disco** | 500 MB libres | 2 GB libres |
| **Sistema operativo** | Windows 10, macOS 12, Ubuntu 20.04 | Windows 11, macOS 14, Ubuntu 22.04 |

---

### 2.3 Requisitos de red

| Recurso | Requerido | Para qué |
|---|---|---|
| **Conexión a Internet** | Solo en desarrollo | Descargar dependencias (Composer, npm) |
| **Google AI Studio API key** | Sí (para el chatbot) | Acceso a Google Gemini API |
| **Puerto 80** (Apache) o **8000** (artisan) | Libre | Servidor HTTP local |
| **Puerto 5173** | Libre | Servidor de desarrollo Vite |

---

### 2.4 Extensiones PHP requeridas

Estas extensiones son estándar en XAMPP/PHP 8.2+:

| Extensión | Uso en el proyecto |
|---|---|
| `pdo` | Capa de abstracción de base de datos |
| `pdo_sqlite` | Conexión con SQLite |
| `mbstring` | Manipulación de cadenas multibyte |
| `openssl` | Cifrado de sesiones y cookies |
| `tokenizer` | Tokenización para Blade y Artisan |
| `xml` | Parseo de XML (phpunit, Composer) |
| `ctype` | Validación de tipos de caracteres |
| `fileinfo` | Detección de tipos MIME |
| `curl` | Llamadas HTTP a la API de Gemini |

---

## 3. Dependencias

### 3.1 Dependencias PHP (composer.json)

#### Producción

| Paquete | Versión | Función en el proyecto |
|---|---|---|
| `laravel/framework` | ^12.0 | Framework principal: routing, ORM, middleware, autenticación, sesiones, validación |
| `laravel/tinker` | ^2.10.1 | REPL interactivo para depuración desde la terminal (`php artisan tinker`) |

#### Desarrollo (no se usan en producción)

| Paquete | Versión | Función |
|---|---|---|
| `fakerphp/faker` | ^1.23 | Generación de datos falsos en factories y seeders |
| `laravel/pail` | ^1.2.2 | Visor de logs en tiempo real en la terminal |
| `laravel/pint` | ^1.24 | Formateador de código PHP (PSR-12) |
| `laravel/sail` | ^1.41 | Entorno Docker para Laravel (disponible pero no usado) |
| `mockery/mockery` | ^1.6 | Creación de mocks en pruebas unitarias |
| `nunomaduro/collision` | ^8.6 | Mejor visualización de errores en consola |
| `phpunit/phpunit` | ^11.5.50 | Framework de pruebas automatizadas |

**Autoloading PSR-4:**
```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    }
}
```

---

### 3.2 Dependencias JavaScript (package.json)

#### Producción (dependencies)

| Paquete | Versión | Función en el proyecto |
|---|---|---|
| `react` | ^19.2.5 | Librería de UI para el componente del chatbot |
| `react-dom` | ^19.2.5 | Renderizado de React en el DOM del navegador |

#### Desarrollo (devDependencies)

| Paquete | Versión | Función |
|---|---|---|
| `vite` | ^7.0.7 | Bundler y servidor de desarrollo con HMR |
| `laravel-vite-plugin` | ^2.0.0 | Integración entre Vite y Laravel (manifesto de assets) |
| `@vitejs/plugin-react` | ^5.2.0 | Soporte JSX y Fast Refresh para React en Vite |
| `tailwindcss` | ^4.0.0 | Framework CSS de utilidades (disponible, uso parcial) |
| `@tailwindcss/vite` | ^4.0.0 | Plugin de integración Tailwind con Vite |
| `concurrently` | ^10.0.3 | Ejecucion paralela de multiples procesos (artisan + vite + queue + pail) |

---

### 3.3 Dependencia externa — Google Gemini API

| Atributo | Valor |
|---|---|
| **Proveedor** | Google (Google AI Studio) |
| **Modelo** | `gemini-2.5-flash` (configurable via `.env`) |
| **Endpoint** | `https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent` |
| **Autenticación** | API Key en header `x-goog-api-key` |
| **Timeout** | 30 segundos |
| **Parámetros** | `temperature: 0.7`, `maxOutputTokens: 500` |
| **Costo** | Gratuito hasta cierta cuota (Google AI Studio free tier) |
| **Requisito** | `GEMINI_API_KEY` en `.env` |

La comunicación con Gemini se realiza via `curl` nativo de PHP (a través de Laravel's HTTP client o directamente), sin dependencia de un SDK de terceros.

---

## 4. Plan de Implementación

El plan está dividido en **6 fases** ordenadas por prioridad y dependencia técnica.

---

### Fase 1 — Preparación del entorno

**Objetivo:** Tener el proyecto corriendo localmente desde cero.

```
Paso 1: Verificar requisitos de software
    ├── php --version          → debe ser ≥ 8.2
    ├── composer --version     → debe ser ≥ 2.x
    ├── node --version         → debe ser ≥ 22.x
    └── npm --version          → debe ser ≥ 9.x

Paso 2: Clonar o copiar el proyecto
    └── Colocar en: C:\xampp\htdocs\boceto\

Paso 3: Instalar dependencias PHP
    └── composer install

Paso 4: Instalar dependencias JavaScript
    └── npm install

Paso 5: Crear el archivo de entorno
    ├── copy .env.example .env
    └── php artisan key:generate
        (genera APP_KEY única para cifrar sesiones y cookies)

Paso 6: Crear la base de datos SQLite
    └── New-Item database\database.sqlite -ItemType File
        (en Windows PowerShell)

Paso 7: Ejecutar las migraciones
    └── php artisan migrate
        (crea todas las tablas en database.sqlite)

Paso 8: Compilar los assets del frontend
    └── npm run build
        (genera public/build/ con los archivos compilados)
```

**Verificación:** Abrir `http://localhost/boceto/public` — debe mostrar la página de inicio.

---

### Fase 2 — Configuración de variables de entorno

**Objetivo:** Personalizar `.env` para el entorno específico.

```
Editar .env:

APP_NAME="JM y JS Alimentos"
APP_URL=http://localhost/boceto/public
APP_LOCALE=es

# Base de datos (ya configurada para SQLite)
DB_CONNECTION=sqlite

# Sesiones y caché en BD
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Chatbot IA — obtener en https://aistudio.google.com/app/apikey
GEMINI_API_KEY=tu_clave_aqui
GEMINI_MODEL=gemini-2.5-flash
```

**Verificación del chatbot:** Abrir la aplicación y enviar un mensaje al asistente. Debe responder con información sobre la empresa.

---

### Fase 3 — Creación del usuario administrador

**Objetivo:** Tener acceso al panel de administración.

```
Opción A — Via tinker (consola interactiva):
    php artisan tinker
    >>> App\Models\User::create([
    ...     'name' => 'Administrador',
    ...     'email' => 'admin@jmjs.com',
    ...     'password' => 'admin2024',
    ...     'is_admin' => true,
    ... ]);

Opción B — Via DatabaseSeeder (si existe):
    php artisan db:seed

Opción C — Registro normal + elevación:
    1. Registrarse en /register con cualquier cuenta
    2. Via tinker: App\Models\User::find(1)->update(['is_admin' => true])
```

**Verificación:** Iniciar sesión con el admin → debe redirigir a `/admin`.

---

### Fase 4 — Modo desarrollo (frontend + backend en paralelo)

**Objetivo:** Activar el entorno de desarrollo con hot reload.

```
Opción A — Comando todo-en-uno (composer.json script "dev"):
    composer run dev
    (lanza en paralelo: artisan serve + queue:listen + pail + vite dev)

Opción B — Terminales separadas:
    Terminal 1: php artisan serve        → http://localhost:8000
    Terminal 2: npm run dev              → http://localhost:5173 (Vite HMR)
    Terminal 3: php artisan queue:listen → procesa trabajos en cola
```

**Con XAMPP activo:** No es necesario `artisan serve`. Vite sigue siendo necesario en desarrollo para HMR:
```
    npm run dev
    Acceder via: http://localhost/boceto/public
```

---

### Fase 5 — Ejecución de pruebas automatizadas

**Objetivo:** Verificar que el sistema funciona correctamente.

```
Paso 1: Limpiar configuración previa
    php artisan config:clear

Paso 2: Ejecutar toda la suite de pruebas
    php artisan test
    (o alternativamente: composer run test)

Paso 3: Ejecutar solo pruebas de Feature
    php artisan test --testsuite=Feature

Paso 4: Ejecutar solo pruebas de Unit
    php artisan test --testsuite=Unit

Paso 5: Ver reporte con cobertura de código
    php artisan test --coverage
```

**Salida esperada:**
```
   PASS  Tests\Unit\ExampleTest
   PASS  Tests\Feature\ExampleTest

   Tests:    2 passed
   Duration: X.XXs
```

---

### Fase 6 — Comandos útiles de mantenimiento

**Limpiar cachés:**
```bash
php artisan config:clear     # Limpia caché de configuración
php artisan route:clear      # Limpia caché de rutas
php artisan view:clear       # Limpia vistas Blade compiladas
php artisan cache:clear      # Limpia caché de aplicación
```

**Revertir y re-ejecutar migraciones (¡borra todos los datos!):**
```bash
php artisan migrate:fresh    # Elimina todas las tablas y las re-crea
```

**Explorar la BD interactivamente:**
```bash
php artisan tinker
>>> App\Models\User::all()
>>> App\Models\Enrollment::with('user')->get()
>>> App\Models\Contact::where('leido', false)->count()
```

**Formatear código PHP (Pint):**
```bash
./vendor/bin/pint             # Formatea todos los archivos PHP
./vendor/bin/pint app/        # Solo el directorio app/
```

**Ver todas las rutas registradas:**
```bash
php artisan route:list        # Lista completa
php artisan route:list --path=admin  # Filtrar por prefijo
```

---

## 5. Diagrama de dependencias

```
┌─────────────────────────────────────────────────────────────┐
│                    CAPA DE PRESENTACIÓN                     │
│  React 19.2.5 + Tailwind 4.0 + CSS personalizado           │
│  Compilado por Vite 7.0.7 → public/build/                  │
│  Plantillas Blade (motor built-in de Laravel)               │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│                    CAPA DE APLICACIÓN                       │
│  Laravel 12.0 (PHP ^8.2)                                    │
│  ├── Routing (web + api)                                    │
│  ├── Middleware (auth, guest, admin)                        │
│  ├── Controladores (lógica de cada pantalla)                │
│  ├── Eloquent ORM (modelos User, Enrollment, Contact)       │
│  ├── Validación de formularios                              │
│  └── Gestión de sesiones y autenticación                    │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│                    CAPA DE DATOS                            │
│  SQLite 3 — database/database.sqlite                        │
│  Tablas: users, enrollments, contacts,                      │
│           sessions, cache, jobs                             │
└─────────────────────────────────────────────────────────────┘

                    SERVICIO EXTERNO
┌─────────────────────────────────────────────────────────────┐
│  Google Gemini API (gemini-2.5-flash)                       │
│  Accedido desde: Api\ChatController                         │
│  Vía: HTTP + API Key (curl, 30s timeout)                    │
└─────────────────────────────────────────────────────────────┘
```

---

## 6. Resumen de versiones

| Componente | Versión |
|---|---|
| PHP | ^8.2 |
| Laravel Framework | ^12.0 |
| Laravel Tinker | ^2.10.1 |
| PHPUnit | ^11.5.50 |
| Faker | ^1.23 |
| Laravel Pint | ^1.24 |
| Mockery | ^1.6 |
| React | ^19.2.5 |
| React DOM | ^19.2.5 |
| Vite | ^7.0.7 |
| laravel-vite-plugin | ^2.0.0 |
| @vitejs/plugin-react | ^5.2.0 |
| Tailwind CSS | ^4.0.0 |
| concurrently | ^10.0.3 |
| Node.js recomendado | 22.x LTS o superior |
| Google Gemini | gemini-2.5-flash |
| SQLite | 3.x (incluido en PHP) |

---

*Documentación de implementación — JM y JS Alimentos — Mayo 2026*
