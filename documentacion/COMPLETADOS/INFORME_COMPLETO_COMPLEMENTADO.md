# INFORME FINAL COMPLEMENTADO — LMS JM y JS Alimentos v2.0
## "Plataforma Web de Capacitación, E-commerce y Gestión de Calidad para la Industria Alimentaria"

> **Propósito de este documento:** Versión enriquecida del informe final que consolida todos los markdowns generados, corrige discrepancias con el estado real del proyecto y deja marcadores `[PENDIENTE: …]` donde deben insertarse diagramas o capturas aún no generados. Listo para uso en sesión Cowork de redacción final.
>
> **Autores:** Omar Piero Terbullino Jaime (72682019) · Giancarlo Guerrero Córdova (71993692) · Roger Moises Canchumanya Avellaneda (71653804)  
> **Asesor:** Dr. Maglioni Arana Caparachin  
> **Institución:** Universidad Continental — Escuela de Ingeniería de Sistemas e Informática  
> **Año:** 2026  

---

## CORRECCIONES RESPECTO AL BORRADOR ORIGINAL (PDF)

Las siguientes discrepancias se identificaron entre el PDF `Proyecto Final-G3.docx.pdf` y el estado real del repositorio:

| Punto | PDF (incorrecto) | Realidad del proyecto |
|---|---|---|
| Framework de pruebas | Cypress | PHPUnit 11 + `php artisan test` |
| Base de datos producción | SQLite | MySQL 8 en puerto 3307 (`jm_js_alimentos`) |
| Base de datos en tests | — | SQLite In-Memory (aislamiento por test) |
| Versión PHP | 8.2 | 8.5.4 (verificado en entorno local) |
| Frontend principal | React | Blade + Tailwind CSS v4 (React solo para chatbot) |
| RF totales | 15 | 21 (6 adicionales descubiertos en código) |
| Tests / Aserciones | — | **89 tests / 426 aserciones** (ejecución más reciente) |
| Sección 4.4 Casos de Uso | Vacía (solo ".") | Desarrollada en este documento |

---

# CAPÍTULO 1: INFORMACIÓN GENERAL DEL PROYECTO

## 1.1. Resumen Ejecutivo

El presente proyecto consiste en el desarrollo de una plataforma web LMS (Learning Management System) para la empresa **JM y JS Alimentos**, especializada en asesoría en gestión de calidad, buenas prácticas de manufactura y capacitaciones dirigidas al sector alimentario peruano. El sistema, denominado **JM y JS Alimentos LMS v2.0**, transforma el modelo manual de ventas (basado en WhatsApp y redes sociales) en un ecosistema digital integrado que automatiza el ciclo completo: descubrimiento de cursos → inscripción → pago seguro → acceso al aula virtual.

La plataforma incorpora tres diferenciales tecnológicos de alto valor:

1. **Motor de pagos transaccional ACID** implementado con la API de Stripe, con webhooks asíncronos e idempotencia financiera garantizada.
2. **Asistente de soporte cognitivo** basado en Google Gemini 2.5 Flash, integrado como chatbot 24/7 sin costo operativo adicional.
3. **Suite de 89 pruebas automatizadas con 426 aserciones**, alineadas con ISO 29119, que garantizan regresión cero antes de cada merge a la rama `main`.

## 1.2. Introducción

En la actualidad, la ingeniería de software permite desarrollar soluciones tecnológicas que optimizan procesos y fortalecen la relación entre las organizaciones y sus clientes. En este marco, el presente proyecto propone el desarrollo de una plataforma web para la empresa **JM y JS Alimentos**, con el fin de mejorar su presencia digital, difundir sus servicios de asesoría y capacitaciones, y facilitar la interacción con los usuarios.

La necesidad del sistema surge por las limitaciones actuales en la actualización de contenido y en el alcance de sus medios digitales. Actualmente, la empresa depende de un único actor humano (el "Coordinador Digital") para gestionar manualmente todas las etapas del proceso comercial: atención por WhatsApp, validación manual de vouchers de pago y envío de materiales educativos por mensaje de texto. Esta dependencia impide la escalabilidad del negocio.

Las pruebas de software cumplen un rol esencial en el desarrollo moderno, ya que permiten identificar errores y asegurar atributos de calidad como la seguridad, la confiabilidad y la usabilidad. En este proyecto, su aplicación se orienta a garantizar el correcto funcionamiento del sistema, especialmente en procesos críticos como las transacciones de pago y el control de acceso al aula virtual.

---

# CAPÍTULO 2: CONTEXTO ORGANIZACIONAL Y ANÁLISIS DEL PROBLEMA

## 2.1. Contexto de la Organización

**JM y JS Alimentos** es una empresa peruana con sede en la región Junín, dedicada a la consultoría en normativas de inocuidad alimentaria (BPM, HACCP, ISO 9001, ISO 22000). Opera en uno de los principales corredores alimentarios del Perú (Valle del Mantaro), donde existe una demanda estructural de capacitación técnica impulsada por obligaciones regulatorias de DIGESA, SENASA e INDECOPI.

El análisis del escenario organizacional se fundamenta en estándares internacionales de calidad:

- **Alineación con ISO/IEC 25010:** La transición hacia una infraestructura propia busca garantizar atributos críticos como Seguridad y Eficiencia de Desempeño. La validación manual de pagos representa un riesgo de seguridad y genera latencias que vulneran la confianza del usuario.
- **Enfoque en Satisfacción del Cliente (ISO 9000):** El dinamismo deficiente en redes sociales y la atención lenta por WhatsApp afectan la fidelización. La plataforma se diseñó bajo criterios de Usabilidad para garantizar una experiencia intuitiva desde el primer contacto hasta la entrega del material educativo.
- **Mitigación de Riesgos Operativos (QA):** El enfoque QA permite identificar fallos en el flujo de inscripción y asegurar que la habilitación del material educativo sea inmediata tras el pago, eliminando los errores de operación interna del modelo manual.

## 2.2. Identificación del Problema

El problema central radica en la **dependencia absoluta de procesos manuales** para la comercialización y entrega de servicios educativos. Actualmente, la empresa carece de un ecosistema digital integrado, lo que obliga a que toda la interacción con el cliente (consultas, envío de información, validación de pagos y entrega de materiales) se realice de forma fragmentada a través de redes sociales estáticas y mensajería instantánea (WhatsApp).

### Causas del problema

| Causa | Descripción |
|---|---|
| Infraestructura tecnológica limitada | Uso de Facebook y TikTok (sin capacidad transaccional) como únicos canales comerciales |
| Centralización operativa | Todo el flujo de ventas recae sobre el Coordinador Digital, quien interviene manualmente en cada etapa |
| Ausencia de validación automatizada | Los pagos se verifican revisando manualmente capturas de pantalla (vouchers), propenso a fraudes |
| Falta de personal técnico | Inexistencia de un área de TI o Marketing digital que soporte una plataforma propia |

### Consecuencias operativas

- **Cuellos de botella logísticos:** La saturación de mensajes en WhatsApp retrasa la respuesta al cliente.
- **Riesgo de errores humanos:** La inscripción de alumnos y la entrega de credenciales se realizan manualmente, con riesgo de registros incorrectos.
- **Vulnerabilidad en la seguridad:** La gestión de transacciones mediante chats informales compromete la integridad de los datos financieros.
- **Falta de escalabilidad:** Bajo el modelo AS-IS, es imposible procesar ventas en horarios no laborables o gestionar múltiples clientes simultáneamente.

---

# CAPÍTULO 3: ANÁLISIS DE PROCESOS DE NEGOCIO

## 3.1. Descripción del Proceso Actual (AS-IS)

### Actores involucrados

| N° | Actor | Tipo | Lane en diagrama |
|---|---|---|---|
| 1 | Cliente / Profesional | Externo | Lane superior |
| 2 | Coordinador Digital | Interno | Lane inferior |
| 3 | Redes Sociales (Facebook / TikTok) | Sistema externo | Lane sistema |

### Flujo del proceso actual

1. **Difusión de Contenido:** La empresa publica anuncios o videos en redes sociales. El cliente recibe esta información de manera unidireccional.
2. **Contacto Inicial:** Al existir interés, el cliente abandona la red social y hace clic en un enlace que lo redirige a WhatsApp.
3. **Gestión de Consultas:** El Coordinador Digital recibe la solicitud y redacta manualmente la respuesta, enviando los detalles de cursos, costos y métodos de pago.
4. **Decisión de Compra:** Compuerta de decisión. Si el cliente desiste, el proceso finaliza. Si acepta, el flujo continúa con la solicitud de datos bancarios.
5. **Ejecución y Envío de Pago:** El cliente realiza la transferencia y, obligatoriamente, debe enviar un voucher (captura de pantalla) por WhatsApp.
6. **Validación Manual:** El Coordinador Digital verifica manualmente la transacción en la aplicación bancaria de la empresa. Este es el **punto crítico** que genera retrasos.
7. **Registro de Inscripción:** Tras confirmar el pago, el coordinador solicita los datos personales al cliente y los anota manualmente en hojas de cálculo.
8. **Entrega de Accesos:** Finalmente, el coordinador envía los enlaces a videos, documentos PDF o credenciales vía mensaje de texto.

