# Pruebas de Calidad — JM y JS Alimentos
## Plataforma de Capacitación en Línea

---

## 1. Estrategia General de Pruebas

Las pruebas de calidad del sistema se organizan en dos enfoques complementarios:

| Enfoque | Perspectiva | Conocimiento del código |
|---|---|---|
| **Caja Negra** | Comportamiento externo del sistema | No requerido |
| **Caja Blanca** | Lógica interna, caminos de ejecución | Requerido |

**Entorno de pruebas (phpunit.xml):**
- Base de datos: SQLite en memoria (`:memory:`) — se crea y destruye en cada ejecución
- Sesiones: Driver `array` — aisladas por test
- Caché: Driver `array` — sin persistencia
- Colas: `sync` — ejecución inmediata sin workers
- Rondas bcrypt: `4` — hashing acelerado para no ralentizar los tests

---

## 2. Pruebas de Caja Negra

Las pruebas de caja negra validan el comportamiento del sistema desde la perspectiva del usuario final, sin conocer el código fuente. Se definen entradas, acciones y salidas esperadas.

### Nomenclatura
- **PCN** = Prueba de Caja Negra
- Formato: `PCN-[MÓDULO]-[número]`

---

### 2.1 Módulo: Autenticación (AUTH)

#### PCN-AUTH-01 — Inicio de sesión con credenciales válidas

| Campo | Detalle |
|---|---|
| **Precondición** | Existe un usuario registrado con `email: test@ejemplo.com`, `password: clave1234` |
| **Entrada** | Email: `test@ejemplo.com` / Contraseña: `clave1234` |
| **Acción** | `POST /login` |
| **Salida esperada** | Redirección a `/mi-cuenta` (usuario normal) o `/admin` (administrador) |
| **Código HTTP** | 302 Redirect |
| **Proceso relacionado** | Flujo de autenticación de estudiante |

#### PCN-AUTH-02 — Inicio de sesión con contraseña incorrecta

| Campo | Detalle |
|---|---|
| **Precondición** | Existe un usuario con `email: test@ejemplo.com` |
| **Entrada** | Email: `test@ejemplo.com` / Contraseña: `incorrecta` |
| **Acción** | `POST /login` |
| **Salida esperada** | Regresa al formulario de login con error: _"Correo o contraseña incorrectos."_ |
| **Código HTTP** | 302 Redirect (back con errores) |
| **Proceso relacionado** | Validación de credenciales |

#### PCN-AUTH-03 — Inicio de sesión con email inexistente

| Campo | Detalle |
|---|---|
| **Precondición** | No existe ningún usuario con el email ingresado |
| **Entrada** | Email: `noexiste@ejemplo.com` / Contraseña: `cualquiera` |
| **Acción** | `POST /login` |
| **Salida esperada** | Error: _"Correo o contraseña incorrectos."_ |
| **Código HTTP** | 302 Redirect (back con errores) |
| **Proceso relacionado** | Validación de credenciales |

#### PCN-AUTH-04 — Registro con datos válidos completos

| Campo | Detalle |
|---|---|
| **Precondición** | No existe ningún usuario con el email a registrar |
| **Entrada** | Nombre: `Juan Quispe` / Email: `juan@ejemplo.com` / DNI: `12345678` / Teléfono: `987654321` / Contraseña: `segura2024` / Confirmación: `segura2024` |
| **Acción** | `POST /register` |
| **Salida esperada** | Usuario creado, sesión iniciada, redirección a `/mi-cuenta` |
| **Código HTTP** | 302 Redirect |
| **Proceso relacionado** | Creación de cuenta de estudiante |

#### PCN-AUTH-05 — Registro con email ya registrado

| Campo | Detalle |
|---|---|
| **Precondición** | Ya existe un usuario con `email: juan@ejemplo.com` |
| **Entrada** | Email: `juan@ejemplo.com` (duplicado) |
| **Acción** | `POST /register` |
| **Salida esperada** | Error de validación: _"Este correo ya está registrado."_ |
| **Código HTTP** | 422 / 302 Redirect con errores |
| **Proceso relacionado** | Validación de unicidad de correo |

#### PCN-AUTH-06 — Registro con contraseña menor a 8 caracteres

