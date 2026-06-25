<!-- Archivo Markdown generado a partir de: Revisión de proyecto y documentación.docx -->

# Contexto completo del proyecto JM y JS Alimentos

> Nota de actualizacion 2026-06-07: este documento conserva informacion historica del prototipo y puede mencionar SQLite o modulos aun no alineados con la base LMS actual. Para el estado auditado reciente, revisar `documentacion/AUDITORIA_LMS_2026_06_07.md` y el Kanban actualizado en `documentacion/KANBAN.md`.

## 1. Resumen ejecutivo

JM y JS Alimentos es una plataforma de e-learning especializada en normativas de calidad para el sector alimentario peruano. El sistema permite a técnicos, jefes de planta, emprendedores y profesionales del rubro adquirir certificaciones en Buenas Prácticas de Manufactura (BPM), Análisis de Peligros y Puntos Críticos de Control (HACCP), ISO 9001/22000 y normas afines. El proyecto surgió al detectar la ausencia de programas de formación que combinen normativas internacionales con requisitos sanitarios peruanos【52†L42-L61】. La solución propuesta ofrece un catálogo de cursos en línea, proceso de inscripción y pago integrado, panel administrativo para gestionar usuarios y contactos, así como un chatbot basado en Gemini 2.5 Flash que atiende consultas sobre los productos y servicios【55†L13-L19】. La plataforma se implementa con Laravel 12 en el backend, React 19 + Vite para el componente de chat y Tailwind CSS para la interfaz【53†L3-L12】, utilizando una base de datos SQLite definida en el archivo de variables de entorno【69†L25-L34】.

## 2. Descripción general

### Nombre del sistema

JM y JS Alimentos – Plataforma de Capacitación en Calidad Alimentaria.

### Objetivo general

Desarrollar una plataforma de formación digital que imparta cursos certificados en BPM, HACCP e ISO orientados al sector alimentario peruano, con la finalidad de mejorar la seguridad alimentaria y la competitividad de las empresas del rubro.

### Problema que resuelve

Las empresas alimentarias en el Perú enfrentan dificultades para cumplir las normativas sanitarias debido a la escasez de programas de capacitación especializados. Las principales plataformas de e-learning ofrecen cursos generales que no cubren las reglamentaciones locales【52†L42-L61】. JM y JS Alimentos soluciona este problema al proporcionar formación específica, evaluaciones y asesoría en la implementación de sistemas de calidad, integrando normativa local y estándares internacionales.

### Público objetivo

Profesionales del sector alimentario: técnicos, jefes de planta, analistas de calidad, emprendedores y pequeñas empresas que requieren certificarse en BPM, HACCP, ISO 9001/ISO 22000 y otras normas de inocuidad.

### Sector de aplicación

Industria alimentaria peruana, con énfasis en plantas de producción, restaurantes, procesadoras y emprendimientos que buscan cumplir reglamentos sanitarios (DIGESA, BPM, HACCP).

### Beneficios

- Especialización local: cursos que combinan normativa internacional con requisitos sanitarios peruanos【52†L42-L61】.

- Accesibilidad: modalidad 100 % online, con contenidos descargables y clases autogestionadas【70†L35-L50】.

- Certificación: se emiten constancias digitales y se acompaña al estudiante durante el proceso de implementación.

- Automatización: inscripción y pago en línea, chat de soporte, panel administrativo y generación automática de matrículas【38†L11-L64】【39†L13-L55】.

- Escalabilidad: arquitectura modular basada en Laravel que facilita añadir nuevos cursos y funciones.

### Alcance funcional

El sistema permite:

1. Registro y autenticación de usuarios: los visitantes pueden crear una cuenta o iniciar sesión. El registro solicita nombre, correo electrónico, DNI, teléfono y contraseña; los datos se almacenan en la tabla users【67†L16-L19】.

2. Exploración del catálogo: página de cursos con filtros por nivel (básico, intermedio, avanzado) y tarjetas interactivas con información de precio y duración【70†L27-L50】.