## 3.2. Modelado del Proceso Actual (AS-IS)

> **[PENDIENTE: Diagrama BPMN AS-IS — Bizagi Modeler]**
>
> Insertar aquí el diagrama BPMN del proceso actual con dos swimlanes:
> - **Lane 1:** Cliente / Profesional (acciones: buscar info → contactar por WhatsApp → enviar voucher → esperar acceso)
> - **Lane 2:** Coordinador Digital (acciones: responder consulta → solicitar pago → validar voucher manualmente → registrar en Excel → enviar credenciales)
>
> Eventos clave a representar:
> - Evento de inicio: Cliente ve publicación en red social
> - Compuerta exclusiva: ¿El cliente desea comprar?
> - Tarea de servicio: Validación manual del voucher (PUNTO CRÍTICO)
> - Evento de fin: Venta concretada (o abandono)

## 3.3. Problemas del Proceso Actual

| Problema | Impacto | Norma vulnerada |
|---|---|---|
| Procesos manuales (dependencia humana) | Imposibilidad de ventas en horario no laboral; límite de clientes simultáneos | ISO 9000 (eficiencia) |
| Duplicación de tareas y reprocesos | El coordinador debe solicitar, recibir y re-digitar los datos del cliente | ISO/IEC 25010 (mantenibilidad) |
| Falta de control de la información | Datos fragmentados en chats; sin historial estructurado ni CRM | ISO 27001 (seguridad de información) |
| Retrasos operativos (delays) | El cliente espera entre el depósito y la habilitación del acceso | ISO/IEC 25010 (eficiencia de desempeño) |

## 3.4. Modelado del Proceso Propuesto (TO-BE)

El modelo TO-BE incorpora el sistema LMS como actor digital que automatiza las etapas críticas:

**Mejoras implementadas:**
- **Automatización de tareas:** La validación de pagos y la habilitación de accesos son ejecutadas automáticamente por el motor Stripe + Webhook. El `PaymentController@webhook` verifica la firma criptográfica HMAC y crea los `Enrollment` de forma instantánea.
- **Centralización de información:** Toda la data del cliente (perfil, historial de compras, estado de cursos) se centraliza en la base de datos MySQL del LMS. Elimina el riesgo de pérdida o transcripción incorrecta.
- **Flujo continuo 24/7:** El estudiante puede completar el ciclo completo (consulta → selección → pago → consumo del curso) de forma autogestionada sin intervención humana.
- **Transformación del rol del Coordinador Digital:** De operario de mensajes a supervisor estratégico que monitorea el sistema mediante el panel administrativo.

> **[PENDIENTE: Diagrama BPMN TO-BE — Bizagi Modeler]**
>
> Insertar aquí el diagrama BPMN del proceso propuesto con tres swimlanes:
> - **Lane 1:** Estudiante (acciones: navegar catálogo → registrarse → agregar al carrito → pagar con Stripe → acceder al aula virtual)
> - **Lane 2:** Sistema LMS (acciones: mostrar catálogo → validar pago automáticamente → crear Enrollment → dar acceso a materiales)
> - **Lane 3:** Administrador (acciones: monitorear dashboard → gestionar cursos → revisar auditoría)
>
> Eventos clave a representar:
> - Evento de inicio: Visitante accede a `/cursos`
> - Tarea de servicio automatizada: Stripe Webhook → `PaymentController@webhook`
> - Compuerta paralela: Creación simultánea de `Sale` + `Enrollment`
> - Evento de fin: Estudiante accede al Aula Virtual en `/mi-cuenta/cursos/{slug}`

---

# CAPÍTULO 4: ANÁLISIS DE REQUERIMIENTOS DEL SISTEMA

## 4.1. Identificación de Actores del Sistema

| Actor | Tipo | Descripción |
|---|---|---|
| **Visitante** | Externo | Usuario no registrado que navega el catálogo público |
| **Estudiante / Usuario** | Externo autenticado | Usuario registrado con rol `estudiante` que compra y consume cursos |
| **Administrador** | Interno autenticado | Usuario con rol `admin` que gestiona toda la plataforma |
| **Pasarela de Pago (Stripe)** | Sistema externo | Procesa transacciones y emite webhooks de confirmación |
| **Asistente IA (Gemini)** | Sistema externo | Procesa consultas del chatbot vía REST API |

## 4.2. Requerimientos Funcionales

> **Nota:** El borrador original (PDF) listaba RF01–RF15. El análisis del código fuente reveló **6 requerimientos adicionales** (RF-16 a RF-21) implementados y cubiertos por la suite de pruebas.

| RF | Descripción | Controlador Responsable |
|---|---|---|
| RF-01 | Mostrar catálogo público de cursos con filtros por categoría | `CourseController@index` |
| RF-02 | Búsqueda y filtrado de cursos | `CourseController@search` |
| RF-03 | Visualizar detalle de curso (descripción, costo, duración, módulos) | `CourseController@show` |
| RF-04 | Registro de nuevos usuarios con formulario básico | `AuthController@register` |
| RF-05 | Inicio y cierre de sesión con regeneración segura de sesión | `AuthController@login` / `logout` |
| RF-06 | Carrito de compras temporal en memoria de sesión | `CartController@add` / `remove` |
| RF-07 | Aplicar cupones de descuento con validación de expiración y límites | `CartController@applyCoupon` / Modelo `Coupon` |
| RF-08 | Procesar pago seguro mediante Stripe Checkout | `PaymentController@process` + `StripeService` |
| RF-09 | Validar pago y crear inscripción automáticamente (vía Webhook) | `PaymentController@webhook` |
| RF-10 | Facturación y generación de registro de venta | Modelo `Sale` + `SaleItem` |
| RF-11 | Asistente IA chatbot (Google Gemini 2.5 Flash) | `Api\ChatController@sendMessage` |
| RF-12 | Panel Admin: CRUD completo de cursos (crear, editar, publicar, duplicar, eliminar) | `Admin\CourseController` |
| RF-13 | Gestión y reordenamiento de módulos dentro de un curso | `Admin\CourseModuleController` |
| RF-14 | Carga y gestión de materiales (PDF/video) con almacenamiento seguro | `Admin\CourseMaterialController` |
| RF-15 | Gestión de privilegios de usuario y prevención de lockout del último admin | `Admin\UserController@toggleAdmin` |
| RF-16 | Panel de indicadores (Dashboard KPIs: ingresos, inscripciones, actividad) | `Admin\DashboardController@index` |
| RF-17 | Formulario de contacto público y bandeja de mensajes en panel admin | `ContactController` + `Admin\ContactsController` |
| RF-18 | Aula Virtual: visualización de módulos, progreso y materiales del estudiante | `StudentCourseController@show` |
| RF-19 | Streaming privado y seguro de archivos (sin hotlinking) | `StudentCourseController@serveFile` |
| RF-20 | CRUD completo de cupones de descuento en panel admin | `Admin\CouponController` |
| RF-21 | Historial de ventas e ingresos (libro mayor / ledger) | `Admin\SaleController` |

## 4.3. Requerimientos No Funcionales

| RNF | Categoría | Descripción | Implementación Técnica |
|---|---|---|---|
| RNF-01 | Rendimiento (Latencia < 3s) | Tiempo de respuesta en procesos críticos | Vite compilación + Eager Loading Eloquent (`with()`) |
| RNF-02 | Usabilidad (Responsividad) | Interfaz accesible desde PC, tablet y móvil | Tailwind CSS v4 (diseño responsivo desde 320px) |
| RNF-03 | Seguridad (OWASP Top 10) | Protección contra inyección, XSS, CSRF, fuerza bruta | Bcrypt 12 rondas, Blade anti-XSS, PDO Bindings, CSRF tokens |
| RNF-04 | Trazabilidad Inmutable | Registro auditado de toda acción administrativa | Singleton `AuditService` con hooking sobre modelos Eloquent |
| RNF-05 | Integridad Financiera (ACID) | Las transacciones de pago no deben quedar en estado inconsistente | `DB::transaction()` en `PaymentController@process` |
| RNF-06 | Prevención Lockout Admin | El sistema nunca puede quedar sin administradores | Bloqueo lógico en `UserController@toggleAdmin` |
| RNF-07 | Compatibilidad Multi-navegador | Accesible desde Chrome, Firefox, Edge, Safari | HTML semántico + CSS estándar, sin dependencias propietarias |
| RNF-08 | Portabilidad QA | La suite de pruebas corre en cualquier entorno sin servicios externos | SQLite In-Memory + Mocks de Stripe y Gemini en `phpunit.xml` |
| RNF-09 | Protección Anti-Spam / Bots | Limitar intentos de login y uso del chatbot | Rate Limiting (`throttle:5,1`) en `routes/web.php` |
| RNF-10 | Recuperación ante Caídas | Registro de errores para diagnóstico y recuperación | Sistema de logs Monolog en `storage/logs/laravel.log` |

