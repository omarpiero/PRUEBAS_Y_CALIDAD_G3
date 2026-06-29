# Macroprocesos del Sistema — JM y JS Alimentos LMS v2.0 (Versión Técnica y Extendida)

> Documento de referencia avanzado para generar diagramas BPMN en Bizagi Modeler. Incluye un mapeo exhaustivo con el código fuente del proyecto (`app/Http/Controllers`, `routes/web.php`, `App\Models`).

---

## 1. Mapa de Macroprocesos

El sistema LMS v2.0 de JM y JS Alimentos se organiza en **8 macroprocesos** principales:

```
┌──────────────────────────────────────────────────────────────────────────┐
│                     PROCESOS ESTRATÉGICOS                                │
│  MP-01: Gestión del Catálogo de Cursos                                   │
│  MP-02: Gestión de Usuarios y Roles                                      │
│  MP-03: Configuración y Auditoría del Sistema                            │
├──────────────────────────────────────────────────────────────────────────┤
│                     PROCESOS OPERATIVOS (CORE)                           │
│  MP-04: Exploración y Selección de Cursos                                │
│  MP-05: Proceso de Compra y Pago (Stripe Checkout)                       │
│  MP-06: Habilitación y Consumo de Contenido Educativo                    │
├──────────────────────────────────────────────────────────────────────────┤
│                     PROCESOS DE SOPORTE Y SEGUIMIENTO                    │
│  MP-07: Soporte Inteligente y Comunicación (IA + Contacto)               │
│  MP-08: Monitoreo de Indicadores y Reportería Financiera                 │
└──────────────────────────────────────────────────────────────────────────┘
```

---

## 2. Detalle de cada Macroproceso a Nivel Técnico

### MP-01: Gestión del Catálogo de Cursos

**Propietario:** Administrador / Instructor  
**Objetivo:** Mantenimiento transaccional de la oferta educativa (`App\Models\Course`).  
**Ruta Principal:** `/admin/courses` (Protegido por `AdminMiddleware`)

#### Subprocesos

| ID | Subproceso | Acción Técnica y Controlador | Método HTTP |
|---|---|---|---|
| SP-01.1 | Crear Curso | Inyección de `Request` y persistencia en `Admin\CourseController@store`. El `Slug` se genera con `Str::slug()`. | `POST` |
| SP-01.2 | Editar Curso | Binding automático del modelo `$course`. Persistencia de metadatos en `Admin\CourseController@update`. | `PUT/PATCH` |
| SP-01.3 | Publicar/Despublicar | Alteración del enumerador de estado en BD vía `Admin\CourseController@publish` / `unpublish`. | `PATCH` |
| SP-01.4 | Duplicar Curso | Clonación profunda del objeto `Course` incluyendo relaciones. Implementado en `Admin\CourseController@duplicate`. | `POST` |
| SP-01.5 | Eliminar Curso | Uso del trait `SoftDeletes` en el modelo `Course` (`Admin\CourseController@destroy`). | `DELETE` |
| SP-01.6 | Gestionar Módulos | Reordenamiento JSON posicional. Orquestado por `CourseModuleController`. | `POST/PUT` |
| SP-01.7 | Gestionar Materiales | Carga multiparte (multipart/form-data) almacenada en `Storage::disk('local')` por `CourseMaterialController`. | `POST/PUT` |

---

### MP-02: Gestión de Usuarios y Roles

**Propietario:** Administrador  
**Objetivo:** Control basado en permisos de la plataforma (`App\Models\User`).

#### Subprocesos

| ID | Subproceso | Detalle Técnico |
|---|---|---|
| SP-02.1 | Registro de Usuario | Endpoint `AuthController@register`. Hashing de contraseña vía `Hash::make()` (Bcrypt). |
| SP-02.2 | Autenticación Segura | Control de throttle `throttle:5,1` en `routes/web.php` para `/login`. Auth guard de Laravel. |
| SP-02.3 | Resetear Contraseña | Manejo de olvido de contraseña a través del ResetsPasswords trait. |
| SP-02.4 | Editar Perfil | Mutación a través de `Admin\UserController@update` o el perfil del estudiante. |
| SP-02.5 | Asignar Rol Admin | Método `toggleAdmin()` en `UserController`. Modifica bandera lógica booleana. |
| SP-02.6 | Prevención Lockout | Validación crítica codificada (RNF-06): Reversión automática si se intenta degradar al último `admin`. |

---

### MP-03: Configuración y Auditoría del Sistema

**Propietario:** Administrador  
**Objetivo:** Control inmutable del historial, parametrización y control de cupones.

#### Subprocesos

| ID | Subproceso | Controlador y Mecanismo Interno |
|---|---|---|
| SP-03.1 | Configuración General | Manipulación de la tabla clave-valor mediante `SettingController`. |
| SP-03.2 | Generación de Cupones | Lógica CRUD alojada en `Admin\CouponController`. Fechas de expiración y límites de uso. |
| SP-03.3 | Auditoría Inmutable | Eventos inyectados utilizando el Helper `App\Services\AuditService::log()`. |