3. Carrito de inscripciones y checkout: los usuarios añaden cursos al carrito mediante botones que invocan CartController@add【38†L11-L64】. En la vista de checkout se muestran los cursos seleccionados y se recopilan datos de la tarjeta para procesar el pago【71†L147-L170】.

4. Procesamiento de pago: PaymentController@pay valida la tarjeta y crea inscripciones (enrollments) por cada curso, cambiando su estado a pagado【39†L13-L55】.

5. Gestión de contacto: formulario de contacto que captura nombre, correo, tema, curso e inquietud; los datos se guardan en la tabla contacts【65†L3-L9】.

6. Panel de usuario: los usuarios autenticados acceden a “Mi Cuenta” donde visualizan sus inscripciones y estadísticas de progreso【47†L11-L16】.

7. Panel administrativo: accesible solo para administradores (is_admin), ofrece panel de estadísticas, gestión de usuarios (listar y alternar rol administrador) y gestión de contactos (marcar como leídos, eliminar)【57†L3-L7】【42†L17-L30】【43†L16-L24】.

8. Chatbot de soporte: componente React AiChat.jsx que envía consultas al endpoint /api/chat. El controlador ChatController consume la API de Google Gemini con un systemPrompt diseñado para responder en español sobre cursos y servicios【55†L13-L19】.

## 3. Objetivos

1. Incrementar la conformidad sanitaria de las empresas alimentarias a través de formación especializada y certificada.

2. Digitalizar el proceso de matrícula y pago de cursos, reduciendo la necesidad de trámites manuales.

3. Proveer un canal de soporte automático mediante un chatbot que resuelva consultas frecuentes de estudiantes y potenciales clientes.

4. Garantizar la escalabilidad y mantenibilidad del software mediante arquitectura modular, pruebas automatizadas y documentación detallada.

5. Facilitar la administración de usuarios, contactos y estadísticas mediante un panel centralizado con roles y permisos.

## 4. Arquitectura

### Arquitectura general

La solución se basa en el marco Laravel 12 que implementa el patrón Modelo-Vista-Controlador (MVC). La arquitectura se estructura en capas que separan la presentación (vistas Blade y componente React), la lógica de negocio (controladores y servicios) y el acceso a datos (modelos Eloquent). Para este proyecto se adoptaron principios de spec-driven development, generando especificaciones detalladas antes de implementar cada módulo.

#### Capas

| Capa | Descripción | Evidencia |
| --- | --- | --- |
| Presentación | Compuesta por vistas Blade (inicio.blade.php, cursos.blade.php, checkout.blade.php, mi-cuenta.blade.php, pago-exito.blade.php), layouts (layouts/app.blade.php) y el componente React AiChat.jsx. | Listado de vistas en el directorio resources/views[1]; componente React con lógica de envío de mensajes al chatbot【54†L4-L23】. |
| Controladores | Gestionan las peticiones HTTP: AuthController (registro/inicio de sesión)【45†L21-L75】, CartController (carrito)【38†L11-L64】, PaymentController (pagos)【39†L13-L55】, EnrollmentController (inscripciones)【40†L13-L37】, ContactController (contactos), MiCuentaController【47†L11-L16】, Admin\DashboardController, Admin\UserController, Admin\ContactsController【42†L17-L30】【43†L16-L24】【44†L14-L29】, y Api\ChatController【55†L13-L19】. |  |
| Modelos | Representan las tablas de la base de datos: User, Enrollment, Contact con sus atributos y relaciones. User incluye campos name, email, dni, phone, password y is_admin【67†L16-L19】; Enrollment registra user_id, course_name, level, price, status【66†L16-L23】; Contact almacena mensajes y el estado de lectura【65†L3-L9】【49†L11-L23】. |  |
| Servicios y middleware | ChatController implementa un servicio de integración con la API de Google Gemini; el middleware AdminMiddleware restringe el acceso a rutas administrativas verificando el campo is_admin del usuario【48†L13-L16】. | Middleware en app/Http/Middleware/AdminMiddleware.php y configuración de servicios en config/services.php con variables GEMINI_API_KEY y modelo gemini-2.5-flash【68†L40-L43】. |

### Arquitectura hexagonal