| Campo | Detalle |
|---|---|
| **Precondición** | Ninguna |
| **Entrada** | Contraseña: `abc123` (6 caracteres) |
| **Acción** | `POST /register` |
| **Salida esperada** | Error: _"La contraseña debe tener al menos 8 caracteres."_ |
| **Código HTTP** | 422 / 302 Redirect con errores |
| **Proceso relacionado** | Validación de seguridad de contraseña |

#### PCN-AUTH-07 — Registro con contraseñas que no coinciden

| Campo | Detalle |
|---|---|
| **Precondición** | Ninguna |
| **Entrada** | Contraseña: `segura2024` / Confirmación: `diferente2024` |
| **Acción** | `POST /register` |
| **Salida esperada** | Error: _"Las contraseñas no coinciden."_ |
| **Código HTTP** | 422 / 302 Redirect con errores |
| **Proceso relacionado** | Validación de confirmación de contraseña |

#### PCN-AUTH-08 — Cierre de sesión

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario autenticado activo |
| **Entrada** | Token CSRF válido |
| **Acción** | `POST /logout` |
| **Salida esperada** | Sesión destruida, redirección a `/` (inicio) |
| **Código HTTP** | 302 Redirect |
| **Proceso relacionado** | Gestión segura de sesiones |

#### PCN-AUTH-09 — Acceso a login siendo ya autenticado (middleware guest)

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario ya autenticado |
| **Entrada** | Ninguna |
| **Acción** | `GET /login` |
| **Salida esperada** | Redirección (no muestra el formulario de login) |
| **Código HTTP** | 302 Redirect |
| **Proceso relacionado** | Middleware `guest` |

---

### 2.2 Módulo: Carrito de Compras (CART)

#### PCN-CART-01 — Agregar un curso al carrito

| Campo | Detalle |
|---|---|
| **Precondición** | Ninguna (no requiere autenticación) |
| **Entrada** | `course_name: "BPM en Industria Alimentaria"`, `level: "Básico"`, `price: 350` |
| **Acción** | `POST /cart/add` (AJAX) |
| **Salida esperada** | JSON: `{"ok": true, "msg": "...", "count": 1}` |
| **Código HTTP** | 200 |
| **Proceso relacionado** | Gestión del carrito de compras |

#### PCN-CART-02 — Agregar el mismo curso dos veces

| Campo | Detalle |
|---|---|
| **Precondición** | El curso `"BPM en Industria Alimentaria"` ya está en el carrito |
| **Entrada** | `course_name: "BPM en Industria Alimentaria"`, `level: "Básico"`, `price: 350` |
| **Acción** | `POST /cart/add` (AJAX) |
| **Salida esperada** | JSON: `{"ok": false, "msg": "Este curso ya está en tu carrito."}` |
| **Código HTTP** | 422 |
| **Proceso relacionado** | Validación de duplicados en carrito |

#### PCN-CART-03 — Eliminar un curso del carrito

| Campo | Detalle |
|---|---|
| **Precondición** | El carrito tiene al menos un curso |
| **Entrada** | `course_name: "BPM en Industria Alimentaria"` |
| **Acción** | `POST /cart/remove` (AJAX) |
| **Salida esperada** | JSON con `count` decrementado en 1 |
| **Código HTTP** | 200 |
| **Proceso relacionado** | Gestión del carrito de compras |

#### PCN-CART-04 — Ver el checkout sin estar autenticado

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario no autenticado |
| **Entrada** | Ninguna |
| **Acción** | `GET /checkout` |
| **Salida esperada** | Redirección a `/login` |
| **Código HTTP** | 302 Redirect |
| **Proceso relacionado** | Middleware `auth` en rutas protegidas |

#### PCN-CART-05 — Ver el checkout con usuario autenticado

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario autenticado, carrito con al menos un curso |
| **Entrada** | Ninguna |
| **Acción** | `GET /checkout` |
| **Salida esperada** | Página de checkout con resumen del carrito y totales calculados |
| **Código HTTP** | 200 |
| **Proceso relacionado** | Cálculo de subtotal, IGV y total |

---

### 2.3 Módulo: Pagos e Inscripciones (PAY)