---

### MP-04: Exploración y Selección de Cursos

**Propietario:** Visitante / Estudiante  
**Objetivo:** Proceso público de descubrimiento de productos y carrito de compras temporal.

#### Flujo Técnico BPMN
```
[Inicio] → Visitante accede a GET /cursos
    → `CourseController@index` invoca a Eloquent ORM:
        `Course::where('status', 'publicado')->with(['category'])->get()`
    → Blade renderiza la cuadrícula de tarjetas de Tailwind CSS
    → Visitante accede a curso específico: GET /cursos/{slug}
    → [Gateway: ¿Añadir al carrito?]
        → SÍ → POST /cart/add gestionado por `CartController@add`
            → Mutación del array en Memoria/Sesión: `session()->put('cart', $cart)`
            → [Gateway: ¿Añadir Cupón de Descuento?]
                → POST /cart/apply-coupon → Verificación matemática.
[Fin]
```

---

### MP-05: Proceso de Compra y Pago (Stripe)

**Propietario:** Estudiante / Sistema  
**Objetivo:** Transacciones ACID e Idempotencia Financiera.

#### Flujo Técnico BPMN Detallado
```
[Inicio] → POST /pago → interceptado por `PaymentController@process`
    → Sistema invoca base de datos bloqueante: `DB::transaction()`
    → Se generan objetos `Sale` y `SaleItem` en memoria.
    → Delegación al proveedor externo: `$this->stripeService->createCheckoutSession()`
    → Construcción del objeto `Stripe\Checkout\Session` y redireccionamiento HTTP 303.
    → [Stripe Checkout API]
        → Usuario aprueba cargo → Pasarela emite evento.
    → [Recepción Asíncrona Webhook] → POST /stripe/webhook
        → `PaymentController@webhook` verifica firma criptográfica (HMAC).
        → [Gateway: Verificación de Idempotencia]
            → ¿Ya procesado? → Descartar y retornar 200 OK.
            → NO → Buscar modelo `Sale`, cambiar `payment_status = 'pagado'`.
                → Auto-generación iterativa de modelos `Enrollment` (Acceso al Aula).
                → `CartController` elimina variables de sesión persistentes.
[Fin]
```

---

### MP-06: Habilitación y Consumo de Contenido Educativo

**Propietario:** Estudiante  
**Objetivo:** Entrega segura de archivos y seguimiento de progreso de aprendizaje.

#### Flujo Técnico
```
[Inicio] → Estudiante autenticado en GET /mi-cuenta/cursos/{slug}
    → `StudentCourseController@show` valida objeto `Enrollment` ligado a `Auth::id()`.
    → Sistema renderiza vista del temario.
    → Estudiante demanda archivo PDF o Video: GET /mi-cuenta/.../materials/{id}/file
    → Sistema invoca método `serveFile()`.
        → Retorno seguro binario: `Storage::disk('local')->response(...)` (Protección contra hotlinking).
    → Estudiante completa módulo (checkbox).
    → Fetch asíncrono hacia POST /mi-cuenta/.../toggle
    → Eloquent actualiza tabla pivot en BD para calcular el progreso total (0-100%).
[Fin]
```

---

### MP-07: Soporte Inteligente y Comunicación

**Propietario:** Visitante / Chatbot  
**Objetivo:** Procesamiento LLM integrado al LMS para Nivel 1 de soporte.

#### Flujo Técnico
```
[Inicio] → React Component (Frontend Vite) envía JSON payload.
    → POST /api/chat hacia `App\Http\Controllers\Api\ChatController@sendMessage`
    → Controlador arma request HTTP utilizando facade nativo: `Http::withHeaders(...)`
    → Comunicación por TLS con API REST de Google Gemini Flash 2.5
    → [Gateway: ¿Try-Catch exitoso?]
        → FALLO → Logging local de error de red y fallback humano.
        → ÉXITO → Extracción algorítmica de la respuesta y renderizado en UI (Markdown React).
[Fin]
```

---

### MP-08: Monitoreo de Indicadores y Reportería Financiera

**Propietario:** Administrador / Gerencia  
**Objetivo:** Telemetría empresarial para la toma de decisiones (Capa estratégica).

#### Flujo Técnico
```
[Inicio] → Administrador accede a GET /admin
    → `DashboardController@index` ejecuta subqueries SQL optimizados:
        → `Sale::where('payment_status', 'pagado')->sum('total_amount')`
        → `Enrollment::count()`
    → Renderizado de tarjetas de KPIs y gráficas en el Panel Principal.
    → Administrador accede a GET /admin/sales
    → `SaleController@index` muestra el libro mayor (Ledger) con DataTables.
    → Filtros por fechas y estado de Stripe.
[Fin]
```