A pesar de ser un proyecto MVC clásico, algunos elementos se alinean con la arquitectura hexagonal (puertos y adaptadores):

- Puertos: los controladores actúan como puertos de entrada que reciben solicitudes HTTP desde la web (routes/web.php) y API (routes/api.php).

- Adaptadores: la capa de presentación (Blade y React) y el servicio de Gemini son adaptadores de salida. ChatController transforma los mensajes del usuario en un formato compatible con la API externa.

- Casos de uso: cada controlador encapsula un caso de uso: registrar usuarios, añadir cursos al carrito, procesar pagos, crear inscripciones, gestionar contactos, etc.

- Repositorios: los modelos Eloquent son repositorios que interactúan con la base de datos, ocultando consultas SQL y permitiendo pruebas aisladas.

Aun cuando no se implementó un dominio independiente con interfaces para repositorios, la separación de responsabilidades facilita la evolución hacia una arquitectura hexagonal completa si se creasen interfaces y adaptadores adicionales.

## 5. Frontends

El proyecto cuenta con un único frontend integrado en la aplicación Laravel.

| Proyecto | Ruta | Framework y versión | Propósito | Módulos principales | Roles que lo utilizan | Integraciones |
| --- | --- | --- | --- | --- | --- | --- |
| Frontend web (Blade + React) | resources/views, resources/js | Laravel Blade para páginas principales y React 19 con Vite para el chatbot【53†L3-L12】. Se utiliza Tailwind CSS para estilos y componentes UI. | Ofrecer la interfaz de usuario del sitio: página de inicio, catálogo de cursos, formulario de contacto, checkout, página de cuenta, panel admin y widget de chat. | Vistas: Inicio, Cursos, Contacto, Checkout, Mi Cuenta y Panel Admin. Componente AiChat para chat. | Visitantes, usuarios registrados y administradores. | Integración con la API de Gemini a través del endpoint /api/chat【54†L4-L23】. |

La estructura de módulos incluye menús, tarjetas de cursos con filtros, carrito de inscripciones, formularios de pago y páginas responsivas con Tailwind. El componente AiChat.jsx se monta en la plantilla principal y permite al usuario chatear en tiempo real con un asistente programado, enviando mensajes por medio de fetch al backend【54†L4-L23】.

## 6. Backends

La plataforma posee un único backend implementado en Laravel 12 que gestiona toda la lógica de negocio.

| Backend | Ruta | Framework | Arquitectura | Tecnologías | Base de datos | Responsabilidades | Servicios expuestos | Dependencias |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Backend unificado (Laravel) | Raíz del repositorio | Laravel 12 (PHP 8.2) | MVC con rutas definidas en routes/web.php y routes/api.php. Utiliza controladores para casos de uso y Eloquent para acceso a datos. | PHP 8.2, Composer, Vite, Tailwind, React para chat, PHPUnit para pruebas, Cypress para pruebas end-to-end (según plan de pruebas). | SQLite por defecto (DB_CONNECTION=sqlite en .env.example)【69†L25-L34】; la migración crea tablas users, enrollments, contacts, cache, jobs, sessions【66†L16-L23】【67†L16-L19】【65†L3-L9】. | Autenticación, registro, gestión de catálogos, carrito, pagos, inscripciones, contactos, perfil de usuario, panel admin, API de chat. | HTTP endpoints (listados en sección API), API REST para chat (/api/chat). | Dependencias externas: Google Gemini (API de lenguaje)【68†L40-L43】, Postmark/Resend/AWS SES (potenciales servicios de correo), Tailwind via Node, PHPUnit, Cypress. |

El código incluye middleware de autenticación nativa de Laravel y un middleware personalizado AdminMiddleware que verifica el rol de administrador (is_admin)【48†L13-L16】. No se identificaron microservicios ni backends adicionales como backendcrm, servicesend o servicio-ml; por lo tanto, se catalogan como pendiente de identificar si estos módulos existen en otros repositorios.

## 7. Base de datos

### Motor y configuraciones