#### PCN-PAY-01 — Procesar pago con datos de tarjeta válidos

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario autenticado, carrito con al menos un curso |
| **Entrada** | `card_name: "Juan Quispe"`, `card_number: "1234567890123456"`, `card_exp: "12/26"`, `card_cvc: "123"` |
| **Acción** | `POST /pago` |
| **Salida esperada** | Inscripciones creadas con `status: "pagado"`, carrito vacío, redirección a `/pago/exito` |
| **Código HTTP** | 302 Redirect |
| **Proceso relacionado** | Creación automática de inscripciones al pagar |

#### PCN-PAY-02 — Procesar pago con carrito vacío

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario autenticado, carrito vacío |
| **Entrada** | Datos de tarjeta válidos |
| **Acción** | `POST /pago` |
| **Salida esperada** | Redirección a `/cursos` con mensaje de estado |
| **Código HTTP** | 302 Redirect |
| **Proceso relacionado** | Validación de carrito antes del pago |

#### PCN-PAY-03 — Procesar pago con número de tarjeta menor a 16 dígitos

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario autenticado, carrito con cursos |
| **Entrada** | `card_number: "123456"` (6 dígitos) |
| **Acción** | `POST /pago` |
| **Salida esperada** | Error: _"El número debe tener al menos 16 dígitos."_ |
| **Código HTTP** | 422 / 302 con errores |
| **Proceso relacionado** | Validación de datos de tarjeta |

#### PCN-PAY-04 — Procesar pago con CVC menor a 3 dígitos

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario autenticado, carrito con cursos |
| **Entrada** | `card_cvc: "12"` (2 dígitos) |
| **Acción** | `POST /pago` |
| **Salida esperada** | Error: _"El CVC debe tener al menos 3 dígitos."_ |
| **Código HTTP** | 422 / 302 con errores |
| **Proceso relacionado** | Validación de datos de tarjeta |

#### PCN-PAY-05 — Intentar inscribirse en un curso ya adquirido

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario ya tiene inscripción en `"BPM en Industria Alimentaria"`, mismo curso en carrito |
| **Entrada** | Datos de tarjeta válidos |
| **Acción** | `POST /pago` |
| **Salida esperada** | El curso duplicado se omite; solo se crean inscripciones nuevas |
| **Código HTTP** | 302 Redirect a `/pago/exito` |
| **Proceso relacionado** | Prevención de inscripciones duplicadas |

---

### 2.4 Módulo: Formulario de Contacto (CONT)

#### PCN-CONT-01 — Enviar formulario con todos los campos válidos

| Campo | Detalle |
|---|---|
| **Precondición** | Ninguna |
| **Entrada** | `nombre: "María López"`, `correo: "maria@ejemplo.com"`, `tema: "consultoría"`, `mensaje: "Necesito información sobre diagnósticos."` |
| **Acción** | `POST /contacto/enviar` (AJAX) |
| **Salida esperada** | JSON: `{"ok": true}`, registro guardado en BD con `leido: false` |
| **Código HTTP** | 200 |
| **Proceso relacionado** | Envío asíncrono del formulario de contacto |

#### PCN-CONT-02 — Enviar formulario con correo inválido

| Campo | Detalle |
|---|---|
| **Precondición** | Ninguna |
| **Entrada** | `correo: "no-es-un-correo"` |
| **Acción** | `POST /contacto/enviar` |
| **Salida esperada** | Error de validación de formato de email |
| **Código HTTP** | 422 |
| **Proceso relacionado** | Validación del formulario de contacto |

#### PCN-CONT-03 — Enviar mensaje que excede 2000 caracteres

| Campo | Detalle |
|---|---|
| **Precondición** | Ninguna |
| **Entrada** | `mensaje:` texto de 2001+ caracteres |
| **Acción** | `POST /contacto/enviar` |
| **Salida esperada** | Error de validación: mensaje demasiado largo |
| **Código HTTP** | 422 |
| **Proceso relacionado** | Validación de longitud máxima |

---

### 2.5 Módulo: Panel de Administración (ADMIN)

#### PCN-ADMIN-01 — Acceso al panel admin sin autenticación

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario no autenticado |
| **Entrada** | Ninguna |
| **Acción** | `GET /admin` |
| **Salida esperada** | Acceso denegado (error 403 o redirección a login) |
| **Código HTTP** | 403 o 302 |
| **Proceso relacionado** | Middleware de control de acceso |