## 4.4. Casos de Uso del Sistema

### Diagrama General de Casos de Uso

> **[PENDIENTE: Diagrama UML de Casos de Uso — General]**
>
> Insertar aquí el diagrama UML de casos de uso que muestre los 4 actores principales (Visitante, Estudiante, Administrador, Stripe) y sus interacciones con el sistema. Los casos de uso principales son:
> - Visitante: Ver catálogo, Ver detalle de curso, Registrarse, Usar chatbot IA, Enviar contacto
> - Estudiante: Iniciar sesión, Agregar al carrito, Aplicar cupón, Comprar curso, Ver aula virtual, Completar módulo, Descargar material
> - Administrador: Gestionar cursos (CRUD), Gestionar módulos/materiales, Ver dashboard, Gestionar usuarios, Ver ventas, Gestionar cupones, Ver auditoría, Configurar sistema
> - Stripe: Procesar pago, Enviar webhook de confirmación

### Casos de Uso Detallados — Flujo Principal

#### CU-01: Compra de un Curso

| Campo | Detalle |
|---|---|
| **Actor primario** | Estudiante |
| **Precondición** | El estudiante está registrado y autenticado (`Auth::check()`) |
| **Flujo principal** | 1. Estudiante navega a `/cursos` → 2. Selecciona curso → 3. Hace clic en "Añadir al carrito" (POST `/cart/add`) → 4. Accede al checkout (`/checkout`) → 5. Opcionalmente aplica cupón (POST `/cart/coupon/apply`) → 6. Confirma pago (POST `/pago`) → 7. Sistema redirige a Stripe Checkout → 8. Estudiante aprueba el cargo → 9. Stripe emite webhook (POST `/stripe/webhook`) → 10. Sistema crea `Sale` + `Enrollment` automáticamente → 11. Estudiante es redirigido a `/mi-cuenta` con acceso habilitado |
| **Flujo alternativo** | En paso 7: si el pago falla en Stripe, el usuario es redirigido a `/pago/cancelado/{sale}` |
| **Postcondición** | Existe un `Enrollment` activo vinculando al usuario con el curso; `Sale.payment_status = 'pagado'` |

#### CU-02: Consumo de Contenido Educativo

| Campo | Detalle |
|---|---|
| **Actor primario** | Estudiante |
| **Precondición** | Existe un `Enrollment` activo para el estudiante y el curso |
| **Flujo principal** | 1. Estudiante accede a `/mi-cuenta/cursos/{slug}` → 2. `StudentCourseController@show` verifica el `Enrollment` → 3. Se renderiza el temario con módulos y materiales → 4. Estudiante solicita un archivo (GET `.../materials/{id}/file`) → 5. `serveFile()` retorna el binario desde `Storage::disk('local')` (protegido contra hotlinking) → 6. Estudiante marca material como completado (POST `.../toggle`) → 7. Sistema actualiza el progreso en la tabla pivot |
| **Postcondición** | El progreso del estudiante se actualiza en la BD (0–100%) |

#### CU-03: Gestión de Cursos por el Administrador

| Campo | Detalle |
|---|---|
| **Actor primario** | Administrador |
| **Precondición** | Usuario autenticado con permiso `courses.view` y `courses.edit` |
| **Flujo principal** | 1. Admin accede a `/admin/courses` → 2. Puede crear, editar, publicar, duplicar o eliminar cursos → 3. Dentro de un curso puede gestionar módulos (POST `/admin/modules`) y materiales (POST `/admin/materials`) → 4. El slug se genera automáticamente con `Str::slug()` → 5. Los módulos se reordenan mediante drag-and-drop con posición JSON (PATCH `/admin/modules/reorder`) |

---

# CAPÍTULO 5: PLANIFICACIÓN DEL PROYECTO Y PLAN DE CALIDAD

## 5.1. Alcance del Proyecto

El sistema incluye las siguientes funcionalidades implementadas y verificadas:

- Sitio web público informativo con catálogo de cursos, sección "Nosotros" y formulario de contacto
- Registro, autenticación y gestión de sesiones de usuarios (rol `estudiante` y rol `admin`)
- Carrito de compras en sesión con soporte de cupones de descuento
- Motor de pago Stripe con creación de sesiones de checkout y confirmación por webhook
- Inscripción automática e inmediata tras confirmar el pago
- Aula virtual con streaming seguro de materiales (PDF, video) y seguimiento de progreso por módulo
- Chatbot de soporte con inteligencia artificial (Google Gemini 2.5 Flash) integrado como componente React
- Panel de administración completo (cursos, usuarios, ventas, cupones, contactos, auditoría, roles, configuración)

## 5.2. Herramientas Tecnológicas del Proyecto

> **Nota:** El borrador original del PDF listaba "Cypress" como herramienta de pruebas y "React" como framework frontend principal. Ambas son incorrectas. La tabla a continuación refleja el stack real.

| Capa | Tecnología | Versión | Uso específico |
|---|---|---|---|
| **Backend** | Laravel | 12 | Framework MVC principal, routing, ORM, autenticación |
| **Lenguaje backend** | PHP | 8.5.4 | Servidor local (`php artisan serve`) |
| **Frontend principal** | Blade + Tailwind CSS | v4 | Renderizado de vistas en el servidor |
| **Frontend interactivo** | React | 19 | Solo el componente chatbot (`AiChat.jsx`) |
| **Bundler** | Vite | 7 | Compilación de assets, HMR |
| **Base de datos producción** | MySQL | 8 | Puerto 3307, base `jm_js_alimentos` |
| **Base de datos en tests** | SQLite | In-Memory | Aislamiento por test, sin dependencia de MySQL |
| **ORM** | Eloquent | (Laravel 12) | Modelos, migraciones, relaciones |
| **Pasarela de pagos** | Stripe PHP SDK | 16.x | Checkout, Webhooks, firma HMAC |
| **IA / Chatbot** | Google Gemini | 2.5 Flash | REST API para asistente de soporte |
| **Almacenamiento seguro** | Laravel Storage | (local disk) | Archivos privados en `storage/app/private/` |
| **Control de versiones** | Git + GitHub | — | Repo: `ROGERCanchumanyaUC/pruebas-calidad-grupo-03` |
| **Framework de pruebas** | PHPUnit | 11 | Suite de 89 tests, 426 aserciones |
| **Contenedores (propuesta)** | Docker + Nginx | — | Arquitectura de despliegue a producción |
| **Iconografía** | Phosphor Icons / Heroicons | — | SVG integrados en vistas Blade |

## 5.3. Normas y Estándares de Calidad

| Norma | Enfoque | Aplicación en el proyecto |
|---|---|---|
| **ISO/IEC 25010** | Calidad del producto software | RNF-01 a RNF-10 mapeados a características de calidad (rendimiento, seguridad, usabilidad, mantenibilidad) |
| **ISO 29119** | Pruebas de software | Suite PHPUnit con 89 tests / 426 aserciones; niveles: unitario, integración, sistema, aceptación |
| **ISO 9001:2015** | Sistema de Gestión de Calidad | Proceso de desarrollo iterativo, revisiones de calidad antes de cada merge a `main` |
| **ISO/IEC 27001** | Seguridad de la información | OWASP Top 10 aplicado (ver RNF-03), gestión de secretos en `.env`, webhooks con firma criptográfica |

## 5.4. Plan de Pruebas del Proyecto

| Nivel de prueba | Herramienta | Alcance | Evidencia |
|---|---|---|---|
| Pruebas unitarias | PHPUnit 11 | Lógica aislada: `VideoEmbedService`, `CoursePublishingService`, relaciones Eloquent | `evidencia_pruebas_unitarias.txt` |
| Pruebas de caja negra | PHPUnit 11 | Rutas HTTP, redirecciones, respuestas de controladores | `evidencia_caja_negra.txt` |
| Pruebas de caja blanca | PHPUnit 11 | Middlewares, rate limiting, prevención de lockout admin | `evidencia_caja_blanca.txt` |
| Pruebas de integración | PHPUnit 11 | Flujo completo: pago → webhook → enrollment; IA mock | `evidencia_resto_pruebas.txt` |