El archivo .env.example establece DB_CONNECTION=sqlite y habilita el almacenamiento de sesiones en base de datos【69†L25-L34】. Aunque se incluyen variables comentadas para MySQL, el motor principal para las pruebas es SQLite. Laravel permite cambiar a MySQL o PostgreSQL modificando las variables de entorno.

### Esquema y tablas

| Tabla | Campos clave | Relaciones | Evidencia |
| --- | --- | --- | --- |
| users | id, name, email (único), email_verified_at, password, remember_token, created_at, updated_at, dni (nullable), phone (nullable), is_admin (boolean) | Relación de uno a muchos con enrollments (un usuario tiene muchas inscripciones). Campos adicionales dni y phone añadidos mediante migración【67†L16-L19】; campo is_admin para roles administrativos【63†L3-L5】. | Migración create_users_table.php【61†L3-L8】; migración add_is_admin_to_users_table【63†L3-L5】; migración add_dni_phone_to_users_table【67†L16-L19】. |
| enrollments | id, user_id (clave foránea), course_name, level, price (decimal 8,2), status (enum: pendiente, pagado, completado), created_at, updated_at | user_id referencia a users.id con cascadeOnDelete. | Migración create_enrollments_table.php【66†L16-L23】. |
| contacts | id, nombre, correo, tema, curso (nullable), mensaje, leido (boolean), created_at, updated_at | Sin claves foráneas. Utilizada para registrar mensajes de contacto y gestionar su lectura. | Migración create_contacts_table.php【65†L3-L9】. |
| cache/jobs/sessions | Tablas auxiliares de Laravel (cache, jobs, failed_jobs, sessions, password_reset_tokens) creadas en migraciones predeterminadas. | Soportan caché, colas de trabajos y manejo de sesiones. | Directorio database/migrations[2]. |

No se identifican procedimientos almacenados ni vistas en el repositorio. Cualquier funcionalidad avanzada de base de datos debe implementarse en controladores o servicios; en caso de ser requerida, se marca como pendiente de identificar.

## 8. Módulos funcionales

| Módulo | Objetivo | Funcionalidades | Procesos de negocio soportados | Entidades involucradas |
| --- | --- | --- | --- | --- |
| Autenticación y registro | Gestionar el acceso al sistema. | Formulario de registro (nombre, correo, DNI, teléfono, contraseña); inicio de sesión y cierre de sesión. Validaciones y redirección en función del rol【45†L21-L75】. | Proceso 1 del documento de procesos: registro y autenticación. | users |
| Catálogo de cursos | Mostrar los cursos disponibles y facilitar su selección. | Vista cursos.blade.php con filtros y tarjetas; botón “Inscribirme” que añade el curso al carrito mediante CartController@add【38†L11-L64】【70†L27-L50】. | Proceso 2: exploración y búsqueda de cursos. | enrollments (temporalmente en carrito) |
| Carrito y checkout | Permitir al usuario revisar cursos seleccionados y pagar. | Mantenimiento del carrito en la sesión; vista checkout.blade.php con lista de productos, formulario de tarjeta, cálculo de totales y botón de pago【71†L147-L170】. | Proceso 3: selección y pago de cursos. | users, enrollments |
| Procesamiento de pagos | Registrar inscripciones y actualizar su estado. | Validación de datos de tarjeta, creación de registros enrollments para cada elemento del carrito, marcado de estado “pagado” y eliminación del carrito【39†L13-L55】. | Proceso 3: confirmación del pago e inscripción. | enrollments, users |
| Contacto y asistencia | Recibir mensajes de usuarios o visitantes. | Formulario contacto.blade.php que envía información a ContactController@store; gestión en panel admin para marcar como leído o eliminar【44†L14-L29】. | Proceso 4: soporte y consultas. | contacts |
| Perfil de usuario | Mostrar información personal y cursos matriculados. | Vista mi-cuenta.blade.php con estadísticas de progreso e historial de inscripciones; controlado por MiCuentaController@index【47†L11-L16】. | Proceso 5: seguimiento de aprendizaje. | users, enrollments |
| Panel de administración | Gestionar la plataforma. | Dashboards con métricas (usuarios, administradores, inscripciones, contactos)【42†L17-L30】, listado de usuarios con opción para alternar rol de administrador【43†L16-L24】, listado de mensajes de contacto con opciones para marcar como leído y eliminar【44†L14-L29】. | Proceso 6: administración y control. | users, contacts, enrollments |
| Chatbot | Brindar asesoría automática mediante IA. | Widget React AiChat que envía mensajes a /api/chat; ChatController gestiona la conversación y se integra con la API de Google Gemini mediante una clave y modelo configurados en services.php【55†L13-L19】【68†L40-L43】. | Proceso 7: atención al cliente automatizada. | No almacena datos adicionales; usa sesión. |