#### PCN-ADMIN-02 — Acceso al panel admin con usuario regular (no admin)

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario autenticado con `is_admin: false` |
| **Entrada** | Ninguna |
| **Acción** | `GET /admin` |
| **Salida esperada** | Error 403: _"Acceso restringido."_ |
| **Código HTTP** | 403 |
| **Proceso relacionado** | Middleware AdminMiddleware |

#### PCN-ADMIN-03 — Acceso al panel admin con usuario administrador

| Campo | Detalle |
|---|---|
| **Precondición** | Usuario autenticado con `is_admin: true` |
| **Entrada** | Ninguna |
| **Acción** | `GET /admin` |
| **Salida esperada** | Dashboard con estadísticas correctas |
| **Código HTTP** | 200 |
| **Proceso relacionado** | Dashboard de administración |

#### PCN-ADMIN-04 — Alternar rol de administrador de un usuario

| Campo | Detalle |
|---|---|
| **Precondición** | Admin autenticado, existe usuario con `is_admin: false` |
| **Entrada** | ID del usuario destino |
| **Acción** | `PATCH /admin/users/{id}/toggle` |
| **Salida esperada** | El campo `is_admin` cambia de `false` a `true` (o viceversa) |
| **Código HTTP** | 302 Redirect |
| **Proceso relacionado** | Toggle de estado de administrador |

#### PCN-ADMIN-05 — Marcar mensaje de contacto como leído

| Campo | Detalle |
|---|---|
| **Precondición** | Admin autenticado, existe mensaje con `leido: false` |
| **Entrada** | ID del mensaje |
| **Acción** | `PATCH /admin/contacts/{id}/read` |
| **Salida esperada** | El campo `leido` cambia a `true` |
| **Código HTTP** | 302 Redirect |
| **Proceso relacionado** | Gestión de mensajes en panel admin |

#### PCN-ADMIN-06 — Eliminar mensaje de contacto

| Campo | Detalle |
|---|---|
| **Precondición** | Admin autenticado, existe mensaje de contacto |
| **Entrada** | ID del mensaje |
| **Acción** | `DELETE /admin/contacts/{id}` |
| **Salida esperada** | Mensaje eliminado de la base de datos |
| **Código HTTP** | 302 Redirect |
| **Proceso relacionado** | Eliminación de mensajes de contacto |

---

### 2.6 Módulo: Chatbot IA (CHAT)

#### PCN-CHAT-01 — Enviar mensaje válido al chatbot

| Campo | Detalle |
|---|---|
| **Precondición** | Variable `GEMINI_API_KEY` configurada en `.env` |
| **Entrada** | JSON: `{"message": "¿Cuáles son los cursos disponibles?"}` |
| **Acción** | `POST /api/chat` |
| **Salida esperada** | JSON: `{"reply": "<respuesta de la IA sobre cursos>"}` |
| **Código HTTP** | 200 |
| **Proceso relacionado** | Feedback automatizado con IA |

#### PCN-CHAT-02 — Enviar mensaje sin la clave de Gemini configurada

| Campo | Detalle |
|---|---|
| **Precondición** | `GEMINI_API_KEY` no configurada o vacía |
| **Entrada** | JSON: `{"message": "Hola"}` |
| **Acción** | `POST /api/chat` |
| **Salida esperada** | JSON: `{"reply": "El asistente no tiene configurada la clave de Gemini."}` |
| **Código HTTP** | 500 |
| **Proceso relacionado** | Manejo de errores del chatbot IA |

#### PCN-CHAT-03 — Enviar mensaje que excede 2000 caracteres

| Campo | Detalle |
|---|---|
| **Precondición** | Ninguna |
| **Entrada** | JSON con `message` de 2001+ caracteres |
| **Acción** | `POST /api/chat` |
| **Salida esperada** | Error de validación |
| **Código HTTP** | 422 |
| **Proceso relacionado** | Validación del endpoint de IA |

---

## 3. Pruebas de Caja Blanca

Las pruebas de caja blanca verifican los caminos de ejecución internos del código: condiciones lógicas, operaciones de base de datos, gestión de sesiones y relaciones entre componentes.

### Nomenclatura
- **PCB** = Prueba de Caja Blanca
- Formato: `PCB-[MÓDULO]-[número]`

---

### 3.1 Módulo: Sesiones y Estado (SES)