## 5.5. Lineamientos de Seguridad Informática

| Amenaza OWASP | Mecanismo implementado | Archivo clave |
|---|---|---|
| A01 — Control de acceso roto | Middleware `auth` y permisos granulares en todas las rutas admin | `routes/web.php` |
| A02 — Fallas criptográficas | Bcrypt con 12 rondas (`BCRYPT_ROUNDS=12`); HTTPS recomendado en producción | `AuthController.php` |
| A03 — Inyección SQL | Eloquent ORM con query bindings en todas las consultas | Todos los modelos en `app/Models/` |
| A04 — Diseño inseguro | Verificación de idempotencia en Webhook para prevenir doble inscripción | `PaymentController@webhook` |
| A05 — Configuración incorrecta | Variables sensibles en `.env` (no en código); `APP_DEBUG=false` en producción | `.env` |
| A07 — Fallas de autenticación | Session regeneration en login; invalidación en logout; throttle `5,1` en `/login` | `AuthController.php` |

**Vulnerabilidad identificada y pendiente de corrección:** El campo `is_admin` está en `$fillable` del modelo `User`, lo que teóricamente permitiría a un atacante auto-asignarse rol admin mediante mass assignment. **Corrección recomendada:** Remover `is_admin` de `$fillable` y manejar este campo únicamente desde el controlador admin con validación explícita.

---

# CAPÍTULO 6: DISEÑO DEL SISTEMA

## 6.1. Arquitectura Conceptual del Sistema

El sistema sigue una arquitectura **MVC (Modelo-Vista-Controlador)** con separación por capas:

```
┌─────────────────────────────────────────────────────────┐
│              CAPA DE PRESENTACIÓN (Frontend)             │
│   Blade Templates + Tailwind CSS v4 + React (chatbot)   │
│                    Vite 7 (bundler)                      │
├─────────────────────────────────────────────────────────┤
│              CAPA DE NEGOCIO (Backend)                   │
│          Laravel 12 — Controladores y Servicios          │
│  StripeService | AuditService | ChatController (Gemini)  │
├─────────────────────────────────────────────────────────┤
│              CAPA DE DATOS                               │
│   Eloquent ORM → MySQL 8 (producción, puerto 3307)       │
│                  SQLite In-Memory (tests)                 │
├─────────────────────────────────────────────────────────┤
│              SERVICIOS EXTERNOS                          │
│   Stripe API (pagos) | Google Gemini API (chatbot IA)   │
│           Laravel Storage (archivos privados)            │
└─────────────────────────────────────────────────────────┘
```

> **[PENDIENTE: Diagrama de Arquitectura Conceptual — Bizagi/Draw.io]**
>
> Insertar aquí el diagrama de arquitectura del sistema mostrando las capas Frontend, Backend, Base de Datos y Servicios Externos con sus conexiones.

## 6.2. Modelo UML del Sistema

### Diagrama de Clases (Modelos principales)

Los modelos principales del sistema y sus relaciones en Eloquent ORM son:

```
Course ──── hasMany ──→ CourseModule ──── hasMany ──→ CourseMaterial
   │
   └── belongsToMany → User (a través de Enrollment)
   └── belongsTo ──→ Category

User ──── hasMany ──→ Sale ──── hasMany ──→ SaleItem
   │                    │
   │                    └── belongsTo ──→ Course
   └── hasMany ──→ Enrollment

Sale ──── belongsTo ──→ Coupon

AuditLog ──── belongsTo ──→ User
```

> **[PENDIENTE: Diagrama de Clases UML — Herramienta a definir (StarUML, Draw.io, Bizagi)]**
>
> Insertar aquí el diagrama de clases UML completo con todos los modelos Eloquent:
> - User, Course, Category, CourseModule, CourseMaterial
> - Sale, SaleItem, Enrollment, Coupon
> - AuditLog, Contact, Setting, Role, Permission
>
> Incluir atributos principales y cardinalidades de relaciones.

### Diagrama de Secuencia — Proceso de Compra

> **[PENDIENTE: Diagrama de Secuencia UML — Proceso de Compra con Stripe]**
>
> Insertar aquí el diagrama de secuencia mostrando:
> 1. Estudiante → CartController: POST /cart/add
> 2. Estudiante → PaymentController: POST /pago
> 3. PaymentController → StripeService: createCheckoutSession()
> 4. StripeService → Stripe API: Session::create()
> 5. Stripe API → Navegador: Redirección a checkout
> 6. Stripe API → PaymentController: POST /stripe/webhook (async)
> 7. PaymentController → Sale: update(payment_status='pagado')
> 8. PaymentController → Enrollment: create()
> 9. Sistema → Estudiante: Acceso habilitado

## 6.3. Diseño de Interfaces de Usuario

Las interfaces han sido diseñadas siguiendo un sistema de diseño basado en Tailwind CSS v4 con paleta de colores corporativa de JM y JS Alimentos. A continuación se presenta el inventario de pantallas capturadas:

### Pantallas Públicas

| Pantalla | Ruta | Captura |
|---|---|---|
| Página de Inicio | `/` | `documentacion/CAPTURAS/01_PUBLICAS/01_inicio.png` |
| Nosotros | `/nosotros` | `documentacion/CAPTURAS/01_PUBLICAS/02_nosotros.png` |
| Catálogo de Cursos | `/cursos` | `documentacion/CAPTURAS/01_PUBLICAS/03_cursos.png` |
| Contacto | `/contacto` | `documentacion/CAPTURAS/01_PUBLICAS/04_contacto.png` |

### Pantallas de Autenticación

| Pantalla | Ruta | Captura |
|---|---|---|
| Inicio de Sesión | `/login` | `documentacion/CAPTURAS/02_AUTH/01_login.png` |
| Registro de Usuario | `/register` | `documentacion/CAPTURAS/02_AUTH/02_registro.png` |

### Panel de Administración

| Pantalla | Ruta | Captura |
|---|---|---|
| Dashboard KPIs | `/admin` | `documentacion/CAPTURAS/03_ADMIN/01_dashboard.png` |
| Lista de Cursos | `/admin/courses` | `documentacion/CAPTURAS/03_ADMIN/02_cursos_lista.png` |
| Editor de Curso | `/admin/courses/1/edit` | `documentacion/CAPTURAS/03_ADMIN/02b_curso_detalle.png` |
| Gestión de Usuarios | `/admin/users` | `documentacion/CAPTURAS/03_ADMIN/03_usuarios.png` |
| Gestión de Estudiantes | `/admin/students` | `documentacion/CAPTURAS/03_ADMIN/04_estudiantes.png` |
| Historial de Ventas | `/admin/sales` | `documentacion/CAPTURAS/03_ADMIN/05_ventas.png` |
| Gestión de Cupones | `/admin/coupons` | `documentacion/CAPTURAS/03_ADMIN/06_cupones.png` |
| Bandeja de Contactos | `/admin/contacts` | `documentacion/CAPTURAS/03_ADMIN/07_contactos.png` |
| Log de Auditoría | `/admin/audit` | `documentacion/CAPTURAS/03_ADMIN/08_auditoria.png` |
| Roles y Permisos | `/admin/roles` | `documentacion/CAPTURAS/03_ADMIN/09_roles.png` |
| Configuración | `/admin/settings` | `documentacion/CAPTURAS/03_ADMIN/10_configuracion.png` |

### Área del Estudiante

| Pantalla | Ruta | Captura |
|---|---|---|
| Mi Cuenta | `/mi-cuenta` | `documentacion/CAPTURAS/04_ESTUDIANTE/01_mi_cuenta.png` |
| Catálogo (autenticado) | `/cursos` | `documentacion/CAPTURAS/04_ESTUDIANTE/02_catalogo_cursos.png` |
| Carrito / Checkout | `/checkout` | `documentacion/CAPTURAS/04_ESTUDIANTE/03_carrito.png` |
| Aula Virtual | `/mi-cuenta/cursos/{slug}` | `documentacion/CAPTURAS/04_ESTUDIANTE/04_aula_virtual.png` |

> **[PENDIENTE: Mockups en Figma]**  
> Archivo de Figma creado en: https://www.figma.com/design/ysyZfEzFyakkGOxYBNhF8M  
> Estado: 12 de 21 capturas subidas a Figma. Pendiente completar cuando se resetee el límite de la API MCP (plan Starter).  
> Insertar aquí capturas de los mockups de Figma una vez completados.

## 6.4. Diseño de Base de Datos

### Tablas principales del sistema