## 9. Flujos de negocio

### Proceso 1 – Registro y autenticación

Inicio: un visitante accede a la plataforma y selecciona “Registrarse” o “Iniciar sesión”.
Validaciones: se verifica que el correo electrónico no esté registrado; se aplican reglas de contraseña; se valida el DNI y el teléfono【45†L21-L75】.
Flujo principal: si los datos son correctos, se crea un registro en la tabla users y se inicia la sesión; en caso de ser administrador (campo is_admin activado por un administrador en el panel) se redirige al panel admin.
Flujo alterno: si las credenciales son incorrectas, se devuelve un mensaje de error y se permanece en la vista de login.
Resultado final: usuario autenticado con sesión creada; puede navegar por el catálogo y acceder a su cuenta.

### Proceso 2 – Exploración de cursos y selección

Inicio: usuario autenticado o visitante navega por la página de cursos.
Validaciones: se filtran cursos por nivel y se muestran descripciones, precios y duración【70†L27-L50】.
Flujo principal: al hacer clic en “Inscribirme”, se llama al método CartController@add, que valida que no exista un curso duplicado en el carrito y lo añade a la sesión【38†L11-L64】.
Flujo alterno: si el curso ya se encuentra en el carrito, se muestra un mensaje de error.
Resultado final: el carrito contiene los cursos seleccionados y el usuario puede ir al checkout.

### Proceso 3 – Pago e inscripción

Inicio: el usuario accede a la vista de checkout (/checkout).
Validaciones: se verifica que el carrito no esté vacío; se valida la información de la tarjeta (número, CVV, fecha de expiración)【39†L13-L55】; se muestra el resumen de totales【71†L147-L170】.
Flujo principal: al enviar el formulario, PaymentController@pay recorre los elementos del carrito y crea un registro en la tabla enrollments por cada curso, con estado pagado【39†L13-L55】.
Flujo alterno: si el usuario ya está inscrito en un curso, se omite su creación; si los datos de la tarjeta son inválidos, se devuelve un mensaje de error.
Resultado final: se crean las inscripciones asociadas al usuario, se vacía el carrito y se redirige a la página de éxito.

### Proceso 4 – Soporte y contacto

Inicio: un usuario o visitante necesita información adicional y accede al formulario de contacto.
Validaciones: se verifican campos requeridos (nombre, correo, tema, mensaje).
Flujo principal: se crea un registro en la tabla contacts con el estado leido=false; se muestra un mensaje de confirmación al usuario.
Flujo alterno: si falta información obligatoria, se devuelven errores de validación.
Resultado final: el mensaje queda pendiente de revisión por el administrador.

### Proceso 5 – Gestión de usuarios y contactos (panel admin)

Inicio: un usuario con rol is_admin=true accede al panel admin/.
Validaciones: el middleware AdminMiddleware comprueba el rol y bloquea el acceso con error 403 si no es administrador【48†L13-L16】.
Flujo principal – Dashboard: el administrador visualiza métricas: total de usuarios, número de administradores, nuevas inscripciones y contactos no leídos【42†L17-L30】.
Flujo principal – Usuarios: en admin/users se listan los usuarios con opción para alternar su rol de administrador mediante una ruta de tipo POST/DELETE【43†L16-L24】.
Flujo principal – Contactos: en admin/contacts se listan los mensajes; puede marcarse un mensaje como leído o eliminarlo【44†L14-L29】.
Resultado final: administración de usuarios y contactos actualizada; control centralizado de la plataforma.