#### PCB-SES-01 — El carrito persiste en la sesión correctamente

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `CartController::add()` → `session()->get('cart', [])` → append → `session()->put('cart', $cart)` |
| **Condición verificada** | El array `$cart` en sesión contiene el curso recién agregado |
| **Dato verificado** | `session('cart')` retorna array con la estructura `['course_name', 'level', 'price']` |
| **Proceso relacionado** | Gestión del carrito de compras (sesión PHP) |

#### PCB-SES-02 — La sesión se regenera al iniciar sesión

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `AuthController::login()` → `Auth::attempt()` exitoso → `$request->session()->regenerate()` |
| **Condición verificada** | El ID de sesión antes y después del login es distinto |
| **Dato verificado** | Prevención de session fixation attacks |
| **Proceso relacionado** | Seguridad de sesiones en autenticación |

#### PCB-SES-03 — La sesión se invalida al cerrar sesión

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `AuthController::logout()` → `Auth::logout()` → `session()->invalidate()` → `session()->regenerateToken()` |
| **Condición verificada** | Después del logout, `session('cart')` es null y el usuario no está autenticado |
| **Dato verificado** | `auth()->check()` retorna `false` post-logout |
| **Proceso relacionado** | Gestión segura de sesiones |

#### PCB-SES-04 — El carrito se vacía correctamente tras el pago

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `PaymentController::process()` → crear enrollments → `session()->forget('cart')` |
| **Condición verificada** | `session('cart')` retorna `null` o `[]` después del pago exitoso |
| **Dato verificado** | Badge del carrito muestra `0` tras el pago |
| **Proceso relacionado** | Creación automática de inscripciones al pagar |

---

### 3.2 Módulo: Lógica de Negocio (BIZ)

#### PCB-BIZ-01 — Prevención de duplicados en el carrito (condición `in_array`)

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `CartController::add()` → bucle sobre `$cart` verificando `$item['course_name'] === $courseName` |
| **Condición verificada** | Si el curso ya existe, retorna `422` sin modificar el carrito |
| **Línea de código crítica** | La condición que compara `course_name` dentro del array de sesión |
| **Proceso relacionado** | Validación de duplicados en carrito |

#### PCB-BIZ-02 — Prevención de inscripciones duplicadas en el pago

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `PaymentController::process()` → `foreach($cart)` → `Enrollment::where('user_id', id)->where('course_name', name)->exists()` |
| **Condición verificada** | `true` en `exists()` omite el `Enrollment::create()` para ese item |
| **Dato verificado** | No se crea un segundo registro en `enrollments` para la misma combinación usuario+curso |
| **Proceso relacionado** | Prevención de inscripciones duplicadas |

#### PCB-BIZ-03 — Redirección diferenciada post-login (admin vs. usuario)

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `AuthController::login()` → `Auth::attempt()` → `if ($request->user()->is_admin)` → redirect distinto |
| **Condición verificada** | Admin → `admin.dashboard`; Usuario normal → `mi-cuenta` |
| **Dato verificado** | El cast `boolean` del campo `is_admin` retorna `true`/`false` correctamente |
| **Proceso relacionado** | Flujo de autenticación diferenciado por rol |

#### PCB-BIZ-04 — Cálculo del total en checkout (subtotal + IGV 18%)

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `CartController::index()` → suma de `price` de todos los items → `total = subtotal * 1.18` |
| **Condición verificada** | Para un curso de S/350, el total mostrado es S/413.00 (IGV = S/63.00) |
| **Dato verificado** | Los valores pasados a la vista son aritméticamente correctos |
| **Proceso relacionado** | Cálculo automático de IGV |

#### PCB-BIZ-05 — Desbloqueo de logros según cantidad de inscripciones

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | Vista `mi-cuenta.blade.php` → evaluación de condiciones sobre `$enrollments` |
| **Condición verificada** | Con 0 inscripciones: todos los logros en gris. Con 1: "Primer Paso" activo. Con 3+: "Aprendiz Dedicado" activo |
| **Dato verificado** | Las condiciones son `>=`, no `==`, por lo que persisten al agregar más cursos |
| **Proceso relacionado** | Desbloqueo automático de logros |

---

### 3.3 Módulo: Base de Datos e Integridad (DB)

#### PCB-DB-01 — Unicidad del email en la tabla `users`

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `AuthController::register()` → `validate(['email' => 'unique:users'])` |
| **Condición verificada** | El registro falla con error de validación antes de llegar a `User::create()` |
| **Dato verificado** | No se ejecuta ningún `INSERT` cuando el email ya existe |
| **Proceso relacionado** | Validación de unicidad en registro |