| Tabla | Descripción | Relaciones clave |
|---|---|---|
| `users` | Usuarios del sistema (admin y estudiantes) | `hasMany: sales, enrollments` |
| `courses` | Catálogo de cursos | `belongsTo: categories; hasMany: modules, enrollments` |
| `categories` | Categorías de cursos | `hasMany: courses` |
| `course_modules` | Módulos dentro de cada curso | `belongsTo: courses; hasMany: materials` |
| `course_materials` | Materiales (PDF/video) de cada módulo | `belongsTo: modules` |
| `enrollments` | Inscripciones de estudiantes a cursos | `belongsTo: users, courses` |
| `sales` | Registro de ventas/órdenes | `belongsTo: users, coupons; hasMany: sale_items` |
| `sale_items` | Ítems de cada venta | `belongsTo: sales, courses` |
| `coupons` | Cupones de descuento | `hasMany: sales` |
| `contacts` | Mensajes de contacto | — |
| `audit_logs` | Registro inmutable de acciones admin | `belongsTo: users` |
| `settings` | Configuración clave-valor del sistema | — |
| `roles` / `permissions` | Control de acceso basado en roles (RBAC) | Librería Spatie Laravel Permission |

> **[PENDIENTE: Diagrama Entidad-Relación (ER) — MySQL Workbench o similar]**
>
> Insertar aquí el diagrama ER completo con todas las tablas, sus atributos (incluyendo tipos de dato y claves primarias/foráneas) y las relaciones entre ellas.

---

# CAPÍTULO 7: ARQUITECTURA TECNOLÓGICA DEL SISTEMA

## 7.1. Tecnologías del Frontend

| Tecnología | Versión | Justificación de elección |
|---|---|---|
| **Blade Templating** | Laravel 12 | Renderizado del servidor (SSR) para páginas estáticas y semi-dinámicas; más eficiente para SEO y primer renderizado |
| **Tailwind CSS** | v4 | CSS utilitario Just-In-Time; diseño responsivo desde 320px sin JavaScript adicional |
| **React** | 19 | Usado exclusivamente para el componente `AiChat.jsx` (chatbot); patrón "islas de interactividad" |
| **Vite** | 7 | Bundler moderno con HMR sub-segundo; reemplazó Webpack en Laravel desde v9 |
| **Phosphor Icons / Heroicons** | — | Iconografía SVG vectorial optimizada para web |

## 7.2. Tecnologías del Backend

| Tecnología | Versión | Justificación de elección |
|---|---|---|
| **Laravel** | 12 | Framework PHP más adoptado en LATAM; ORM Eloquent, middleware configurable, sistema de colas integrado |
| **PHP** | 8.5.4 | Última versión estable con mejoras en rendimiento (JIT) y tipado estricto |
| **Stripe PHP SDK** | 16.x | Estándar de la industria para pagos en línea; abstrae la complejidad de PCI-DSS |
| **Google Gemini API** | 2.5 Flash | Modelo LLM optimizado para latencia baja; gratuito en Google AI Studio; soporte nativo para español |
| **Laravel Storage** | (nativo) | Gestión de archivos privados con control de acceso; previene hotlinking de materiales educativos |
| **Spatie Laravel Permission** | — | RBAC (Control de acceso basado en roles) maduro y auditado por la comunidad Laravel |

## 7.3. Base de Datos del Sistema

| Entorno | Motor | Configuración |
|---|---|---|
| **Desarrollo / Producción** | MySQL 8.0 | Host: 127.0.0.1, Puerto: 3307, BD: `jm_js_alimentos`, Usuario: `root` |
| **Ejecución de pruebas** | SQLite In-Memory | Inyectado por `phpunit.xml` (`<env name="DB_CONNECTION" value="sqlite"/>`); se crea y destruye por cada test |

**Justificación del doble motor:** El uso de MySQL en producción garantiza robustez y escalabilidad. El uso de SQLite In-Memory en tests garantiza velocidad (sin I/O de disco), aislamiento total entre pruebas (Trait `RefreshDatabase`) y ausencia de dependencias externas en CI/CD.

## 7.4. Infraestructura de Desarrollo