### Proceso 6 – Atención con IA

Inicio: el usuario hace clic en el widget de chat.
Validaciones: se comprueba que el mensaje no esté vacío.
Flujo principal: el componente React llama a /api/chat enviando un JSON con el texto del usuario y un identificador de conversación; ChatController genera un systemPrompt con información sobre la empresa y los cursos, y envía la solicitud a la API de Google Gemini con la clave configurada en services.php【55†L13-L19】【68†L40-L43】. El resultado se devuelve al frontend, que lo muestra en el chat【54†L4-L23】.
Resultado final: respuesta generada por la IA que guía al usuario sobre cursos y procesos.

## 10. Integraciones externas

- Google Gemini 2.5 Flash: se integra mediante el endpoint de generative language models; la clave de API y el modelo se definen en config/services.php【68†L40-L43】. ChatController formula un systemPrompt en español que define al asistente y los cursos disponibles【55†L13-L19】 y envía la petición HTTP a https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent. Esta integración se usa únicamente para el chatbot.

- Servicios de correo (Postmark/Resend/AWS SES): configurados en config/services.php pero no se encuentran implementaciones en el código; se consideran pendiente de identificar para futuros envíos de notificaciones.

- Pasarelas de pago: la funcionalidad usa Stripe Checkout desde `PaymentController` y `StripeService`; las ventas se crean pendientes y se confirman por retorno seguro o webhook firmado.

- Librerías: Tailwind CSS, React, Vite y otros paquetes de Node se integran mediante package.json【53†L3-L12】. PHPUnit y Cypress se usan para pruebas automatizadas según la documentación de pruebas (no se muestran en el código).

## 11. Seguridad

- Autenticación y sesiones: Laravel gestiona la autenticación con sesiones. Los usuarios se registran y los datos de la sesión se almacenan en la tabla sessions con un tiempo de expiración de 120 minutos【69†L25-L34】.

- Roles y permisos: campo is_admin en la tabla users permite diferenciar administradores y usuarios normales. El middleware AdminMiddleware controla el acceso a las rutas prefijadas con admin/ y devuelve 403 si el usuario no es administrador【48†L13-L16】.

- Validaciones de datos: cada controlador valida las entradas antes de procesarlas. Ejemplo: AuthController comprueba la unicidad del correo, la longitud de la contraseña y el formato del DNI y teléfono【45†L21-L75】; PaymentController valida la tarjeta antes de crear inscripciones【39†L13-L55】.

- Protección CSRF: Laravel incluye tokens CSRF en formularios y se verifica automáticamente en los controladores.

- Encriptación de contraseñas: las contraseñas se almacenan en la base de datos de forma hash mediante Bcrypt; el número de rondas se puede configurar en .env (por defecto 12)【69†L18-L23】.

- Cifrado de sesiones: en .env.example la opción SESSION_ENCRYPT=false implica que las sesiones no se cifran; se recomienda activarla para producción【69†L31-L34】.

- Validaciones en chat: ChatController sanitiza la entrada y utiliza un systemPrompt para evitar respuestas no deseadas, pero no se observan filtros específicos de inyección; se marca como pendiente de mejorar.

## 12. Tecnologías utilizadas

| Categoría | Herramientas/tecnologías | Evidencia |
| --- | --- | --- |
| Frontend | Laravel Blade para vistas; React 19 + Vite para el chatbot【53†L3-L12】; Tailwind CSS para estilos; HTML5; JavaScript. | package.json declara react, @vitejs/plugin-react, vite, tailwindcss【53†L3-L12】. |
| Backend | Laravel 12 (PHP 8.2); Eloquent ORM; Composer; PHPUnit para pruebas unitarias; Cypress (según plan de pruebas); PHPStan y Pint para análisis estático (mencionados en composer.json). | composer.json (no incluido íntegramente) contiene dependencias de Laravel y herramientas de testing/linting. |
| Base de datos | SQLite para desarrollo y pruebas【69†L25-L34】; opción de migrar a MySQL/PostgreSQL comentada. | .env.example define DB_CONNECTION=sqlite【69†L25-L34】. |
| Infraestructura | Servidor local con PHP; Vite para compilar assets; Node.js para gestionar paquetes; servidor de correo (no implementado). | Documentación IMPLEMENTACION.md (mencionado en el repositorio). |
| DevOps | GitHub como repositorio de control de versiones; setup scripts en Composer y npm; se menciona la posible utilización de Docker (no incluida). | composer.json y package.json muestran scripts de desarrollo. |
| IA | API de Google Gemini (modelo gemini-2.5-flash)【68†L40-L43】; utilizada para generar respuestas al usuario. | services.php configura gemini【68†L40-L43】. |