#### PCB-DB-02 — Eliminación en cascada de inscripciones al borrar usuario

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | Migración `create_enrollments_table` → `$table->foreignId('user_id')->constrained()->cascadeOnDelete()` |
| **Condición verificada** | Al eliminar un usuario, sus inscripciones se eliminan automáticamente |
| **Dato verificado** | `Enrollment::where('user_id', $userId)->count()` retorna `0` después de eliminar el usuario |
| **Proceso relacionado** | Integridad referencial de la base de datos |

#### PCB-DB-03 — Valor por defecto del campo `status` en `enrollments`

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `Enrollment::create(['course_name'=>..., 'level'=>..., 'price'=>...])` sin especificar `status` |
| **Condición verificada** | El campo `status` en la BD tiene valor `"pendiente"` |
| **Dato verificado** | El valor por defecto del enum se aplica a nivel de base de datos |
| **Proceso relacionado** | Creación de inscripciones directas |

#### PCB-DB-04 — Valor por defecto del campo `leido` en `contacts`

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `Contact::create($data)` sin especificar `leido` |
| **Condición verificada** | El campo `leido` en la BD tiene valor `false` (0) |
| **Dato verificado** | Los mensajes nuevos aparecen como "no leídos" en el panel admin |
| **Proceso relacionado** | Contador de mensajes no leídos en admin |

#### PCB-DB-05 — Paginación de usuarios en el panel admin (20 por página)

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `Admin\UserController::index()` → `User::latest()->paginate(20)` |
| **Condición verificada** | Con 25 usuarios, la primera página muestra 20 y la segunda muestra 5 |
| **Dato verificado** | El objeto `LengthAwarePaginator` retorna `perPage() === 20` |
| **Proceso relacionado** | Listado de usuarios en administración |

---

### 3.4 Módulo: Middleware y Control de Acceso (MW)

#### PCB-MW-01 — AdminMiddleware bloquea usuarios no autenticados

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `AdminMiddleware::handle()` → `$request->user()` retorna `null` → `abort(403)` |
| **Condición verificada** | La ruta devuelve 403 sin llegar al controlador |
| **Dato verificado** | El controlador admin nunca se instancia para usuarios no autenticados |
| **Proceso relacionado** | Middleware de control de acceso |

#### PCB-MW-02 — AdminMiddleware bloquea usuarios autenticados sin rol admin

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `AdminMiddleware::handle()` → `$request->user()` retorna usuario → `$request->user()->is_admin` es `false` → `abort(403)` |
| **Condición verificada** | El campo `is_admin` cast como `boolean` evalúa correctamente `false` |
| **Dato verificado** | Un usuario con `is_admin = 0` en BD no puede acceder a ninguna ruta `/admin/*` |
| **Proceso relacionado** | Middleware de control de acceso |

#### PCB-MW-03 — Middleware `auth` protege la ruta `/checkout`

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `routes/web.php` → grupo con `middleware('auth')` → `GET /checkout` |
| **Condición verificada** | Sin sesión activa, la petición redirige a `/login` |
| **Dato verificado** | El middleware de Laravel redirige antes de ejecutar `CartController::index()` |
| **Proceso relacionado** | Protección de rutas del estudiante |

---

### 3.5 Módulo: Relaciones entre Modelos (REL)

#### PCB-REL-01 — Relación User → Enrollments (hasMany)

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `$user->enrollments()->get()` → consulta `SELECT * FROM enrollments WHERE user_id = ?` |
| **Condición verificada** | Solo se retornan inscripciones del usuario autenticado, no las de otros |
| **Dato verificado** | `$enrollments->pluck('user_id')->unique()` contiene solo el ID del usuario |
| **Proceso relacionado** | Cálculo automático de estadísticas en Mi Cuenta |

#### PCB-REL-02 — Relación Enrollment → User (belongsTo)

| Campo | Detalle |
|---|---|
| **Camino de ejecución** | `Enrollment::with('user')->latest()->take(6)->get()` en el dashboard |
| **Condición verificada** | Cada enrollment en la colección tiene un objeto `user` con `name` y `email` |
| **Dato verificado** | No se producen N+1 queries gracias al eager loading `with('user')` |
| **Proceso relacionado** | Dashboard de administración |