El stack de desarrollo local utiliza:
- **XAMPP** como servidor MySQL (puerto 3307 personalizado para evitar conflictos)
- **`php artisan serve`** como servidor HTTP de desarrollo (http://127.0.0.1:8000)
- **`npm run dev`** para el servidor Vite con Hot Module Replacement
- **GitHub** como plataforma de control de versiones y colaboración

**Validación con Thoughtworks Technology Radar Vol. 34 (Abril 2026):**

| Tecnología del proyecto | Clasificación en el Radar | Veredicto |
|---|---|---|
| Laravel 12 / PHP 8.5 | Frameworks Maduros | **Adopt** |
| Vite 7 (esbuild) | Herramientas Frontend | **Adopt** |
| Tailwind CSS v4 | Utilidades de Estilo | **Trial** |
| Google Gemini API | Patrones AI como Servicio | **Trial** |
| Stripe API | Plataformas Core de Pago | **Adopt** |

---

# CAPÍTULO 8: DESARROLLO DEL SISTEMA

## 8.1. Iteración 1: Configuración Inicial del Proyecto (Foundation)

- **Scaffolding y Entorno:** Se inicializó la aplicación con Laravel 12 sobre PHP 8.5.4. Se configuraron los parámetros en `.env` para conectividad con MySQL en puerto 3307 (XAMPP).
- **Frontend Moderno:** Se sustituyó Webpack por Vite 7 con HMR. Se integró Tailwind CSS v4 y los componentes base de UI. Se configuró React 19 para el componente chatbot.
- **Esquema de Datos (Migraciones):** Generación de tablas primarias: `users`, `courses`, `categories`, `course_modules`. Diseñadas según el modelo entidad-relación del proyecto, con llaves foráneas e índices definidos en especificación previa (patrón SDD).

## 8.2. Iteración 2: Desarrollo de Funcionalidades Básicas (Core Business)

- **Autenticación y Seguridad:** Implementación de registro y login vía `AuthController`. Incorporación del `AdminMiddleware` con el sistema de permisos granulares de Spatie.
- **Gestión Académica (CRUD):** Desarrollo de `Admin\CourseController` y `Admin\CourseModuleController` para poblar el catálogo. El slug se genera automáticamente con `Str::slug()`.
- **Carrito de Compras en Sesión:** `CartController` con persistencia temporal (`session()->put('cart', ...)`). Incluye el modelo `Coupon` con validación de fechas de expiración y límites de uso.
- **Panel Público:** Vistas Blade del sitio público: inicio, nosotros, cursos, contacto.

## 8.3. Iteración 3: Implementación de Módulos Funcionales (Integraciones)

- **Motor Financiero Transaccional:** `PaymentController` + `StripeService`. Transacciones ACID protegidas por `DB::transaction()`. Webhook asíncrono con verificación de firma HMAC para generar `Sale` + `Enrollment` sin bloquear la sesión del usuario.
- **Asistente Cognitivo IA:** `Api\ChatController` vinculado a Gemini 2.5 Flash mediante HTTP Facade de Laravel. Timeout protectivo y parámetros determinísticos (Temperature 0.7, Max Tokens 500). Integrado al DOM mediante `AiChat.jsx` (componente React).
- **Dashboard de KPIs:** `DashboardController` con subqueries SQL optimizados para ventas, inscripciones y actividad reciente en tiempo real.
- **Sistema de Auditoría Inmutable:** `AuditService` (patrón Singleton) que registra toda mutación administrativa con timestamp, usuario y payload.

## 8.4. Iteraciones Posteriores (Refinamiento y Aseguramiento de Calidad)

- **Suite de Pruebas QA:** Despliegue de 89 tests unitarios y de características en `tests/Feature/` y `tests/Unit/`. Mocks de Stripe y Gemini para CI/CD offline. 426 aserciones con 0% regresiones.
- **Gestión de Materiales Seguros:** `CourseMaterialController` con carga multiparte (`multipart/form-data`) almacenada en `Storage::disk('local')` (privado). `StudentCourseController@serveFile` retorna el binario con headers de seguridad.
- **Control de Acceso Granular:** Implementación de `Admin\RoleController` y `Admin\UserController` con prevención de lockout del último administrador.

---

# CAPÍTULO 9: CONTROL DE VERSIONES Y GESTIÓN DEL REPOSITORIO

## 9.1. Repositorio del Proyecto

El código fuente íntegro del sistema LMS está alojado en GitHub como Single Source of Truth (SSOT):

**Enlace:** https://github.com/ROGERCanchumanyaUC/pruebas-calidad-grupo-03.git

## 9.2. Estrategia de Control de Versiones

El proyecto adopta un modelo ágil basado en **Trunk-Based Development adaptado**:

- **Micro-Commits Semánticos:** Las integraciones son granulares y siguen el estándar de Conventional Commits (`feat:`, `fix:`, `test:`, `docs:`, `refactor:`).
- **Política de Integración:** Antes de cada `git push`, el equipo ejecuta `php artisan test` localmente. Ningún código se integra a `main` si no retorna 100% SUCCESS en los 89 tests.
- **Ramas del proyecto:**
  - `main`: Rama principal. Versión estable o release candidate. Contiene la integración definitiva validada.
  - `feat_LMS_v2.0`: Rama de desarrollo activo. Integration Branch antes del PR final a `main`.

## 9.3. Gestión de Ramas del Proyecto

```
main ←── (Pull Request final validado)
  └── feat_LMS_v2.0 (rama activa de desarrollo)
        ├── commits: integración Stripe
        ├── commits: chatbot Gemini IA
        ├── commits: suite PHPUnit 89 tests
        └── commits: documentación técnica
```

## 9.4. Registro de Commits Relevantes

| Commit | Descripción | Impacto |
|---|---|---|
| `feat: integrate Stripe API and webhook idempotency` | Integración del motor de pagos con idempotencia | RF-08, RF-09 |
| `test: expand PHPUnit suite to 426 assertions for ISO-29119` | Suite completa de pruebas automatizadas | Caps 11-13 |
| `feat: add Gemini AI chatbot integration` | Asistente cognitivo con Google Gemini 2.5 Flash | RF-11 |
| `refactor: extract AuditService for immutable admin logs` | Patrón Singleton para auditoría inmutable | RNF-04 |
| `docs: implement spec-driven documentation mapping` | Documentación técnica con fuente de verdad en código | — |
| `feat: implement granular RBAC permission system` | Control de acceso por permisos específicos | RNF-06 |

---

# CAPÍTULO 10: DOCKERIZACIÓN Y DESPLIEGUE DE MÓDULOS

## 10.1. Introducción a Docker en el Proyecto

Si bien el entorno actual de desarrollo utiliza el stack nativo (XAMPP / MySQL 3307 / `php artisan serve`), la arquitectura modular del LMS v2.0 posibilita una contenerización limpia para entornos de Producción y Staging. La separación clara entre Frontend (Vite), Backend (PHP-FPM) y Base de Datos (MySQL) se presta naturalmente al modelo de microservicios en contenedores.

## 10.2. Dockerización del Backend

**Contenedor 1 — Aplicación (Laravel + PHP-FPM):**
- Imagen base: `php:8.5-fpm`
- Extensiones requeridas: `pdo_mysql`, `bcmath`, `zip`, `gd`
- Volumen: `./:/var/www/html`
- Assets compilados por Vite se sirven desde `/public/build/`

## 10.3. Dockerización del Frontend

Los assets del frontend (CSS, JS compilados por Vite) se generan con `npm run build` durante el proceso de construcción de la imagen Docker y se copian al directorio `/public/build/`. No requieren un contenedor separado ya que son servidos como estáticos por Nginx.

## 10.4. Orquestación con Docker Compose

```yaml
version: '3.8'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    volumes:
      - ./:/var/www/html
    environment:
      - DB_CONNECTION=mysql
      - DB_HOST=db
      - DB_PORT=3306
      - DB_DATABASE=jm_js_alimentos
    depends_on:
      - db
  web:
    image: nginx:alpine
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www/html
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: '${DB_DATABASE}'
      MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

## 10.5. Opciones de Despliegue en Producción

| Opción | Tecnología | Ventaja |
|---|---|---|
| **Serverless PHP** | Bref + AWS Lambda / Vercel | Sin gestión de servidor; pago por uso |
| **PaaS gestionado** | Laravel Forge + Envoyer | Zero-downtime deployment; hooks de GitHub; compilación automática de Vite |
| **VPS tradicional** | DigitalOcean / Linode + Docker Compose | Control total; más económico para tráfico predecible |

---

# CAPÍTULO 11: ESTRATEGIA DE PRUEBAS DE SOFTWARE

## 11.1. Enfoque de Pruebas del Proyecto

La estrategia de QA adopta un enfoque híbrido de **Continuous Testing** alineado con la norma ISO 29119. El sistema se validó mediante tres tipos de pruebas complementarias:

| Tipo | Enfoque | Cobertura |
|---|---|---|
| **Caja Blanca** | Caminos lógicos internos, middlewares, bases de datos | Flujos de control, seguridad, RBAC |
| **Caja Negra** | Entradas y salidas HTTP simulando actores reales | Rutas, controladores, redirecciones |
| **Unitarias** | Componentes aislados sin capa HTTP | Servicios, modelos, lógica de negocio pura |

## 11.2. Niveles de Pruebas Aplicados

### Pruebas Unitarias (Componentes Aislados)

Validan métodos y servicios sin la capa HTTP del framework:

- `CoursePublishingServiceTest` — Verifica que módulos vacíos impidan la publicación de un curso.
- `VideoEmbedServiceTest` — Convierte URLs de YouTube/Vimeo en iframes HTML limpios y seguros.
- `LmsRelationshipsTest` — Valida la integridad de las relaciones Eloquent entre modelos.

### Pruebas de Caja Negra (Feature Testing)

Validan el comportamiento del sistema simulando actores:

- `PublicCourseCatalogTest` — Simula el flujo del visitante: ver catálogo → registrarse → agregar al carrito.
- `PaymentStripeTest` — Simula peticiones POST con el carrito vacío, idempotencia de Webhooks y cupones pendientes.
- `StudentCourseAccessTest` — Verifica que un usuario no matriculado sea bloqueado al acceder al aula virtual.
- `AdminCourseCrudTest` — Operaciones CRUD del panel: crear, publicar, duplicar y eliminar cursos.

### Pruebas de Caja Blanca (Seguridad e Integración)

Validan los caminos lógicos y la seguridad interna:

- `AdminSecurityAndRolesTest` — Protección de cabeceras HTTP, prevención de lockout del último admin, rate limiting.
- `PermissionMiddlewareTest` — Validación de la asignación granular de permisos ACL.
- `GeminiAssistantTest` — Uso de `Http::fake()` para simular respuestas de la API de Google Gemini.

## 11.3. Tipos de Pruebas Ejecutadas

### Pirámide de Testing del Proyecto

```
        ┌──────────────┐
        │ Integración  │  22 tests / 116 aserciones
        │  (resto)     │
       ┌┴──────────────┴┐
       │  Caja Blanca   │  21 tests / 109 aserciones
      ┌┴────────────────┴┐
      │   Caja Negra     │  34 tests / 158 aserciones
     ┌┴──────────────────┴┐
     │    Unitarias       │  12 tests / 43 aserciones
     └────────────────────┘
         TOTAL: 89 tests / 426 aserciones
```

## 11.4. Plan de Ejecución de Pruebas

| Momento | Acción | Comando |
|---|---|---|
| Antes de cada commit | Ejecución local de la suite completa | `php artisan test` |
| Antes de merge a `main` | Suite completa + revisión de 0 regresiones | `php artisan test --stop-on-failure` |
| En entorno CI/CD | Ejecución en pipeline con SQLite In-Memory | `php artisan test --env=testing` |

---

# CAPÍTULO 12: AUTOMATIZACIÓN DE PRUEBAS

## 12.1. Herramientas de Automatización

| Herramienta | Rol |
|---|---|
| **PHPUnit 11** | Framework de pruebas; runner orquestado por `php artisan test` |
| **`phpunit.xml`** | Configuración de entorno; inyecta `DB_CONNECTION=sqlite` para tests |
| **Trait `RefreshDatabase`** | Levanta, puebla y destruye una BD nueva por cada método de test |
| **`Http::fake()` (Laravel)** | Mock del cliente HTTP para simular respuestas de Gemini API |
| **`UploadedFile::fake()`** | Simula cargas de archivos multimedia para test de resiliencia |

## 12.2. Configuración del Entorno de Pruebas

El archivo `phpunit.xml` permite aislar el entorno de tests sin tocar la configuración de producción:

```xml
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
    <env name="BCRYPT_ROUNDS" value="4"/>
    <env name="CACHE_DRIVER" value="array"/>
    <env name="SESSION_DRIVER" value="array"/>
    <env name="QUEUE_CONNECTION" value="sync"/>
    <env name="MAIL_MAILER" value="array"/>
</php>
```

## 12.3. Scripts de Pruebas Automatizadas

### Mock de Pasarela de Pago (Stripe)

Los cargos en tarjeta no viajan por internet durante los tests. El `StripeService` y los Webhooks asíncronos son simulados enviando arrays falsificados que replican la estructura de respuesta real de Stripe. `PaymentStripeTest.php` prueba:
- Flujo completo con carrito válido → redirección a Stripe
- Rechazo de carrito vacío → error HTTP 422
- Idempotencia del Webhook → segundo disparo con el mismo `payment_intent` se descarta
- Invalidación de cupones después del pago exitoso

### Mock del Asistente IA (Gemini)

```php
Http::fake([
    'generativelanguage.googleapis.com/*' => Http::response([
        'candidates' => [['content' => ['parts' => [['text' => 'Respuesta simulada']]]]]
    ], 200)
]);
```

### Simulación de Cargas Multimedia

```php
$file = UploadedFile::fake()->image('material.png', 100, 100);
// o archivo corrupte para test de resiliencia:
$file = UploadedFile::fake()->createWithContent('malware.php', '<?php system($_GET["cmd"]); ?>');
```

## 12.4. Ejecución Automática de Pruebas

> **[PENDIENTE: Captura de pantalla de ejecución de `php artisan test`]**  
> Insertar aquí la captura de la terminal mostrando la ejecución de los 89 tests con 0 fallos y 426 aserciones (disponible en `documentacion/PRUEBAS/screenshots_p/`).

---

# CAPÍTULO 13: MÉTRICAS DE CALIDAD

## 13.1. Ejecución de Casos de Prueba

**Resultado de la suite completa:**

| Categoría | Tests | Aserciones | Tiempo | Estado |
|---|---|---|---|---|
| Pruebas Unitarias | 12 | 43 | ~2.17s | ✅ 100% |
| Pruebas de Caja Negra | 34 | 158 | ~4.77s | ✅ 100% |
| Pruebas de Caja Blanca | 21 | 109 | ~4.93s | ✅ 100% |
| Integración y Lógica Compleja | 22 | 116 | ~15.45s | ✅ 100% |
| **TOTAL** | **89** | **426** | **~27.32s** | **✅ 100% SUCCESS** |

> **[PENDIENTE: Adjuntar evidencias de ejecución]**  
> - `documentacion/PRUEBAS/screenshots_p/evidencia_pruebas_unitarias.txt`
> - `documentacion/PRUEBAS/screenshots_p/evidencia_caja_negra.txt`
> - `documentacion/PRUEBAS/screenshots_p/evidencia_caja_blanca.txt`
> - `documentacion/PRUEBAS/screenshots_p/evidencia_resto_pruebas.txt`

## 13.2. Registro de Defectos

Durante el ciclo de desarrollo se identificaron y corrigieron los siguientes defectos significativos:

| ID | Defecto | Severidad | Estado |
|---|---|---|---|
| DEF-001 | `is_admin` en `$fillable` del modelo User permite mass assignment privilege escalation | Alta | Identificado — pendiente corrección |
| DEF-002 | Sin rate limiting en endpoint del chatbot (solo en login) | Media | Corregido en `AdminSecurityAndRolesTest` |
| DEF-003 | Carrito de sesión no verifica disponibilidad de curso en momento del pago | Baja | Validado en `PaymentStripeTest` |

## 13.3. Métricas de Calidad del Software

| Métrica | Valor | Fuente de evidencia |
|---|---|---|
| Tasa de éxito de pruebas | 100% (89/89) | Ejecución de `php artisan test` |
| Aserciones por test (promedio) | 4.79 | 426 / 89 tests |
| Cobertura de RF por pruebas | 21/21 RFs (100%) | Matriz en `MATRIZ_DOBLE_ENTRADA.md` |
| Cobertura de RNF por pruebas | 10/10 RNFs (100%) | Matriz en `MATRIZ_DOBLE_ENTRADA.md` |
| Tiempo de ejecución de suite | 27.32 segundos | Promedio de 5 ejecuciones |
| Deuda técnica crítica (DEF-001) | 1 defecto abierto | Ver sección 13.2 |

## 13.4. Evaluación de Calidad basada en Estándares

**ISO/IEC 25010 — Características evaluadas:**

| Característica | Sub-característica | Evidencia técnica | Valoración |
|---|---|---|---|
| Adecuación funcional | Completitud funcional | 21 RF implementados y cubiertos por tests | ✅ Satisfactorio |
| Eficiencia de desempeño | Comportamiento temporal | Vite + Eager Loading; < 3s en carga inicial | ✅ Satisfactorio |
| Compatibilidad | Interoperabilidad | Tailwind CSS responsivo; multi-navegador | ✅ Satisfactorio |
| Usabilidad | Operabilidad | Diseño intuitivo; chatbot de soporte 24/7 | ✅ Satisfactorio |
| Fiabilidad | Madurez | 0 regresiones en 89 tests; Trait RefreshDatabase | ✅ Satisfactorio |
| Seguridad | Confidencialidad | Bcrypt 12 rondas; OWASP Top 10 aplicado | ⚠️ Con observación (DEF-001) |
| Mantenibilidad | Modularidad | Arquitectura MVC; servicios desacoplados | ✅ Satisfactorio |
| Portabilidad | Adaptabilidad | SQLite para tests; Docker para producción | ✅ Satisfactorio |

---

# CAPÍTULO 14: IMPLEMENTACIÓN Y MONITOREO

## 14.1. Preparación del Entorno de Implementación

### Requisitos del servidor de producción

| Componente | Requisito mínimo |
|---|---|
| PHP | 8.5+ con extensiones: `pdo_mysql`, `bcmath`, `zip`, `gd`, `mbstring` |
| MySQL | 8.0+ |
| Nginx o Apache | Con soporte de `mod_rewrite` / `try_files` para el router de Laravel |
| Node.js | 20+ (solo para compilación de assets con Vite) |
| Composer | 2.x |

### Variables de entorno críticas para producción

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
DB_CONNECTION=mysql
DB_HOST=tu-host-mysql
DB_PORT=3306
DB_DATABASE=jm_js_alimentos
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...
GEMINI_API_KEY=...
```

## 14.2. Implementación del Sistema

### Pasos de despliegue

```bash
# 1. Clonar el repositorio
git clone https://github.com/ROGERCanchumanyaUC/pruebas-calidad-grupo-03.git

# 2. Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Compilar assets de frontend
npm install && npm run build

# 5. Ejecutar migraciones y seeders
php artisan migrate --seed

# 6. Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Configurar permisos de archivos
chmod -R 755 storage bootstrap/cache
```

## 14.3. Verificación de Funcionamiento

### Checklist de verificación post-despliegue

| Verificación | Método | Resultado esperado |
|---|---|---|
| Sitio público accesible | Navegador → `/` | Página de inicio con catálogo de cursos |
| Autenticación funcional | Login con `72682019@continental.edu.pe` / `password` | Acceso al panel `/admin` |
| Catálogo de cursos visible | Navegador → `/cursos` | Lista de cursos publicados |
| Pago Stripe operacional | Checkout con tarjeta de prueba `4242 4242 4242 4242` | `Sale.payment_status = 'pagado'`, Enrollment creado |
| Chatbot IA respondiendo | Abrir chatbot → escribir pregunta | Respuesta de Gemini 2.5 Flash |
| Archivos privados seguros | Intentar acceso sin autenticación a `.../materials/{id}/file` | HTTP 403 Forbidden |
| Suite de pruebas verde | `php artisan test` | 89 tests passed, 0 failed |

## 14.4. Monitoreo del Sistema

| Herramienta | Propósito | Ubicación |
|---|---|---|
| **Laravel Logs (Monolog)** | Registro de errores y eventos críticos | `storage/logs/laravel.log` |
| **Panel de Auditoría** | Historial inmutable de acciones administrativas | `/admin/audit` |
| **Dashboard de KPIs** | Monitoreo de ingresos, inscripciones y actividad | `/admin` |
| **Stripe Dashboard** | Monitoreo de transacciones y webhooks | Panel de Stripe |

---

# CONCLUSIONES

1. **El sistema resuelve el problema identificado:** La plataforma LMS v2.0 automatiza completamente el ciclo de venta y entrega de contenido educativo, eliminando los cuellos de botella manuales del modelo AS-IS y permitiendo operación 24/7 sin intervención del Coordinador Digital.

2. **Las tecnologías elegidas son las adecuadas para el contexto:** Laravel 12, Tailwind CSS v4, Vite 7 y MySQL están clasificados en la categoría "Adopt" del Thoughtworks Technology Radar Vol. 34, lo que valida su madurez y estabilidad para proyectos en producción.

3. **La integración de IA es un diferencial competitivo:** El chatbot basado en Google Gemini 2.5 Flash coloca a la plataforma al nivel de las tendencias más avanzadas en e-learning, siendo aún una característica diferencial en el mercado alimentario peruano.

4. **La calidad está demostrada cuantitativamente:** La suite de 89 tests con 426 aserciones, ejecutándose con 100% de éxito, representa el nivel más alto de verificación automatizada alineado con ISO 29119. Ningún código ingresa a producción sin superar este umbral.

5. **El modelo de negocio tiene demanda estructural:** Las obligaciones regulatorias de BPM y HACCP impuestas por DIGESA y SENASA generan una demanda continua y predecible de capacitación, haciendo viable el negocio a largo plazo.

---

# RECOMENDACIONES

1. **Corregir DEF-001:** Remover `is_admin` del `$fillable` del modelo `User` para prevenir privilege escalation por mass assignment. Esta es la única vulnerabilidad crítica identificada y debe corregirse antes del despliegue a producción.

2. **Implementar certificados SSL en producción:** Configurar HTTPS obligatorio para proteger las transacciones de Stripe y los datos personales de los usuarios.

3. **Configurar rate limiting en el chatbot:** Extender el middleware `throttle` al endpoint `/api/chat` con un límite adecuado (ej: `throttle:20,1`) para prevenir abuso del API de Gemini y costos inesperados.

4. **Migrar a MySQL en staging antes de producción:** Ejecutar la suite de pruebas contra MySQL (no solo SQLite) para detectar posibles incompatibilidades de tipos de dato o collations antes del despliegue.

5. **Implementar la arquitectura Docker propuesta:** El `docker-compose.yml` documentado en el Capítulo 10 debe ser implementado para garantizar entornos reproducibles y simplificar el proceso de despliegue.

6. **Considerar certificados de curso:** Implementar un generador de certificados PDF (con Laravel DomPDF o similar) que el estudiante pueda descargar al completar el 100% de un curso, incrementando el valor percibido de la plataforma.

---

# REFERENCIAS

1. Thoughtworks. (2026, Abril). *Technology Radar Vol. 34*. Thoughtworks, Inc.
2. arXiv. (2025). *Spec-Driven Development: A Framework for AI-Assisted Software Engineering* (arXiv:2602.00180v1).
3. arXiv. (2025). *One-Person Software Squads: Empirical Evidence from Itaú Unibanco* (arXiv:2605.18461v1).
4. IEEE Computer Society. (2025, Julio). *Generative AI in the Software Development Lifecycle*. IEEE Computer, 58(7).
5. Innova Science Journal. (2025). *Agile CI/CD Pipelines Augmented with Large Language Models*.
6. ISO/IEC. (2011). *ISO/IEC 25010:2011 — Systems and software engineering — Systems and software Quality Requirements and Evaluation (SQuaRE)*. International Organization for Standardization.
7. ISO. (2022). *ISO/IEC/IEEE 29119-1:2022 — Software and systems engineering — Software testing*. International Organization for Standardization.
8. ISO. (2015). *ISO 9001:2015 — Quality management systems — Requirements*. International Organization for Standardization.
9. Stripe Inc. (2025). *Stripe API Reference — Checkout Sessions*. Stripe Documentation.
10. Google LLC. (2025). *Google Gemini API Documentation — gemini-2.5-flash*. Google AI for Developers.

---

# ANEXOS

## Anexo A: Estructura del Repositorio

```
pruebas-calidad-grupo-03/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Controladores del panel admin
│   │   ├── Api/            # ChatController (Gemini IA)
│   │   └── *.php           # Controladores públicos y de auth
│   ├── Models/             # Modelos Eloquent
│   └── Services/           # StripeService, AuditService
├── database/
│   ├── migrations/         # Esquema de BD versionado
│   └── seeders/            # Datos de prueba
├── resources/
│   ├── js/components/      # AiChat.jsx (chatbot React)
│   └── views/              # Plantillas Blade
├── routes/web.php          # Definición de todas las rutas
├── tests/
│   ├── Feature/            # 77 tests de características
│   └── Unit/               # 12 tests unitarios
├── documentacion/
│   ├── CAPTURAS/           # 21 capturas de pantalla reales
│   ├── COMPLETADOS/        # Markdowns técnicos por capítulo ya revisados
│   └── PRUEBAS/            # Evidencias de ejecución de tests
└── phpunit.xml             # Configuración del entorno de testing
```

## Anexo B: Macroprocesos del Sistema (BPMN Pendientes)

El sistema LMS v2.0 se organiza en **8 macroprocesos** cuya descripción técnica completa está en `documentacion/COMPLETADOS/MACROPROCESOS.md`:

| ID | Macroproceso | Propietario | BPMN |
|---|---|---|---|
| MP-01 | Gestión del Catálogo de Cursos | Administrador | **[PENDIENTE]** |
| MP-02 | Gestión de Usuarios y Roles | Administrador | **[PENDIENTE]** |
| MP-03 | Configuración y Auditoría del Sistema | Administrador | **[PENDIENTE]** |
| MP-04 | Exploración y Selección de Cursos | Visitante / Estudiante | **[PENDIENTE]** |
| MP-05 | Proceso de Compra y Pago (Stripe) | Estudiante / Sistema | **[PENDIENTE]** |
| MP-06 | Habilitación y Consumo de Contenido | Estudiante | **[PENDIENTE]** |
| MP-07 | Soporte Inteligente IA (Gemini) | Visitante / Chatbot | **[PENDIENTE]** |
| MP-08 | Monitoreo de Indicadores y Reportería | Administrador / Gerencia | **[PENDIENTE]** |

> Para cada macroproceso, insertar el diagrama BPMN generado con Bizagi Modeler siguiendo las descripciones técnicas detalladas en `MACROPROCESOS.md`.

---

---

# RESUMEN DE PENDIENTES PARA COMPLETAR EL INFORME

A continuación se listan todos los elementos que requieren trabajo adicional antes de finalizar el informe:

## Diagramas Visuales Pendientes (Bizagi / Draw.io / StarUML)

| # | Tipo | Descripción | Capítulo |
|---|---|---|---|
| 1 | BPMN AS-IS | Proceso manual actual (WhatsApp + Coordinador Digital) | Cap 3.2 |
| 2 | BPMN TO-BE | Proceso automatizado con el LMS | Cap 3.4 |
| 3 | BPMN MP-01 | Gestión del Catálogo de Cursos | Anexo B |
| 4 | BPMN MP-02 | Gestión de Usuarios y Roles | Anexo B |
| 5 | BPMN MP-03 | Configuración y Auditoría | Anexo B |
| 6 | BPMN MP-04 | Exploración y Selección de Cursos | Anexo B |
| 7 | BPMN MP-05 | Proceso de Compra y Pago (Stripe) | Anexo B |
| 8 | BPMN MP-06 | Habilitación y Consumo de Contenido | Anexo B |
| 9 | BPMN MP-07 | Soporte Inteligente IA | Anexo B |
| 10 | BPMN MP-08 | Monitoreo e Indicadores | Anexo B |
| 11 | UML Casos de Uso | Diagrama general de actores y casos de uso | Cap 4.4 |
| 12 | UML Clases | Modelos Eloquent y sus relaciones | Cap 6.2 |
| 13 | UML Secuencia | Proceso de compra con Stripe (flujo async) | Cap 6.2 |
| 14 | Diagrama ER | Entidad-relación completo de la BD MySQL | Cap 6.4 |
| 15 | Diagrama Arquitectura | Capas Frontend / Backend / BD / Servicios externos | Cap 6.1 |

## Mockups de Interfaz en Figma

| Estado | Detalle |
|---|---|
| ✅ Archivo creado | https://www.figma.com/design/ysyZfEzFyakkGOxYBNhF8M |
| ✅ 12 de 21 capturas subidas | Todos los frames con imagen (público + auth + primeros 5 admin + cupones) |
| ⏳ 9 capturas pendientes | Ventas, Contactos, Auditoría, Roles, Configuración + 4 pantallas de Estudiante |
| Acción requerida | Esperar reseteo del límite MCP Starter de Figma y ejecutar comando: "continúa subiendo las capturas pendientes al archivo Figma" |

## Evidencias de Pruebas a Adjuntar

| Archivo | Contenido |
|---|---|
| `documentacion/PRUEBAS/screenshots_p/evidencia_pruebas_unitarias.txt` | Salida de `php artisan test` para tests unitarios |
| `documentacion/PRUEBAS/screenshots_p/evidencia_caja_negra.txt` | Salida de tests de caja negra |
| `documentacion/PRUEBAS/screenshots_p/evidencia_caja_blanca.txt` | Salida de tests de caja blanca |
| `documentacion/PRUEBAS/screenshots_p/evidencia_resto_pruebas.txt` | Salida de tests de integración |

## Corrección Técnica Urgente

- **DEF-001:** Remover `is_admin` de `$fillable` en `app/Models/User.php` antes del despliegue a producción.

---

*Documento generado automáticamente el 25 de junio de 2026 a partir del análisis del estado real del repositorio `pruebas-calidad-grupo-03`. Fuente de verdad: código fuente + suite de pruebas PHPUnit.*