## 13. Características diferenciadoras

1. Especialización sectorial: la plataforma se enfoca en normativas de calidad alimentaria (BPM, HACCP, ISO 9001/22000) y complementa con reglamentos peruanos, diferenciándose de plataformas genéricas【52†L42-L61】.

2. Proceso de inscripción simplificado: permite seleccionar cursos, agregarlos a un carrito y pagar en una sola interfaz, generando inscripciones automáticamente【38†L11-L64】【39†L13-L55】.

3. Panel administrativo integrado: los administradores pueden gestionar usuarios, alternar roles, revisar contactos y monitorizar estadísticas en un mismo panel【42†L17-L30】【43†L16-L24】.

4. Chatbot IA: implementación de un asistente virtual con Gemini 2.5 Flash, personalizado con un prompt que comprende la oferta de cursos y consultas frecuentes【55†L13-L19】【54†L4-L23】.

5. Diseño responsivo y moderno: uso de Tailwind CSS y animaciones; la página de cursos incluye filtrado por niveles y estadísticas visuales【70†L35-L50】.

6. Implementación modular: cada función se encapsula en controladores y vistas independientes, facilitando el mantenimiento.

7. Documentación detallada: el repositorio contiene documentos de metodología, análisis de procesos, estado del arte, arquitectura y pruebas, generados mediante spec-driven development.

## 14. Evidencias técnicas

Para cada afirmación del análisis se proporcionan referencias a archivos y líneas del código fuente. A continuación se resumen las evidencias clave:

| Afirmación | Archivo y línea | Cita |
| --- | --- | --- |
| enrollments contiene los campos user_id, course_name, level, price, status con valores pendiente, pagado, completado. | database/migrations/2026_05_06_214855_create_enrollments_table.php【66†L16-L23】 | 【66†L16-L23】 |
| Se añaden los campos dni y phone a la tabla users. | database/migrations/2026_05_06_214856_add_dni_phone_to_users_table.php【67†L16-L19】 | 【67†L16-L19】 |
| Configuración de la API de Google Gemini con clave y modelo predeterminado. | config/services.php【68†L40-L43】 | 【68†L40-L43】 |
| Parámetros por defecto del entorno: uso de SQLite y sesiones en base de datos. | .env.example【69†L25-L34】 | 【69†L25-L34】 |
| Componente React envía mensajes al endpoint /api/chat y procesa la respuesta. | resources/js/components/AiChat.jsx【54†L4-L23】 | 【54†L4-L23】 |
| ChatController establece un systemPrompt con la descripción de la empresa y los cursos antes de llamar a Gemini. | app/Http/Controllers/Api/ChatController.php【55†L13-L19】 | 【55†L13-L19】 |
| CartController@add gestiona el carrito y evita duplicados en la sesión. | app/Http/Controllers/CartController.php【38†L11-L64】 | 【38†L11-L64】 |
| PaymentController@pay valida la tarjeta y crea inscripciones marcadas como pagadas. | app/Http/Controllers/PaymentController.php【39†L13-L55】 | 【39†L13-L55】 |
| ContactController@store guarda los mensajes de contacto en la base de datos. | app/Http/Controllers/ContactController.php |  |
| AdminMiddleware verifica el rol de administrador (is_admin). | app/Http/Middleware/AdminMiddleware.php【48†L13-L16】 | 【48†L13-L16】 |
| Rutas de administrador (admin/users, admin/contacts, etc.) protegidas por auth y admin middleware. | routes/web.php【57†L3-L7】 | 【57†L3-L7】 |
| Panel de estadísticas administrado por DashboardController. | app/Http/Controllers/Admin/DashboardController.php【42†L17-L30】 | 【42†L17-L30】 |
| Listado de vistas y estructura del frontend. | Directorio resources/views en GitHub[1] | [1] |
| Dependencias de React, Vite y Tailwind en el frontend. | package.json【53†L3-L12】 | 【53†L3-L12】 |
| Los cursos se muestran con filtros por nivel y permiten agregar al carrito. | resources/views/cursos.blade.php【70†L27-L50】 | 【70†L27-L50】 |