---

## 4. Verificación de Nombres según las PPT

La siguiente tabla relaciona los nombres de las funcionalidades según la documentación del proyecto con los identificadores de prueba correspondientes:

| Nombre en documentación | ID Caja Negra | ID Caja Blanca |
|---|---|---|
| Flujo de autenticación de estudiante | PCN-AUTH-01, PCN-AUTH-08 | PCB-SES-02, PCB-SES-03 |
| Validación de credenciales | PCN-AUTH-02, PCN-AUTH-03 | — |
| Creación de cuenta de estudiante | PCN-AUTH-04, PCN-AUTH-05, PCN-AUTH-06, PCN-AUTH-07 | PCB-DB-01 |
| Middleware `guest` | PCN-AUTH-09 | — |
| Gestión del carrito de compras (sesión PHP) | PCN-CART-01, PCN-CART-02, PCN-CART-03 | PCB-SES-01, PCB-BIZ-01 |
| Middleware `auth` en rutas protegidas | PCN-CART-04 | PCB-MW-03 |
| Cálculo automático de IGV | PCN-CART-05 | PCB-BIZ-04 |
| Creación automática de inscripciones al pagar | PCN-PAY-01 | PCB-SES-04 |
| Validación de carrito antes del pago | PCN-PAY-02 | — |
| Validación de datos de tarjeta | PCN-PAY-03, PCN-PAY-04 | — |
| Prevención de inscripciones duplicadas | PCN-PAY-05 | PCB-BIZ-02 |
| Envío asíncrono del formulario de contacto | PCN-CONT-01, PCN-CONT-02, PCN-CONT-03 | PCB-DB-04 |
| Middleware de control de acceso (AdminMiddleware) | PCN-ADMIN-01, PCN-ADMIN-02 | PCB-MW-01, PCB-MW-02 |
| Dashboard de administración | PCN-ADMIN-03 | PCB-REL-02 |
| Toggle de estado de administrador | PCN-ADMIN-04 | PCB-BIZ-03 |
| Gestión de mensajes en panel admin | PCN-ADMIN-05, PCN-ADMIN-06 | PCB-DB-04 |
| Feedback automatizado con IA | PCN-CHAT-01, PCN-CHAT-02, PCN-CHAT-03 | — |
| Desbloqueo automático de logros | — | PCB-BIZ-05 |
| Integridad referencial de la BD | — | PCB-DB-02, PCB-DB-03 |
| Paginación en administración | — | PCB-DB-05 |
| Relaciones entre modelos (ORM) | — | PCB-REL-01, PCB-REL-02 |

---

## 5. Relación de Pruebas con los Procesos Automatizados

| Proceso automatizado | Pruebas que lo cubren |
|---|---|
| **Sesión-Based Cart** — carrito en `session()` | PCN-CART-01, PCN-CART-02, PCN-CART-03, PCB-SES-01, PCB-BIZ-01 |
| **Creación automática de inscripciones al pagar** | PCN-PAY-01, PCN-PAY-05, PCB-SES-04, PCB-BIZ-02 |
| **Cálculo de estadísticas en Mi Cuenta** | PCB-BIZ-05, PCB-REL-01 |
| **Desbloqueo automático de logros** | PCB-BIZ-05 |
| **Contador de mensajes no leídos en admin** | PCN-ADMIN-05, PCB-DB-04 |
| **Formulario de contacto con campos condicionales** | PCN-CONT-01, PCN-CONT-02 |
| **Envío asíncrono del formulario de contacto** | PCN-CONT-01 |
| **Toggle de estado de administrador** | PCN-ADMIN-04, PCB-BIZ-03 |
| **Middleware de control de acceso** | PCN-ADMIN-01, PCN-ADMIN-02, PCN-AUTH-09, PCN-CART-04, PCB-MW-01, PCB-MW-02, PCB-MW-03 |
| **Feedback automatizado con IA (Gemini)** | PCN-CHAT-01, PCN-CHAT-02, PCN-CHAT-03 |

---

## 6. Explicación del Feedback Automatizado con IA

### 6.1 ¿Qué es y para qué sirve?

La plataforma integra un **chatbot de inteligencia artificial** basado en **Google Gemini** (modelo `gemini-2.5-flash`) que actúa como asistente virtual de JM y JS Alimentos. Su propósito es:

- Responder consultas frecuentes sobre los cursos disponibles
- Orientar al usuario en el proceso de inscripción y pago
- Brindar información institucional (horarios, contacto, metodología)
- Reducir la carga del equipo administrativo en consultas repetitivas

### 6.2 Arquitectura del chatbot

```
Usuario (frontend)
    │
    │  { "message": "¿Qué cursos tienen de HACCP?" }
    ▼
POST /api/chat  (routes/api.php)
    │
    ▼
Api\ChatController::handleChat()
    │
    ├── Valida: message requerido, max 2000 caracteres
    ├── Verifica: config('services.gemini.key') está configurado
    │
    ▼
HTTP POST → googleapis.com/v1beta/models/gemini-2.5-flash:generateContent
    │
    │  Parámetros enviados:
    │  - temperature: 0.7  (respuestas variadas pero coherentes)
    │  - maxOutputTokens: 500  (respuestas concisas)
    │  - systemInstruction: prompt de contexto empresarial
    │  - contents: [{ "role": "user", "parts": [{ "text": mensaje }] }]
    │
    ▼
Respuesta JSON de Gemini
    │
    ▼
{ "reply": "Tenemos el curso HACCP en Plantas de Alimentos a S/420..." }
    │
    ▼
Frontend (componente React) muestra la respuesta
```

### 6.3 System Prompt (prompt de contexto)

El chatbot recibe un *system prompt* predefinido que lo contextualiza en el negocio. Este prompt le indica:
- Nombre y rubro de la empresa
- Cursos disponibles con precios y niveles
- Información de contacto y ubicación
- Instrucciones de tono (profesional, claro, conciso)
- Limitaciones (solo responder sobre temas relacionados con la empresa)

Esto garantiza que el chatbot no divague ni proporcione información irrelevante.

### 6.4 Manejo de errores del chatbot

El sistema contempla tres escenarios de fallo con mensajes específicos al usuario:

| Escenario | Respuesta al usuario | Código HTTP |
|---|---|---|
| Clave Gemini no configurada en `.env` | _"El asistente no tiene configurada la clave de Gemini."_ | 500 |
| Gemini API retorna error o timeout | _"Hubo un error al conectar con Google Gemini. Por favor, intenta de nuevo."_ | 500 |
| Error inesperado del servidor | _"Ocurrió un error inesperado al procesar tu solicitud."_ | 500 |
| Mensaje vacío o mayor a 2000 caracteres | Error de validación | 422 |

### 6.5 Configuración requerida

En el archivo `.env`:

```env
GEMINI_API_KEY=tu_clave_de_google_ai_studio
GEMINI_MODEL=gemini-2.5-flash
```

En `config/services.php`:

```php
'gemini' => [
    'key'   => env('GEMINI_API_KEY'),
    'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
],
```

### 6.6 Pruebas del chatbot IA (resumen)

| ID | Escenario | Resultado esperado |
|---|---|---|
| PCN-CHAT-01 | Consulta válida con API configurada | Respuesta coherente de la IA |
| PCN-CHAT-02 | API key ausente | Mensaje de error descriptivo (no crash) |
| PCN-CHAT-03 | Mensaje de más de 2000 caracteres | Error de validación 422 |

La prueba PCN-CHAT-01 es la más crítica: valida que el ciclo completo frontend → backend → Gemini → respuesta funcione de extremo a extremo sin errores.

---

## 7. Resumen de Cobertura de Pruebas

| Módulo | Caja Negra | Caja Blanca | Total |
|---|---|---|---|
| Autenticación (AUTH) | 9 | 3 | 12 |
| Carrito (CART) | 5 | 2 | 7 |
| Pagos (PAY) | 5 | 1 | 6 |
| Contacto (CONT) | 3 | 1 | 4 |
| Administración (ADMIN) | 6 | 5 | 11 |
| Chatbot IA (CHAT) | 3 | 0 | 3 |
| Sesiones (SES) | — | 4 | 4 |
| Lógica de negocio (BIZ) | — | 5 | 5 |
| Base de datos (DB) | — | 5 | 5 |
| Relaciones (REL) | — | 2 | 2 |
| **TOTAL** | **31** | **28** | **59** |

---

*Documentación de pruebas de calidad — JM y JS Alimentos — Mayo 2026*