## 15. Información relevante para el registro en INDECOPI

Para el expediente de registro de software ante INDECOPI se deben incluir los siguientes elementos:

1. Identificación del titular: datos del propietario legal del software (3 integrantes: GIANCARLO ANGELO GUERREROS CORDOVA, OMAR PIERO TERBULLINO JAIME, ROGER MOISES CANCHUMANYA AVELLANEDA)

2. Descripción funcional: utilizar la sección 2 y 8 de este documento como base para describir el funcionamiento del sistema.

3. Arquitectura y diagramas: incluir diagramas de casos de uso, de clases y de secuencia; generar diagramas de procesos AS-IS y TO-BE para evidenciar la transformación digital.

4. Relaciones de la base de datos: agregar un diagrama entidad-relación que muestre tablas y relaciones (users – enrollments y contacts).

5. Código fuente y estructuras: adjuntar la estructura del repositorio y evidencias de las funciones más relevantes (ver sección 14).

6. Características innovadoras: destacar la especialización en normativas de calidad alimentaria, el chatbot integrado y el proceso automatizado de inscripción y pago.

7. Manual de usuario y manual técnico: desarrollar documentos separados basados en esta memoria, detallando procedimientos operativos y configuraciones del sistema.

8. Declaraciones de originalidad: certificar que el software fue desarrollado por el titular y no infringe derechos de terceros.

## 16. Información pendiente de identificar

Durante el análisis no se encontraron algunos elementos que podrían ser relevantes para la memoria descriptiva y el registro de software. Se listan a continuación como pendientes:

- Backends adicionales: el enunciado menciona backendcrm, servicesend y servicio-ml. Estos proyectos no existen en el repositorio actual; se requiere confirmar si se encuentran en otros repositorios o ramas.

- Procedimientos almacenados, vistas o consultas complejas: no se identificaron funciones SQL avanzadas; si existen, deben documentarse.

- Integración con pasarelas de pago reales: la funcionalidad de pago está simulada; se debe especificar qué pasarela (Culqi, Niubiz, etc.) se integrará en producción.

- Implementación de correo electrónico: el código contiene configuraciones para Postmark/Resend/AWS SES pero no se usan; se debe documentar si se enviarán correos de confirmación o notificaciones.

- Pruebas automatizadas: el repositorio posee un archivo PRUEBAS_CALIDAD.md con casos de prueba, pero no se encontraron scripts de Cypress o PHPUnit; se debe generar evidencia de ejecución.

- Dockerización e infraestructura: la documentación no incluye un Dockerfile ni scripts de despliegue; se recomienda definir un entorno de contenedores para facilitar la instalación.

- Protección de seguridad en el chat: no se aplican filtros de prompt injection ni políticas de seguridad adicionales en el chatbot; se debe analizar y reforzar esta capa.

- Licencias y términos de uso: no se identificó un archivo de licencia; se debe incluir la licencia pertinente y definir políticas de privacidad.

[1] pruebas-calidad-grupo-03/resources/views at main · ROGERCanchumanyaUC/pruebas-calidad-grupo-03 · GitHub

https://github.com/ROGERCanchumanyaUC/pruebas-calidad-grupo-03/tree/main/resources/views

[2] pruebas-calidad-grupo-03/database/migrations at main · ROGERCanchumanyaUC/pruebas-calidad-grupo-03 · GitHub

https://github.com/ROGERCanchumanyaUC/pruebas-calidad-grupo-03/tree/main/database/migrations
